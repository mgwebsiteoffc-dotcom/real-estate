<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'created_by',
        'name',
        'project_code',
        'builder_name',
        'type',
        'status',
        'address',
        'locality',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'total_units',
        'available_units',
        'total_towers',
        'total_floors',
        'total_area_acres',
        'price_min',
        'price_max',
        'launch_date',
        'possession_date',
        'amenities',
        'specifications',
        'description',
        'highlights',
        'featured_image',
        'brochure_pdf',
        'rera_number',
        'approvals',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
        'total_area_acres' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'amenities' => 'array',
        'specifications' => 'array',
        'approvals' => 'array',
        'is_featured' => 'boolean',
        'launch_date' => 'date',
        'possession_date' => 'date',
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

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    // Scopes
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        $colors = [
            'upcoming' => 'blue',
            'under_construction' => 'yellow',
            'ready_to_move' => 'green',
            'completed' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getFormattedPriceRangeAttribute()
    {
        if (!$this->price_min || !$this->price_max) {
            return 'Contact for Price';
        }

        $minPrice = $this->formatPrice($this->price_min);
        $maxPrice = $this->formatPrice($this->price_max);

        return $minPrice . ' - ' . $maxPrice;
    }

    private function formatPrice($price)
    {
        if ($price >= 10000000) {
            return '₹' . number_format($price / 10000000, 2) . ' Cr';
        } elseif ($price >= 100000) {
            return '₹' . number_format($price / 100000, 2) . ' Lac';
        } else {
            return '₹' . number_format($price, 0);
        }
    }
    public function leads()
{
    return $this->hasMany(\App\Models\Lead::class);
}
}
