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
                            <a href="{{ route('admin.settings.security') }}" class="flex p-4 border border-gray-200 rounded-lg bg-blue-50 border-blue-200">
                                <div class="mr-4 text-blue-500">
                                    <i class="fi fi-rr-shield-check text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-blue-700">Segurança</h4>
                                    <p class="text-sm text-blue-500">Políticas de senha e configurações de segurança</p>
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

                    <!-- Security Settings Form -->
                    <div class="mt-10">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Configurações de Segurança</h3>
                        
                        <form action="{{ route('admin.settings.save') }}" method="POST" class="space-y-8">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Password Policies -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Políticas de Senha</h3>
                                    
                                    <div class="mb-4">
                                        <label for="password_expiration_days" class="block text-sm font-medium text-gray-700 mb-1">Expiração de Senha (dias)</label>
                                        <input type="number" name="password_expiration_days" id="password_expiration_days" min="0" max="365" value="{{ $settings['password_expiration_days'] ?? '90' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Número de dias antes da senha expirar (0 = nunca expira)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-1">Tamanho Mínimo da Senha</label>
                                        <input type="number" name="password_min_length" id="password_min_length" min="6" max="32" value="{{ $settings['password_min_length'] ?? '8' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Número mínimo de caracteres para senhas (recomendado: 8 ou mais)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="require_uppercase" id="require_uppercase" value="1" {{ isset($settings['require_uppercase']) && $settings['require_uppercase'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="require_uppercase" class="ml-2 block text-sm text-gray-700">Exigir letra maiúscula</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">A senha deve conter pelo menos uma letra maiúscula</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="require_number" id="require_number" value="1" {{ isset($settings['require_number']) && $settings['require_number'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="require_number" class="ml-2 block text-sm text-gray-700">Exigir número</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">A senha deve conter pelo menos um número</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="require_special_char" id="require_special_char" value="1" {{ isset($settings['require_special_char']) && $settings['require_special_char'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="require_special_char" class="ml-2 block text-sm text-gray-700">Exigir caractere especial</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">A senha deve conter pelo menos um caractere especial (!@#$%^&*(),.?":{}|<>)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="force_password_change" id="force_password_change" value="1" {{ isset($settings['force_password_change']) && $settings['force_password_change'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="force_password_change" class="ml-2 block text-sm text-gray-700">Forçar troca de senha no primeiro login</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Usuários novos precisarão alterar sua senha no primeiro acesso</p>
                                    </div>
                                </div>
                                
                                <!-- Security Features -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recursos de Segurança</h3>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="enable_2fa" id="enable_2fa" value="1" {{ isset($settings['enable_2fa']) && $settings['enable_2fa'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="enable_2fa" class="ml-2 block text-sm text-gray-700">Habilitar Autenticação de Dois Fatores</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Permite que usuários configurem autenticação de dois fatores para maior segurança</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="enable_recaptcha" id="enable_recaptcha" value="1" {{ isset($settings['enable_recaptcha']) && $settings['enable_recaptcha'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="enable_recaptcha" class="ml-2 block text-sm text-gray-700">Habilitar reCAPTCHA em formulários públicos</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Adiciona proteção contra bots em formulários de login, registro e contato</p>
                                    </div>
                                </div>
                                
                                <!-- reCAPTCHA Settings -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações do reCAPTCHA</h3>
                                    
                                    <div class="mb-4">
                                        <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700 mb-1">Site Key</label>
                                        <input type="text" name="recaptcha_site_key" id="recaptcha_site_key" value="{{ $settings['recaptcha_site_key'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Chave do site obtida no console do Google reCAPTCHA</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                                        <input type="password" name="recaptcha_secret_key" id="recaptcha_secret_key" value="{{ $settings['recaptcha_secret_key'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Chave secreta obtida no console do Google reCAPTCHA</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="recaptcha_version" class="block text-sm font-medium text-gray-700 mb-1">Versão do reCAPTCHA</label>
                                        <select name="recaptcha_version" id="recaptcha_version" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="v2" {{ ($settings['recaptcha_version'] ?? 'v2') == 'v2' ? 'selected' : '' }}>v2 (Checkbox)</option>
                                            <option value="v3" {{ ($settings['recaptcha_version'] ?? '') == 'v3' ? 'selected' : '' }}>v3 (Invisível)</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Escolha a versão do reCAPTCHA a ser utilizada</p>
                                    </div>
                                </div>
                                
                                <!-- Session Settings -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Sessão</h3>
                                    
                                    <div class="mb-4">
                                        <label for="session_lifetime" class="block text-sm font-medium text-gray-700 mb-1">Tempo de Vida da Sessão (minutos)</label>
                                        <input type="number" name="session_lifetime" id="session_lifetime" min="5" max="1440" value="{{ $settings['session_lifetime'] ?? '120' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Tempo de inatividade antes da sessão expirar (padrão: 120 minutos)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="session_secure_cookie" id="session_secure_cookie" value="1" {{ isset($settings['session_secure_cookie']) && $settings['session_secure_cookie'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="session_secure_cookie" class="ml-2 block text-sm text-gray-700">Cookies somente em HTTPS</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Os cookies de sessão só serão enviados por HTTPS (recomendado para produção)</p>
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