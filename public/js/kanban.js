document.addEventListener('DOMContentLoaded', function() {
    // Determine the current page type
    const isTaskBoard = document.getElementById('task-form') !== null;
    const isMeetingBoard = document.getElementById('meeting-form') !== null;
    
    // Initialize Sortable for each column
    const columns = document.querySelectorAll('.kanban-column-body');
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'bg-gray-100',
            chosenClass: 'dragging',
            dragClass: 'dragging',
            onEnd: function(evt) {
                const itemId = evt.item.getAttribute('data-id');
                const newStatus = evt.to.id;
                
                if (isTaskBoard) {
                    updateTaskStatus(itemId, newStatus);
                } else if (isMeetingBoard) {
                    updateMeetingStatus(itemId, newStatus);
                }
                
                // Update counts
                updateColumnCounts();
            }
        });
    });
    
    // Handle form submissions
    if (isTaskBoard) {
        setupTaskForms();
    } else if (isMeetingBoard) {
        setupMeetingForms();
    }
    
    // Initial load of items
    if (isTaskBoard) {
        loadTasks();
    } else if (isMeetingBoard) {
        loadMeetings();
    }
});

// Tasks Functions
function setupTaskForms() {
    const taskForm = document.getElementById('task-form');
    const deleteTaskBtn = document.getElementById('delete-task');
    
    taskForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const taskId = document.getElementById('task-id').value;
        const mode = taskId ? 'update' : 'create';
        
        saveTask(mode);
    });
    
    deleteTaskBtn.addEventListener('click', function() {
        const taskId = document.getElementById('task-id').value;
        if (taskId) {
            deleteTask(taskId);
        }
    });
}

function openTaskModal(mode, status = '') {
    const modal = document.getElementById('task-modal');
    const modalTitle = document.getElementById('modal-title');
    const deleteBtn = document.getElementById('delete-task');
    const form = document.getElementById('task-form');
    
    // Reset form
    form.reset();
    document.getElementById('task-id').value = '';
    
    if (mode === 'create') {
        modalTitle.textContent = 'Add New Task';
        document.getElementById('task-status').value = status;
        deleteBtn.classList.add('hidden');
    } else if (mode === 'edit') {
        modalTitle.textContent = 'Edit Task';
        deleteBtn.classList.remove('hidden');
    }
    
    modal.style.display = 'block';
}

function closeTaskModal() {
    const modal = document.getElementById('task-modal');
    modal.style.display = 'none';
}

async function loadTasks() {
    try {
        const response = await fetch('/api/tasks');
        const tasks = await response.json();
        
        // Clear existing tasks
        document.querySelectorAll('.kanban-card').forEach(card => {
            if (!card.classList.contains('kanban-add-card')) {
                card.remove();
            }
        });
        
        // Add tasks to appropriate columns
        tasks.forEach(task => {
            addTaskToColumn(task);
        });
        
        // Update column counts
        updateColumnCounts();
    } catch (error) {
        showToast('Error loading tasks', 'error');
        console.error('Error loading tasks:', error);
    }
}

function addTaskToColumn(task) {
    const column = document.getElementById(task.status);
    const addCardBtn = column.querySelector('.kanban-add-card');
    
    const cardHtml = `
        <div class="kanban-card" data-id="${task.id}">
            <div class="flex justify-between items-start">
                <h3 class="font-medium text-gray-900">${task.title}</h3>
                <span class="px-2 py-1 text-xs rounded-full ${getPriorityClass(task.priority)}">${task.priority}</span>
            </div>
            <p class="text-sm text-gray-600 mt-2 line-clamp-2">${task.description || ''}</p>
            ${task.due_date ? `<div class="mt-3 text-xs text-gray-500">Due: ${formatDate(task.due_date)}</div>` : ''}
            <button class="mt-2 text-sm text-blue-600 hover:text-blue-800" onclick="editTask(${task.id})">
                Edit
            </button>
        </div>
    `;
    
    // Insert before the add card button
    addCardBtn.insertAdjacentHTML('beforebegin', cardHtml);
}

