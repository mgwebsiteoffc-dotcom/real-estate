<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalConfig extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function propertySyncs()
    {
        return $this->hasMany(PortalPropertySync::class);
    }
}
