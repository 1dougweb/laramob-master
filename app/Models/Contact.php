<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'property_id',
        'is_read',
        'status',
        'notes',
        'assigned_to',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function assignedPerson()
    {
        return $this->belongsTo(Person::class, 'assigned_to');
    }
}
