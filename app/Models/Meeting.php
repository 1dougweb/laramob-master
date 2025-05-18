<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
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
        'date',
        'time',
        'location',
        'participants',
        'status',
        'broker_id',
        'client_id',
        'property_id',
        'scheduled_at',
        'ended_at',
        'is_virtual',
        'meeting_link',
        'notes',
        'outcome',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'scheduled_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_virtual' => 'boolean',
    ];

    /**
     * Get the user that owns the meeting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the broker for this meeting.
     */
    public function broker()
    {
        return $this->belongsTo(Person::class, 'broker_id');
    }

    /**
     * Get the client for this meeting.
     */
    public function client()
    {
        return $this->belongsTo(Person::class, 'client_id');
    }

    /**
     * Get the property related to this meeting.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Mark a meeting as completed.
     */
    public function complete($outcome = null)
    {
        $this->status = 'completed';
        $this->ended_at = now();
        
        if ($outcome) {
            $this->outcome = $outcome;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Cancel a meeting.
     */
    public function cancel($reason = null)
    {
        $this->status = 'cancelled';
        
        if ($reason) {
            $this->notes = $this->notes ? $this->notes . "\n\nCancellation reason: " . $reason : "Cancellation reason: " . $reason;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Start a meeting.
     */
    public function start()
    {
        $this->status = 'ongoing';
        $this->save();
        
        return $this;
    }

    /**
     * Scope a query to only include meetings for a specific broker.
     */
    public function scopeForBroker($query, $brokerId)
    {
        return $query->where('broker_id', $brokerId);
    }

    /**
     * Scope a query to only include meetings for a specific client.
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope a query to only include upcoming meetings.
     */
    public function scopeUpcoming($query)
    {
        return $query
            ->where('scheduled_at', '>', now())
            ->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include today's meetings.
     */
    public function scopeToday($query)
    {
        return $query
            ->whereDate('scheduled_at', now()->toDateString())
            ->whereIn('status', ['scheduled', 'ongoing']);
    }

    /**
     * Scope a query to only include this week's meetings.
     */
    public function scopeThisWeek($query)
    {
        return $query
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereIn('status', ['scheduled', 'ongoing']);
    }
} 