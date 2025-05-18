<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'document',
        'document_type',
        'phone',
        'mobile',
        'address',
        'birth_date',
        'type',
        'status',
        'notes',
        'photo',
        'user_id',
        'is_active',
        'commission_rate',
        'marital_status',
        'nationality',
        'profession',
        'bank_name',
        'bank_agency',
        'bank_account',
        'pix_key',
        'broker_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the properties owned by the person.
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    /**
     * Get the contracts for the person.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    /**
     * Get the broker assigned to this person (for clients and tenants).
     */
    public function broker()
    {
        return $this->belongsTo(Person::class, 'broker_id');
    }

    /**
     * Get clients assigned to this broker.
     */
    public function clients()
    {
        return $this->hasMany(Person::class, 'broker_id')
            ->where(function($query) {
                $query->where('type', 'client')
                      ->orWhere('type', 'tenant');
            });
    }

    /**
     * Scope a query to only include owners.
     */
    public function scopeOwners($query)
    {
        return $query->where('type', 'owner')->orWhere('type', 'both');
    }

    /**
     * Scope a query to only include clients.
     */
    public function scopeClients($query)
    {
        return $query->where('type', 'client')->orWhere('type', 'both');
    }

    /**
     * Scope a query to only include active people.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function ownerContracts()
    {
        return $this->hasMany(Contract::class, 'owner_id');
    }

    public function brokerContracts()
    {
        return $this->hasMany(Contract::class, 'broker_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'broker_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'person_id');
    }

    public function assignedContacts()
    {
        return $this->hasMany(Contact::class, 'assigned_to');
    }

    /**
     * Get documents associated with this person.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the properties favorites by this person.
     */
    public function favoriteProperties()
    {
        return $this->belongsToMany(Property::class, 'property_favorites', 'person_id', 'property_id')
            ->withTimestamps();
    }
    
    /**
     * Get tasks related to this person.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'person_id');
    }
    
    /**
     * Get tasks assigned to this person.
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    
    /**
     * Get meetings related to this person.
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'broker_id');
    }
}
