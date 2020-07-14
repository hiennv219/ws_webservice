<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-16
 * Time: 17:17
 */

namespace Katniss\Everdeen\Themes;


abstract class Plugins
{
    protected $defines;

    public function __construct(array $config = [])
    {
        $this->resolveDefines($config);
    }

    protected function resolveDefines($config, $prefix = '')
    {
        if (!empty($prefix)) {
            $prefix .= '.';
        }
        foreach ($config as $name => $class) {
            if (is_array($class)) {
                $this->resolveDefines($class, $name);
            } else {
                $this->defines[$prefix . $name] = $class;
            }
        }
    }

    public function all()
    {
        return $this->defines;
    }

    public function resolveClass($plugin, $data = null)
    {
        $class = empty($this->defines[$plugin]) ? null : $this->defines[$plugin];
        if (empty($class) || !class_exists($class)) {
            return null;
        }
        return empty($data) ? new $class() : new $class($data);
    }

    public abstract function init();

    public abstract function register();
}