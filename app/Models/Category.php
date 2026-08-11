<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * A category can have many leads.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(LeadManagement::class);
    }

    /**
     * Scope: only active categories (for select dropdowns on Add Lead form).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }
}