function getPriorityClass(priority) {
    switch(priority) {
        case 'low':
            return 'bg-green-100 text-green-800';
        case 'medium':
            return 'bg-yellow-100 text-yellow-800';
        case 'high':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

async function saveTask(mode) {
    const taskId = document.getElementById('task-id').value;
    const status = document.getElementById('task-status').value;
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    const dueDate = document.getElementById('due_date').value;
    const priority = document.getElementById('priority').value;
    
    const taskData = {
        title,
        description,
        due_date: dueDate,
        priority,
        status
    };
    
    try {
        let response;
        if (mode === 'create') {
            response = await fetch('/api/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(taskData)
            });
        } else {
            response = await fetch(`/api/tasks/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(taskData)
            });
        }
        
        if (!response.ok) {
            throw new Error('Failed to save task');
        }
        
        const task = await response.json();
        
        // Update UI
        if (mode === 'create') {
            addTaskToColumn(task);
        } else {
            // Remove existing card
            const existingCard = document.querySelector(`.kanban-card[data-id="${taskId}"]`);
            if (existingCard) {
                existingCard.remove();
            }
            // Add updated card
            addTaskToColumn(task);
        }
        
        // Update column counts
        updateColumnCounts();
        
        // Close modal
        closeTaskModal();
        
        // Show success toast
        showToast(`Task ${mode === 'create' ? 'created' : 'updated'} successfully`, 'success');
    } catch (error) {
        showToast('Error saving task', 'error');
        console.error('Error saving task:', error);
    }
}

async function editTask(taskId) {
    try {
        const response = await fetch(`/api/tasks/${taskId}`);
        const task = await response.json();
        
        // Populate form
        document.getElementById('task-id').value = task.id;
        document.getElementById('task-status').value = task.status;
        document.getElementById('title').value = task.title;
        document.getElementById('description').value = task.description || '';
        document.getElementById('due_date').value = task.due_date || '';
        document.getElementById('priority').value = task.priority || 'medium';
        
        // Open modal
        openTaskModal('edit');
    } catch (error) {
        showToast('Error loading task details', 'error');
        console.error('Error loading task details:', error);
    }
}

async function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to delete task');
        }
        
        // Remove card from UI
        const card = document.querySelector(`.kanban-card[data-id="${taskId}"]`);
        if (card) {
            card.remove();
        }
        
        // Update column counts
        updateColumnCounts();
        
        // Close modal
        closeTaskModal();
        
        // Show success toast
        showToast('Task deleted successfully', 'success');
    } catch (error) {
        showToast('Error deleting task', 'error');
        console.error('Error deleting task:', error);
    }
}

async function updateTaskStatus(taskId, newStatus) {
    try {
        const response = await fetch(`/api/tasks/${taskId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        if (!response.ok) {
            throw new Error('Failed to update task status');
        }
        
        // Show success toast
        showToast('Task status updated', 'success');
    } catch (error) {
        showToast('Error updating task status', 'error');
        console.error('Error updating task status:', error);
        
        // Reload to restore original state
        loadTasks();
    }
}

// Meetings Functions
function setupMeetingForms() {
    const meetingForm = document.getElementById('meeting-form');
    const deleteMeetingBtn = document.getElementById('delete-meeting');
    
    meetingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const meetingId = document.getElementById('meeting-id').value;
        const mode = meetingId ? 'update' : 'create';
        
        saveMeeting(mode);
    });
    
    deleteMeetingBtn.addEventListener('click', function() {
        const meetingId = document.getElementById('meeting-id').value;
        if (meetingId) {
            deleteMeeting(meetingId);
        }
    });
}

function openMeetingModal(mode, status = '') {
    const modal = document.getElementById('meeting-modal');
    const modalTitle = document.getElementById('modal-title');
    const deleteBtn = document.getElementById('delete-meeting');
    const form = document.getElementById('meeting-form');
    
    // Reset form
    form.reset();
    document.getElementById('meeting-id').value = '';
    
    if (mode === 'create') {
        modalTitle.textContent = 'Add New Meeting';
        document.getElementById('meeting-status').value = status;
        deleteBtn.classList.add('hidden');
    } else if (mode === 'edit') {
        modalTitle.textContent = 'Edit Meeting';
        deleteBtn.classList.remove('hidden');
    }
    
    modal.style.display = 'block';
}

function closeMeetingModal() {
    const modal = document.getElementById('meeting-modal');
    modal.style.display = 'none';
}

async function loadMeetings() {
    try {
        const response = await fetch('/api/meetings');
        const meetings = await response.json();
        
        // Clear existing meetings
        document.querySelectorAll('.kanban-card').forEach(card => {
            if (!card.classList.contains('kanban-add-card')) {
                card.remove();
            }
        });
        
        // Add meetings to appropriate columns
        meetings.forEach(meeting => {
            addMeetingToColumn(meeting);
        });
        
        // Update column counts
        updateColumnCounts();
    } catch (error) {
        showToast('Error loading meetings', 'error');
        console.error('Error loading meetings:', error);
    }
}

function addMeetingToColumn(meeting) {
    const column = document.getElementById(meeting.status);
    const addCardBtn = column.querySelector('.kanban-add-card');
    
    const cardHtml = `
        <div class="kanban-card" data-id="${meeting.id}">
            <div class="font-medium text-gray-900">${meeting.title}</div>
            <p class="text-sm text-gray-600 mt-2 line-clamp-2">${meeting.description || ''}</p>
            <div class="mt-3 text-xs text-gray-500">
                ${meeting.date ? `Date: ${formatDate(meeting.date)}` : ''}
                ${meeting.time ? ` at ${meeting.time}` : ''}
            </div>
            ${meeting.location ? `<div class="text-xs text-gray-500">Location: ${meeting.location}</div>` : ''}
            <button class="mt-2 text-sm text-blue-600 hover:text-blue-800" onclick="editMeeting(${meeting.id})">
                Edit
            </button>
        </div>
    `;
    
    // Insert before the add card button
    addCardBtn.insertAdjacentHTML('beforebegin', cardHtml);
}

async function saveMeeting(mode) {
    const meetingId = document.getElementById('meeting-id').value;
    const status = document.getElementById('meeting-status').value;
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const location = document.getElementById('location').value;
    const participants = document.getElementById('participants').value;
    
    const meetingData = {
        title,
        description,
        date,
        time,
        location,
        participants,
        status
    };
    
    try {
        let response;
        if (mode === 'create') {
            response = await fetch('/api/meetings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(meetingData)
            });
        } else {
            response = await fetch(`/api/meetings/${meetingId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(meetingData)
            });
        }
        
        if (!response.ok) {
            throw new Error('Failed to save meeting');
        }
        
        const meeting = await response.json();
        
        // Update UI
        if (mode === 'create') {
            addMeetingToColumn(meeting);
        } else {
            // Remove existing card
            const existingCard = document.querySelector(`.kanban-card[data-id="${meetingId}"]`);
            if (existingCard) {
                existingCard.remove();
            }
            // Add updated card
            addMeetingToColumn(meeting);
        }
        
        // Update column counts
        updateColumnCounts();
        
        // Close modal
        closeMeetingModal();
        
        // Show success toast
        showToast(`Meeting ${mode === 'create' ? 'created' : 'updated'} successfully`, 'success');
    } catch (error) {
        showToast('Error saving meeting', 'error');
        console.error('Error saving meeting:', error);
    }
}

async function editMeeting(meetingId) {
    try {
        const response = await fetch(`/api/meetings/${meetingId}`);
        const meeting = await response.json();
        
        // Populate form
        document.getElementById('meeting-id').value = meeting.id;
        document.getElementById('meeting-status').value = meeting.status;
        document.getElementById('title').value = meeting.title;
        document.getElementById('description').value = meeting.description || '';
        document.getElementById('date').value = meeting.date || '';
        document.getElementById('time').value = meeting.time || '';
        document.getElementById('location').value = meeting.location || '';
        document.getElementById('participants').value = meeting.participants || '';
        
        // Open modal
        openMeetingModal('edit');
    } catch (error) {
        showToast('Error loading meeting details', 'error');
        console.error('Error loading meeting details:', error);
    }
}

async function deleteMeeting(meetingId) {
    if (!confirm('Are you sure you want to delete this meeting?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/meetings/${meetingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to delete meeting');
        }
        
        // Remove card from UI
        const card = document.querySelector(`.kanban-card[data-id="${meetingId}"]`);
        if (card) {
            card.remove();
        }
        
        // Update column counts
        updateColumnCounts();
        
        // Close modal
        closeMeetingModal();
        
        // Show success toast
        showToast('Meeting deleted successfully', 'success');
    } catch (error) {
        showToast('Error deleting meeting', 'error');
        console.error('Error deleting meeting:', error);
    }
}

async function updateMeetingStatus(meetingId, newStatus) {
    try {
        const response = await fetch(`/api/meetings/${meetingId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        if (!response.ok) {
            throw new Error('Failed to update meeting status');
        }
        
        // Show success toast
        showToast('Meeting status updated', 'success');
    } catch (error) {
        showToast('Error updating meeting status', 'error');
        console.error('Error updating meeting status:', error);
        
        // Reload to restore original state
        loadMeetings();
    }
}

// Utility Functions
function updateColumnCounts() {
    const columns = document.querySelectorAll('.kanban-column-body');
    columns.forEach(column => {
        const columnId = column.id;
        const cards = column.querySelectorAll('.kanban-card').length - 1; // Subtract 1 for the add card button
        const countElement = document.getElementById(`${columnId}-count`);
        if (countElement) {
            countElement.textContent = cards > 0 ? cards : '0';
        }
    });
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    
    const toast = document.createElement('div');
    toast.className = `flex items-center p-4 mb-3 rounded-md ${getToastClass(type)}`;
    toast.innerHTML = `
        <div class="mr-3">${getToastIcon(type)}</div>
        <div>${message}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-gray-500 hover:text-gray-900 rounded-lg p-1.5 inline-flex h-8 w-8">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0');
        setTimeout(() => {
            container.removeChild(toast);
        }, 300);
    }, 3000);
    
    // Add click event to close button
    const closeBtn = toast.querySelector('button');
    closeBtn.addEventListener('click', () => {
        container.removeChild(toast);
    });
}

function getToastClass(type) {
    switch(type) {
        case 'success':
            return 'bg-green-100 text-green-800';
        case 'error':
            return 'bg-red-100 text-red-800';
        case 'warning':
            return 'bg-yellow-100 text-yellow-800';
        case 'info':
        default:
            return 'bg-blue-100 text-blue-800';
    }
}

function getToastIcon(type) {
    switch(type) {
        case 'success':
            return '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
        case 'error':
            return '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        case 'warning':
            return '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        case 'info':
        default:
            return '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
    }
} 