<x-frontend-layout>
    <div class="bg-blue-700 py-12 text-center text-white">
        <h1 class="text-4xl font-bold mb-4">Encontre o imóvel dos seus sonhos</h1>
        <p class="text-xl mb-6">Oferecemos uma ampla variedade de imóveis para venda e aluguel.</p>
        <div class="flex justify-center space-x-4">
            <a href="{{ route('properties.index') }}" class="px-6 py-2 bg-white text-blue-700 font-bold rounded hover:bg-blue-50">
                Ver imóveis
            </a>
            <a href="{{ route('contact') }}" class="px-6 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-600">
                Contato
            </a>
        </div>
    </div>

    <div class="py-12">
        <h2 class="text-3xl font-bold text-center mb-8">Imóveis em Destaque</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProperties as $property)
                <div class="bg-white rounded shadow overflow-hidden">
                    <img src="{{ $property->featured_image ? asset('storage/' . $property->featured_image) : 'https://via.placeholder.com/300x200' }}" 
                         alt="{{ $property->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold">{{ $property->title }}</h3>
                        <p class="text-gray-500 mt-2">{{ Str::limit($property->description, 100) }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-blue-600 font-bold">
                                R$ {{ number_format($property->price, 2, ',', '.') }}
                            </span>
                            <a href="{{ route('properties.show', $property->slug) }}" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Ver detalhes
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('properties.index') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">
                Ver todos os imóveis
            </a>
        </div>
    </div>
</x-frontend-layout>
