<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Conta Bancária') }}
            </h2>
            <div>
                <a href="{{ route('admin.bank-accounts.edit', $bankAccount->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('admin.bank-accounts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
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
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações da Conta</h3>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Nome da Conta</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $bankAccount->name }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Nome do Banco</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $bankAccount->bank_name }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Agência</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $bankAccount->agency }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Número da Conta</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $bankAccount->account_number }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Tipo de Conta</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $bankAccount->type == 'checking' ? 'Conta Corrente' : 'Conta Poupança' }}
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações Financeiras</h3>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Saldo Inicial</p>
                                <p class="mt-1 text-sm text-gray-900">R$ {{ number_format($bankAccount->initial_balance, 2, ',', '.') }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    @if($bankAccount->is_active)
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            Ativa
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            Inativa
                                        </span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Criada em</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $bankAccount->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Atualizada em</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $bankAccount->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($bankAccount->notes)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Descrição</h3>
                        <p class="text-sm text-gray-900">{{ $bankAccount->notes }}</p>
                    </div>
                    @endif
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Ações</h3>
                        <form action="{{ route('admin.bank-accounts.destroy', $bankAccount->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta conta bancária?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Excluir Conta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 