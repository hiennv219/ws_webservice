<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-10-30
 * Time: 19:26
 */

namespace Katniss\Everdeen\Vendors\Zizaco\Entrust\Traits;


trait EntrustUserTrait
{
    // Remove caching functionality.
    public function cachedRoles()
    {
        return false;
    }

    public function save(array $options = [])
    {
        parent::save($options);
    }

    public function delete(array $options = [])
    {
        parent::delete($options);
    }

    public function restore()
    {
        parent::restore();
    }

    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->roles as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    public function can($permission, $requireAll = false)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);

                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->roles as $role) {
                // Validate against the Permission table
                foreach ($role->perms as $perm) {
                    if ($perm->name == $permission) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}