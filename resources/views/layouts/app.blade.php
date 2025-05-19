<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @auth
        <meta name="user-id" content="{{ auth()->id() }}">
        @endauth

        @php
        use Illuminate\Support\Facades\Cache;
        use Illuminate\Support\Facades\Storage;

        $settings = Cache::remember('app_settings', now()->addDay(), function() {
            if (Storage::exists('settings.json')) {
                return json_decode(Storage::get('settings.json'), true);
            }
            return [];
        });
        
        $fontFamily = $settings['font_family'] ?? 'figtree';
        $siteName = $settings['site_name'] ?? config('app.name', 'LaraMob');
        $siteDescription = $settings['site_description'] ?? 'Sistema imobiliário';
        @endphp

        <title>@yield('title', $siteName)</title>
        
        <!-- Meta tags -->
        <meta name="description" content="{{ $settings['meta_description'] ?? $siteDescription }}">
        @if(isset($settings['meta_keywords']) && $settings['meta_keywords'])
        <meta name="keywords" content="{{ $settings['meta_keywords'] }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        @if($fontFamily !== 'figtree')
        <!-- Alternative Font -->
        @switch($fontFamily)
            @case('inter')
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                @break
            @case('roboto')
                <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
                @break
            @case('open-sans')
                <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
                @break
            @case('montserrat')
                <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                @break
        @endswitch
        @endif
        
        <!-- Flaticon CSS -->
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Scripts e Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Dynamic Styles based on settings -->
        <link rel="stylesheet" href="{{ route('dynamic.styles') }}">
        
        <!-- Estilos adicionais para garantir prioridade das cores personalizadas -->
        <style>
            /* Forçar que as cores personalizadas tenham prioridade */
            aside nav a, aside nav button, aside nav .text-gray-700 {
                color: var(--text-color) !important;
            }
            
            aside nav i, aside nav svg, aside nav .text-gray-500 {
                color: var(--icon-color) !important;
            }
            
            /* Hover states */
            aside nav a:hover, aside nav button:hover {
                background-color: var(--hover-bg-color) !important;
            }
            
            aside nav a:hover span:not(.mr-2), aside nav button:hover span:not(.mr-2) {
                color: var(--hover-text-color) !important;
            }
            
            aside nav a:hover i, aside nav a:hover svg, 
            aside nav button:hover i, aside nav button:hover svg {
                color: var(--hover-icon-color) !important;
            }
            
            /* Active states */
            aside nav .bg-blue-100, aside nav a.bg-blue-100 {
                background-color: var(--active-bg-color) !important;
            }
            
            aside nav .bg-blue-100 span:not(.mr-2), 
            aside nav a.bg-blue-100 span:not(.mr-2) {
                color: var(--active-text-color) !important;
            }
            
            aside nav .bg-blue-100 i, aside nav .bg-blue-100 svg, 
            aside nav a.bg-blue-100 i, aside nav a.bg-blue-100 svg {
                color: var(--active-icon-color) !important;
            }

            /* Override specific Tailwind classes */
            aside nav a.text-gray-600,
            aside nav button.text-gray-600 {
                color: var(--text-color) !important;
            }

            aside nav a.hover\:text-gray-900:hover,
            aside nav button.hover\:text-gray-900:hover {
                color: var(--hover-text-color) !important;
            }

            aside nav a.hover\:bg-gray-100:hover,
            aside nav button.hover\:bg-gray-100:hover {
                background-color: var(--hover-bg-color) !important;
            }

            /* Para menus aninhados/dropdown na barra lateral */
            aside nav ul li ul a,
            aside nav .submenu a {
                color: var(--text-color) !important;
            }

            aside nav ul li ul a:hover,
            aside nav .submenu a:hover {
                background-color: var(--hover-bg-color) !important;
                color: var(--hover-text-color) !important;
            }
        </style>
        
        <!-- Tailwind CSS CDN as fallback -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        
        @if(isset($settings['google_analytics_id']) && $settings['google_analytics_id'])
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics_id'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $settings['google_analytics_id'] }}');
        </script>
        @endif
        
        @livewireStyles
        @stack('styles')
        @yield('styles')
        
        <!-- Script para corrigir dados da dashboard -->
        @if(request()->routeIs('client.dashboard'))
            <script>
                // Script para processar dados no dashboard do cliente
                document.addEventListener('DOMContentLoaded', function() {
                    // Função para detectar e corrigir dados JSON
                    function fixJsonInElement(element) {
                        if (!element) return;
                        
                        const text = element.textContent.trim();
                        
                        // Verifica se o conteúdo começa com [ ou { (possível JSON)
                        if ((text.startsWith('[') && text.endsWith(']')) || 
                            (text.startsWith('{') && text.endsWith('}'))) {
                            
                            try {
                                // Tenta analisar como JSON
                                const jsonData = JSON.parse(text);
                                
                                // Se for um array de tarefas
                                if (Array.isArray(jsonData)) {
                                    // Exibe o número de tarefas ao invés do JSON
                                    element.textContent = jsonData.length;
                                    
                                    // Atualiza o texto secundário, se existir
                                    const infoElem = element.closest('.bg-blue-50')?.querySelector('.text-sm.text-blue-600');
                                    if (infoElem) {
                                        const todoCount = jsonData.filter(task => task.status === 'todo').length;
                                        const inProgressCount = jsonData.filter(task => task.status === 'in_progress').length;
                                        
                                        if (todoCount > 0 || inProgressCount > 0) {
                                            infoElem.textContent = `${todoCount} para fazer | ${inProgressCount} em progresso`;
                                        } else {
                                            infoElem.textContent = 'Nenhuma tarefa pendente';
                                        }
                                    }
                                    
                                    console.log('Dados JSON corrigidos:', jsonData);
                                }
                            } catch (e) {
                                console.error('Erro ao processar possível JSON:', e);
                            }
                        }
                    }
                    
                    // Procura por todos os elementos de contagem numérica no resumo de atividades
                    const counterElements = document.querySelectorAll('.bg-white .grid-cols-4 .text-3xl');
                    counterElements.forEach(fixJsonInElement);
                    
                    // Procura especificamente pelo elemento de tarefas pendentes
                    const pendingTasksElement = document.querySelector('.bg-blue-50 .text-3xl');
                    if (pendingTasksElement) {
                        fixJsonInElement(pendingTasksElement);
                    }
                    
                    // Procura por elementos de texto que podem conter JSON
                    const textElements = document.querySelectorAll('.text-sm.text-blue-600');
                    textElements.forEach(element => {
                        const text = element.textContent.trim();
                        
                        // Verifica se o texto contém um padrão que parece JSON
                        if (text.includes('[{') || text.includes('"}]')) {
                            try {
                                // Extrai a parte JSON
                                const jsonStart = text.indexOf('[');
                                const jsonEnd = text.lastIndexOf(']') + 1;
                                
                                if (jsonStart >= 0 && jsonEnd > jsonStart) {
                                    const jsonText = text.substring(jsonStart, jsonEnd);
                                    const jsonData = JSON.parse(jsonText);
                                    
                                    // Substitui o JSON por uma contagem simples
                                    const todoCount = jsonData.filter(task => task.status === 'todo').length;
                                    const inProgressCount = jsonData.filter(task => task.status === 'in_progress').length;
                                    
                                    element.textContent = `${todoCount} para fazer | ${inProgressCount} em progresso`;
                                }
                            } catch (e) {
                                console.warn('Erro ao extrair JSON do texto:', e);
                            }
                        }
                    });
                });
            </script>
        @endif
        
        <!-- Chart.js CDN for dashboard -->
        @if(request()->routeIs('admin.dashboard'))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden bg-gray-100">
                        <!-- Sidebar backdrop (mobile only) -->
                        <div 
                            x-show="sidebarOpen" 
                            x-transition:enter="transition-opacity ease-linear duration-300" 
                            x-transition:enter-start="opacity-0" 
                            x-transition:enter-end="opacity-100" 
                            x-transition:leave="transition-opacity ease-linear duration-300" 
                            x-transition:leave-start="opacity-100" 
                            x-transition:leave-end="opacity-0" 
                            @click="sidebarOpen = false" 
                            class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
                        ></div>

                        <!-- Sidebar -->
                        <aside 
                            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" 
                            class="fixed inset-y-0 left-0 z-30 w-64 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:relative"
                            style="background-color: {{ $settings['sidebar_style'] == 'light' ? ($settings['sidebar_color'] ?? '#ffffff') : 'inherit' }}"
                        >
                            <!-- Logo -->
                            <div class="flex items-center justify-between p-6 text-white" style="background-color: {{ $settings['logo_bg_color'] ?? '#3b82f6' }}">
                                <a href="{{ route('home') }}" class="text-xl font-bold">
                                    @if(isset($settings['site_logo']) && $settings['site_logo'])
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ $siteName }}" class="h-8 w-auto">
                                    @else
                                        {{ $siteName }}
                                    @endif
                                </a>
                                <button @click="sidebarOpen = false" class="p-1 rounded-md lg:hidden focus:outline-none focus:ring">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Sidebar content -->
                            <div class="h-full overflow-y-auto" style="height: calc(100vh - 88px);">
                                <nav class="py-4">
                                    <div class="px-4 mb-3">
                                        <p class="text-gray-500 uppercase text-xs font-semibold">Main</p>
                                    </div>
                                    
                                    <!-- Dashboard Link -->
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('dashboard') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-dashboard"></i>
                                        </span>
                                        Dashboard
                                    </a>

                                    <!-- Kanban Link -->
                                    <a href="{{ route('admin.kanban.tasks') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.kanban.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                            <i class="fi fi-rr-list-check"></i>
                                        </span>
                                        Tarefas
                                    </a>

                                    <div class="px-4 mb-3 mt-6">
                                        <p class="text-gray-500 uppercase text-xs font-semibold">Propriedades</p>
                                    </div>
                                    
                                    <!-- Properties Link -->
                                    <a href="{{ route('admin.properties.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.properties.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-house-key"></i>
                                        </span>
                                        Propriedades
                                    </a>
                                    
                                    <!-- Property Types Link -->
                                    <a href="{{ route('admin.property-types.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.property-types.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-tags"></i>
                                        </span>
                                        Tipos de propriedades
                                    </a>
                                    
                                    <div class="px-4 mb-3 mt-6">
                                        <p class="text-gray-500 uppercase text-xs font-semibold">Localização</p>
                                    </div>
                                    
                                    <!-- Cities Link -->
                                    <a href="{{ route('admin.cities.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.cities.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-house-building"></i>
                                        </span>
                                        Cidades
                                    </a>
                                    
                                    <!-- Districts Link -->
                                    <a href="{{ route('admin.districts.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.districts.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-marker"></i>
                                        </span>
                                        Bairros
                                    </a>
                                    
                                    <div class="px-4 mb-3 mt-6">
                                        <p class="text-gray-500 uppercase text-xs font-semibold">Social</p>
                                    </div>
                                    
                                    <!-- People Link -->
                                    <a href="{{ route('admin.people.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.people.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-circle-user"></i>
                                        </span>
                                        Pessoas
                                    </a>
                                    
                                    <!-- Contacts Link -->
                                    <a href="{{ route('admin.contacts.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.contacts.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-book-bookmark"></i>
                                        </span>
                                        Contatos
                                    </a>
                                    
                                    <!-- Blog Link -->
                                    <a href="{{ route('admin.blog.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.blog.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-document-signed"></i>
                                        </span>
                                        Blog
                                    </a>
                                    
                                    <div class="px-4 mb-3 mt-6">
                                        <p class="text-gray-500 uppercase text-xs font-semibold">Business</p>
                                    </div>
                                    
                                    <!-- Contracts Link -->
                                    <a href="{{ route('admin.contracts.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.contracts.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-memo-circle-check"></i>  
                                        </span>
                                        Contratos
                                    </a>

                                    <div class="px-4 mb-3 mt-6">
                                        <p class="text-gray-500 uppercase text-xs font-semibold">Financeiro</p>
                                    </div>
                                    
                                    <!-- Bank Accounts Link -->
                                    <a href="{{ route('admin.bank-accounts.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.bank-accounts.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-bank"></i>
                                        </span>
                                        Contas
                                    </a>
                                    
                                    <!-- Transactions Link -->
                                    <a href="{{ route('admin.transactions.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-money-bill-transfer"></i>
                                        </span>
                                        Transações
                                    </a>
                                    
                                    <!-- Accounts Receivable Link -->
                                    <a href="{{ route('admin.accounts-receivable.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.accounts-receivable.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                            <i class="fi fi-rr-money-transfer-coin-arrow"></i>
                                        </span>
                                        Contas a Receber
                                    </a>
                                    
                                    <!-- Accounts Payable Link -->
                                    <a href="{{ route('admin.accounts-payable.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.accounts-payable.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                        <i class="fi fi-rr-money-wings"></i>
                                        </span>
                                        Contas a Pagar
                                    </a>
                                    
                                    <!-- Commissions Link -->
                                    <a href="{{ route('admin.commissions.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.commissions.*') ? 'bg-blue-100' : '' }}">
                                        <span class="mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </span>
                                        Comissões
                                    </a>
                                    
                                    <div class="px-4 mb-3 mt-6">
                                        <p class="text-gray-500 uppercase text-xs font-semibold">Sistema</p>
                                    </div>
                                    
                                    <!-- Settings Dropdown -->
                                    <div x-data="{ settingsOpen: false }" class="relative">
                                        <button @click="settingsOpen = !settingsOpen" 
                                                class="flex items-center justify-between w-full px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('admin.settings.*') ? 'bg-blue-100' : '' }}">
                                            <div class="flex items-center">
                                                <span class="mr-2">
                                                    <i class="fi fi-rr-settings"></i>
                                                </span>
                                                <span>Configurações</span>
                                            </div>
                                            <svg :class="{'rotate-180': settingsOpen}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        
                                        <div x-show="settingsOpen" 
                                             x-transition:enter="transition ease-out duration-100" 
                                             x-transition:enter-start="transform opacity-0 scale-95" 
                                             x-transition:enter-end="transform opacity-100 scale-100" 
                                             x-transition:leave="transition ease-in duration-75" 
                                             x-transition:leave-start="transform opacity-100 scale-100" 
                                             x-transition:leave-end="transform opacity-0 scale-95" 
                                             class="pl-8">
                                            
                                            <a href="{{ route('admin.settings.general') }}" 
                                               class="block px-4 py-2 text-sm hover:bg-blue-100 {{ request()->routeIs('admin.settings.general') ? 'bg-blue-100' : '' }}">
                                                <span class="mr-2">
                                                    <i class="fi fi-rr-apps"></i>
                                                </span>
                                                Geral
                                            </a>
                                            
                                            <a href="{{ route('admin.settings.email') }}" 
                                               class="block px-4 py-2 text-sm hover:bg-blue-100 {{ request()->routeIs('admin.settings.email') ? 'bg-blue-100' : '' }}">
                                                <span class="mr-2">
                                                    <i class="fi fi-rr-envelope"></i>
                                                </span>
                                                E-mail
                                            </a>
                                            
                                            <a href="{{ route('admin.settings.seo') }}" 
                                               class="block px-4 py-2 text-sm hover:bg-blue-100 {{ request()->routeIs('admin.settings.seo') ? 'bg-blue-100' : '' }}">
                                                <span class="mr-2">
                                                    <i class="fi fi-rr-megaphone"></i>
                                                </span>
                                                SEO
                                            </a>
                                            
                                            <a href="{{ route('admin.settings.security') }}" 
                                               class="block px-4 py-2 text-sm hover:bg-blue-100 {{ request()->routeIs('admin.settings.security') ? 'bg-blue-100' : '' }}">
                                                <span class="mr-2">
                                                    <i class="fi fi-rr-shield-check"></i>
                                                </span>
                                                Segurança
                                            </a>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </aside>

                        <!-- Main Content -->
                        <div class="flex-1 flex flex-col overflow-hidden relative">
                            <!-- Top navigation -->
                            <header class="bg-white shadow-sm z-10">
                                <div class="max-w-full px-4 sm:px-6 lg:px-8">
                                    <div class="flex justify-between h-16">
                                        <!-- Mobile hamburger button -->
                                        <div class="flex items-center lg:hidden">
                                            <button @click="sidebarOpen = true" class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Header right side (profile dropdown) -->
                                        <div class="ml-auto flex items-center">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        <div>{{ Auth::user()->name }}</div>

                                                        <div class="ml-1">
                                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">
                                                    <x-dropdown-link :href="route('profile.edit')">
                                                        {{ __('Profile') }}
                                                    </x-dropdown-link>

                                                    <!-- Authentication -->
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf

                                                        <x-dropdown-link :href="route('logout')"
                                                                onclick="event.preventDefault();
                                                                            this.closest('form').submit();">
                                                            {{ __('Log Out') }}
                                                        </x-dropdown-link>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                    </div>
                                </div>
                            </header>

                            <!-- Page header with title -->
                            @isset($header)
                                <header class="bg-white shadow">
                                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                        {{ $header }}
                                    </div>
                                </header>
                            @endisset

                            <!-- Main content -->
                            <main class="flex-1 overflow-y-auto p-4">
                                @isset($slot)
                                    {{ $slot }}
                                @endisset
                                @yield('content')
                            </main>
                        </div>
                    </div>
                @else
                    <!-- Simple Layout for Regular Clients -->
                    <div>
                        @include('layouts.navigation')

                        <!-- Page Heading -->
                        @isset($header)
                            <header class="bg-white shadow">
                                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endisset

                        <!-- Page Content -->
                        <main>
                            @isset($slot)
                                {{ $slot }}
                            @endisset
                            @yield('content')
                        </main>
                    </div>
                @endif
            @else
                <!-- Guest Layout -->
                <div>
                    @include('layouts.navigation')

                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main>
                        @isset($slot)
                            {{ $slot }}
                        @endisset
                        @yield('content')
                    </main>
                </div>
            @endauth
        </div>
        
        @livewireScripts
        @stack('scripts')
        
        <script>
            // Registrar o service worker apenas uma vez por sessão
            if ('serviceWorker' in navigator && !sessionStorage.getItem('swRegistered')) {
                // Primeiro, marcar como registrado para prevenir registros duplos
                sessionStorage.setItem('swRegistered', 'true');
                
                // Registrar o service worker sem forçar recarregamento
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {
                        console.log('Service worker registrado com sucesso:', registration.scope);
                        
                        // Verificar se há uma nova versão, sem forçar atualização
                        registration.update();
                    })
                    .catch(error => {
                        console.error('Falha ao registrar service worker:', error);
                    });
            }
            
            // Interceptar cliques em links para melhorar a navegação
            document.addEventListener('click', function(e) {
                // Verificar se o elemento clicado é um link ou tem um link como pai
                const link = e.target.closest('a');
                
                if (link && 
                    link.href.startsWith(window.location.origin) && 
                    !link.getAttribute('download') &&
                    !link.getAttribute('target') &&
                    !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    
                    // Impedir comportamento padrão para links internos
                    const currentPath = window.location.pathname;
                    const targetPath = new URL(link.href).pathname;
                    
                    // Se estiver navegando para a mesma página, previna o recarregamento
                    if (currentPath === targetPath) {
                        e.preventDefault();
                        // Opcional: role até o topo da página
                        window.scrollTo(0, 0);
                    }
                }
            });
        </script>
        @yield('scripts')
    </body>
</html>