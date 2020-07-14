<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-27
 * Time: 00:33
 */

namespace Katniss\Everdeen\Themes;

use Illuminate\Support\Facades\Facade;

class ThemeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'theme';
    }
}