<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 09:21
 */

namespace Katniss\Everdeen\Themes;

use Illuminate\Support\Facades\Facade;


class WidgetsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'widgets';
    }
}