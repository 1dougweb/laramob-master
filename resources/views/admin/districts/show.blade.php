<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Bairro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Informações Básicas') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Nome') }}</p>
                                <p class="mt-1">{{ $district->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Cidade') }}</p>
                                <p class="mt-1">{{ $district->city->name }} - {{ $district->city->state }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Status') }}</p>
                                <p class="mt-1">
                                    <span class="px-2 py-1 rounded text-xs {{ $district->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $district->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Total de Imóveis') }}</p>
                                <p class="mt-1">{{ $district->properties_count ?? $district->properties()->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Imóveis Relacionados') }}</h3>
                        @if($district->properties->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="py-3 px-4 text-left">ID</th>
                                            <th class="py-3 px-4 text-left">Título</th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach($district->properties as $property)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="py-3 px-4">{{ $property->id }}</td>
                                                <td class="py-3 px-4">{{ $property->title }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 rounded text-xs {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $property->status }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('admin.properties.show', $property) }}" class="text-blue-500 hover:text-blue-700" title="Visualizar">
                                                        {{ __('Visualizar') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600">{{ __('Nenhum imóvel associado a este bairro.') }}</p>
                        @endif
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.districts.edit', $district) }}" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                {{ __('Editar Bairro') }}
                            </a>
                            <a href="{{ route('admin.districts.index') }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('Voltar') }}
                            </a>
                        </div>
                        
                        <form action="{{ route('admin.districts.destroy', $district) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este bairro?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" {{ $district->properties->count() > 0 ? 'disabled' : '' }}>
                                {{ __('Excluir Bairro') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 