<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Cidade') }}: {{ $city->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.cities.edit', $city) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('admin.cities.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    {{ __('Voltar para Lista') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Informações da Cidade') }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('ID') }}</p>
                                <p class="mt-1">{{ $city->id }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Nome') }}</p>
                                <p class="mt-1">{{ $city->name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Estado (UF)') }}</p>
                                <p class="mt-1">{{ $city->state }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Slug') }}</p>
                                <p class="mt-1">{{ $city->slug }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Status') }}</p>
                                <p class="mt-1">
                                    <span class="px-2 py-1 rounded text-xs {{ $city->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $city->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Data de Criação') }}</p>
                                <p class="mt-1">{{ $city->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Última Atualização') }}</p>
                                <p class="mt-1">{{ $city->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Bairros Relacionados') }}</h3>
                        
                        @if($city->districts->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="py-3 px-4 text-left">ID</th>
                                            <th class="py-3 px-4 text-left">Nome</th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Imóveis</th>
                                            <th class="py-3 px-4 text-left">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach($city->districts as $district)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="py-3 px-4">{{ $district->id }}</td>
                                                <td class="py-3 px-4">{{ $district->name }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 rounded text-xs {{ $district->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $district->is_active ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    {{ $district->properties_count ?? $district->properties()->count() }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('admin.districts.show', $district) }}" class="text-blue-500 hover:text-blue-700" title="Visualizar">
                                                        {{ __('Visualizar') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600">{{ __('Nenhum bairro associado a esta cidade.') }}</p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Imóveis Relacionados') }}</h3>
                        
                        @if($city->properties->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="py-3 px-4 text-left">ID</th>
                                            <th class="py-3 px-4 text-left">Título</th>
                                            <th class="py-3 px-4 text-left">Bairro</th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach($city->properties as $property)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="py-3 px-4">{{ $property->id }}</td>
                                                <td class="py-3 px-4">{{ $property->title }}</td>
                                                <td class="py-3 px-4">{{ $property->district->name }}</td>
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
                            <p class="text-gray-600">{{ __('Nenhum imóvel associado a esta cidade.') }}</p>
                        @endif
                    </div>

                    <div class="flex justify-end mt-6">
                        <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir esta cidade?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" {{ $city->districts->count() > 0 || $city->properties->count() > 0 ? 'disabled' : '' }}>
                                {{ __('Excluir Cidade') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 