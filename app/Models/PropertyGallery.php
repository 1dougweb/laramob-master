<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'image',
        'title',
        'alt',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
