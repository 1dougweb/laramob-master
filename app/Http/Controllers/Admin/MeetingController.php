<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    /**
     * Display the meetings kanban board.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
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
            ->with(['client', 'property'])
            ->upcoming()
            ->orderBy('scheduled_at')
            ->get();
            
        $todayMeetings = Meeting::where('broker_id', $person->id)
            ->with(['client', 'property'])
            ->today()
            ->orderBy('scheduled_at')
            ->get();
            
        $completedMeetings = Meeting::where('broker_id', $person->id)
            ->with(['client', 'property'])
            ->where('status', 'completed')
            ->latest('ended_at')
            ->take(20)
            ->get();
            
        $cancelledMeetings = Meeting::where('broker_id', $person->id)
            ->with(['client', 'property'])
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
     * Get all meetings for the current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMeetings()
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person) {
            return response()->json(['success' => false, 'message' => 'No person profile found'], 404);
        }
        
        $meetings = Meeting::where('broker_id', $person->id)
            ->with(['client', 'property'])
            ->get();
        
        return response()->json(['success' => true, 'meetings' => $meetings]);
    }

    /**
     * Get a specific meeting.
     *
     * @param  Meeting  $meeting
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMeeting(Meeting $meeting)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $meeting->broker_id !== $person->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        return response()->json([
            'success' => true, 
            'meeting' => $meeting->load(['client', 'property'])
        ]);
    }

    /**
     * Store a new meeting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMeeting(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person) {
            return response()->json([
                'success' => false,
                'message' => 'You need a person profile to schedule meetings'
            ], 403);
        }
        
        $meeting = new Meeting($request->all());
        $meeting->broker_id = $person->id;
        $meeting->status = 'scheduled';
        $meeting->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Meeting scheduled successfully',
            'meeting' => $meeting->load(['client', 'property'])
        ]);
    }

    /**
     * Update a meeting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Meeting  $meeting
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMeeting(Request $request, Meeting $meeting)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $meeting->broker_id !== $person->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $oldStatus = $meeting->status;
        $meeting->fill($request->all());
        
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $meeting->ended_at = now();
        }
        
        $meeting->save();

        return response()->json([
            'success' => true,
            'message' => 'Meeting updated successfully',
            'meeting' => $meeting->load(['client', 'property'])
        ]);
    }

    /**
     * Update a meeting's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Meeting  $meeting
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMeetingStatus(Request $request, Meeting $meeting)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'outcome' => 'nullable|string',
            'cancellation_reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $meeting->broker_id !== $person->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($request->status === 'completed') {
            $meeting->complete($request->outcome ?? null);
        } elseif ($request->status === 'cancelled') {
            $meeting->cancel($request->cancellation_reason ?? null);
        } elseif ($request->status === 'ongoing') {
            $meeting->start();
        } else {
            $meeting->status = $request->status;
            $meeting->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Meeting status updated successfully',
            'meeting' => $meeting->fresh(['client', 'property'])
        ]);
    }

    /**
     * Delete a meeting.
     *
     * @param  Meeting  $meeting
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMeeting(Meeting $meeting)
    {
        $user = Auth::user();
        $person = $user->person;
        
        if (!$person || $meeting->broker_id !== $person->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $meeting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meeting deleted successfully'
        ]);
    }
} 