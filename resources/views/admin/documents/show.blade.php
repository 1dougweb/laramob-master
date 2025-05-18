<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Documento') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.people.documents.edit', [$person, $document]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('admin.people.documents.download', [$person, $document]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Download') }}
                </a>
                <a href="{{ route('admin.people.documents.index', $person) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    {{ __('Voltar para Lista') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row md:space-x-8">
                        <!-- Informações básicas -->
                        <div class="w-full md:w-1/2 mb-6 md:mb-0">
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Documento</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Título</span>
                                        <span class="block mt-1 text-lg">{{ $document->title }}</span>
                                    </div>

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Tipo</span>
                                        <span class="block mt-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $document->type == 'contract' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $document->type == 'identity' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $document->type == 'address_proof' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $document->type == 'property' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                {{ $document->type == 'financial' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $document->type == 'other' ? 'bg-gray-100 text-gray-800' : '' }}
                                            ">
                                                @if($document->type == 'contract')
                                                    Contrato
                                                @elseif($document->type == 'identity')
                                                    Identidade
                                                @elseif($document->type == 'address_proof')
                                                    Comprovante de Endereço
                                                @elseif($document->type == 'property')
                                                    Documentação de Imóvel
                                                @elseif($document->type == 'financial')
                                                    Financeiro
                                                @elseif($document->type == 'other')
                                                    Outro
                                                @else
                                                    {{ $document->type }}
                                                @endif
                                            </span>
                                        </span>
                                    </div>

                                    @if($document->description)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Descrição</span>
                                        <span class="block mt-1">{{ $document->description }}</span>
                                    </div>
                                    @endif

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Arquivo</span>
                                        <span class="block mt-1">{{ $document->file_name }}</span>
                                        <span class="block mt-1 text-sm text-gray-500">
                                            Tipo: {{ $document->file_type }} | 
                                            Tamanho: {{ $document->formatted_size }}
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Data de Expiração</span>
                                        <span class="block mt-1">
                                            @if($document->expiration_date)
                                                {{ $document->expiration_date->format('d/m/Y') }}
                                                @if($document->is_expired)
                                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Expirado
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">Não expira</span>
                                            @endif
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Visibilidade</span>
                                        <span class="block mt-1">
                                            @if($document->is_private)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Privado
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Público
                                                </span>
                                            @endif
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Compartilhamento</span>
                                        <span class="block mt-1">
                                            @if($document->shared_at)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Compartilhado em {{ $document->shared_at->format('d/m/Y H:i') }}
                                                </span>
                                                
                                                <form action="{{ route('admin.people.documents.unshare', [$person, $document]) }}" method="POST" class="inline-block mt-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                                        Remover compartilhamento
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Não compartilhado
                                                </span>
                                                
                                                <form action="{{ route('admin.people.documents.share', [$person, $document]) }}" method="POST" class="inline-block mt-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-900">
                                                        Compartilhar com o cliente
                                                    </button>
                                                </form>
                                            @endif
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Cadastrado em</span>
                                        <span class="block mt-1">{{ $document->created_at->format('d/m/Y H:i') }}</span>
                                    </div>

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Última atualização</span>
                                        <span class="block mt-1">{{ $document->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Visualização do documento (para imagens) -->
                        <div class="w-full md:w-1/2">
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Visualização</h3>
                                
                                @if(in_array($document->file_type, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']))
                                    <div class="flex justify-center">
                                        <img src="{{ Storage::disk('private')->url($document->file_path) }}" alt="{{ $document->title }}" class="max-w-full h-auto rounded border border-gray-200">
                                    </div>
                                @elseif(in_array($document->file_type, ['application/pdf']))
                                    <div class="flex flex-col items-center justify-center py-6 bg-gray-100 rounded border border-gray-200">
                                        <svg class="h-16 w-16 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="mt-2 text-gray-600">Documento PDF</span>
                                        <a href="{{ route('admin.people.documents.download', [$person, $document]) }}" class="mt-4 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Baixar PDF
                                        </a>
                                    </div>
                                @elseif(in_array($document->file_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                                    <div class="flex flex-col items-center justify-center py-6 bg-gray-100 rounded border border-gray-200">
                                        <svg class="h-16 w-16 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="mt-2 text-gray-600">Documento Word</span>
                                        <a href="{{ route('admin.people.documents.download', [$person, $document]) }}" class="mt-4 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Baixar Documento
                                        </a>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-6 bg-gray-100 rounded border border-gray-200">
                                        <svg class="h-16 w-16 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="mt-2 text-gray-600">Tipo de arquivo: {{ $document->file_type }}</span>
                                        <a href="{{ route('admin.people.documents.download', [$person, $document]) }}" class="mt-4 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Baixar Arquivo
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Ações</h3>
                                
                                <div class="flex flex-col space-y-4">
                                    <a href="{{ route('admin.people.documents.edit', [$person, $document]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar Documento
                                    </a>
                                    
                                    <a href="{{ route('admin.people.documents.download', [$person, $document]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Baixar Documento
                                    </a>
                                    
                                    <form action="{{ route('admin.people.documents.destroy', [$person, $document]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este documento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 w-full">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Excluir Documento
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 