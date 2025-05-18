<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-end items-center gap-3">

                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Voltar ao Painel
                </a>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="openTaskModal('create')">
                Adicionar Nova Tarefa
            </button>
</div>
    </x-slot>

    <style>
        .kanban-container {
            display: flex;
            width: 100%;
            height: calc(100vh - 9rem); /* Full height minus header and page header */
            overflow-x: auto;
            padding: 0.5rem;
        }

        .kanban-column {
            display: flex;
            flex-direction: column;
            min-width: 300px;
            max-width: 300px;
            margin-right: 1rem;
            background-color: #f1f5f9;
            border-radius: 0.5rem;
            height: 100%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .kanban-column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #e2e8f0; 
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .kanban-column-body {
            flex: 1;
            padding: 0.5rem;
            overflow-y: auto;
        }

        .kanban-card {
            background-color: white;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: grab;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 3px solid #3b82f6;
        }

        .kanban-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
        }

        .kanban-card.dragging {
            transform: rotate(1deg) scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            opacity: 0.9;
        }

        .kanban-add-card {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 2.5rem;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px dashed #cbd5e1;
            border-radius: 0.375rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .kanban-add-card:hover {
            background-color: white;
            border-color: #94a3b8;
        }

        /* Column color indicators */
        #backlog-column .kanban-card {
            border-left-color: #6b7280; /* Gray */
        }
        
        #todo-column .kanban-card {
            border-left-color: #3b82f6; /* Blue */
        }
        
        #in_progress-column .kanban-card {
            border-left-color: #eab308; /* Yellow */
        }
        
        #review-column .kanban-card {
            border-left-color: #8b5cf6; /* Purple */
        }
        
        #done-column .kanban-card {
            border-left-color: #22c55e; /* Green */
        }

        /* Modal Styles */
        .task-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            overflow: auto;
        }

        .task-modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 1.5rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>

