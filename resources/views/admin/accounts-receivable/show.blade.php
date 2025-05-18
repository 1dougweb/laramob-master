<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Conta a Receber') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.accounts-receivable.edit', $receivable->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit mr-2"></i>{{ __('Editar') }}
                </a>
                <a href="{{ route('admin.accounts-receivable.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ __('Informações Básicas') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Descrição') }}</p>
                            <p class="font-medium text-gray-900">{{ $receivable->description }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Valor') }}</p>
                            <p class="font-medium text-green-600">R$ {{ number_format($receivable->amount, 2, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Status') }}</p>
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $receivable->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($receivable->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                    {{ __($receivable->status == 'paid' ? 'Pago' : ($receivable->status == 'pending' ? 'Pendente' : 'Cancelado')) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Categoria') }}</p>
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $receivable->category == 'rent' ? 'bg-blue-100 text-blue-800' : 
                                   ($receivable->category == 'sale' ? 'bg-green-100 text-green-800' : 
                                   ($receivable->category == 'commission' ? 'bg-purple-100 text-purple-800' : 
                                   'bg-gray-100 text-gray-800')) }}">
                                    {{ __($receivable->category) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Data de Emissão') }}</p>
                            <p class="font-medium text-gray-900">{{ $receivable->date->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Data de Vencimento') }}</p>
                            <p class="font-medium {{ $receivable->status == 'pending' && $receivable->due_date < now() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $receivable->due_date->format('d/m/Y') }}
                                @if($receivable->status == 'pending' && $receivable->due_date < now())
                                    <span class="text-red-600 text-xs ml-2">{{ __('Vencido') }}</span>
                                @endif
                            </p>
                        </div>
                        
                        @if($receivable->status == 'paid' && $receivable->payment_date)
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Data de Pagamento') }}</p>
                                <p class="font-medium text-green-600">{{ $receivable->payment_date->format('d/m/Y') }}</p>
                            </div>
                        @endif
                        
                        @if($receivable->document_number)
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Número do Documento') }}</p>
                                <p class="font-medium text-gray-900">{{ $receivable->document_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($receivable->installment_number)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ __('Informações de Parcelamento') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Parcela') }}</p>
                                <p class="font-medium text-gray-900">{{ $receivable->installment_number }} de {{ $receivable->total_installments }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">{{ __('ID do Parcelamento') }}</p>
                                <p class="font-medium text-gray-900">{{ $receivable->recurring_id }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ __('Informações de Relacionamento') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Conta Bancária') }}</p>
                            <p class="font-medium text-gray-900">
                                {{ $receivable->bankAccount->name }} - {{ $receivable->bankAccount->bank_name }}
                                ({{ $receivable->bankAccount->account_number }})
                            </p>
                        </div>
                        
                        @if($receivable->person)
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Cliente/Pessoa') }}</p>
                                <p class="font-medium text-gray-900">
                                    <a href="{{ route('admin.people.show', $receivable->person_id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $receivable->person->name }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        @if($receivable->contract)
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Contrato Relacionado') }}</p>
                                <p class="font-medium text-gray-900">
                                    <a href="{{ route('admin.contracts.show', $receivable->contract_id) }}" class="text-blue-600 hover:text-blue-800">
                                        Contrato #{{ $receivable->contract_id }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        @if($receivable->property)
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Imóvel Relacionado') }}</p>
                                <p class="font-medium text-gray-900">
                                    <a href="{{ route('admin.properties.show', $receivable->property_id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $receivable->property->title }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($receivable->notes)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ __('Observações') }}</h3>
                        <div class="bg-gray-50 p-4 rounded">
                            {{ $receivable->notes }}
                        </div>
                    </div>
                @endif
                
                @if($receivable->attachment)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ __('Anexo') }}</h3>
                        <div class="mt-2">
                            <a href="{{ Storage::url($receivable->attachment) }}" target="_blank" class="bg-blue-100 text-blue-700 px-3 py-2 rounded inline-flex items-center">
                                <i class="fas fa-download mr-2"></i>
                                {{ __('Baixar Anexo') }}
                            </a>
                        </div>
                    </div>
                @endif
                
                <!-- Actions -->
                <div class="mt-8 border-t pt-4 flex justify-between">
                    <div>
                        @if($receivable->status == 'pending')
                            <button onclick="showPaymentModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-money-bill-wave mr-2"></i>{{ __('Registrar Pagamento') }}
                            </button>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.accounts-receivable.destroy', $receivable->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir esta conta a receber?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-trash mr-2"></i>{{ __('Excluir') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center" style="z-index: 50;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Registrar Pagamento') }}</h3>
                <button onclick="hidePaymentModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.accounts-receivable.register-payment', $receivable->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Data do Pagamento') }}</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Valor Pago') }}</label>
                    <input type="number" name="amount_paid" step="0.01" min="0.01" value="{{ $receivable->amount }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="hidePaymentModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Confirmar Pagamento') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function showPaymentModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }
        
        function hidePaymentModal() {
            document.getElementById('paymentModal').classList.remove('flex');
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout> 