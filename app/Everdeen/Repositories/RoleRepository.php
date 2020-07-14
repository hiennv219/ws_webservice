<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Utils\AppConfig;

class RoleRepository extends ModelRepository
{
    public function getById($id)
    {
        return Role::findOrFail($id);
    }

    public function getPaged()
    {
        return Role::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Role::all();
    }

    public function getByHavingStatuses(array $statuses)
    {
        return Role::haveStatuses($statuses)->get();
    }

    public function getByName($name)
    {
        return Role::where('name', $name)->firstOrFail();
    }

    public function getByNames(array $names)
    {
        return Role::whereIn('name', $names)->get();
    }
}