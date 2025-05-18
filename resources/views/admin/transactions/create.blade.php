<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nova Transação') }}
            </h2>
            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Lista') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.transactions.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" required>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Valor <span class="text-red-500">*</span></label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror" required>
                                @error('amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                                <select name="type" id="type" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror" required>
                                    <option value="">Selecione</option>
                                    <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Receita</option>
                                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Despesa</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                                    <option value="">Selecione</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Pago</option>
                                    <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Data <span class="text-red-500">*</span></label>
                                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror" required>
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                                @error('category')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="bank_account_id" class="block text-sm font-medium text-gray-700 mb-1">Conta Bancária <span class="text-red-500">*</span></label>
                                <select name="bank_account_id" id="bank_account_id" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('bank_account_id') border-red-500 @enderror" required>
                                    <option value="">Selecione uma conta</option>
                                    @foreach($bankAccounts as $account)
                                        <option value="{{ $account->id }}" {{ old('bank_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ({{ $account->bank_name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_account_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="contract_id" class="block text-sm font-medium text-gray-700 mb-1">Contrato (opcional)</label>
                                <select name="contract_id" id="contract_id" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('contract_id') border-red-500 @enderror">
                                    <option value="">Nenhum contrato</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                            {{ $contract->number }} - {{ $contract->property->title ?? 'Sem propriedade' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contract_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Salvar Transação') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 