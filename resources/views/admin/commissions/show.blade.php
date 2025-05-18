<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Comissão') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.commissions.edit', $commission->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('admin.commissions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Sucesso!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Informações da Comissão</h3>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                    <p class="mt-1">
                                        @if($commission->status == 'paid')
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                Pago
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-orange-800 bg-orange-100 rounded-full">
                                                Pendente
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Valor</p>
                                    <p class="mt-1 text-sm text-gray-900">R$ {{ number_format($commission->amount, 2, ',', '.') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Percentual</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ number_format($commission->percentage, 2, ',', '.') }}%</p>
                                </div>
                                
                                @if($commission->payment_date)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Data de Pagamento</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $commission->payment_date->format('d/m/Y') }}</p>
                                </div>
                                @endif
                                
                                @if($commission->notes)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Observações</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $commission->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Detalhes Relacionados</h3>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Corretor</p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $commission->broker->name ?? 'N/A' }}
                                        @if($commission->broker)
                                            ({{ $commission->broker->email ?? 'Sem email' }})
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Contrato</p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @if($commission->contract)
                                            {{ $commission->contract->contract_number ?? 'Contrato #' . $commission->contract->id }}
                                            <span class="block text-xs text-gray-500">
                                                {{ $commission->contract->property->address ?? 'Sem endereço' }}
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($commission->transaction)
                        <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Detalhes da Transação</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Descrição</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $commission->transaction->description }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Valor</p>
                                    <p class="mt-1 text-sm text-gray-900">R$ {{ number_format($commission->transaction->amount, 2, ',', '.') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Data</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $commission->transaction->date->format('d/m/Y') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                    <p class="mt-1">
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            {{ ucfirst($commission->transaction->status) }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Conta Bancária</p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @if($commission->transaction->bankAccount)
                                            {{ $commission->transaction->bankAccount->name }} - {{ $commission->transaction->bankAccount->bank_name }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Categoria</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($commission->transaction->category) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-6 flex space-x-2">
                        <a href="{{ route('admin.commissions.edit', $commission->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Editar
                        </a>
                        
                        <form action="{{ route('admin.commissions.destroy', $commission->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta comissão?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 