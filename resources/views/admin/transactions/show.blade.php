<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Transação') }}
            </h2>
            <div>
                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    {{ __('Voltar para Lista') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações da Transação</h3>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Descrição</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaction->description }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Valor</p>
                                <p class="mt-1 text-sm {{ $transaction->type === 'expense' ? 'text-red-600' : 'text-green-600' }} font-semibold">
                                    {{ $transaction->formatted_amount }}
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Tipo</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($transaction->type == 'income')
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            Receita
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            Despesa
                                        </span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    @if($transaction->status === 'paid')
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            Pago
                                        </span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                            Pendente
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            Cancelado
                                        </span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Data</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaction->date->format('d/m/Y') }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Categoria</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaction->category ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações Relacionadas</h3>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Conta Bancária</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $transaction->bankAccount->name }} ({{ $transaction->bankAccount->bank_name }})
                                </p>
                            </div>
                            
                            @if($transaction->contract)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Contrato</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $transaction->contract->number }} - {{ $transaction->contract->property->title ?? 'Sem propriedade' }}
                                </p>
                            </div>
                            @endif
                            
                            @if($transaction->property)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Propriedade</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaction->property->title }}</p>
                            </div>
                            @endif
                            
                            @if($transaction->person)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Pessoa</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaction->person->name }}</p>
                            </div>
                            @endif
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Criada em</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Atualizada em</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaction->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($transaction->notes)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Observações</h3>
                        <p class="text-sm text-gray-900">{{ $transaction->notes }}</p>
                    </div>
                    @endif
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Ações</h3>
                        <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta transação?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Excluir Transação
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 