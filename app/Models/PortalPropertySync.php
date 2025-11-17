<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalPropertySync extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'last_synced_at' => 'datetime',
        'field_mapping' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function portalConfig()
    {
        return $this->belongsTo(PortalConfig::class);
    }

    public function logs()
    {
        return $this->hasMany(PortalSyncLog::class);
    }
}
