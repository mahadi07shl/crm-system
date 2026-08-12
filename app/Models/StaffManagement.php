<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\LeadManagement as Lead;

class StaffManagement extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table= "staff_management";
  

    protected $fillable = [
        'name',
        'position',
        'email',
        'password',
        'role',                       // Admin | Supervisor | User — SRS section 2
        'status',                     // active | inactive
        'phone',
        'profile_picture',
        'gender',                     // male | female | other
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------
    | Relationships (ties back to the Lead module)
    |--------------------------------------------------------------------
    */

    public function createdLeads()
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    public function assignedLeads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    /*
    |--------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeOfRole(Builder $query, ?string $role): Builder
    {
        return $role ? $query->where('role', $role) : $query;
    }

    /*
    |--------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return strtolower($this->role) === 'admin';
    }

    public function isSupervisor(): bool
    {
        return strtolower($this->role) === 'supervisor';
    }

    /**
     * Admin and Supervisor share most elevated permissions in the SRS permission matrix.
     */
    public function isAdminOrSupervisor(): bool
    {
        return in_array(strtolower($this->role), ['admin', 'supervisor'], true);
    }

    /**
     * Full URL to the stored profile picture, or null if none uploaded.
     * Views can fall back to initials when this is null.
     */
    public function avatarUrl(): ?string
    {
        return $this->profile_picture
            ? \Illuminate\Support\Facades\Storage::url($this->profile_picture)
            : null;
    }
}