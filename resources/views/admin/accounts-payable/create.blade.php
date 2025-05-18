<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nova Conta a Pagar') }}
            </h2>
            <a href="{{ route('admin.accounts-payable.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>{{ __('Oops! Algo deu errado.') }}</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.accounts-payable.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Conta Bancária -->
                        <div>
                            <label for="bank_account_id" class="block text-sm font-medium text-gray-700">{{ __('Conta Bancária') }} <span class="text-red-500">*</span></label>
                            <select id="bank_account_id" name="bank_account_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Selecione uma conta bancária') }}</option>
                                @foreach($bankAccounts as $bankAccount)
                                    <option value="{{ $bankAccount->id }}" {{ old('bank_account_id') == $bankAccount->id ? 'selected' : '' }}>
                                        {{ $bankAccount->name }} - {{ $bankAccount->bank_name }} ({{ $bankAccount->account_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Fornecedor/Pessoa -->
                        <div>
                            <label for="person_id" class="block text-sm font-medium text-gray-700">{{ __('Fornecedor/Pessoa') }}</label>
                            <select id="person_id" name="person_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Selecione um fornecedor') }}</option>
                                @foreach($people as $person)
                                    <option value="{{ $person->id }}" {{ old('person_id') == $person->id ? 'selected' : '' }}>
                                        {{ $person->name }} {{ $person->document ? '(' . $person->document . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Descrição') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="description" name="description" value="{{ old('description') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Categoria -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">{{ __('Categoria') }} <span class="text-red-500">*</span></label>
                            <select id="category" name="category" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="tax" {{ old('category') == 'tax' ? 'selected' : '' }}>{{ __('Imposto') }}</option>
                                <option value="salary" {{ old('category') == 'salary' ? 'selected' : '' }}>{{ __('Salário') }}</option>
                                <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>{{ __('Manutenção') }}</option>
                                <option value="utility" {{ old('category') == 'utility' ? 'selected' : '' }}>{{ __('Utilidade') }}</option>
                                <option value="supplier" {{ old('category') == 'supplier' ? 'selected' : '' }}>{{ __('Fornecedor') }}</option>
                                <option value="service" {{ old('category') == 'service' ? 'selected' : '' }}>{{ __('Serviço') }}</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                            </select>
                        </div>
                        
                        <!-- Valor -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Valor (R$)') }} <span class="text-red-500">*</span></label>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Data de Emissão -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Data de Emissão') }} <span class="text-red-500">*</span></label>
                            <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Data de Vencimento -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">{{ __('Data de Vencimento') }} <span class="text-red-500">*</span></label>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>{{ __('Pago') }}</option>
                                <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                            </select>
                        </div>
                        
                        <!-- Data de Pagamento (Visível apenas quando o status for "Pago") -->
                        <div id="payment_date_container" class="{{ old('status') == 'paid' ? '' : 'hidden' }}">
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">{{ __('Data de Pagamento') }}</label>
                            <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Contrato Relacionado -->
                        <div>
                            <label for="contract_id" class="block text-sm font-medium text-gray-700">{{ __('Contrato Relacionado') }}</label>
                            <select id="contract_id" name="contract_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Selecione um contrato (opcional)') }}</option>
                                @foreach($contracts as $contract)
                                    <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                        {{ $contract->id }} - {{ $contract->property ? $contract->property->title : 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Número do Documento -->
                        <div>
                            <label for="document_number" class="block text-sm font-medium text-gray-700">{{ __('Número do Documento') }}</label>
                            <input type="text" id="document_number" name="document_number" value="{{ old('document_number') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Anexo -->
                        <div>
                            <label for="attachment" class="block text-sm font-medium text-gray-700">{{ __('Anexo (Comprovante/Documento)') }}</label>
                            <input type="file" id="attachment" name="attachment"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                    
                    <!-- Parcelamento -->
                    <div class="mt-6 p-4 bg-gray-50 rounded">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="create_installments" name="create_installments" type="checkbox" {{ old('create_installments') ? 'checked' : '' }}
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="create_installments" class="font-medium text-gray-700">{{ __('Criar Parcelamento') }}</label>
                                <p class="text-gray-500">{{ __('Marque esta opção para criar parcelas automáticas.') }}</p>
                            </div>
                        </div>
                        
                        <div id="installments_config" class="mt-4 {{ old('create_installments') ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="total_installments" class="block text-sm font-medium text-gray-700">{{ __('Número de Parcelas') }}</label>
                                    <input type="number" id="total_installments" name="total_installments" value="{{ old('total_installments', 2) }}" min="2" max="60"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Observações -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Observações') }}</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('admin.accounts-payable.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-save mr-2"></i>{{ __('Salvar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Show/hide payment date field based on status
        document.getElementById('status').addEventListener('change', function() {
            const paymentDateContainer = document.getElementById('payment_date_container');
            if (this.value === 'paid') {
                paymentDateContainer.classList.remove('hidden');
            } else {
                paymentDateContainer.classList.add('hidden');
            }
        });
        
        // Show/hide installments configuration
        document.getElementById('create_installments').addEventListener('change', function() {
            const installmentsConfig = document.getElementById('installments_config');
            if (this.checked) {
                installmentsConfig.classList.remove('hidden');
            } else {
                installmentsConfig.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout> 