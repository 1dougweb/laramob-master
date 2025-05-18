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
                            <a href="{{ route('admin.settings.general') }}" class="flex p-4 border border-gray-200 rounded-lg bg-blue-50 border-blue-200">
                                <div class="mr-4 text-blue-500">
                                    <i class="fi fi-rr-settings text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-blue-700">Geral</h4>
                                    <p class="text-sm text-blue-500">Visualizar e atualizar configurações gerais</p>
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
                                    <p class="text-sm text-gray-500">Senha e configurações de segurança</p>
                                </div>
                            </a>
                            
                            <!-- Style Settings -->
                            <a href="{{ route('admin.settings.styles') }}" class="flex p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="mr-4 text-gray-500">
                                    <i class="fi fi-rr-paint-brush text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Estilos</h4>
                                    <p class="text-sm text-gray-500">Personalizar aparência e cores do sistema</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- General Settings Form -->
                    <div class="mt-10">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Configurações Gerais</h3>
                        
                        <form action="{{ route('admin.settings.save') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Informações do Site -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Site</h3>
                                    
                                    <div class="mb-4">
                                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Nome do Site</label>
                                        <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? 'LaraMob' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Descrição do Site</label>
                                        <textarea name="site_description" id="site_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{ $settings['site_description'] ?? 'Sistema imobiliário' }}</textarea>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">E-mail do Administrador</label>
                                        <input type="email" name="admin_email" id="admin_email" value="{{ $settings['admin_email'] ?? 'admin@exemplo.com' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-1">Logo do Site</label>
                                        <div class="flex items-center">
                                            @if(isset($settings['site_logo']) && $settings['site_logo'])
                                                <div class="mr-4">
                                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-12 w-auto">
                                                </div>
                                            @endif
                                            <input type="file" name="site_logo" id="site_logo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Formatos aceitos: PNG, JPG, SVG. Tamanho recomendado: 150x50px</p>
                                    </div>
                                </div>
                                
                                <!-- Configurações Regionais -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações Regionais</h3>
                                    
                                    <div class="mb-4">
                                        <label for="currency_symbol" class="block text-sm font-medium text-gray-700 mb-1">Símbolo de Moeda</label>
                                        <input type="text" name="currency_symbol" id="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'R$' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-1">Formato de Data</label>
                                        <select name="date_format" id="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/AAAA (31/12/2023)</option>
                                            <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/AAAA (12/31/2023)</option>
                                            <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>AAAA-MM-DD (2023-12-31)</option>
                                            <option value="d.m.Y" {{ ($settings['date_format'] ?? '') == 'd.m.Y' ? 'selected' : '' }}>DD.MM.AAAA (31.12.2023)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="time_format" class="block text-sm font-medium text-gray-700 mb-1">Formato de Horário</label>
                                        <select name="time_format" id="time_format" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="H:i" {{ ($settings['time_format'] ?? 'H:i') == 'H:i' ? 'selected' : '' }}>24 horas (14:30)</option>
                                            <option value="h:i A" {{ ($settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>12 horas (02:30 PM)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Configurações de Sistema -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Sistema</h3>
                                    
                                    <div class="mb-4">
                                        <label for="items_per_page" class="block text-sm font-medium text-gray-700 mb-1">Itens por Página</label>
                                        <input type="number" name="items_per_page" id="items_per_page" value="{{ $settings['items_per_page'] ?? 15 }}" min="5" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Número de itens a serem mostrados por página em listas e tabelas</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="enable_registrations" id="enable_registrations" value="1" {{ isset($settings['enable_registrations']) && $settings['enable_registrations'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="enable_registrations" class="ml-2 block text-sm text-gray-700">Permitir registros de usuários</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Se desativado, novos usuários não poderão se registrar no sistema</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="enable_blog" id="enable_blog" value="1" {{ isset($settings['enable_blog']) && $settings['enable_blog'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="enable_blog" class="ml-2 block text-sm text-gray-700">Habilitar Blog</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Ativar ou desativar o sistema de blog</p>
                                    </div>
                                </div>
                                
                                <!-- Google Analytics -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Integrações</h3>
                                    
                                    <div class="mb-4">
                                        <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">ID do Google Analytics</label>
                                        <input type="text" name="google_analytics_id" id="google_analytics_id" value="{{ $settings['google_analytics_id'] ?? '' }}" placeholder="G-XXXXXXXXXX ou UA-XXXXXXXX-X" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="enable_social_login" id="enable_social_login" value="1" {{ isset($settings['enable_social_login']) && $settings['enable_social_login'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="enable_social_login" class="ml-2 block text-sm text-gray-700">Habilitar login com redes sociais</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Permitir que usuários façam login com suas contas de redes sociais</p>
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
</x-app-layout> 