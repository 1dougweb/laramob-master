@push('styles')
<style>
    /* Precarregar a imagem de favorito para evitar flashes durante carregamento */
    .preload-heart {
        position: absolute;
        width: 0;
        height: 0;
        overflow: hidden;
        z-index: -1;
    }
</style>
@endpush

@push('scripts')
<script>
    // Carregar imagens assincronamente
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            img.setAttribute('src', img.getAttribute('data-src'));
        });
    });
</script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meus Imóveis Favoritos') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('client.properties.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fi fi-rr-search mr-2"></i> {{ __('Buscar Imóveis') }}
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

                    @if(isset($error_message))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ $error_message }}
                        </div>
                    @endif

                    <!-- Listagem de Propriedades Favoritas -->
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
                                        </div>
                                        
                                        <!-- Remove from Favorites Button -->
                                        <div class="absolute top-2 right-2">
                                            <form action="{{ route('client.properties.toggle-favorite', $property->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-white rounded-full p-2 shadow-sm hover:bg-gray-100 focus:outline-none">
                                                    <i class="fi fi-sr-heart text-red-500"></i>
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
                                    <i class="fi fi-rr-info text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Você ainda não adicionou nenhuma propriedade aos favoritos.
                                    </p>
                                    <p class="text-sm text-yellow-700 mt-2">
                                        <a href="{{ route('client.properties.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Clique aqui para navegar pelas propriedades disponíveis
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 