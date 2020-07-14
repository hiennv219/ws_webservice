<?php

namespace Katniss\Everdeen\Models;

use Katniss\Everdeen\Vendors\Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    const STATUS_HIDDEN = 0;
    const STATUS_NORMAL = 1;

    protected $fillable = [
        'name', 'display_name', 'public', 'description', 'status',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'roles_users', 'role_id', 'user_id');
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(config('entrust.permission'), config('entrust.permission_role_table'), 'role_id', 'permission_id');
    }

    public function scopeHaveStatuses($query, array $statuses)
    {
        if (empty($statuses)) {
            return $query;
        }

        return $query->whereIn('status', $statuses);
    }
}
