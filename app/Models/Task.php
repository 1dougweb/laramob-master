<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'due_time',
        'priority',
        'status',
        'person_id',
        'assigned_to',
        'property_id',
        'completed_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'due_time' => 'string',
        'completed_at' => 'datetime',
        'user_id' => 'integer',
        'person_id' => 'integer',
        'assigned_to' => 'integer',
        'property_id' => 'integer',
    ];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the person (client) related to this task.
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * Get the person (broker) assigned to this task.
     */
    public function assignedTo()
    {
        return $this->belongsTo(Person::class, 'assigned_to');
    }

    /**
     * Get the property related to this task.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Mark a task as completed.
     */
    public function complete()
    {
        $this->status = 'done';
        $this->completed_at = now();
        $this->save();
        
        return $this;
    }

    /**
     * Scope a query to only include tasks for a specific broker.
     */
    public function scopeForBroker($query, $brokerId)
    {
        return $query->where('assigned_to', $brokerId);
    }

    /**
     * Scope a query to only include tasks for a specific client.
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('person_id', $clientId);
    }

    /**
     * Scope a query to only include tasks for a specific property.
     */
    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Scope a query to only include tasks that are overdue.
     */
    public function scopeOverdue($query)
    {
        return $query
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['done']);
    }

    /**
     * Scope a query to only include tasks due today.
     */
    public function scopeDueToday($query)
    {
        return $query
            ->whereNotNull('due_date')
            ->whereDate('due_date', now())
            ->whereNotIn('status', ['done']);
    }

    /**
     * Scope a query to only include tasks due this week.
     */
    public function scopeDueThisWeek($query)
    {
        return $query
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotIn('status', ['done']);
    }
} 