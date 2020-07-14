<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 10:30
 */

namespace Katniss\Everdeen\Utils\ExtraActions;

class ActionTrigger
{

    private static $triggers = [];

    private static function check($id)
    {
        if (!isset(self::$triggers[$id])) {
            self::$triggers[$id] = [];
        }
    }

    public static function add($id, CallableObject $callableTrigger, $name, $strict = true)
    {
        if (empty($id) || empty($name)) return false;

        self::check($id);

        if ($strict && self::has($id, $name)) {
            return false;
        }

        self::$triggers[$id][$name] = $callableTrigger;
        return true;
    }

    public static function has($id, $name)
    {
        return isset(self::$triggers[$id]) && isset(self::$triggers[$id][$name]);
    }

    public static function remove($id, $name)
    {
        if (self::has($id, $name)) {
            unset(self::$triggers[$id][$name]);
            return true;
        }
        return false;
    }

    public static function activate($id, $name, array $params = [])
    {
        if (self::has($id, $name)) {
            $callableTrigger = self::$triggers[$id][$name];
            $callableTrigger->unShiftParams($params);
            return $callableTrigger->execute();
        }

        return null;
    }
}