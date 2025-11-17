<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalSyncLog extends Model
{
    protected $guarded = [];

    public function portalPropertySync()
    {
        return $this->belongsTo(PortalPropertySync::class);
    }
}
