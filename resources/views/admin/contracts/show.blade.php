<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Contrato') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.contracts.edit', $contract) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('admin.contracts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Contrato</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">ID</span>
                                    <span class="block mt-1">{{ $contract->id }}</span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">Tipo</span>
                                    <span class="block mt-1">
                                        @if($contract->type == 'sale')
                                            Venda
                                        @elseif($contract->type == 'rental')
                                            Aluguel
                                        @elseif($contract->type == 'lease')
                                            Arrendamento
                                        @else
                                            {{ $contract->type }}
                                        @endif
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">Valor</span>
                                    <span class="block mt-1">R$ {{ number_format($contract->value, 2, ',', '.') }}</span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">Status</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $contract->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $contract->status == 'expired' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $contract->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $contract->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $contract->status == 'active' ? 'Ativo' : '' }}
                                        {{ $contract->status == 'expired' ? 'Expirado' : '' }}
                                        {{ $contract->status == 'pending' ? 'Pendente' : '' }}
                                        {{ $contract->status == 'cancelled' ? 'Cancelado' : '' }}
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">Data de Início</span>
                                    <span class="block mt-1">{{ $contract->start_date->format('d/m/Y') }}</span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">Data de Fim</span>
                                    <span class="block mt-1">{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">Criado em</span>
                                    <span class="block mt-1">{{ $contract->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 block">Atualizado em</span>
                                    <span class="block mt-1">{{ $contract->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                @if($contract->document_file)
                                <div class="mt-4">
                                    <span class="text-sm font-medium text-gray-500 block">Documento do Contrato</span>
                                    <div class="mt-2 flex items-center">
                                        <div class="bg-gray-100 p-3 rounded border border-gray-200 flex flex-col sm:flex-row items-start sm:items-center">
                                            <div class="flex items-center mb-2 sm:mb-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium">{{ basename($contract->document_file) }}</p>
                                                    <p class="text-xs text-gray-500">Adicionado em: {{ \Carbon\Carbon::parse($contract->updated_at)->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($contract->document_file) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-sm flex items-center ml-0 sm:ml-auto mt-2 sm:mt-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Baixar / Visualizar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Cliente</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Nome</span>
                                        <span class="block mt-1">{{ $contract->client_name }}</span>
                                    </div>
                                    
                                    <!-- Adicione outros campos de cliente conforme necessário -->
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Propriedade</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Título</span>
                                        <span class="block mt-1">{{ $contract->property->title }}</span>
                                    </div>
                                    
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Endereço</span>
                                        <span class="block mt-1">{{ $contract->property->address }}</span>
                                    </div>
                                    
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Localização</span>
                                        <span class="block mt-1">{{ $contract->property->city->name }} - {{ $contract->property->district->name }}</span>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('admin.properties.show', $contract->property) }}" class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Ver detalhes da propriedade
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($contract->notes)
                        <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Observações</h3>
                            <div class="prose max-w-none">
                                {{ $contract->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 