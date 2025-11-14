<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'max_users',
        'max_projects',
        'max_leads',
        'max_properties',
        'features',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Get features as array
    public function getFeaturesArrayAttribute()
    {
        if (empty($this->features)) {
            return [];
        }
        
        $decoded = json_decode($this->features, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return [];
        }
        
        return $decoded;
    }

    // Relationships
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
