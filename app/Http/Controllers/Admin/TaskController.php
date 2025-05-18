<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display the tasks kanban board.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $person = $user->person;
            
            if (!$person) {
                $tasks = Task::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('status');
            } else {
                $tasks = Task::where('assigned_to', $person->id)
                    ->with(['person', 'property'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('status');
            }
            
            // Ensure all status columns exist even if empty
            $statuses = ['backlog', 'todo', 'in_progress', 'review', 'done'];
            foreach ($statuses as $status) {
                if (!isset($tasks[$status])) {
                    $tasks[$status] = collect();
                }
            }
            
            return view('admin.kanban.tasks', compact('tasks'));
        } catch (\Exception $e) {
            return view('admin.kanban.tasks', [
                'tasks' => collect([
                    'backlog' => collect(),
                    'todo' => collect(),
                    'in_progress' => collect(),
                    'review' => collect(),
                    'done' => collect()
                ]),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get all tasks for the current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasks()
    {
        try {
            $user = Auth::user();
            $person = $user->person;
            
            if (!$person) {
                $tasks = Task::where('user_id', Auth::id())->get();
            } else {
                $tasks = Task::where('assigned_to', $person->id)
                    ->with(['person', 'property'])
                    ->get();
            }
            
            return response()->json(['success' => true, 'tasks' => $tasks]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific task.
     *
     * @param  Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTask(Task $task)
    {
        try {
            $user = Auth::user();
            $person = $user->person;
            
            if (!$person || $task->assigned_to !== $person->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            return response()->json([
                'success' => true, 
                'task' => $task->load(['person', 'property'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Task not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    /**
     * Store a new task.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:backlog,todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'person_id' => 'nullable|exists:people,id',
            'property_id' => 'nullable|exists:properties,id',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $person = $user->person;
        
        if (!$person) {
            return response()->json([
                'success' => false,
                'message' => 'You need a person profile to create tasks. Please contact your administrator to set up your profile.'
            ], 403);
        }

        $task = new Task($request->all());
        $task->user_id = Auth::id();
        $task->assigned_to = $person->id;
        
        if ($request->status === 'done') {
            $task->completed_at = now();
        }
        
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'task' => $task->load(['person', 'property'])
        ]);
    }

    /**
     * Update a task.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTask(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:backlog,todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'person_id' => 'nullable|exists:people,id',
            'property_id' => 'nullable|exists:properties,id',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $task->assigned_to !== $person->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $oldStatus = $task->status;
        $task->fill($request->all());
        
        // Update completed_at if status changes to/from done
        if ($request->status === 'done' && $oldStatus !== 'done') {
            $task->completed_at = now();
        } elseif ($request->status !== 'done' && $oldStatus === 'done') {
            $task->completed_at = null;
        }
        
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'task' => $task->load(['person', 'property'])
        ]);
    }

    /**
     * Update a task's status.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:backlog,todo,in_progress,review,done',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $task->assigned_to !== $person->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $oldStatus = $task->status;
        $task->status = $request->status;
        
        // Update completed_at if status changes to/from done
        if ($request->status === 'done' && $oldStatus !== 'done') {
            $task->completed_at = now();
        } elseif ($request->status !== 'done' && $oldStatus === 'done') {
            $task->completed_at = null;
        }
        
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'task' => $task->load(['person', 'property'])
        ]);
    }

    /**
     * Delete a task.
     * 
     * @param  Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTask(Task $task)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $task->assigned_to !== $person->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
} 