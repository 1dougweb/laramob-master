<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Propriedades') }}
            </h2>
            <a href="{{ route('admin.properties.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Adicionar propriedade') }}
            </a>
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

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">ID</th>
                                    <th class="py-3 px-4 text-left">Título</th>
                                    <th class="py-3 px-4 text-left">Tipo</th>
                                    <th class="py-3 px-4 text-left">Localização</th>
                                    <th class="py-3 px-4 text-left">Preço</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @forelse($properties as $property)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $property->id }}</td>
                                        <td class="py-3 px-4">{{ $property->title }}</td>
                                        <td class="py-3 px-4">{{ $property->propertyType->name }}</td>
                                        <td class="py-3 px-4">{{ $property->city->name }} - {{ $property->district->name }}</td>
                                        <td class="py-3 px-4">
                                            @if($property->purpose !== 'rent')
                                                <div>R$ {{ number_format($property->price, 2, ',', '.') }}</div>
                                            @endif
                                            @if($property->purpose === 'rent' || $property->purpose === 'both')
                                                <div>R$ {{ number_format($property->rental_price, 2, ',', '.') }}/mês</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 rounded text-xs 
                                                {{ $property->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $property->status == 'sold' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $property->status == 'rented' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $property->status == 'reserved' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $property->status == 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}"
                                            >
                                                {{ $property->status == 'available' ? 'Available' : '' }}
                                                {{ $property->status == 'sold' ? 'Sold' : '' }}
                                                {{ $property->status == 'rented' ? 'Rented' : '' }}
                                                {{ $property->status == 'reserved' ? 'Reserved' : '' }}
                                                {{ $property->status == 'inactive' ? 'Inactive' : '' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.properties.show', $property) }}" class="text-blue-500 hover:text-blue-700" title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.properties.edit', $property) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-3 px-4 text-center">No properties found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $properties->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 