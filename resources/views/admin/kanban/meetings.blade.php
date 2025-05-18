<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meeting Kanban Board') }}
        </h2>
    </x-slot>

    <style>
        .kanban-container {
            display: flex;
            width: 100%;
            height: calc(100vh - 9rem); /* Full height minus header and page header */
            overflow-x: auto;
            padding: 0.5rem;
            background-color: #f8fafc;
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
        <div class="p-4 bg-white shadow-sm flex justify-between items-center">
            <div class="flex space-x-2">
                <a href="{{ route('admin.kanban.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Back to Dashboard
                </a>
            </div>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="openMeetingModal('create')">
                Add New Meeting
            </button>
        </div>

        <div class="kanban-container">
            <!-- Scheduled Column -->
            <div class="kanban-column" id="scheduled-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Scheduled</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="scheduled-count">0</span>
                </div>
                <div class="kanban-column-body" id="scheduled">
                    <div class="kanban-add-card" onclick="openMeetingModal('create', 'scheduled')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <!-- Meeting cards will be added here dynamically -->
                </div>
            </div>

            <!-- Ongoing Column -->
            <div class="kanban-column" id="ongoing-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Ongoing</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="ongoing-count">0</span>
                </div>
                <div class="kanban-column-body" id="ongoing">
                    <div class="kanban-add-card" onclick="openMeetingModal('create', 'ongoing')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <!-- Meeting cards will be added here dynamically -->
                </div>
            </div>

            <!-- Completed Column -->
            <div class="kanban-column" id="completed-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Completed</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="completed-count">0</span>
                </div>
                <div class="kanban-column-body" id="completed">
                    <div class="kanban-add-card" onclick="openMeetingModal('create', 'completed')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <!-- Meeting cards will be added here dynamically -->
                </div>
            </div>

            <!-- Cancelled Column -->
            <div class="kanban-column" id="cancelled-column">
                <div class="kanban-column-header">
                    <h2 class="font-semibold">Cancelled</h2>
                    <span class="px-2 py-1 bg-gray-200 rounded-full text-xs" id="cancelled-count">0</span>
                </div>
                <div class="kanban-column-body" id="cancelled">
                    <div class="kanban-add-card" onclick="openMeetingModal('create', 'cancelled')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <!-- Meeting cards will be added here dynamically -->
                </div>
            </div>
        </div>

        <!-- Meeting Modal -->
        <div id="meeting-modal" class="meeting-modal">
            <div class="meeting-modal-content">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold" id="modal-title">Add New Meeting</h2>
                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeMeetingModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="meeting-form">
                    <input type="hidden" id="meeting-id">
                    <input type="hidden" id="meeting-status">
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="title" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" id="date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                            <input type="time" id="time" name="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="participants" class="block text-sm font-medium text-gray-700 mb-1">Participants</label>
                        <input type="text" id="participants" name="participants" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Comma separated names">
                    </div>
                    
                    <div class="flex justify-end mt-6 space-x-3">
                        <button type="button" id="delete-meeting" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 hidden">
                            Delete
                        </button>
                        <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500" onclick="closeMeetingModal()">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Save
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