<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadScore extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'score_breakdown' => 'array',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
