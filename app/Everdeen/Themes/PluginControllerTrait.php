<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-16
 * Time: 18:03
 */

namespace Katniss\Everdeen\Themes;

use Katniss\Everdeen\Themes\ThemeFacade;

trait PluginControllerTrait
{
    public function _extra($name, $plugin, $themeOnly = false)
    {
        return !$themeOnly ?
            ThemeFacade::commonPluginPath($plugin, 'extra.' . $name)
            : ThemeFacade::pluginPath($plugin, 'extra.' . $name);
    }
}