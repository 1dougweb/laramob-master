<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Bem-vindo, {{ Auth::user()->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">Utilize os menus abaixo para navegar pelas funcionalidades</p>
                </div>
            </div>
            
            <!-- Quick Access Section -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Properties -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Imóveis</h3>
                        
                        <div class="space-y-2">
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

                <!-- Client Area -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Área do Cliente</h3>
                        
                        <div class="space-y-2">
                            <a href="{{ route('client') }}" class="block bg-purple-50 hover:bg-purple-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fi fi-sr-apps text-purple-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-purple-800">Portal do Cliente</h4>
                                        <p class="text-xs text-purple-600">Acesse todas as funcionalidades</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ route('client.dashboard') }}" class="block bg-blue-50 hover:bg-blue-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fi fi-sr-user text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800">Meu Painel</h4>
                                        <p class="text-xs text-blue-600">Acesse seu painel de cliente</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ route('client.documents.index') }}" class="block bg-green-50 hover:bg-green-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-green-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fi fi-rr-document text-green-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-green-800">Documentos</h4>
                                        <p class="text-xs text-green-600">Acesse seus documentos</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Perfil e Conta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sua Conta</h3>
                        
                        <div class="space-y-2">
                            <a href="{{ route('profile.edit') }}" class="block bg-amber-50 hover:bg-amber-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-amber-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fi fi-sr-user-pen text-amber-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-amber-800">Editar Perfil</h4>
                                        <p class="text-xs text-amber-600">Atualize suas informações pessoais</p>
                                    </div>
                                </div>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left bg-gray-50 hover:bg-gray-100 p-3 rounded-md transition-colors duration-150">
                                    <div class="flex items-center">
                                        <div class="bg-gray-200 rounded-full w-10 h-10 flex items-center justify-center">
                                            <i class="fi fi-br-sign-out-alt text-gray-600 text-xl"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-gray-800">Sair</h4>
                                            <p class="text-xs text-gray-600">Encerrar sua sessão</p>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
