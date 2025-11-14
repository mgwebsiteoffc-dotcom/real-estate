<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'created_by',
        'title',
        'property_code',
        'type',
        'listing_type',
        'status',
        'price',
        'price_per_sqft',
        'price_negotiable',
        'address',
        'locality',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'balconies',
        'area_sqft',
        'carpet_area',
        'total_floors',
        'floor_number',
        'furnishing',
        'facing',
        'parking_spaces',
        'age_of_property',
        'amenities',
        'description',
        'highlights',
        'featured_image',
        'is_featured',
        'is_verified',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_sqft' => 'decimal:2',
        'area_sqft' => 'decimal:2',
        'carpet_area' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'amenities' => 'array',
        'price_negotiable' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    // Scopes
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        if ($this->price >= 10000000) {
            return '₹' . number_format($this->price / 10000000, 2) . ' Cr';
        } elseif ($this->price >= 100000) {
            return '₹' . number_format($this->price / 100000, 2) . ' Lac';
        } else {
            return '₹' . number_format($this->price, 0);
        }
    }

    public function getPropertyTypeLabel()
    {
        $labels = [
            'apartment' => 'Apartment',
            'villa' => 'Villa',
            'plot' => 'Plot/Land',
            'commercial' => 'Commercial',
            'office' => 'Office Space',
            'warehouse' => 'Warehouse',
            'shop' => 'Shop/Showroom',
        ];

        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'available' => 'green',
            'sold' => 'red',
            'rented' => 'blue',
            'reserved' => 'yellow',
            'under_construction' => 'orange',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function project()
{
    return $this->belongsTo(Project::class);
}

public function tasks()
{
    return $this->morphMany(Task::class, 'taskable');
}

public function activities()
{
    return $this->morphMany(Activity::class, 'activityable');
}
}
