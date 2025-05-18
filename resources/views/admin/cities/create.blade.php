<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Adicionar Cidade') }}
            </h2>
            <a href="{{ route('admin.cities.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Lista') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.cities.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nome') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Estado (UF)') }} <span class="text-red-500">*</span></label>
                                <select name="state" id="state" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('state') border-red-500 @enderror">
                                    <option value="">Selecione um estado</option>
                                    <option value="AC" {{ old('state') == 'AC' ? 'selected' : '' }}>Acre (AC)</option>
                                    <option value="AL" {{ old('state') == 'AL' ? 'selected' : '' }}>Alagoas (AL)</option>
                                    <option value="AP" {{ old('state') == 'AP' ? 'selected' : '' }}>Amapá (AP)</option>
                                    <option value="AM" {{ old('state') == 'AM' ? 'selected' : '' }}>Amazonas (AM)</option>
                                    <option value="BA" {{ old('state') == 'BA' ? 'selected' : '' }}>Bahia (BA)</option>
                                    <option value="CE" {{ old('state') == 'CE' ? 'selected' : '' }}>Ceará (CE)</option>
                                    <option value="DF" {{ old('state') == 'DF' ? 'selected' : '' }}>Distrito Federal (DF)</option>
                                    <option value="ES" {{ old('state') == 'ES' ? 'selected' : '' }}>Espírito Santo (ES)</option>
                                    <option value="GO" {{ old('state') == 'GO' ? 'selected' : '' }}>Goiás (GO)</option>
                                    <option value="MA" {{ old('state') == 'MA' ? 'selected' : '' }}>Maranhão (MA)</option>
                                    <option value="MT" {{ old('state') == 'MT' ? 'selected' : '' }}>Mato Grosso (MT)</option>
                                    <option value="MS" {{ old('state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul (MS)</option>
                                    <option value="MG" {{ old('state') == 'MG' ? 'selected' : '' }}>Minas Gerais (MG)</option>
                                    <option value="PA" {{ old('state') == 'PA' ? 'selected' : '' }}>Pará (PA)</option>
                                    <option value="PB" {{ old('state') == 'PB' ? 'selected' : '' }}>Paraíba (PB)</option>
                                    <option value="PR" {{ old('state') == 'PR' ? 'selected' : '' }}>Paraná (PR)</option>
                                    <option value="PE" {{ old('state') == 'PE' ? 'selected' : '' }}>Pernambuco (PE)</option>
                                    <option value="PI" {{ old('state') == 'PI' ? 'selected' : '' }}>Piauí (PI)</option>
                                    <option value="RJ" {{ old('state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro (RJ)</option>
                                    <option value="RN" {{ old('state') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte (RN)</option>
                                    <option value="RS" {{ old('state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul (RS)</option>
                                    <option value="RO" {{ old('state') == 'RO' ? 'selected' : '' }}>Rondônia (RO)</option>
                                    <option value="RR" {{ old('state') == 'RR' ? 'selected' : '' }}>Roraima (RR)</option>
                                    <option value="SC" {{ old('state') == 'SC' ? 'selected' : '' }}>Santa Catarina (SC)</option>
                                    <option value="SP" {{ old('state') == 'SP' ? 'selected' : '' }}>São Paulo (SP)</option>
                                    <option value="SE" {{ old('state') == 'SE' ? 'selected' : '' }}>Sergipe (SE)</option>
                                    <option value="TO" {{ old('state') == 'TO' ? 'selected' : '' }}>Tocantins (TO)</option>
                                </select>
                                @error('state')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                        {{ __('Ativo') }}
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Criar Cidade') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 