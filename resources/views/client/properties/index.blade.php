<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Propriedades Disponíveis') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('client.properties.favorites') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fi fi-sr-heart mr-2"></i> {{ __('Meus Favoritos') }}
                </a>
                <a href="{{ route('client.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fi fi-rr-home mr-2"></i> {{ __('Voltar para Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filtros -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Filtros de Busca</h3>
                        <form action="{{ route('client.properties.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Busca por texto -->
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Busca</label>
                                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="Código, título ou endereço" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Finalidade -->
                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Finalidade</label>
                                    <select name="purpose" id="purpose" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Todas</option>
                                        <option value="sale" {{ ($purpose ?? '') == 'sale' ? 'selected' : '' }}>Venda</option>
                                        <option value="rent" {{ ($purpose ?? '') == 'rent' ? 'selected' : '' }}>Aluguel</option>
                                        <option value="both" {{ ($purpose ?? '') == 'both' ? 'selected' : '' }}>Venda e Aluguel</option>
                                    </select>
                                </div>

                                <!-- Cidade -->
                                <div>
                                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                                    <select name="city_id" id="city_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Todas</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ ($cityId ?? '') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Bairro -->
                                <div>
                                    <label for="district_id" class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                                    <select name="district_id" id="district_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Todos</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ ($districtId ?? '') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo de Imóvel -->
                                <div>
                                    <label for="property_type_id" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Imóvel</label>
                                    <select name="property_type_id" id="property_type_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Todos</option>
                                        @foreach($propertyTypes as $type)
                                            <option value="{{ $type->id }}" {{ ($propertyTypeId ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Faixa de Preço -->
                                <div class="col-span-1 md:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Faixa de Preço</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="number" name="min_price" id="min_price" value="{{ $minPrice ?? '' }}" placeholder="Mínimo" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <input type="number" name="max_price" id="max_price" value="{{ $maxPrice ?? '' }}" placeholder="Máximo" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <!-- Área -->
                                <div>
                                    <label for="min_area" class="block text-sm font-medium text-gray-700 mb-1">Área (m²)</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="number" name="min_area" id="min_area" value="{{ $minArea ?? '' }}" placeholder="Mínima" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <input type="number" name="max_area" id="max_area" value="{{ $maxArea ?? '' }}" placeholder="Máxima" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <!-- Quartos e Banheiros -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cômodos</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <select name="bedrooms" id="bedrooms" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Quartos</option>
                                                <option value="1" {{ ($bedrooms ?? '') == '1' ? 'selected' : '' }}>1+</option>
                                                <option value="2" {{ ($bedrooms ?? '') == '2' ? 'selected' : '' }}>2+</option>
                                                <option value="3" {{ ($bedrooms ?? '') == '3' ? 'selected' : '' }}>3+</option>
                                                <option value="4" {{ ($bedrooms ?? '') == '4' ? 'selected' : '' }}>4+</option>
                                                <option value="5" {{ ($bedrooms ?? '') == '5' ? 'selected' : '' }}>5+</option>
                                            </select>
                                        </div>
                                        <div>
                                            <select name="bathrooms" id="bathrooms" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Banheiros</option>
                                                <option value="1" {{ ($bathrooms ?? '') == '1' ? 'selected' : '' }}>1+</option>
                                                <option value="2" {{ ($bathrooms ?? '') == '2' ? 'selected' : '' }}>2+</option>
                                                <option value="3" {{ ($bathrooms ?? '') == '3' ? 'selected' : '' }}>3+</option>
                                                <option value="4" {{ ($bathrooms ?? '') == '4' ? 'selected' : '' }}>4+</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex space-x-4">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fi fi-rr-search mr-2"></i> Filtrar
                                </button>
                                <a href="{{ route('client.properties.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fi fi-rr-refresh mr-2"></i> Limpar Filtros
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Listagem de Propriedades -->
                    @if(count($properties) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($properties as $property)
                                <div class="overflow-hidden rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                                    <div class="relative">
                                        <a href="{{ route('client.properties.show', $property->slug) }}">
                                            <img src="{{ $property->featured_image_url }}" alt="{{ $property->title }}" class="w-full h-48 object-cover">
                                        </a>
                                        
                                        <!-- Badges -->
                                        <div class="absolute top-2 left-2 flex flex-wrap gap-2">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full
                                                {{ $property->purpose == 'sale' ? 'bg-blue-500 text-white' : '' }}
                                                {{ $property->purpose == 'rent' ? 'bg-green-500 text-white' : '' }}
                                                {{ $property->purpose == 'both' ? 'bg-purple-500 text-white' : '' }}">
                                                {{ $property->purpose == 'sale' ? 'Venda' : '' }}
                                                {{ $property->purpose == 'rent' ? 'Aluguel' : '' }}
                                                {{ $property->purpose == 'both' ? 'Venda/Aluguel' : '' }}
                                            </span>
                                            
                                            @if($property->is_featured)
                                                <span class="px-2 py-1 text-xs font-bold bg-yellow-500 text-white rounded-full">Destaque</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Favorite Button -->
                                        <div class="absolute top-2 right-2">
                                            <form action="{{ route('client.properties.toggle-favorite', $property->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-white rounded-full p-2 shadow-sm hover:bg-gray-100 focus:outline-none">
                                                    @if($person && $person->favoriteProperties->contains($property->id))
                                                        <i class="fi fi-sr-heart text-red-500"></i>
                                                    @else
                                                        <i class="fi fi-rr-heart text-gray-500"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-semibold mb-1 text-gray-900 hover:text-indigo-600">
                                                    <a href="{{ route('client.properties.show', $property->slug) }}">{{ $property->title }}</a>
                                                </h3>
                                                <p class="text-sm text-gray-500 mb-2">{{ $property->address }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $property->district->name }}, {{ $property->city->name }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs text-gray-500">Cód. {{ $property->code }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 border-t border-gray-100 pt-4">
                                            <div class="flex justify-between items-center">
                                                <div class="flex space-x-3 text-gray-500 text-sm">
                                                    @if($property->bedrooms)
                                                        <span><i class="fi fi-rr-bed-alt mr-1"></i> {{ $property->bedrooms }}</span>
                                                    @endif
                                                    
                                                    @if($property->bathrooms)
                                                        <span><i class="fi fi-rr-bathroom mr-1"></i> {{ $property->bathrooms }}</span>
                                                    @endif
                                                    
                                                    @if($property->area)
                                                        <span><i class="fi fi-rr-square mr-1"></i> {{ $property->area }}m²</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                @if($property->purpose == 'sale' || $property->purpose == 'both')
                                                    <div class="font-semibold text-gray-900">
                                                        {{ $property->formatted_price }}
                                                    </div>
                                                @endif
                                                
                                                @if($property->purpose == 'rent' || $property->purpose == 'both')
                                                    <div class="font-semibold {{ $property->purpose == 'both' ? 'text-sm text-gray-700' : 'text-gray-900' }}">
                                                        {{ $property->formatted_rental_price }} /mês
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $properties->links() }}
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Nenhuma propriedade encontrada com os filtros selecionados.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cidade e Bairro relacionados
            const citySelect = document.getElementById('city_id');
            const districtSelect = document.getElementById('district_id');
            const districts = @json($districts);
            
            citySelect.addEventListener('change', function() {
                const cityId = this.value;
                
                // Limpar select de bairros
                districtSelect.innerHTML = '<option value="">Todos</option>';
                
                if (cityId) {
                    // Filtrar bairros pela cidade selecionada
                    const filteredDistricts = districts.filter(district => district.city_id == cityId);
                    
                    // Adicionar opções de bairros
                    filteredDistricts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                } else {
                    // Se nenhuma cidade selecionada, mostrar todos os bairros
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                }
            });
        });
    </script>
</x-app-layout> 