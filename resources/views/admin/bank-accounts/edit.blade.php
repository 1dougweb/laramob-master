<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Conta Bancária') }}
            </h2>
            <a href="{{ route('admin.bank-accounts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Lista') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.bank-accounts.update', $bankAccount->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome da Conta <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $bankAccount->name) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Nome do Banco <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $bankAccount->bank_name) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('bank_name') border-red-500 @enderror" required>
                                @error('bank_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1">Número da Conta <span class="text-red-500">*</span></label>
                                <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('account_number') border-red-500 @enderror" required>
                                @error('account_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="agency" class="block text-sm font-medium text-gray-700 mb-1">Agência <span class="text-red-500">*</span></label>
                                <input type="text" name="agency" id="agency" value="{{ old('agency', $bankAccount->agency) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('agency') border-red-500 @enderror" required>
                                @error('agency')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="account_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Conta <span class="text-red-500">*</span></label>
                                <select name="account_type" id="account_type" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('account_type') border-red-500 @enderror" required>
                                    <option value="checking" {{ old('account_type', $bankAccount->account_type) == 'checking' ? 'selected' : '' }}>Conta Corrente</option>
                                    <option value="savings" {{ old('account_type', $bankAccount->account_type) == 'savings' ? 'selected' : '' }}>Conta Poupança</option>
                                </select>
                                @error('account_type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="initial_balance" class="block text-sm font-medium text-gray-700 mb-1">Saldo Inicial <span class="text-red-500">*</span></label>
                                <input type="number" name="initial_balance" id="initial_balance" value="{{ old('initial_balance', $bankAccount->initial_balance) }}" step="0.01" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('initial_balance') border-red-500 @enderror" required>
                                @error('initial_balance')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $bankAccount->notes) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $bankAccount->is_active) ? 'checked' : '' }} 
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                        Conta Ativa
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Atualizar Conta') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 