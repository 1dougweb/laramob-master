<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pessoas') }}
            </h2>
            <a href="{{ route('admin.people.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Adicionar Pessoa') }}
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

                    <!-- Filtros de tipo de pessoa -->
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.people.index') }}" class="inline-block px-4 py-2 rounded-lg {{ !request('type') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Todos
                            </a>
                            <a href="{{ route('admin.people.index', ['type' => 'employee']) }}" class="inline-block px-4 py-2 rounded-lg {{ request('type') == 'employee' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Funcionários
                            </a>
                            <a href="{{ route('admin.people.index', ['type' => 'broker']) }}" class="inline-block px-4 py-2 rounded-lg {{ request('type') == 'broker' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Corretores
                            </a>
                            <a href="{{ route('admin.people.index', ['type' => 'owner']) }}" class="inline-block px-4 py-2 rounded-lg {{ request('type') == 'owner' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Vendedores/Locadores
                            </a>
                            <a href="{{ route('admin.people.index', ['type' => 'client']) }}" class="inline-block px-4 py-2 rounded-lg {{ request('type') == 'client' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Compradores
                            </a>
                            <a href="{{ route('admin.people.index', ['type' => 'tenant']) }}" class="inline-block px-4 py-2 rounded-lg {{ request('type') == 'tenant' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Locatários
                            </a>
                        </div>
                    </div>

                    <!-- Campo de busca -->
                    <div class="mb-6">
                        <form action="{{ route('admin.people.index') }}" method="GET" class="flex gap-2">
                            @if(request('type'))
                                <input type="hidden" name="type" value="{{ request('type') }}">
                            @endif
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome, e-mail ou telefone" class="flex-1 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buscar
                            </button>
                            @if(request('search'))
                                <a href="{{ url()->current() }}{{ request('type') ? '?type='.request('type') : '' }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Limpar
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th class="py-3 px-6 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        E-mail / Telefone
                                    </th>
                                    <th class="py-3 px-6 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="py-3 px-6 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="py-3 px-6 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($people as $person)
                                    <tr>
                                        <td class="py-4 px-6 text-sm">
                                            <div class="flex items-center">
                                                @if($person->photo)
                                                    <img src="{{ Storage::url($person->photo) }}" alt="{{ $person->name }}" class="h-10 w-10 rounded-full mr-3 object-cover">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                        <span class="text-gray-500 text-lg">{{ substr($person->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $person->name }}</div>
                                                    @if($person->document)
                                                        <div class="text-gray-500 text-xs">{{ $person->document }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-sm">
                                            @if($person->email)
                                                <div class="text-gray-900">{{ $person->email }}</div>
                                            @endif
                                            @if($person->phone)
                                                <div class="text-gray-500">{{ $person->phone }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $person->type == 'employee' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $person->type == 'broker' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $person->type == 'owner' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $person->type == 'client' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                {{ $person->type == 'tenant' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            ">
                                                @if($person->type == 'employee')
                                                    Funcionário
                                                @elseif($person->type == 'broker')
                                                    Corretor
                                                @elseif($person->type == 'owner')
                                                    Vendedor/Locador
                                                @elseif($person->type == 'client')
                                                    Comprador
                                                @elseif($person->type == 'tenant')
                                                    Locatário
                                                @else
                                                    {{ $person->type }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $person->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $person->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.people.show', $person) }}" class="text-blue-600 hover:text-blue-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                
                                                <a href="{{ route('admin.people.edit', $person) }}" class="text-yellow-600 hover:text-yellow-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                
                                                <form action="{{ route('admin.people.destroy', $person) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta pessoa?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
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
                                        <td colspan="5" class="py-4 px-6 text-center text-gray-500">
                                            Nenhuma pessoa encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $people->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 