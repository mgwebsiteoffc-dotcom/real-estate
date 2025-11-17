<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Microsite extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'theme_colors' => 'array',
        'sections' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($microsite) {
            if (empty($microsite->slug)) {
                $microsite->slug = Str::slug($microsite->title) . '-' . Str::random(6);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function capturedLeads()
    {
        return $this->hasMany(MicrositeLead::class);
    }

    public function getUrlAttribute()
    {
        if ($this->custom_domain) {
            return 'https://' . $this->custom_domain;
        }
        return route('microsite.show', $this->slug);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
