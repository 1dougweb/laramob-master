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
                            <a href="{{ route('admin.settings.seo') }}" class="flex p-4 border border-gray-200 rounded-lg bg-blue-50 border-blue-200">
                                <div class="mr-4 text-blue-500">
                                    <i class="fi fi-rr-megaphone text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-blue-700">SEO</h4>
                                    <p class="text-sm text-blue-500">Otimização para mecanismos de busca</p>
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

                    <!-- SEO Settings Form -->
                    <div class="mt-10">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Configurações de SEO</h3>
                        
                        <form action="{{ route('admin.settings.save') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Meta Tags -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Meta Tags</h3>
                                    
                                    <div class="mb-4">
                                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Título Meta</label>
                                        <input type="text" name="meta_title" id="meta_title" value="{{ $settings['meta_title'] ?? config('app.name', 'LaraMob') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">O título utilizado nas buscas do Google (recomendado: até 60 caracteres)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Descrição Meta</label>
                                        <textarea name="meta_description" id="meta_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{ $settings['meta_description'] ?? 'Sistema imobiliário completo para gestão de propriedades, clientes e transações.' }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1">A descrição que aparece abaixo do título nos resultados de busca (recomendado: até 160 caracteres)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Palavras-chave Meta</label>
                                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ $settings['meta_keywords'] ?? 'imobiliária, propriedades, imóveis, aluguel, venda' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Palavras-chave separadas por vírgula (menos importante atualmente para SEO, mas ainda utilizado por alguns buscadores)</p>
                                    </div>
                                </div>
                                
                                <!-- Social Media / Open Graph -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Compartilhamento em Redes Sociais</h3>
                                    
                                    <div class="mb-4">
                                        <label for="og_title" class="block text-sm font-medium text-gray-700 mb-1">Título para Redes Sociais</label>
                                        <input type="text" name="og_title" id="og_title" value="{{ $settings['og_title'] ?? '' }}" placeholder="Se diferente do título meta" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Título usado quando o site é compartilhado nas redes sociais (deixe em branco para usar o título meta)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="og_description" class="block text-sm font-medium text-gray-700 mb-1">Descrição para Redes Sociais</label>
                                        <textarea name="og_description" id="og_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Se diferente da descrição meta">{{ $settings['og_description'] ?? '' }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1">Descrição usada quando o site é compartilhado nas redes sociais (deixe em branco para usar a descrição meta)</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="og_image" class="block text-sm font-medium text-gray-700 mb-1">Imagem para Redes Sociais</label>
                                        <div class="flex items-center">
                                            @if(isset($settings['og_image']) && $settings['og_image'])
                                                <div class="mr-4">
                                                    <img src="{{ asset('storage/' . $settings['og_image']) }}" alt="Imagem OG" class="h-20 w-auto object-cover">
                                                </div>
                                            @endif
                                            <input type="file" name="og_image" id="og_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Imagem que aparecerá quando o site for compartilhado nas redes sociais (tamanho recomendado: 1200 x 630 pixels)</p>
                                    </div>
                                </div>
                                
                                <!-- Sitemap Settings -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sitemap</h3>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="generate_sitemap" id="generate_sitemap" value="1" {{ isset($settings['generate_sitemap']) && $settings['generate_sitemap'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="generate_sitemap" class="ml-2 block text-sm text-gray-700">Gerar sitemap automaticamente</label>
                                        </div>
                                        <p class="text-xs text-gray-500 ml-6">Ativa a geração automática do sitemap.xml, que ajuda os mecanismos de busca a indexar o site</p>
                                    </div>
                                    
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <i class="fi fi-rr-info text-blue-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-blue-700">
                                                    O sitemap.xml será gerado automaticamente e estará disponível em <span class="font-mono">{{ config('app.url') }}/sitemap.xml</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Robots.txt Settings -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Robots.txt</h3>
                                    
                                    <div class="mb-4">
                                        <label for="robots_txt" class="block text-sm font-medium text-gray-700 mb-1">Conteúdo do Robots.txt</label>
                                        <textarea name="robots_txt" id="robots_txt" rows="6" class="font-mono w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{ $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /register\n\nSitemap: https://seu-site.com/sitemap.xml" }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1">Define quais páginas os motores de busca podem acessar. Linhas começando com '#' são comentários.</p>
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