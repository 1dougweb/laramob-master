<x-frontend-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-8">Imóveis Disponíveis</h1>
            
            <!-- Search Filters -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <form action="{{ route('properties.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="property_type_id" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Imóvel</label>
                            <select id="property_type_id" name="property_type_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos os tipos</option>
                                @foreach($propertyTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                            <select id="city_id" name="city_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todas as cidades</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }} - {{ $city->state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="district_id" class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                            <select id="district_id" name="district_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos os bairros</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Finalidade</label>
                            <select id="purpose" name="purpose" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Qualquer</option>
                                <option value="sale" {{ request('purpose') == 'sale' ? 'selected' : '' }}>Venda</option>
                                <option value="rent" {{ request('purpose') == 'rent' ? 'selected' : '' }}>Aluguel</option>
                                <option value="both" {{ request('purpose') == 'both' ? 'selected' : '' }}>Ambos</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Preço Mínimo</label>
                            <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" step="1000">
                        </div>
                        
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Preço Máximo</label>
                            <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" step="1000">
                        </div>
                        
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Quartos (mínimo)</label>
                            <select id="bedrooms" name="bedrooms" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Qualquer</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>
                                        {{ $i }}{{ $i == 5 ? '+' : '' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('properties.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                            Limpar filtros
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Buscar imóveis
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Properties List -->
            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($properties as $property)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <img src="{{ $property->featured_image ? asset('storage/' . $property->featured_image) : 'https://via.placeholder.com/300x200' }}" 
                                alt="{{ $property->title }}" class="w-full h-48 object-cover">
                            
                            <div class="p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-xs font-medium">
                                        {{ $property->propertyType->name }}
                                    </span>
                                    <span class="px-2 py-1 {{ $property->purpose === 'sale' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-md text-xs font-medium">
                                        {{ $property->purpose === 'sale' ? 'Venda' : ($property->purpose === 'rent' ? 'Aluguel' : 'Venda/Aluguel') }}
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $property->title }}</h3>
                                
                                <p class="text-gray-600 mb-4">
                                    <span class="font-medium">{{ $property->city->name }} - {{ $property->district->name }}</span><br>
                                    {{ Str::limit($property->description, 100) }}
                                </p>
                                
                                <div class="flex justify-between mb-4">
                                    <div class="flex space-x-3 text-gray-500">
                                        <span>{{ $property->bedrooms }} quartos</span>
                                        <span>{{ $property->bathrooms }} banheiros</span>
                                        <span>{{ $property->area }} m²</span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if($property->purpose !== 'rent')
                                            <span class="block text-lg font-bold text-blue-600">R$ {{ number_format($property->price, 2, ',', '.') }}</span>
                                        @endif
                                        @if($property->purpose === 'rent' || $property->purpose === 'both')
                                            <span class="block text-lg font-bold text-blue-600">R$ {{ number_format($property->rental_price, 2, ',', '.') }}/mês</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('properties.show', $property->slug) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600 text-lg">Nenhum imóvel encontrado com os filtros selecionados.</p>
                    <a href="{{ route('properties.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Ver todos os imóveis
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-frontend-layout> 