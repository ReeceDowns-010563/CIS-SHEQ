<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_email',
        'contact_phone',
        'address',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get all incident reports associated with this agency.
     */
    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class);
    }

    /**
     * Scope to get only active agencies.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
