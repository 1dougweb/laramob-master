<x-frontend-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Property Details -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <!-- Property Images -->
                <div class="relative h-96">
                    <img src="{{ $property->featured_image ? asset('storage/' . $property->featured_image) : 'https://via.placeholder.com/1200x600' }}" 
                         alt="{{ $property->title }}" class="w-full h-full object-cover">
                         
                    <div class="absolute top-4 right-4 flex space-x-2">
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm font-medium">
                            {{ $property->propertyType->name }}
                        </span>
                        <span class="px-3 py-1 {{ $property->purpose === 'sale' ? 'bg-green-600' : 'bg-yellow-600' }} text-white rounded-md text-sm font-medium">
                            {{ $property->purpose === 'sale' ? 'Venda' : ($property->purpose === 'rent' ? 'Aluguel' : 'Venda/Aluguel') }}
                        </span>
                    </div>
                </div>
                
                <!-- Property Details -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
                            <p class="text-lg text-gray-600 mb-4">
                                {{ $property->address }}, {{ $property->district->name }}, {{ $property->city->name }} - {{ $property->city->state }}
                            </p>
                        </div>
                        
                        <div class="mt-4 md:mt-0 text-right">
                            @if($property->purpose !== 'rent')
                                <p class="text-2xl font-bold text-blue-600 mb-1">R$ {{ number_format($property->price, 2, ',', '.') }}</p>
                            @endif
                            @if($property->purpose === 'rent' || $property->purpose === 'both')
                                <p class="text-2xl font-bold text-blue-600">R$ {{ number_format($property->rental_price, 2, ',', '.') }}/mês</p>
                            @endif
                            <p class="text-gray-500">Código: {{ $property->code }}</p>
                        </div>
                    </div>
                    
                    <!-- Property Features -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-6 p-4 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $property->bedrooms }}</p>
                            <p class="text-gray-600">Quartos</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $property->bathrooms }}</p>
                            <p class="text-gray-600">Banheiros</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $property->suites }}</p>
                            <p class="text-gray-600">Suítes</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $property->parking }}</p>
                            <p class="text-gray-600">Vagas</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Detalhes</h2>
                            <ul class="space-y-2">
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Área total:</span>
                                    <span class="font-medium">{{ $property->area }} m²</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Área construída:</span>
                                    <span class="font-medium">{{ $property->built_area ?? '-' }} m²</span>
                                </li>
                                @if($property->purpose === 'rent' || $property->purpose === 'both')
                                    <li class="flex justify-between">
                                        <span class="text-gray-600">Condomínio:</span>
                                        <span class="font-medium">R$ {{ number_format($property->condominium_fee ?? 0, 2, ',', '.') }}</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span class="text-gray-600">IPTU:</span>
                                        <span class="font-medium">R$ {{ number_format($property->iptu ?? 0, 2, ',', '.') }}/ano</span>
                                    </li>
                                @endif
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium">{{ $property->status === 'available' ? 'Disponível' : ($property->status === 'sold' ? 'Vendido' : ($property->status === 'rented' ? 'Alugado' : ($property->status === 'reserved' ? 'Reservado' : 'Indisponível'))) }}</span>
                                </li>
                            </ul>
                        </div>
                        
                        @if($property->features)
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Características</h2>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(explode(',', $property->features) as $feature)
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span>{{ trim($feature) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Descrição</h2>
                        <p class="text-gray-700 whitespace-pre-line">{{ $property->description }}</p>
                    </div>
                    
                    <!-- Contact Form -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Interessado neste imóvel?</h2>
                        
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('properties.contact', $property->id) }}" method="POST">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="name" class="block text-gray-700 font-medium mb-2">Nome</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-gray-700 font-medium mb-2">Telefone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="block text-gray-700 font-medium mb-2">Mensagem</label>
                                <textarea name="message" id="message" rows="4" required
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('message', 'Olá, tenho interesse neste imóvel (' . $property->code . ') e gostaria de mais informações.') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full md:w-auto bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200">
                                    Enviar Mensagem
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Similar Properties -->
            @if($similarProperties->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Imóveis Similares</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($similarProperties as $similar)
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <img src="{{ $similar->featured_image ? asset('storage/' . $similar->featured_image) : 'https://via.placeholder.com/300x200' }}" 
                                    alt="{{ $similar->title }}" class="w-full h-48 object-cover">
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $similar->title }}</h3>
                                    
                                    <p class="text-gray-600 mb-3">
                                        <span class="font-medium">{{ $similar->city->name }} - {{ $similar->district->name }}</span>
                                    </p>
                                    
                                    <div class="flex justify-between items-center">
                                        <div>
                                            @if($similar->purpose !== 'rent')
                                                <span class="block text-lg font-bold text-blue-600">R$ {{ number_format($similar->price, 2, ',', '.') }}</span>
                                            @endif
                                            @if($similar->purpose === 'rent' || $similar->purpose === 'both')
                                                <span class="block text-lg font-bold text-blue-600">R$ {{ number_format($similar->rental_price, 2, ',', '.') }}/mês</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('properties.show', $similar->slug) }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Ver detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-frontend-layout> 