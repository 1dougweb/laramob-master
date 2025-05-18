<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Criar Nova Comissão') }}
            </h2>
            <a href="{{ route('admin.commissions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Voltar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Ops!</strong>
                            <span class="block sm:inline">Por favor, corrija os erros abaixo:</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.commissions.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contract_id" class="block text-sm font-medium text-gray-700 mb-1">Contrato *</label>
                                <select id="contract_id" name="contract_id" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecione um contrato</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                            {{ $contract->contract_number ?? 'Contrato #' . $contract->id }} - 
                                            {{ $contract->property->address ?? 'Sem endereço' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="broker_id" class="block text-sm font-medium text-gray-700 mb-1">Corretor *</label>
                                <select id="broker_id" name="broker_id" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecione um corretor</option>
                                    @foreach($brokers as $broker)
                                        <option value="{{ $broker->id }}" {{ old('broker_id') == $broker->id ? 'selected' : '' }}>
                                            {{ $broker->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Valor da Comissão (R$) *</label>
                                <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount') }}" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="percentage" class="block text-sm font-medium text-gray-700 mb-1">Percentual (%) *</label>
                                <input type="number" step="0.01" min="0" max="100" name="percentage" id="percentage" value="{{ old('percentage') }}" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select id="status" name="status" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Pago</option>
                                </select>
                            </div>
                            
                            <div class="payment-details" id="payment-details" style="display: none;">
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Pagamento</label>
                                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <div class="payment-details" id="bank-account-details" style="display: none;">
                                <label for="bank_account_id" class="block text-sm font-medium text-gray-700 mb-1">Conta Bancária para Pagamento</label>
                                <select id="bank_account_id" name="bank_account_id" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Selecione uma conta</option>
                                    @foreach($bankAccounts as $account)
                                        <option value="{{ $account->id }}" {{ old('bank_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} - {{ $account->bank_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                            <textarea name="notes" id="notes" rows="3" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Salvar Comissão
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const paymentDetails = document.getElementById('payment-details');
            const bankAccountDetails = document.getElementById('bank-account-details');
            
            // Function to toggle payment fields visibility
            function togglePaymentFields() {
                if (statusSelect.value === 'paid') {
                    paymentDetails.style.display = 'block';
                    bankAccountDetails.style.display = 'block';
                } else {
                    paymentDetails.style.display = 'none';
                    bankAccountDetails.style.display = 'none';
                }
            }
            
            // Initial state
            togglePaymentFields();
            
            // Listen for changes
            statusSelect.addEventListener('change', togglePaymentFields);
        });
    </script>
</x-app-layout> 