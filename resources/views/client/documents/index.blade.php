<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meus Documentos') }}
            </h2>
            <a href="{{ route('client.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Dashboard') }}
            </a>
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

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filtros -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-base font-medium text-gray-700 mb-3">Filtrar por tipo</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('client.documents.index') }}" class="px-3 py-1 rounded-full text-sm {{ !request('type') ? 'bg-blue-100 text-blue-800 font-medium' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Todos
                            </a>
                            <a href="{{ route('client.documents.index', ['type' => 'contract']) }}" class="px-3 py-1 rounded-full text-sm {{ request('type') == 'contract' ? 'bg-purple-100 text-purple-800 font-medium' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Contratos
                            </a>
                            <a href="{{ route('client.documents.index', ['type' => 'identity']) }}" class="px-3 py-1 rounded-full text-sm {{ request('type') == 'identity' ? 'bg-blue-100 text-blue-800 font-medium' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Identidade
                            </a>
                            <a href="{{ route('client.documents.index', ['type' => 'address_proof']) }}" class="px-3 py-1 rounded-full text-sm {{ request('type') == 'address_proof' ? 'bg-green-100 text-green-800 font-medium' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Comprovante de Endereço
                            </a>
                            <a href="{{ route('client.documents.index', ['type' => 'property']) }}" class="px-3 py-1 rounded-full text-sm {{ request('type') == 'property' ? 'bg-indigo-100 text-indigo-800 font-medium' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Documentos de Imóvel
                            </a>
                            <a href="{{ route('client.documents.index', ['type' => 'financial']) }}" class="px-3 py-1 rounded-full text-sm {{ request('type') == 'financial' ? 'bg-yellow-100 text-yellow-800 font-medium' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Financeiro
                            </a>
                            <a href="{{ route('client.documents.index', ['type' => 'other']) }}" class="px-3 py-1 rounded-full text-sm {{ request('type') == 'other' ? 'bg-gray-600 text-white font-medium' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Outros
                            </a>
                        </div>
                    </div>

                    @if(count($documents) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($documents as $document)
                                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="px-4 py-5 border-b border-gray-200 bg-gray-50">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $document->title }}</h3>
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
                                        </div>
                                        
                                        @if($document->description)
                                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $document->description }}</p>
                                        @endif
                                        
                                        <div class="mt-2 text-xs text-gray-500">
                                            Compartilhado em {{ $document->shared_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    
                                    <div class="px-4 py-3 bg-white">
                                        <div class="text-xs text-gray-500 mb-2 flex items-center">
                                            <span class="mr-2">{{ $document->file_name }}</span>
                                            <span class="text-gray-400">{{ $document->formatted_size }}</span>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('client.documents.show', $document) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver detalhes
                                            </a>
                                            <a href="{{ route('client.documents.download', $document) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                Download
                                            </a>
                                        </div>
                                        
                                        @if($document->expiration_date)
                                            <div class="mt-2">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ $document->is_expired ? 'bg-red-100 text-red-800' : ($document->expiration_date->diffInDays(now()) < 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    @if($document->is_expired)
                                                        Expirado em {{ $document->expiration_date->format('d/m/Y') }}
                                                    @else
                                                        Expira em {{ $document->expiration_date->format('d/m/Y') }}
                                                        ({{ $document->expiration_date->diffForHumans() }})
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Nenhum documento disponível para visualização.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 