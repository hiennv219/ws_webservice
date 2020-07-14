<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 11:38
 */

namespace Katniss\Everdeen\Themes;

use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Utils\AppOptionHelper;

class Extensions extends Plugins
{
    private $statics;

    private $activated;

    private $adminExcepts;

    public function __construct()
    {
        parent::__construct(array_merge(config('katniss.extensions'), homeThemeExtensions()));
    }

    public function init()
    {
        $this->statics = config('katniss.static_extensions');
        $this->activated = array_unique(
            array_merge((array)AppOptionHelper::get('activated_extensions', []), $this->statics())
        );
        $this->adminExcepts = config('katniss.admin_except_extensions');
    }

    public function register()
    {
        $extensions = $this->activated();
        foreach ($extensions as $extension) {
            if (!inAdmin() || !in_array($extension, $this->adminExcepts)) {
                $extension = $this->resolveClass($extension);
                if (!is_null($extension)) {
                    $extension->register();
                }
            }
        }
    }

    public function statics()
    {
        return $this->statics;
    }

    public function isStatic($extension)
    {
        return in_array($extension, $this->statics);
    }

    public function activated()
    {
        return $this->activated;
    }

    public function isActivated($extension)
    {
        return in_array($extension, $this->activated);
    }
}