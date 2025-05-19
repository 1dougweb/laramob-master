@php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

$settings = Cache::remember('app_settings', now()->addDay(), function() {
    if (Storage::exists('settings.json')) {
        return json_decode(Storage::get('settings.json'), true);
    }
    return [];
});

$siteName = $settings['site_name'] ?? config('app.name', 'LaraMob');
$primaryColor = $settings['primary_color'] ?? '#3b82f6';
$hoverColor = $settings['hover_color'] ?? '#3b82f6';
$textColor = $settings['text_color'] ?? '#4b5563';
$hoverTextColor = $settings['hover_text_color'] ?? '#3b82f6';
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/">
                    <img class="h-10 w-auto" src="{{ isset($settings['site_logo']) && $settings['site_logo'] ? asset('storage/' . $settings['site_logo']) : asset('images/logo-default.png') }}" alt="Logo">
                </a>
            </div>

            <!-- Search Bar -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" 
                           id="main-search-input" 
                           placeholder="Buscar imóveis..." 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg" style="border-color: var(--primary-color);"
                           autocomplete="off">
                    <div id="main-search-results" class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg hidden">
                        <!-- Results will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 items-center">
                <a href="/" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Home</a>
                <a href="{{ route('properties.index') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Imóveis</a>
                <a href="{{ route('blog.index') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Blog</a>
                <a href="{{ route('about') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Sobre</a>
                <a href="{{ route('contact') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Contato</a>
                <!-- Login/Register Links -->
                @guest
                    <a href="{{ route('login') }}" class="ml-4 flex items-center font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}" class="ml-2 flex items-center px-4 py-2 rounded-lg font-medium" style="background: var(--primary-color); color: #fff;" onmouseover="this.style.background='var(--primary-color-dark)'" onmouseout="this.style.background='var(--primary-color)'">
                        <i class="fi fi-rr-arrow-right-to-bracket mr-1"></i>
                        Cadastrar
                    </a>
                @else
                    <div class="ml-4 relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-{{ $textColor }} hover:bg-{{ $hoverColor }} hover:text-white">Meu Painel</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-{{ $textColor }} hover:bg-{{ $hoverColor }} hover:text-white">
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-{{ $textColor }} hover:text-{{ $hoverTextColor }} focus:outline-none">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-100">
        <div class="px-4 py-4 space-y-2">
            <!-- Mobile Search Bar -->
            <div class="mb-4">
                <input type="text" 
                       id="mobile-search-input" 
                       placeholder="Buscar imóveis..." 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg" style="border-color: var(--primary-color);"
                       autocomplete="off">
                <div id="mobile-search-results" class="mt-1 bg-white rounded-lg shadow-lg hidden">
                    <!-- Results will be populated here -->
                </div>
            </div>
            <a href="/" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Home</a>
            <a href="{{ route('properties.index') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Imóveis</a>
            <a href="{{ route('blog.index') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Blog</a>
            <a href="{{ route('about') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Sobre</a>
            <a href="{{ route('contact') }}" class="font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Contato</a>
            @guest
                <a href="{{ route('login') }}" class="flex items-center font-medium" style="color: var(--text-color);" onmouseover="this.style.color='var(--hover-text-color)'" onmouseout="this.style.color='var(--text-color)'">Entrar</a>
                <a href="{{ route('register') }}" class="flex items-center px-4 py-2 rounded-lg font-medium" style="background: var(--primary-color); color: #fff;" onmouseover="this.style.background='var(--primary-color-dark)'" onmouseout="this.style.background='var(--primary-color)'">
                    <i class="fi fi-rr-arrow-right-to-bracket mr-1"></i>
                    Cadastrar
                </a>
            @else
                <a href="{{ route('profile.edit') }}" class="block text-{{ $textColor }} hover:text-{{ $hoverTextColor }} font-medium mt-2">Meu Painel</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left text-{{ $textColor }} hover:text-{{ $hoverTextColor }} font-medium mt-2">
                        Sair
                    </button>
                </form>
            @endguest
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn .2s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: none; } }
        
        .property-card {
            @apply p-4 border-b border-gray-100 hover:bg-gray-50 transition cursor-pointer;
        }
        
        .property-card:last-child {
            @apply border-b-0;
        }
        
        .property-title {
            @apply text-lg font-medium text-gray-900;
        }
        
        .property-details {
            @apply text-sm text-gray-600 mt-1;
        }
        
        .property-price {
            @apply text-{{ $primaryColor }} font-semibold mt-2;
        }
    </style>
    <script>
        let searchTimeout;
        const mainSearchInput = document.getElementById('main-search-input');
        const mainSearchResults = document.getElementById('main-search-results');
        const mobileSearchInput = document.getElementById('mobile-search-input');
        const mobileSearchResults = document.getElementById('mobile-search-results');
        
        function performSearch(query, resultsContainer) {
            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }
            
            fetch(`/api/properties/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    
                    if (data.length === 0) {
                        resultsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum imóvel encontrado</p>';
                    } else {
                        data.forEach(property => {
                            const card = document.createElement('div');
                            card.className = 'property-card';
                            card.innerHTML = `
                                <div class="property-title">${property.title}</div>
                                <div class="property-details">
                                    ${property.address} • ${property.type}
                                </div>
                                <div class="property-price">
                                    R$ ${property.price.toLocaleString('pt-BR')}
                                </div>
                            `;
                            card.onclick = () => window.location.href = `/properties/${property.id}`;
                            resultsContainer.appendChild(card);
                        });
                    }
                    
                    resultsContainer.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erro na busca:', error);
                    resultsContainer.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao realizar a busca</p>';
                    resultsContainer.classList.remove('hidden');
                });
        }
        
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile menu
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            btn.addEventListener('click', function () {
                menu.classList.toggle('hidden');
            });
            
            // Search functionality for desktop
            mainSearchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch(e.target.value, mainSearchResults);
                }, 300);
            });
            
            // Search functionality for mobile
            mobileSearchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch(e.target.value, mobileSearchResults);
                }, 300);
            });
            
            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!mainSearchInput.contains(e.target) && !mainSearchResults.contains(e.target)) {
                    mainSearchResults.classList.add('hidden');
                }
                if (!mobileSearchInput.contains(e.target) && !mobileSearchResults.contains(e.target)) {
                    mobileSearchResults.classList.add('hidden');
                }
            });
        });
    </script>
</nav>
<!-- Nota: Se os ícones do Flat Icons não aparecerem, verifique se o CSS do Flat Icons está corretamente incluído no seu projeto. --> 