<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kanban Administrativo') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ver todas as tarefas
                </a>
                <a href="{{ route('admin.kanban.meetings') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ver todas as reuniões
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Resumo -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Resumo de Atividades') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Visão geral das suas tarefas e reuniões.') }}
                            </p>
                        </header>
                    
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Card de Tarefas Pendentes -->
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <h3 class="font-semibold text-blue-800">Tarefas Pendentes</h3>
                                <p class="text-3xl font-bold mt-2">
                                    {{ ($tasks['todo'] ?? collect())->count() + ($tasks['in_progress'] ?? collect())->count() }}
                                </p>
                                <div class="mt-2 text-sm">
                                    <span class="text-blue-600">{{ $tasks['todo'] ?? collect()->count() }} para fazer</span> | 
                                    <span class="text-purple-600">{{ $tasks['in_progress'] ?? collect()->count() }} em progresso</span>
                                </div>
                            </div>
                            
                            <!-- Card de Reuniões Hoje -->
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <h3 class="font-semibold text-green-800">Reuniões Hoje</h3>
                                <p class="text-3xl font-bold mt-2">{{ $todayMeetings->count() }}</p>
                                @if($todayMeetings->count() > 0)
                                    <p class="mt-2 text-sm text-green-600">
                                        Próxima: {{ $todayMeetings->sortBy('scheduled_at')->first()->scheduled_at->format('H:i') }}
                                    </p>
                                @else
                                    <p class="mt-2 text-sm text-gray-600">Sem reuniões hoje</p>
                                @endif
                            </div>
                            
                            <!-- Card de Clientes -->
                            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                <h3 class="font-semibold text-purple-800">Seus Clientes</h3>
                                <p class="text-3xl font-bold mt-2">{{ $clients->count() }}</p>
                                <p class="mt-2 text-sm text-purple-600">
                                    {{ $clients->where('type', 'client')->count() }} compradores | 
                                    {{ $clients->where('type', 'tenant')->count() }} locatários
                                </p>
                            </div>
                            
                            <!-- Card de Tarefas Atrasadas -->
                            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                <h3 class="font-semibold text-red-800">Tarefas Atrasadas</h3>
                                @php
                                    $overdueTasks = collect();
                                    foreach ($tasks as $status => $statusTasks) {
                                        if ($status !== 'done') {
                                            $overdueTasks = $overdueTasks->merge($statusTasks->filter(function($task) {
                                                return $task->due_date && $task->due_date->isPast();
                                            }));
                                        }
                                    }
                                @endphp
                                <p class="text-3xl font-bold mt-2">{{ $overdueTasks->count() }}</p>
                                <p class="mt-2 text-sm text-red-600">
                                    Requer sua atenção imediata
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Reuniões de Hoje -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header class="flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Reuniões de Hoje') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Seus compromissos agendados para hoje.') }}
                                </p>
                            </div>
                            <a href="{{ route('admin.kanban.meetings') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                Ver todas →
                            </a>
                        </header>
                        
                        <div class="mt-6">
                            @if ($todayMeetings->isEmpty())
                                <p class="text-gray-500 italic">Não há reuniões agendadas para hoje.</p>
                            @else
                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($todayMeetings->sortBy('scheduled_at') as $meeting)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $meeting->scheduled_at->format('H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $meeting->title }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $meeting->client ? $meeting->client->name : 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if ($meeting->status === 'scheduled')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                Agendada
                                                            </span>
                                                        @elseif ($meeting->status === 'ongoing')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Em andamento
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <!-- Tarefas em Andamento -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header class="flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Tarefas em Andamento') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Suas tarefas em progresso.') }}
                                </p>
                            </div>
                            <a href="{{ route('admin.kanban.tasks') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                Ver todas →
                            </a>
                        </header>
                        
                        <div class="mt-6">
                            @if (!isset($tasks['in_progress']) || $tasks['in_progress']->isEmpty())
                                <p class="text-gray-500 italic">Não há tarefas em andamento.</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($tasks['in_progress']->take(4) as $task)
                                        <div class="border border-purple-200 rounded-lg p-4 bg-purple-50">
                                            <h3 class="font-medium text-purple-800">{{ $task->title }}</h3>
                                            <div class="mt-2 flex justify-between">
                                                <span class="text-sm text-gray-600">
                                                    @if ($task->person)
                                                        Cliente: {{ $task->person->name }}
                                                    @endif
                                                </span>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Em progresso
                                                </span>
                                            </div>
                                            @if ($task->due_date)
                                                <div class="mt-2 text-sm {{ $task->due_date->isPast() ? 'text-red-600' : 'text-gray-600' }}">
                                                    Data limite: {{ $task->due_date->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <!-- Próximas Reuniões -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header class="flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Próximas Reuniões') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Reuniões agendadas para os próximos dias.') }}
                                </p>
                            </div>
                            <a href="{{ route('admin.kanban.meetings') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                Ver todas →
                            </a>
                        </header>
                        
                        <div class="mt-6">
                            @if ($upcomingMeetings->isEmpty())
                                <p class="text-gray-500 italic">Não há reuniões agendadas para os próximos dias.</p>
                            @else
                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($upcomingMeetings->sortBy('scheduled_at')->take(5) as $meeting)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $meeting->scheduled_at->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $meeting->scheduled_at->format('H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $meeting->title }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $meeting->client ? $meeting->client->name : 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                            {{ $meeting->type ?? 'Padrão' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 