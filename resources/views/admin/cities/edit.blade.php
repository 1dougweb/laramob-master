<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Cidade') }}: {{ $city->name }}
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
                    <form action="{{ route('admin.cities.update', $city) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nome') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $city->name) }}" required
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
                                    <option value="AC" {{ old('state', $city->state) == 'AC' ? 'selected' : '' }}>Acre (AC)</option>
                                    <option value="AL" {{ old('state', $city->state) == 'AL' ? 'selected' : '' }}>Alagoas (AL)</option>
                                    <option value="AP" {{ old('state', $city->state) == 'AP' ? 'selected' : '' }}>Amapá (AP)</option>
                                    <option value="AM" {{ old('state', $city->state) == 'AM' ? 'selected' : '' }}>Amazonas (AM)</option>
                                    <option value="BA" {{ old('state', $city->state) == 'BA' ? 'selected' : '' }}>Bahia (BA)</option>
                                    <option value="CE" {{ old('state', $city->state) == 'CE' ? 'selected' : '' }}>Ceará (CE)</option>
                                    <option value="DF" {{ old('state', $city->state) == 'DF' ? 'selected' : '' }}>Distrito Federal (DF)</option>
                                    <option value="ES" {{ old('state', $city->state) == 'ES' ? 'selected' : '' }}>Espírito Santo (ES)</option>
                                    <option value="GO" {{ old('state', $city->state) == 'GO' ? 'selected' : '' }}>Goiás (GO)</option>
                                    <option value="MA" {{ old('state', $city->state) == 'MA' ? 'selected' : '' }}>Maranhão (MA)</option>
                                    <option value="MT" {{ old('state', $city->state) == 'MT' ? 'selected' : '' }}>Mato Grosso (MT)</option>
                                    <option value="MS" {{ old('state', $city->state) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul (MS)</option>
                                    <option value="MG" {{ old('state', $city->state) == 'MG' ? 'selected' : '' }}>Minas Gerais (MG)</option>
                                    <option value="PA" {{ old('state', $city->state) == 'PA' ? 'selected' : '' }}>Pará (PA)</option>
                                    <option value="PB" {{ old('state', $city->state) == 'PB' ? 'selected' : '' }}>Paraíba (PB)</option>
                                    <option value="PR" {{ old('state', $city->state) == 'PR' ? 'selected' : '' }}>Paraná (PR)</option>
                                    <option value="PE" {{ old('state', $city->state) == 'PE' ? 'selected' : '' }}>Pernambuco (PE)</option>
                                    <option value="PI" {{ old('state', $city->state) == 'PI' ? 'selected' : '' }}>Piauí (PI)</option>
                                    <option value="RJ" {{ old('state', $city->state) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro (RJ)</option>
                                    <option value="RN" {{ old('state', $city->state) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte (RN)</option>
                                    <option value="RS" {{ old('state', $city->state) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul (RS)</option>
                                    <option value="RO" {{ old('state', $city->state) == 'RO' ? 'selected' : '' }}>Rondônia (RO)</option>
                                    <option value="RR" {{ old('state', $city->state) == 'RR' ? 'selected' : '' }}>Roraima (RR)</option>
                                    <option value="SC" {{ old('state', $city->state) == 'SC' ? 'selected' : '' }}>Santa Catarina (SC)</option>
                                    <option value="SP" {{ old('state', $city->state) == 'SP' ? 'selected' : '' }}>São Paulo (SP)</option>
                                    <option value="SE" {{ old('state', $city->state) == 'SE' ? 'selected' : '' }}>Sergipe (SE)</option>
                                    <option value="TO" {{ old('state', $city->state) == 'TO' ? 'selected' : '' }}>Tocantins (TO)</option>
                                </select>
                                @error('state')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $city->is_active) ? 'checked' : '' }}
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
                                {{ __('Atualizar Cidade') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 