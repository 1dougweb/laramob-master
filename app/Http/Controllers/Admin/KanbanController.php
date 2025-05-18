<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    /**
     * Mostra o dashboard do Kanban com tarefas e reuniões.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verificar se o usuário é admin ou manager
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return redirect()->route('admin.dashboard')->with('error', 'Acesso não autorizado.');
        }
        
        $person = $user->person;
        
        // Se o usuário não tiver uma pessoa associada, cria um objeto vazio
        if (!$person) {
            // Sem dados para exibir, mas ainda permite a visualização
            $tasks = collect();
            $upcomingMeetings = collect();
            $todayMeetings = collect();
            $clients = collect();
            
            return view('admin.kanban.index', compact('tasks', 'upcomingMeetings', 'todayMeetings', 'clients'));
        }
        
        // Busca todas as tarefas atribuídas à pessoa
        $tasks = Task::where('assigned_to', $person->id)
            ->with(['person', 'property'])
            ->latest()
            ->get()
            ->groupBy('status');
        
        // Reuniões para os próximos 7 dias
        $upcomingMeetings = Meeting::where('broker_id', $person->id)
            ->with(['client', 'property'])
            ->upcoming()
            ->orderBy('scheduled_at')
            ->take(10)
            ->get();
        
        // Reuniões para hoje
        $todayMeetings = Meeting::where('broker_id', $person->id)
            ->with(['client', 'property'])
            ->today()
            ->orderBy('scheduled_at')
            ->get();
        
        // Clientes atribuídos a este corretor (se for corretor)
        $clients = collect();
        if ($person->type === 'broker') {
            $clients = $person->clients()->get();
        }
        
        return view('admin.kanban.index', compact('tasks', 'upcomingMeetings', 'todayMeetings', 'clients', 'person'));
    }
    
    /**
     * Mostra todas as tarefas.
     */
    public function tasks()
    {
        $user = Auth::user();
        
        // Verificar se o usuário é admin ou manager
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return redirect()->route('admin.dashboard')->with('error', 'Acesso não autorizado.');
        }
        
        $person = $user->person;
        
        if (!$person) {
            // Nenhuma pessoa associada
            $backlog = collect();
            $todo = collect();
            $inProgress = collect();
            $review = collect();
            $done = collect();
            $clients = collect();
            
            return view('client.kanban.tasks', compact('backlog', 'todo', 'inProgress', 'review', 'done', 'clients'));
        }
        
        $backlog = Task::where('assigned_to', $person->id)->where('status', 'backlog')->with(['person', 'property'])->get();
        $todo = Task::where('assigned_to', $person->id)->where('status', 'todo')->with(['person', 'property'])->get();
        $inProgress = Task::where('assigned_to', $person->id)->where('status', 'in_progress')->with(['person', 'property'])->get();
        $review = Task::where('assigned_to', $person->id)->where('status', 'review')->with(['person', 'property'])->get();
        $done = Task::where('assigned_to', $person->id)->where('status', 'done')->with(['person', 'property'])->latest('completed_at')->take(20)->get();
        
        // Clientes para o formulário de nova tarefa (se for corretor)
        $clients = collect();
        if ($person->type === 'broker') {
            $clients = $person->clients()->get();
        }
        
        return view('admin.kanban.tasks', compact('backlog', 'todo', 'inProgress', 'review', 'done', 'clients', 'person'));
    }
    
    /**
     * Mostra todas as reuniões.
     */
    public function meetings()
    {
        $user = Auth::user();
        
        // Verificar se o usuário é admin ou manager
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return redirect()->route('admin.dashboard')->with('error', 'Acesso não autorizado.');
        }
        
        $person = $user->person;
        
        if (!$person) {
            // Nenhuma pessoa associada
            $upcomingMeetings = collect();
            $todayMeetings = collect();
            $completedMeetings = collect();
            $cancelledMeetings = collect();
            $clients = collect();
            
            return view('admin.kanban.meetings', compact('upcomingMeetings', 'todayMeetings', 'completedMeetings', 'cancelledMeetings', 'clients'));
        }
        
        $upcomingMeetings = Meeting::where('broker_id', $person->id)
            ->with(['admin', 'property'])
            ->upcoming()
            ->orderBy('scheduled_at')
            ->get();
            
        $todayMeetings = Meeting::where('broker_id', $person->id)
            ->with(['admin', 'property'])
            ->today()
            ->orderBy('scheduled_at')
            ->get();
            
        $completedMeetings = Meeting::where('broker_id', $person->id)
            ->with(['admin', 'property'])
            ->where('status', 'completed')
            ->latest('ended_at')
            ->take(20)
            ->get();
            
        $cancelledMeetings = Meeting::where('broker_id', $person->id)
            ->with(['admin', 'property'])
            ->where('status', 'cancelled')
            ->latest('updated_at')
            ->take(10)
            ->get();
        
        // Clientes para o formulário de nova reunião (se for corretor)
        $clients = collect();
        if ($person->type === 'broker') {
            $clients = $person->clients()->get();
        }
        
        return view('admin.kanban.meetings', compact('upcomingMeetings', 'todayMeetings', 'completedMeetings', 'cancelledMeetings', 'clients', 'person'));
    }
    
    /**
     * Cria uma nova tarefa.
     */
    public function storeTask(Request $request)
    {
        $user = Auth::user();
        
        // Verificar se o usuário é admin ou manager
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return redirect()->route('admin.dashboard')->with('error', 'Acesso não autorizado.');
        }
        
        $person = $user->person;
        
        if (!$person) {
            return redirect()->route('admin.kanban.tasks')->with('error', 'Você precisa ter um perfil para criar tarefas.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:backlog,todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'person_id' => 'nullable|exists:people,id',
            'property_id' => 'nullable|exists:properties,id',
            'due_date' => 'nullable|date',
        ]);
        
        $task = new Task($validated);
        $task->assigned_to = $person->id;
        
        if ($task->status === 'done') {
            $task->completed_at = now();
        }
        
        $task->save();
        
        return redirect()->back()->with('success', 'Tarefa criada com sucesso!');
    }
    
    /**
     * Atualiza o status de uma tarefa.
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Verificar se o usuário é admin ou manager
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }
        
        $person = $user->person;
        
        if (!$person || $task->assigned_to !== $person->id) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:backlog,todo,in_progress,review,done',
        ]);
        
        $oldStatus = $task->status;
        $task->status = $validated['status'];
        
        if ($validated['status'] === 'done' && $oldStatus !== 'done') {
            $task->completed_at = now();
        } elseif ($validated['status'] !== 'done' && $oldStatus === 'done') {
            $task->completed_at = null;
        }
        
        $task->save();
        
        return response()->json([
            'success' => true,
            'task' => $task->fresh(['person', 'property'])
        ]);
    }
    
    /**
     * Atualiza uma tarefa.
     */
    public function updateTask(Request $request, Task $task)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $task->assigned_to !== $person->id) {
            return redirect()->route('admin.kanban.tasks')->with('error', 'Não autorizado.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:backlog,todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'person_id' => 'nullable|exists:people,id',
            'property_id' => 'nullable|exists:properties,id',
            'due_date' => 'nullable|date',
        ]);
        
        $oldStatus = $task->status;
        $task->fill($validated);
        
        if ($validated['status'] === 'done' && $oldStatus !== 'done') {
            $task->completed_at = now();
        } elseif ($validated['status'] !== 'done' && $oldStatus === 'done') {
            $task->completed_at = null;
        }
        
        $task->save();
        
        return redirect()->route('admin.kanban.tasks')->with('success', 'Tarefa atualizada com sucesso!');
    }
    
    /**
     * Remove uma tarefa.
     */
    public function destroyTask(Task $task)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $task->assigned_to !== $person->id) {
            return redirect()->route('admin.kanban.tasks')->with('error', 'Não autorizado.');
        }
        
        $task->delete();
        
        return redirect()->route('admin.kanban.tasks')->with('success', 'Tarefa removida com sucesso!');
    }
    
    /**
     * Cria uma nova reunião.
     */
    public function storeMeeting(Request $request)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person) {
            return redirect()->route('admin.kanban.meetings')->with('error', 'Você precisa ter um perfil para agendar reuniões.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:people,id',
            'property_id' => 'nullable|exists:properties,id',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'is_virtual' => 'boolean',
            'meeting_link' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $meeting = new Meeting($validated);
        $meeting->broker_id = $person->id;
        $meeting->status = 'scheduled';
        $meeting->save();
        
        return redirect()->back()->with('success', 'Reunião agendada com sucesso!');
    }
    
    /**
     * Atualiza o status de uma reunião.
     */
    public function updateMeetingStatus(Request $request, Meeting $meeting)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $meeting->broker_id !== $person->id) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'outcome' => 'nullable|string',
            'cancellation_reason' => 'nullable|string',
        ]);
        
        if ($validated['status'] === 'completed') {
            $meeting->complete($validated['outcome'] ?? null);
        } elseif ($validated['status'] === 'cancelled') {
            $meeting->cancel($validated['cancellation_reason'] ?? null);
        } elseif ($validated['status'] === 'ongoing') {
            $meeting->start();
        } else {
            $meeting->status = $validated['status'];
            $meeting->save();
        }
        
        return response()->json([
            'success' => true,
            'meeting' => $meeting->fresh(['admin', 'property'])
        ]);
    }
    
    /**
     * Atualiza uma reunião.
     */
    public function updateMeeting(Request $request, Meeting $meeting)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $meeting->broker_id !== $person->id) {
            return redirect()->route('admin.kanban.meetings')->with('error', 'Não autorizado.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:people,id',
            'property_id' => 'nullable|exists:properties,id',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'location' => 'nullable|string|max:255',
            'is_virtual' => 'boolean',
            'meeting_link' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'outcome' => 'nullable|string',
        ]);
        
        $meeting->fill($validated);
        
        if ($validated['status'] === 'completed' && $meeting->getOriginal('status') !== 'completed') {
            $meeting->ended_at = now();
        }
        
        $meeting->save();
        
        return redirect()->route('admin.kanban.meetings')->with('success', 'Reunião atualizada com sucesso!');
    }
    
    /**
     * Remove uma reunião.
     */
    public function destroyMeeting(Meeting $meeting)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $meeting->broker_id !== $person->id) {
            return redirect()->route('admin.kanban.meetings')->with('error', 'Não autorizado.');
        }
        
        $meeting->delete();
        
        return redirect()->route('admin.kanban.meetings')->with('success', 'Reunião removida com sucesso!');
    }
} 