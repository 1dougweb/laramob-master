<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'code',
        'property_type_id',
        'description',
        'city_id',
        'district_id',
        'address',
        'area',
        'built_area',
        'bedrooms',
        'bathrooms',
        'suites',
        'parking',
        'features',
        'purpose',
        'price',
        'rental_price',
        'condominium_fee',
        'iptu',
        'status',
        'featured_image',
        'is_featured',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'features' => 'json',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title . '-' . $property->code);
            }
        });

        static::updating(function ($property) {
            if (empty($property->slug) || $property->isDirty('title')) {
                $property->slug = Str::slug($property->title . '-' . $property->code);
            }
        });
    }

    /**
     * Get the property type that owns the property.
     */
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Get the city that owns the property.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the district that owns the property.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the gallery images for the property.
     */
    public function gallery()
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * Get the owner of the property.
     */
    public function owner()
    {
        return $this->belongsTo(Person::class, 'owner_id');
    }

    /**
     * Get the contracts for the property.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price) {
            return 'R$ ' . number_format($this->price, 2, ',', '.');
        }
        
        return null;
    }

    /**
     * Get the formatted rental price.
     */
    public function getFormattedRentalPriceAttribute()
    {
        if ($this->rental_price) {
            return 'R$ ' . number_format($this->rental_price, 2, ',', '.');
        }
        
        return null;
    }

    /**
     * Get the formatted condominium fee.
     */
    public function getFormattedCondominiumFeeAttribute()
    {
        if ($this->condominium_fee) {
            return 'R$ ' . number_format($this->condominium_fee, 2, ',', '.');
        }
        
        return null;
    }

    /**
     * Get the formatted IPTU.
     */
    public function getFormattedIptuAttribute()
    {
        if ($this->iptu) {
            return 'R$ ' . number_format($this->iptu, 2, ',', '.');
        }
        
        return null;
    }

    /**
     * Get featured image URL.
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        
        return asset('images/property-placeholder.jpg');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->address_number}" .
            ($this->complement ? ", {$this->complement}" : '') .
            ", {$this->district->name}, {$this->city->name}, {$this->city->state}";
    }
}
