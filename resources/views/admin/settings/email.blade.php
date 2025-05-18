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
                            <a href="{{ route('admin.settings.email') }}" class="flex p-4 border border-gray-200 rounded-lg bg-blue-50 border-blue-200">
                                <div class="mr-4 text-blue-500">
                                    <i class="fi fi-rr-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-blue-700">E-mail</h4>
                                    <p class="text-sm text-blue-500">Configurar servidor SMTP e templates</p>
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

                    <!-- Email Settings Form -->
                    <div class="mt-10">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Configurações de E-mail</h3>
                        
                        <form action="{{ route('admin.settings.save') }}" method="POST" class="space-y-8">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- SMTP Settings -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações SMTP</h3>
                                    
                                    <div class="mb-4">
                                        <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-1">Driver de E-mail</label>
                                        <select name="mail_driver" id="mail_driver" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="sendmail" {{ ($settings['mail_driver'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                            <option value="mailgun" {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                            <option value="ses" {{ ($settings['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">Servidor SMTP</label>
                                        <input type="text" name="mail_host" id="mail_host" value="{{ $settings['mail_host'] ?? 'smtp.example.com' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">Porta SMTP</label>
                                        <input type="number" name="mail_port" id="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-1">Usuário SMTP</label>
                                        <input type="text" name="mail_username" id="mail_username" value="{{ $settings['mail_username'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-1">Senha SMTP</label>
                                        <input type="password" name="mail_password" id="mail_password" value="{{ $settings['mail_password'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-1">Criptografia</label>
                                        <select name="mail_encryption" id="mail_encryption" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>Nenhuma</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Email Sender Settings -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Remetente dos E-mails</h3>
                                    
                                    <div class="mb-4">
                                        <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-1">E-mail do Remetente</label>
                                        <input type="email" name="mail_from_address" id="mail_from_address" value="{{ $settings['mail_from_address'] ?? 'noreply@example.com' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-1">Nome do Remetente</label>
                                        <input type="text" name="mail_from_name" id="mail_from_name" value="{{ $settings['mail_from_name'] ?? config('app.name', 'LaraMob') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="mail_reply_to" class="block text-sm font-medium text-gray-700 mb-1">E-mail para Resposta</label>
                                        <input type="email" name="mail_reply_to" id="mail_reply_to" value="{{ $settings['mail_reply_to'] ?? '' }}" placeholder="Opcional - Se diferente do remetente" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                                
                                <!-- Test Email Settings -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Testar Configurações</h3>
                                    
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <i class="fi fi-rr-info text-blue-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-blue-700">
                                                    Salve as configurações antes de testar o envio de e-mail. Um e-mail de teste será enviado para o endereço especificado.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="test_email" class="block text-sm font-medium text-gray-700 mb-1">E-mail para Teste</label>
                                        <div class="flex">
                                            <input type="email" name="test_email" id="test_email" placeholder="seu@email.com" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <button type="button" id="send_test_email" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Enviar Teste
                                            </button>
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
</x-app-layout> 