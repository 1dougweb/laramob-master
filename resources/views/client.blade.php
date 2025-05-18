<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Área do Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Bem-vindo, {{ Auth::user()->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">Utilize os menus abaixo para gerenciar suas informações e acessar os serviços</p>
                </div>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Painel do Cliente</h3>
                        
                        <a href="{{ route('client.dashboard') }}" class="block bg-blue-50 hover:bg-blue-100 p-4 rounded-md transition-colors duration-150">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fi fi-sr-user text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-blue-800">Meu Dashboard</h4>
                                    <p class="text-xs text-blue-600">Acesse seu painel completo com resumo de atividades</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Imóveis -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Imóveis</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('client.properties.index') }}" class="block bg-indigo-50 hover:bg-indigo-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fi fi-rr-search text-indigo-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-indigo-800">Buscar Imóveis</h4>
                                        <p class="text-xs text-indigo-600">Encontre as melhores opções disponíveis</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ route('client.properties.favorites') }}" class="block bg-red-50 hover:bg-red-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-red-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fi fi-sr-heart text-red-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-red-800">Meus Favoritos</h4>
                                        <p class="text-xs text-red-600">Veja os imóveis que você salvou</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Documentos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos</h3>
                        
                        <a href="{{ route('client.documents.index') }}" class="block bg-green-50 hover:bg-green-100 p-4 rounded-md transition-colors duration-150">
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fi fi-rr-document text-green-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-green-800">Meus Documentos</h4>
                                    <p class="text-xs text-green-600">Acesse todos os seus documentos compartilhados</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Informações adicionais -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Precisa de ajuda?</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="bg-yellow-100 rounded-full w-10 h-10 flex items-center justify-center mt-1">
                                    <i class="fi fi-rr-comment-alt text-yellow-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-yellow-800">Fale com seu corretor</h4>
                                    <p class="text-xs text-yellow-700 mt-1">Tire suas dúvidas diretamente com seu corretor designado.</p>
                                    <a href="#" class="inline-block mt-2 text-xs font-medium text-yellow-600 hover:text-yellow-800">
                                        Entre em contato →
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="bg-purple-100 rounded-full w-10 h-10 flex items-center justify-center mt-1">
                                    <i class="fi fi-rr-interrogation text-purple-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-purple-800">Perguntas frequentes</h4>
                                    <p class="text-xs text-purple-700 mt-1">Encontre respostas para as perguntas mais comuns.</p>
                                    <a href="#" class="inline-block mt-2 text-xs font-medium text-purple-600 hover:text-purple-800">
                                        Ver perguntas →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 