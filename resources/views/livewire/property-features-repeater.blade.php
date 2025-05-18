<div>
    <div class="border rounded-md p-4">
        <div class="space-y-3">
            @foreach($features as $index => $feature)
                <div class="grid grid-cols-3 gap-4 mb-3 pb-3 border-b last:border-b-0" wire:key="feature-{{ $index }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nome do Recurso') }}</label>
                        <input type="text" 
                               wire:model="features.{{ $index }}.name" 
                               name="feature_name[]" 
                               placeholder="Ex: Piscina, Jardim, Ar-condicionado"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ícone') }}</label>
                        <select wire:model="features.{{ $index }}.icon" 
                                name="feature_icon[]" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($iconOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" 
                                wire:click="removeFeature({{ $index }})" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Remover') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-3">
            <button type="button" 
                    wire:click="addFeature" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Adicionar Recurso') }}
            </button>
        </div>
    </div>
</div>
