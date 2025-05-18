// Adicionar cache para os recursos estáticos e rotas frequentes
const CACHE_NAME = 'laramob-cache-v1';
const STATIC_ASSETS = [
  '/favicon.ico',
  '/build/assets/app.css',
  '/build/assets/app.js'
];

// Evitar problemas com rotas dinâmicas
const NAVIGATION_ROUTES = [
  '/'
];

// Função para adicionar recursos ao cache de forma mais resiliente
const addResourcesToCache = async (resources) => {
  const cache = await caches.open(CACHE_NAME);
  
  // Em vez de usar addAll (que falha se qualquer recurso falhar),
  // vamos adicionar cada recurso individualmente
  await Promise.allSettled(
    resources.map(async (resource) => {
      try {
        // Tenta buscar e armazenar o recurso
        const response = await fetch(resource, { 
          credentials: 'same-origin',
          cache: 'no-store' // Força uma request fresca
        });
        if (response.ok) {
          await cache.put(resource, response);
          console.log(`Cached: ${resource}`);
        } else {
          console.warn(`Failed to cache: ${resource}, status: ${response.status}`);
        }
      } catch (error) {
        console.warn(`Failed to cache: ${resource}, error: ${error.message}`);
      }
    })
  );
};

self.addEventListener('install', (event) => {
  event.waitUntil(
    Promise.all([
      addResourcesToCache(STATIC_ASSETS),
      self.skipWaiting() // Assume o controle imediatamente
    ])
  );
});

self.addEventListener('activate', (event) => {
  // Limpar caches antigos
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all([
        // Remover caches antigos
        Promise.all(
          cacheNames
            .filter((cacheName) => cacheName !== CACHE_NAME)
            .map((cacheName) => caches.delete(cacheName))
        ),
        // Tomar controle de clientes não controlados
        self.clients.claim()
      ]);
    })
  );
});

self.addEventListener('fetch', (event) => {
  // Não tente interceptar requisições para outras origens
  if (!event.request.url.startsWith(self.location.origin)) {
    return;
  }

  // Não tente interceptar requisições POST ou de API
  if (
    event.request.method !== 'GET' ||
    event.request.url.includes('/api/')
  ) {
    return;
  }
  
  // Estratégia para recursos estáticos (cache-first)
  const url = new URL(event.request.url);
  const isStaticAsset = STATIC_ASSETS.some(asset => 
    url.pathname.endsWith(asset) || asset.endsWith(url.pathname)
  );
  
  if (isStaticAsset) {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        // Retorna do cache se disponível
        if (cachedResponse) {
          // Atualiza o cache em segundo plano (stale-while-revalidate)
          fetch(event.request).then(response => {
            if (response.ok) {
              const responseToCache = response.clone();
              caches.open(CACHE_NAME).then(cache => {
                cache.put(event.request, responseToCache);
              });
            }
          }).catch(() => {});
          
          return cachedResponse;
        }

        // Se não está em cache, busca da rede
        return fetch(event.request).then(response => {
          if (!response || !response.ok) {
            return response;
          }

          const responseToCache = response.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, responseToCache);
          });
          
          return response;
        });
      })
    );
  } else {
    // Para outras requisições, usar network-first
    // Isso evita problemas com rotas dinâmicas
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Não cachear páginas dinâmicas para evitar problemas
          return response;
        })
        .catch(() => {
          // Em caso de falha na rede, tenta buscar do cache
          return caches.match(event.request)
            .then(cachedResponse => {
              if (cachedResponse) {
                return cachedResponse;
              }
              
              // Se for uma navegação, retorna a página inicial
              if (event.request.mode === 'navigate') {
                return caches.match('/');
              }
              
              return new Response('Não foi possível carregar o recurso.', {
                status: 503,
                statusText: 'Service Unavailable'
              });
            });
        })
    );
  }
}); 