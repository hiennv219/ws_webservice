<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-11
 * Time: 08:34
 */

namespace Katniss\Everdeen\Utils;

use Illuminate\Support\Facades\Facade;

class SettingsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}