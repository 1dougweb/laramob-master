/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configuração melhorada para HMR - evitar refreshes automáticos
if (import.meta.hot) {
    import.meta.hot.accept();
    
    // Desabilitar o comportamento que causa refresh automático
    import.meta.hot.decline();
    
    // Registrar eventos apenas para logging, sem ações adicionais
    import.meta.hot.on('vite:beforeUpdate', () => {
        console.log('Atualização de módulo detectada');
    });
}

// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
