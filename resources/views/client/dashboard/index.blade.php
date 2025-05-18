<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Área do Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('warning'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                    <p>{{ session('warning') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Resumo de Atividades -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Resumo de Atividades</h3>
                    <p class="text-sm text-gray-600 mb-4">Visão geral das suas tarefas e reuniões.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Tarefas Pendentes -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="text-blue-800 font-medium mb-1">Tarefas Pendentes</h4>
                            <div class="text-3xl font-bold text-blue-900 mb-2">
                                {{ $pendingTasksCount }}
                            </div>
                            <p class="text-sm text-blue-600">
                                @if(isset($person) && $person->type === 'broker')
                                    @if($todoTasksCount > 0 || $inProgressTasksCount > 0)
                                        {{ $todoTasksCount }} para fazer | {{ $inProgressTasksCount }} em progresso
                                    @else
                                        Nenhuma tarefa pendente
                                    @endif
                                @else
                                    Acesso ao corretor necessário
                                @endif
                            </p>
                            @if(isset($person) && $person->type === 'broker')
                                <div class="mt-2">
                                    <a href="{{ route('client.kanban.tasks') }}" class="text-xs text-blue-700 hover:text-blue-900 font-medium">Ver todas →</a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Reuniões Hoje -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="text-green-800 font-medium mb-1">Reuniões Hoje</h4>
                            <div class="text-3xl font-bold text-green-900 mb-2">
                                {{ $todayMeetingsCount }}
                            </div>
                            <p class="text-sm text-green-600">
                                @if(isset($person) && $person->type === 'broker')
                                    @if($todayMeetingsCount > 0 && $nextMeeting)
                                        Próxima: {{ \Carbon\Carbon::parse($nextMeeting->scheduled_at)->format('H:i') }}
                                    @else
                                        Sem reuniões hoje
                                    @endif
                                @else
                                    Acesso ao corretor necessário
                                @endif
                            </p>
                            @if(isset($person) && $person->type === 'broker')
                                <div class="mt-2">
                                    <a href="{{ route('client.kanban.meetings') }}" class="text-xs text-green-700 hover:text-green-900 font-medium">Ver todas →</a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Seus Clientes -->
                        <div class="bg-purple-50 rounded-lg p-4">
                            <h4 class="text-purple-800 font-medium mb-1">Seus Clientes</h4>
                            <div class="text-3xl font-bold text-purple-900 mb-2">
                                {{ $clientsCount }}
                            </div>
                            <p class="text-sm text-purple-600">
                                @if(isset($person) && $person->type === 'broker')
                                    @if($clientsCount > 0)
                                        {{ $activeClientsCount }} compradores | {{ $ownerClientsCount }} locatários
                                    @else
                                        Nenhum cliente registrado
                                    @endif
                                @else
                                    Acesso ao corretor necessário
                                @endif
                            </p>
                        </div>
                        
                        <!-- Tarefas Atrasadas -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="text-red-800 font-medium mb-1">Tarefas Atrasadas</h4>
                            <div class="text-3xl font-bold text-red-900 mb-2">
                                {{ $overdueTasksCount }}
                            </div>
                            <p class="text-sm text-red-600">
                                @if($overdueTasksCount > 0)
                                    Requer sua atenção imediata
                                @else
                                    Nenhuma tarefa atrasada
                                @endif
                            </p>
                            @if(isset($person) && $person->type === 'broker' && $overdueTasksCount > 0)
                                <div class="mt-2">
                                    <a href="{{ route('client.kanban.tasks') }}" class="text-xs text-red-700 hover:text-red-900 font-medium">Verificar agora →</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informações do Cliente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Seus dados</h3>
                        
                        @if(isset($person))
                            <div class="flex items-center mb-4">
                                @if($person->photo)
                                    <img src="{{ Storage::url($person->photo) }}" alt="{{ $person->name }}" class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xl">{{ substr($person->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $person->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $person->email }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm">
                                @if($person->document)
                                    <div>
                                        <span class="font-medium">{{ $person->document_type == 'cpf' ? 'CPF' : 'CNPJ' }}:</span> {{ $person->document }}
                                    </div>
                                @endif

                                @if($person->phone)
                                    <div>
                                        <span class="font-medium">Telefone:</span> {{ $person->phone }}
                                    </div>
                                @endif

                                @if($person->mobile)
                                    <div>
                                        <span class="font-medium">WhatsApp:</span> {{ $person->mobile }}
                                    </div>
                                @endif

                                @if(isset($person->broker))
                                    <div>
                                        <span class="font-medium">Seu corretor:</span> {{ $person->broker->name }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-sm text-gray-500">
                                <p>Seus dados ainda não foram completamente configurados.</p>
                                <p>Entre em contato com a administração para mais informações.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Documentos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Seus documentos</h3>
                            <a href="{{ route('client.documents.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Ver todos</a>
                        </div>
                        
                        @if(isset($documentCount) && $documentCount > 0)
                            <div class="mb-4">
                                <div class="bg-blue-50 rounded-lg p-4 flex items-center">
                                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold text-blue-800">{{ $documentCount }}</h4>
                                        <p class="text-sm text-blue-600">{{ $documentCount == 1 ? 'Documento disponível' : 'Documentos disponíveis' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if(isset($recentDocuments) && count($recentDocuments) > 0)
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Documentos recentes</h4>
                                <ul class="space-y-2">
                                    @foreach($recentDocuments as $document)
                                        <li class="text-sm border border-gray-200 rounded-md p-2">
                                            <a href="{{ route('client.documents.show', $document) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ $document->title }}</a>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Compartilhado em {{ $document->shared_at->format('d/m/Y') }}
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @else
                            <div class="text-sm text-gray-500">
                                <p>Você ainda não possui documentos compartilhados.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Imóveis -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Imóveis</h3>
                            <a href="{{ route('client.properties.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Ver todos</a>
                        </div>
                        
                        <div class="space-y-2">
                            <a href="{{ route('client.properties.index') }}" class="block bg-indigo-50 hover:bg-indigo-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-indigo-100 rounded-full p-2">
                                        <i class="fas fa-search text-indigo-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-indigo-800">Buscar Imóveis</h4>
                                        <p class="text-xs text-indigo-600">Encontre as melhores opções disponíveis</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ route('client.properties.favorites') }}" class="block bg-red-50 hover:bg-red-100 p-3 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="bg-red-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fi fi-sr-heart text-red-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-red-800">Meus Favoritos</h4>
                                        <p class="text-xs text-red-600">Veja os imóveis que você salvou</p>
                                    </div>
                                </div>
                            </a>
                            
                            @if(isset($person) && $person->type == 'owner')
                                <a href="{{ route('client.properties.index', ['owner_id' => $person->id]) }}" class="block bg-green-50 hover:bg-green-100 p-3 rounded-md transition-colors duration-150">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 rounded-full p-2">
                                            <i class="fas fa-home text-green-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-green-800">Meus Imóveis</h4>
                                            <p class="text-xs text-green-600">Gerencie seus imóveis cadastrados</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documentos a Vencer -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-3">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos a Vencer</h3>
                        
                        @if(isset($expiringDocuments) && count($expiringDocuments) > 0)
                            <ul class="space-y-2">
                                @foreach($expiringDocuments as $document)
                                    <li class="text-sm border border-gray-200 rounded-md p-3">
                                        <div class="flex justify-between items-start">
                                            <a href="{{ route('client.documents.show', $document) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ $document->title }}</a>
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $document->expiration_date->diffInDays(now()) < 7 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $document->expiration_date->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $document->expiration_date->diffForHumans() }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-sm text-gray-500">
                                <p>Nenhum documento próximo do vencimento.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Imóveis Favoritos -->
            @if(isset($favoriteProperties) && count($favoriteProperties) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-3">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Seus Imóveis Favoritos</h3>
                            <a href="{{ route('client.properties.favorites') }}" class="text-sm text-blue-600 hover:text-blue-800">Ver todos</a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($favoriteProperties as $property)
                                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="relative">
                                        <a href="{{ route('client.properties.show', $property->slug) }}">
                                            <img src="{{ $property->featured_image_url }}" alt="{{ $property->title }}" class="w-full h-36 object-cover">
                                        </a>
                                        <div class="absolute top-2 left-2">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full
                                                {{ $property->purpose == 'sale' ? 'bg-blue-500 text-white' : '' }}
                                                {{ $property->purpose == 'rent' ? 'bg-green-500 text-white' : '' }}
                                                {{ $property->purpose == 'both' ? 'bg-purple-500 text-white' : '' }}">
                                                {{ $property->purpose == 'sale' ? 'Venda' : '' }}
                                                {{ $property->purpose == 'rent' ? 'Aluguel' : '' }}
                                                {{ $property->purpose == 'both' ? 'Venda/Aluguel' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3">
                                        <h4 class="text-sm font-medium text-gray-900 truncate hover:text-indigo-600">
                                            <a href="{{ route('client.properties.show', $property->slug) }}">{{ $property->title }}</a>
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $property->district->name }}, {{ $property->city->name }}</p>
                                        
                                        <div class="mt-2 flex justify-between">
                                            <div class="flex space-x-2 text-xs text-gray-500">
                                                @if($property->bedrooms)
                                                    <span><i class="fas fa-bed mr-1"></i>{{ $property->bedrooms }}</span>
                                                @endif
                                                
                                                @if($property->bathrooms)
                                                    <span><i class="fas fa-bath mr-1"></i>{{ $property->bathrooms }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if($property->purpose == 'sale' || $property->purpose == 'both')
                                                    <span class="text-xs font-medium text-gray-900">{{ $property->formatted_price }}</span>
                                                @elseif($property->purpose == 'rent')
                                                    <span class="text-xs font-medium text-gray-900">{{ $property->formatted_rental_price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Imóveis em Destaque -->
            @if(isset($featuredProperties) && count($featuredProperties) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-3">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Imóveis em Destaque</h3>
                            <a href="{{ route('client.properties.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Ver todos</a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($featuredProperties as $property)
                                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="relative">
                                        <a href="{{ route('client.properties.show', $property->slug) }}">
                                            <img src="{{ $property->featured_image_url }}" alt="{{ $property->title }}" class="w-full h-36 object-cover">
                                        </a>
                                        <div class="absolute top-2 left-2 flex space-x-2">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full
                                                {{ $property->purpose == 'sale' ? 'bg-blue-500 text-white' : '' }}
                                                {{ $property->purpose == 'rent' ? 'bg-green-500 text-white' : '' }}
                                                {{ $property->purpose == 'both' ? 'bg-purple-500 text-white' : '' }}">
                                                {{ $property->purpose == 'sale' ? 'Venda' : '' }}
                                                {{ $property->purpose == 'rent' ? 'Aluguel' : '' }}
                                                {{ $property->purpose == 'both' ? 'Venda/Aluguel' : '' }}
                                            </span>
                                            <span class="px-2 py-1 text-xs font-bold bg-yellow-500 text-white rounded-full">Destaque</span>
                                        </div>
                                        
                                        <!-- Favorite Button -->
                                        <div class="absolute top-2 right-2">
                                            <form action="{{ route('client.properties.toggle-favorite', $property->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-white rounded-full p-1 shadow-sm hover:bg-gray-100 focus:outline-none">
                                                    @if($person && $person->favoriteProperties->contains($property->id))
                                                        <i class="fas fa-heart text-red-500 text-sm"></i>
                                                    @else
                                                        <i class="far fa-heart text-gray-500 text-sm"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3">
                                        <h4 class="text-sm font-medium text-gray-900 truncate hover:text-indigo-600">
                                            <a href="{{ route('client.properties.show', $property->slug) }}">{{ $property->title }}</a>
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $property->district->name }}, {{ $property->city->name }}</p>
                                        
                                        <div class="mt-2 flex justify-between">
                                            <div class="flex space-x-2 text-xs text-gray-500">
                                                @if($property->bedrooms)
                                                    <span><i class="fas fa-bed mr-1"></i>{{ $property->bedrooms }}</span>
                                                @endif
                                                
                                                @if($property->bathrooms)
                                                    <span><i class="fas fa-bath mr-1"></i>{{ $property->bathrooms }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if($property->purpose == 'sale' || $property->purpose == 'both')
                                                    <span class="text-xs font-medium text-gray-900">{{ $property->formatted_price }}</span>
                                                @elseif($property->purpose == 'rent')
                                                    <span class="text-xs font-medium text-gray-900">{{ $property->formatted_rental_price }}</span>
                                                @endif
                                            </div>
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