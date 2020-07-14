<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-10-30
 * Time: 19:35
 */

namespace Katniss\Everdeen\Vendors\Zizaco\Entrust\Traits;


trait EntrustRoleTrait
{
    // Remove caching functionality.
    public function cachedPermissions()
    {
        return false;
    }

    public function save(array $options = [])
    {
        if (!parent::save($options)) {
            return false;
        }
        return true;
    }

    public function delete(array $options = [])
    {
        if (!parent::delete($options)) {
            return false;
        }
        return true;
    }

    public function restore()
    {
        if (!parent::restore()) {
            return false;
        }
        return true;
    }

    public function hasPermission($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);

                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the permissions were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the permissions were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->perms as $permission) {
                if ($permission->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }
}