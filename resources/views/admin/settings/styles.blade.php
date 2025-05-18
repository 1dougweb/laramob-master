<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Configurações') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex text-sm text-gray-500 mb-4">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-500">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">Configurações</span>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Categories Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Comum</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- General Settings -->
                            <a href="{{ route('admin.settings.general') }}" class="flex p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="mr-4 text-gray-500">
                                    <i class="fi fi-rr-settings text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Geral</h4>
                                    <p class="text-sm text-gray-500">Visualizar e atualizar configurações gerais</p>
                                </div>
                            </a>
                            
                            <!-- Email Settings -->
                            <a href="{{ route('admin.settings.email') }}" class="flex p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="mr-4 text-gray-500">
                                    <i class="fi fi-rr-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">E-mail</h4>
                                    <p class="text-sm text-gray-500">Configurar servidor SMTP e templates</p>
                                </div>
                            </a>
                            
                            <!-- SEO Settings -->
                            <a href="{{ route('admin.settings.seo') }}" class="flex p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="mr-4 text-gray-500">
                                    <i class="fi fi-rr-megaphone text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">SEO</h4>
                                    <p class="text-sm text-gray-500">Otimização para mecanismos de busca</p>
                                </div>
                            </a>
                            
                            <!-- Security Settings -->
                            <a href="{{ route('admin.settings.security') }}" class="flex p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="mr-4 text-gray-500">
                                    <i class="fi fi-rr-shield-check text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Segurança</h4>
                                    <p class="text-sm text-gray-500">Políticas de senha e configurações de segurança</p>
                                </div>
                            </a>
                            
                            <!-- Style Settings -->
                            <a href="{{ route('admin.settings.styles') }}" class="flex p-4 border border-gray-200 rounded-lg bg-blue-50 border-blue-200">
                                <div class="mr-4 text-blue-500">
                                    <i class="fi fi-rr-paint-brush text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-blue-700">Estilos</h4>
                                    <p class="text-sm text-blue-500">Personalizar aparência e cores do sistema</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Styles Form -->
                    <div class="mt-10">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Configurações de Estilo</h3>
                        
                        <form action="{{ route('admin.settings.styles.save') }}" method="POST" class="space-y-8">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Coluna da Esquerda - Cores Principais -->
                                <div class="space-y-6">
                                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cores Principais</h3>
                                    
                                        <div class="mb-4">
                                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Cor Primária</label>
                                            <div class="flex items-center">
                                                <input type="color" name="primary_color" id="primary_color" value="{{ $settings['primary_color'] ?? '#3b82f6' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                <input type="text" name="primary_color_text" id="primary_color_text" value="{{ $settings['primary_color'] ?? '#3b82f6' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Cor utilizada em botões e elementos de destaque</p>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Cor Secundária</label>
                                            <div class="flex items-center">
                                                <input type="color" name="secondary_color" id="secondary_color" value="{{ $settings['secondary_color'] ?? '#1e40af' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                <input type="text" name="secondary_color_text" id="secondary_color_text" value="{{ $settings['secondary_color'] ?? '#1e40af' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Cor complementar usada em destaques secundários</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Estilo da Interface -->
                                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estilo da Interface</h3>
                                        
                                        <div class="mb-4">
                                            <label for="font_family" class="block text-sm font-medium text-gray-700 mb-1">Fonte Principal</label>
                                            <select name="font_family" id="font_family" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="figtree" {{ ($settings['font_family'] ?? 'figtree') == 'figtree' ? 'selected' : '' }}>Figtree</option>
                                                <option value="inter" {{ ($settings['font_family'] ?? '') == 'inter' ? 'selected' : '' }}>Inter</option>
                                                <option value="roboto" {{ ($settings['font_family'] ?? '') == 'roboto' ? 'selected' : '' }}>Roboto</option>
                                                <option value="open-sans" {{ ($settings['font_family'] ?? '') == 'open-sans' ? 'selected' : '' }}>Open Sans</option>
                                                <option value="montserrat" {{ ($settings['font_family'] ?? '') == 'montserrat' ? 'selected' : '' }}>Montserrat</option>
                                                <option value="nunito" {{ ($settings['font_family'] ?? '') == 'nunito' ? 'selected' : '' }}>Nunito</option>
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label for="border_radius" class="block text-sm font-medium text-gray-700 mb-1">Arredondamento de Bordas</label>
                                            <select name="border_radius" id="border_radius" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="default" {{ ($settings['border_radius'] ?? 'default') == 'default' ? 'selected' : '' }}>Padrão</option>
                                                <option value="none" {{ ($settings['border_radius'] ?? '') == 'none' ? 'selected' : '' }}>Sem arredondamento</option>
                                                <option value="small" {{ ($settings['border_radius'] ?? '') == 'small' ? 'selected' : '' }}>Pequeno</option>
                                                <option value="large" {{ ($settings['border_radius'] ?? '') == 'large' ? 'selected' : '' }}>Grande</option>
                                                <option value="full" {{ ($settings['border_radius'] ?? '') == 'full' ? 'selected' : '' }}>Completo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Coluna da Direita - Barra Lateral e Cabeçalho -->
                                <div class="space-y-6">
                                    <!-- Estilos Barra Lateral -->
                                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Barra Lateral</h3>
                                        
                                        <div class="mb-4">
                                            <label for="sidebar_style" class="block text-sm font-medium text-gray-700 mb-1">Estilo da Barra Lateral</label>
                                            <select name="sidebar_style" id="sidebar_style" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="light" {{ ($settings['sidebar_style'] ?? 'light') == 'light' ? 'selected' : '' }}>Claro</option>
                                                <option value="dark" {{ ($settings['sidebar_style'] ?? '') == 'dark' ? 'selected' : '' }}>Escuro</option>
                                                <option value="colored" {{ ($settings['sidebar_style'] ?? '') == 'colored' ? 'selected' : '' }}>Colorido</option>
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label for="sidebar_bg_color" class="block text-sm font-medium text-gray-700 mb-1">Cor de Fundo</label>
                                            <div class="flex items-center">
                                                <input type="color" name="sidebar_bg_color" id="sidebar_bg_color" value="{{ $settings['sidebar_bg_color'] ?? '#ffffff' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                <input type="text" name="sidebar_bg_color_text" id="sidebar_bg_color_text" value="{{ $settings['sidebar_bg_color'] ?? '#ffffff' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="text_color" class="block text-sm font-medium text-gray-700 mb-1">Cor do Texto</label>
                                            <div class="flex items-center">
                                                <input type="color" name="text_color" id="text_color" value="{{ $settings['text_color'] ?? '#1f2937' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                <input type="text" name="text_color_text" id="text_color_text" value="{{ $settings['text_color'] ?? '#1f2937' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="icon_color" class="block text-sm font-medium text-gray-700 mb-1">Cor dos Ícones</label>
                                            <div class="flex items-center">
                                                <input type="color" name="icon_color" id="icon_color" value="{{ $settings['icon_color'] ?? '#4b5563' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                <input type="text" name="icon_color_text" id="icon_color_text" value="{{ $settings['icon_color'] ?? '#4b5563' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Estados de Interação -->
                                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estados de Interação</h3>
                                        
                                        <div class="border-b pb-3 mb-3">
                                            <h4 class="text-md font-medium text-gray-800 mb-2">Estado Hover</h4>
                                            
                                            <div class="mb-3">
                                                <label for="hover_bg_color" class="block text-sm font-medium text-gray-700 mb-1">Fundo no Hover</label>
                                                <div class="flex items-center">
                                                    <input type="color" name="hover_bg_color" id="hover_bg_color" value="{{ $settings['hover_bg_color'] ?? '#f3f4f6' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                    <input type="text" name="hover_bg_color_text" id="hover_bg_color_text" value="{{ $settings['hover_bg_color'] ?? '#f3f4f6' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="hover_text_color" class="block text-sm font-medium text-gray-700 mb-1">Texto no Hover</label>
                                                <div class="flex items-center">
                                                    <input type="color" name="hover_text_color" id="hover_text_color" value="{{ $settings['hover_text_color'] ?? '#111827' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                    <input type="text" name="hover_text_color_text" id="hover_text_color_text" value="{{ $settings['hover_text_color'] ?? '#111827' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="hover_icon_color" class="block text-sm font-medium text-gray-700 mb-1">Ícones no Hover</label>
                                                <div class="flex items-center">
                                                    <input type="color" name="hover_icon_color" id="hover_icon_color" value="{{ $settings['hover_icon_color'] ?? '#111827' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                    <input type="text" name="hover_icon_color_text" id="hover_icon_color_text" value="{{ $settings['hover_icon_color'] ?? '#111827' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-md font-medium text-gray-800 mb-2">Estado Ativo</h4>
                                            
                                            <div class="mb-3">
                                                <label for="active_bg_color" class="block text-sm font-medium text-gray-700 mb-1">Fundo no Ativo</label>
                                                <div class="flex items-center">
                                                    <input type="color" name="active_bg_color" id="active_bg_color" value="{{ $settings['active_bg_color'] ?? '#e0f2fe' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                    <input type="text" name="active_bg_color_text" id="active_bg_color_text" value="{{ $settings['active_bg_color'] ?? '#e0f2fe' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="active_text_color" class="block text-sm font-medium text-gray-700 mb-1">Texto no Ativo</label>
                                                <div class="flex items-center">
                                                    <input type="color" name="active_text_color" id="active_text_color" value="{{ $settings['active_text_color'] ?? '#1e40af' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                    <input type="text" name="active_text_color_text" id="active_text_color_text" value="{{ $settings['active_text_color'] ?? '#1e40af' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="active_icon_color" class="block text-sm font-medium text-gray-700 mb-1">Ícones no Ativo</label>
                                                <div class="flex items-center">
                                                    <input type="color" name="active_icon_color" id="active_icon_color" value="{{ $settings['active_icon_color'] ?? '#1e40af' }}" class="h-10 w-20 border border-gray-300 rounded">
                                                    <input type="text" name="active_icon_color_text" id="active_icon_color_text" value="{{ $settings['active_icon_color'] ?? '#1e40af' }}" class="ml-2 w-28 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Preview Section -->
                            <div class="mt-8 p-6 bg-gray-50 border border-gray-200 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Visualização</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Sidebar Preview -->
                                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                        <div class="p-4 text-white bg-blue-600" id="preview_logo_bg">
                                            <div class="font-bold text-lg">Barra Lateral</div>
                                        </div>
                                        <div class="p-4" id="preview_sidebar_bg">
                                            <p class="text-xs text-gray-500 uppercase mb-2" id="preview_section_title">MENU</p>
                                            
                                            <div class="space-y-2">
                                                <div class="flex items-center p-2 rounded" id="preview_menu_item">
                                                    <span class="mr-2 text-gray-500" id="preview_icon">
                                                        <i class="fi fi-rr-dashboard"></i>
                                                    </span>
                                                    <span id="preview_text">Item do Menu</span>
                                                </div>
                                                
                                                <div class="flex items-center p-2 rounded hover:bg-blue-50" id="preview_menu_hover">
                                                    <span class="mr-2 text-gray-500" id="preview_hover_icon">
                                                        <i class="fi fi-rr-user"></i>
                                                    </span>
                                                    <span id="preview_hover_text">Item com Hover</span>
                                                </div>
                                                
                                                <div class="flex items-center p-2 rounded bg-blue-50" id="preview_menu_active">
                                                    <span class="mr-2 text-blue-600" id="preview_active_icon">
                                                        <i class="fi fi-rr-settings"></i>
                                                    </span>
                                                    <span class="text-blue-600" id="preview_active_text">Item Ativo</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- UI Elements Preview -->
                                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm p-4">
                                        <h4 class="text-base font-medium text-gray-900 mb-3">Elementos de UI</h4>
                                        
                                        <div class="space-y-3">
                                            <div class="flex space-x-2">
                                                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" id="preview_primary_button">
                                                    Botão Primário
                                                </button>
                                                <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50" id="preview_secondary_button">
                                                    Botão Secundário
                                                </button>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Campo de Texto</label>
                                                <input type="text" value="Exemplo de Texto" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" id="preview_input">
                                            </div>
                                            
                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Dropdown</label>
                                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" id="preview_select">
                                                    <option>Opção 1</option>
                                                    <option>Opção 2</option>
                                                    <option>Opção 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6 border-t">
                                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('Salvar Configurações') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        @vite(['resources/js/settings-styles.js'])
    @endpush
</x-app-layout>