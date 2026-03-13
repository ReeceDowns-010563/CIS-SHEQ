<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get all incident reports of this type.
     */
    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class);
    }

    /**
     * Scope to get only active incident types.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
