<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadManagement extends Model
{
    use HasFactory;
  
   protected $table = 'leads';
    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'designation',
        'remark',
        'category_id',
        'status',
        'assigned_to',
        'follow_up_date',
        'created_by',
    ];

    protected $casts = [
        'status'          => LeadStatus::class,
        'follow_up_date'  => 'date',
    ];

    /*
    |--------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Single owner model — one lead has at most one assigned user.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Audit: who originally created the lead.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------
    | Scopes — mirror the SRS Permission Matrix (section 3) & Views (section 9)
    |--------------------------------------------------------------------
    */

    /**
     * "My Leads" view (section 9.2): leads the given user created OR is assigned to.
     * Used for the User role (who cannot view all leads) and Supervisor's own view.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if (in_array($user->role, ['admin', 'supervisor'], true)) {
            return $query; // "All Leads" view — no restriction
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('created_by', $user->id)
              ->orWhere('assigned_to', $user->id);
        });
    }

    /**
     * Whether a given user is allowed to edit this lead (section 3 note):
     * Admin/Supervisor: any lead. User: only leads they created or are assigned to.
     */
    public function editableBy(User $user): bool
    {
        if (in_array($user->role, ['admin', 'supervisor'], true)) {
            return true;
        }

        return $this->created_by === $user->id || $this->assigned_to === $user->id;
    }

    /**
     * Only Admin/Supervisor may delete (section 3).
     */
    public function deletableBy(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor'], true);
    }

    /**
     * Only Admin/Supervisor may (re)assign a lead (section 8).
     */
    public function assignableBy(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor'], true);
    }

    /*
    |--------------------------------------------------------------------
    | Filter scopes for "All Leads" screen (section 9.1)
    |--------------------------------------------------------------------
    */

    public function scopeOfStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeOfCategory(Builder $query, ?int $categoryId): Builder
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function scopeAssignedToUser(Builder $query, ?int $userId): Builder
    {
        return $userId ? $query->where('assigned_to', $userId) : $query;
    }
}