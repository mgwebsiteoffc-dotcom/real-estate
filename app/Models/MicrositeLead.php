<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicrositeLead extends Model
{
    protected $guarded = [];

    public function microsite()
    {
        return $this->belongsTo(Microsite::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
