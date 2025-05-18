@props([
    'pendingTasksCount' => 0,
    'todoTasksCount' => 0,
    'inProgressTasksCount' => 0,
    'todayMeetingsCount' => 0,
    'nextMeeting' => null,
    'clientsCount' => 0,
    'activeClientsCount' => 0,
    'ownerClientsCount' => 0,
    'overdueTasksCount' => 0,
    'person' => null
])

@php
    // Ensure we have proper integer values
    $pendingTasksCount = is_numeric($pendingTasksCount) ? (int)$pendingTasksCount : 0;
    $todoTasksCount = is_numeric($todoTasksCount) ? (int)$todoTasksCount : 0;
    $inProgressTasksCount = is_numeric($inProgressTasksCount) ? (int)$inProgressTasksCount : 0;
    $overdueTasksCount = is_numeric($overdueTasksCount) ? (int)$overdueTasksCount : 0;
    $todayMeetingsCount = is_numeric($todayMeetingsCount) ? (int)$todayMeetingsCount : 0;
    $clientsCount = is_numeric($clientsCount) ? (int)$clientsCount : 0;
    $activeClientsCount = is_numeric($activeClientsCount) ? (int)$activeClientsCount : 0;
    $ownerClientsCount = is_numeric($ownerClientsCount) ? (int)$ownerClientsCount : 0;
@endphp

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