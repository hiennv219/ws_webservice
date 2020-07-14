<?php

namespace Katniss\Everdeen\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = [
        'name', 'display_name', 'description',
    ];

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(config('entrust.role'), config('entrust.permission_role_table'), 'permission_id', 'role_id');
    }
}
