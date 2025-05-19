<?php

use Illuminate\Http\Request;

// Kanban Board API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Task API Routes
    Route::get('/tasks', [App\Http\Controllers\Client\TaskController::class, 'getTasks']);
    Route::get('/tasks/{id}', [App\Http\Controllers\Client\TaskController::class, 'getTask']);
    Route::post('/tasks', [App\Http\Controllers\Client\TaskController::class, 'store']);
    Route::put('/tasks/{id}', [App\Http\Controllers\Client\TaskController::class, 'update']);
    Route::put('/tasks/{id}/status', [App\Http\Controllers\Client\TaskController::class, 'updateStatus']);
    Route::delete('/tasks/{id}', [App\Http\Controllers\Client\TaskController::class, 'destroy']);
    
    // Meeting API Routes
    Route::get('/meetings', [App\Http\Controllers\Client\MeetingController::class, 'getMeetings']);
    Route::get('/meetings/{id}', [App\Http\Controllers\Client\MeetingController::class, 'getMeeting']);
    Route::post('/meetings', [App\Http\Controllers\Client\MeetingController::class, 'store']);
    Route::put('/meetings/{id}', [App\Http\Controllers\Client\MeetingController::class, 'update']);
    Route::put('/meetings/{id}/status', [App\Http\Controllers\Client\MeetingController::class, 'updateStatus']);
    Route::delete('/meetings/{id}', [App\Http\Controllers\Client\MeetingController::class, 'destroy']);
});

Route::get('/banks/search', function (Request $request) {
    $query = $request->input('q');
    return \App\Models\Bank::where('name', 'like', "%{$query}%")->orderBy('name')->get();
}); 