<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 11:40
 */

namespace Katniss\Everdeen\Themes;

use Illuminate\Support\Facades\Facade;


class ExtensionsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'extensions';
    }
}