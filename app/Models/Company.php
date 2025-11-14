<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'branding_settings',
        'package_id',
        'status',
    ];

    protected $casts = [
        'branding_settings' => 'array',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}