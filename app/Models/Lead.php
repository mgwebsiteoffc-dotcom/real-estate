<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'assigned_to',
        'created_by',
        'name',
        'email',
        'phone',
        'phone_secondary',
        'address',
        'city',
        'state',
        'country',
        'status',
        'source',
        'priority',
        'budget_min',
        'budget_max',
        'property_type',
        'preferred_location',
        'requirements',
        'notes',
        'contacted_at',
        'follow_up_date',
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'contacted_at' => 'datetime',
        'follow_up_date' => 'datetime',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal Sent',
            'negotiation' => 'Negotiation',
            'won' => 'Won',
            'lost' => 'Lost',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'new' => 'blue',
            'contacted' => 'yellow',
            'qualified' => 'purple',
            'proposal' => 'indigo',
            'negotiation' => 'orange',
            'won' => 'green',
            'lost' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }
    // Tasks & Activities
public function tasks()
{
    return $this->morphMany(Task::class, 'taskable');
}

public function activities()
{
    return $this->morphMany(Activity::class, 'activityable');
}
public function project()
{
    return $this->belongsTo(Project::class);
}
}
