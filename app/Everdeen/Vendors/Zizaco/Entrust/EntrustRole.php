<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-10-30
 * Time: 20:10
 */

namespace Katniss\Everdeen\Vendors\Zizaco\Entrust;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Vendors\Zizaco\Entrust\Traits\EntrustRoleTrait as OverriddenEntrustRoleTrait;
use Zizaco\Entrust\Contracts\EntrustRoleInterface;
use Zizaco\Entrust\Traits\EntrustRoleTrait;

class EntrustRole extends Model implements EntrustRoleInterface
{
    use EntrustRoleTrait, OverriddenEntrustRoleTrait {
        OverriddenEntrustRoleTrait::cachedPermissions insteadof EntrustRoleTrait;
        OverriddenEntrustRoleTrait::save insteadof EntrustRoleTrait;
        OverriddenEntrustRoleTrait::delete insteadof EntrustRoleTrait;
        OverriddenEntrustRoleTrait::restore insteadof EntrustRoleTrait;
        OverriddenEntrustRoleTrait::hasPermission insteadof EntrustRoleTrait;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('entrust.roles_table');
    }

}