<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bairros') }}
            </h2>
            <a href="{{ route('admin.districts.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                {{ __('Novo Bairro') }}
            </a>
        </div>
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">ID</th>
                                    <th class="py-3 px-4 text-left">Nome</th>
                                    <th class="py-3 px-4 text-left">Cidade</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Imóveis</th>
                                    <th class="py-3 px-4 text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @forelse($districts as $district)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $district->id }}</td>
                                        <td class="py-3 px-4">{{ $district->name }}</td>
                                        <td class="py-3 px-4">{{ $district->city->name }} - {{ $district->city->state }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 rounded text-xs {{ $district->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $district->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            {{ $district->properties_count ?? $district->properties()->count() }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('admin.districts.show', $district) }}" class="text-blue-500 hover:text-blue-700 mr-2" title="Visualizar">
                                                {{ __('Visualizar') }}
                                            </a>
                                            <a href="{{ route('admin.districts.edit', $district) }}" class="text-yellow-500 hover:text-yellow-700" title="Editar">
                                                {{ __('Editar') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 px-4 text-center text-gray-500">
                                            {{ __('Nenhum bairro encontrado.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($districts->hasPages())
                        <div class="mt-4">
                            {{ $districts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 