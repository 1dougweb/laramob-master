<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->title }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('client.properties.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    {{ __('Voltar para Lista') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row">
                        <!-- Imagens -->
                        <div class="w-full lg:w-3/5 lg:pr-8">
                            <div class="mb-4 relative">
                                <img src="{{ $property->featured_image_url }}" alt="{{ $property->title }}" class="w-full h-96 object-cover rounded-lg">
                                
                                <!-- Badges -->
                                <div class="absolute top-4 left-4 flex space-x-2">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                                        {{ $property->purpose == 'sale' ? 'bg-blue-500 text-white' : '' }}
                                        {{ $property->purpose == 'rent' ? 'bg-green-500 text-white' : '' }}
                                        {{ $property->purpose == 'both' ? 'bg-purple-500 text-white' : '' }}">
                                        {{ $property->purpose == 'sale' ? 'Venda' : '' }}
                                        {{ $property->purpose == 'rent' ? 'Aluguel' : '' }}
                                        {{ $property->purpose == 'both' ? 'Venda/Aluguel' : '' }}
                                    </span>
                                    
                                    @if($property->is_featured)
                                        <span class="px-3 py-1 text-sm font-medium bg-yellow-500 text-white rounded-full">Destaque</span>
                                    @endif
                                </div>
                                
                                <!-- Favorite Button -->
                                <div class="absolute top-4 right-4">
                                    <form action="{{ route('client.properties.toggle-favorite', $property->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100 focus:outline-none">
                                            @if($person && $person->favoriteProperties->contains($property->id))
                                                <i class="fas fa-heart text-red-500 text-xl"></i>
                                            @else
                                                <i class="far fa-heart text-gray-500 text-xl"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Gallery -->
                            @if($property->gallery->count() > 0)
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($property->gallery as $image)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $property->title }}" class="w-full h-24 object-cover rounded cursor-pointer hover:opacity-90">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Description -->
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Descrição</h3>
                                <div class="prose max-w-none">
                                    {{ $property->description }}
                                </div>
                            </div>
                            
                            <!-- Features -->
                            @if(isset($property->features) && count($property->features) > 0)
                                <div class="mt-8">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Características</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($property->features as $feature)
                                            <div class="flex items-center">
                                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                                <span class="text-sm text-gray-700">{{ $feature }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Property Details and Contact -->
                        <div class="w-full lg:w-2/5 mt-8 lg:mt-0">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $property->title }}</h3>
                                <p class="text-sm text-gray-700 mb-2">{{ $property->address }}</p>
                                <p class="text-sm text-gray-700 mb-6">{{ $property->district->name }}, {{ $property->city->name }}</p>
                                
                                <div class="flex space-x-6 mb-6">
                                    <div class="text-center">
                                        <span class="block text-lg font-medium text-gray-800">{{ $property->bedrooms ?? 0 }}</span>
                                        <span class="text-xs text-gray-500">Quartos</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="block text-lg font-medium text-gray-800">{{ $property->bathrooms ?? 0 }}</span>
                                        <span class="text-xs text-gray-500">Banheiros</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="block text-lg font-medium text-gray-800">{{ $property->area ?? 0 }}</span>
                                        <span class="text-xs text-gray-500">m²</span>
                                    </div>
                                    @if($property->parking)
                                        <div class="text-center">
                                            <span class="block text-lg font-medium text-gray-800">{{ $property->parking }}</span>
                                            <span class="text-xs text-gray-500">Vagas</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="border-t border-gray-200 pt-6 mb-6">
                                    <div class="flex flex-col space-y-2">
                                        @if($property->propertyType)
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Tipo de Imóvel:</span>
                                                <span class="text-sm font-medium">{{ $property->propertyType->name }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($property->built_area)
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Área Construída:</span>
                                                <span class="text-sm font-medium">{{ $property->built_area }} m²</span>
                                            </div>
                                        @endif
                                        
                                        @if($property->iptu)
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">IPTU:</span>
                                                <span class="text-sm font-medium">{{ $property->formatted_iptu }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($property->condominium_fee)
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Condomínio:</span>
                                                <span class="text-sm font-medium">{{ $property->formatted_condominium_fee }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Código:</span>
                                            <span class="text-sm font-medium">{{ $property->code }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mb-6">
                                    @if($property->purpose == 'sale' || $property->purpose == 'both')
                                        <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $property->formatted_price }}</h4>
                                    @endif
                                    
                                    @if($property->purpose == 'rent' || $property->purpose == 'both')
                                        <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $property->formatted_rental_price }} <span class="text-sm font-normal">/mês</span></h4>
                                    @endif
                                </div>
                                
                                <!-- Contact Form -->
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-4">Interessado? Entre em contato</h4>
                                    
                                    <form action="{{ route('client.properties.contact', $property->id) }}" method="POST">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                                                <input type="text" name="name" id="name" value="{{ auth()->user()->name ?? '' }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            
                                            <div>
                                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                                <input type="email" name="email" id="email" value="{{ auth()->user()->email ?? '' }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            
                                            <div>
                                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                                                <input type="text" name="phone" id="phone" value="{{ $person->mobile ?? '' }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            
                                            <div>
                                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensagem</label>
                                                <textarea name="message" id="message" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">Olá, tenho interesse no imóvel {{ $property->code }} - {{ $property->title }}. Gostaria de mais informações.</textarea>
                                            </div>
                                            
                                            <button type="submit" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Enviar Mensagem
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Similar Properties -->
            @if(count($similarProperties) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Imóveis Semelhantes</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($similarProperties as $similarProperty)
                                <div class="overflow-hidden rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                                    <div class="relative">
                                        <a href="{{ route('client.properties.show', $similarProperty->slug) }}">
                                            <img src="{{ $similarProperty->featured_image_url }}" alt="{{ $similarProperty->title }}" class="w-full h-40 object-cover">
                                        </a>
                                        <div class="absolute top-2 left-2">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full
                                                {{ $similarProperty->purpose == 'sale' ? 'bg-blue-500 text-white' : '' }}
                                                {{ $similarProperty->purpose == 'rent' ? 'bg-green-500 text-white' : '' }}
                                                {{ $similarProperty->purpose == 'both' ? 'bg-purple-500 text-white' : '' }}">
                                                {{ $similarProperty->purpose == 'sale' ? 'Venda' : '' }}
                                                {{ $similarProperty->purpose == 'rent' ? 'Aluguel' : '' }}
                                                {{ $similarProperty->purpose == 'both' ? 'Venda/Aluguel' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate hover:text-indigo-600">
                                            <a href="{{ route('client.properties.show', $similarProperty->slug) }}">{{ $similarProperty->title }}</a>
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $similarProperty->district->name }}, {{ $similarProperty->city->name }}</p>
                                        
                                        <div class="mt-2 flex justify-between">
                                            <div class="flex space-x-2 text-xs text-gray-500">
                                                @if($similarProperty->bedrooms)
                                                    <span><i class="fas fa-bed mr-1"></i>{{ $similarProperty->bedrooms }}</span>
                                                @endif
                                                
                                                @if($similarProperty->bathrooms)
                                                    <span><i class="fas fa-bath mr-1"></i>{{ $similarProperty->bathrooms }}</span>
                                                @endif
                                                
                                                @if($similarProperty->area)
                                                    <span><i class="fas fa-vector-square mr-1"></i>{{ $similarProperty->area }}m²</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 font-medium text-gray-900">
                                            @if($similarProperty->purpose == 'sale' || $similarProperty->purpose == 'both')
                                                {{ $similarProperty->formatted_price }}
                                            @endif
                                            
                                            @if($similarProperty->purpose == 'rent' || $similarProperty->purpose == 'both')
                                                {{ $similarProperty->formatted_rental_price }}/mês
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 