<div class="flex flex-col w-full">
    <div class="p-4 flex justify-between items-center">
        <div class="kanban-container">
            <!-- Backlog Column -->
            <div class="kanban-column" id="backlog-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Entrada</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="backlog-count">{{ isset($tasks['backlog']) ? $tasks['backlog']->count() : 0 }}</span>
                </div>
                <div class="kanban-column-body task-list" id="backlog" data-status="backlog">
                    <div class="kanban-add-card" onclick="openTaskModal('create', 'backlog')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    
                    @if(isset($tasks['backlog']))
                        @foreach($tasks['backlog'] as $task)
                            <div class="kanban-card task-card" data-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $task->priority === 'high' ? 'Alta' : ($task->priority === 'medium' ? 'Média' : 'Baixa') }}
                                    </span>
                                </div>
                                @if($task->description)
                                <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($task->description, 100) }}</p>
                                @endif
                                <div class="flex justify-between items-center mt-3">
                                    @if($task->due_date)
                                    <span class="text-xs text-gray-500">Prazo: {{ $task->due_date->format('d/m') }}</span>
                                    @else
                                    <span class="text-xs text-gray-500">Sem prazo</span>
                                    @endif
                                    <div class="flex space-x-2">
                                        <button type="button" class="text-blue-500 hover:text-blue-700" onclick="openTaskModal('edit', '{{ $task->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Todo Column -->
            <div class="kanban-column" id="todo-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">A Fazer</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="todo-count">{{ isset($tasks['todo']) ? $tasks['todo']->count() : 0 }}</span>
                </div>
                <div class="kanban-column-body task-list" id="todo" data-status="todo">
                    <div class="kanban-add-card" onclick="openTaskModal('create', 'todo')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    
                    @if(isset($tasks['todo']))
                        @foreach($tasks['todo'] as $task)
                            <div class="kanban-card task-card" data-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $task->priority === 'high' ? 'Alta' : ($task->priority === 'medium' ? 'Média' : 'Baixa') }}
                                    </span>
                                </div>
                                @if($task->description)
                                <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($task->description, 100) }}</p>
                                @endif
                                <div class="flex justify-between items-center mt-3">
                                    @if($task->due_date)
                                    <span class="text-xs text-gray-500">Prazo: {{ $task->due_date->format('d/m') }}</span>
                                    @else
                                    <span class="text-xs text-gray-500">Sem prazo</span>
                                    @endif
                                    <div class="flex space-x-2">
                                        <button type="button" class="text-blue-500 hover:text-blue-700" onclick="openTaskModal('edit', '{{ $task->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="kanban-column" id="in_progress-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Em Andamento</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="in_progress-count">{{ isset($tasks['in_progress']) ? $tasks['in_progress']->count() : 0 }}</span>
                </div>
                <div class="kanban-column-body task-list" id="in_progress" data-status="in_progress">
                    <div class="kanban-add-card" onclick="openTaskModal('create', 'in_progress')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    
                    @if(isset($tasks['in_progress']))
                        @foreach($tasks['in_progress'] as $task)
                            <div class="kanban-card task-card" data-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $task->priority === 'high' ? 'Alta' : ($task->priority === 'medium' ? 'Média' : 'Baixa') }}
                                    </span>
                                </div>
                                @if($task->description)
                                <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($task->description, 100) }}</p>
                                @endif
                                <div class="flex justify-between items-center mt-3">
                                    @if($task->due_date)
                                    <span class="text-xs text-gray-500">Prazo: {{ $task->due_date->format('d/m') }}</span>
                                    @else
                                    <span class="text-xs text-gray-500">Sem prazo</span>
                                    @endif
                                    <div class="flex space-x-2">
                                        <button type="button" class="text-blue-500 hover:text-blue-700" onclick="openTaskModal('edit', '{{ $task->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Review Column -->
            <div class="kanban-column" id="review-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Revisão</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="review-count">{{ isset($tasks['review']) ? $tasks['review']->count() : 0 }}</span>
                </div>
                <div class="kanban-column-body task-list" id="review" data-status="review">
                    <div class="kanban-add-card" onclick="openTaskModal('create', 'review')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    
                    @if(isset($tasks['review']))
                        @foreach($tasks['review'] as $task)
                            <div class="kanban-card task-card" data-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $task->priority === 'high' ? 'Alta' : ($task->priority === 'medium' ? 'Média' : 'Baixa') }}
                                    </span>
                                </div>
                                @if($task->description)
                                <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($task->description, 100) }}</p>
                                @endif
                                <div class="flex justify-between items-center mt-3">
                                    @if($task->due_date)
                                    <span class="text-xs text-gray-500">Prazo: {{ $task->due_date->format('d/m') }}</span>
                                    @else
                                    <span class="text-xs text-gray-500">Sem prazo</span>
                                    @endif
                                    <div class="flex space-x-2">
                                        <button type="button" class="text-blue-500 hover:text-blue-700" onclick="openTaskModal('edit', '{{ $task->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Done Column -->
            <div class="kanban-column" id="done-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Concluído</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="done-count">{{ isset($tasks['done']) ? $tasks['done']->count() : 0 }}</span>
                </div>
                <div class="kanban-column-body task-list" id="done" data-status="done">
                    <div class="kanban-add-card" onclick="openTaskModal('create', 'done')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    
                    @if(isset($tasks['done']))
                        @foreach($tasks['done'] as $task)
                            <div class="kanban-card task-card" data-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $task->priority === 'high' ? 'Alta' : ($task->priority === 'medium' ? 'Média' : 'Baixa') }}
                                    </span>
                                </div>
                                @if($task->description)
                                <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($task->description, 100) }}</p>
                                @endif
                                <div class="flex justify-between items-center mt-3">
                                    @if($task->due_date)
                                    <span class="text-xs text-gray-500">Prazo: {{ $task->due_date->format('d/m') }}</span>
                                    @else
                                    <span class="text-xs text-gray-500">Sem prazo</span>
                                    @endif
                                    <div class="flex space-x-2">
                                        <button type="button" class="text-blue-500 hover:text-blue-700" onclick="openTaskModal('edit', '{{ $task->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Task Modal -->
        <div id="task-modal" class="task-modal">
            <div class="task-modal-content">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold" id="modal-title">Adicionar Nova Tarefa</h2>
                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeTaskModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="task-form">
                    <input type="hidden" id="task-id">
                    <input type="hidden" id="task-status" name="status">
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input type="text" id="title" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Vencimento</label>
                        <input type="date" id="due_date" name="due_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridade</label>
                        <select id="priority" name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="low">Baixa</option>
                            <option value="medium">Média</option>
                            <option value="high">Alta</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end mt-6 space-x-3">
                        <button type="button" id="delete-task" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 hidden">
                            Excluir
                        </button>
                        <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500" onclick="closeTaskModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="{{ asset('assets/js/kanban.js') }}"></script>
    @endpush
</x-app-layout> 