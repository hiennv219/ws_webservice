<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 10:30
 */

namespace Katniss\Everdeen\Utils\ExtraActions;

class ActionHook
{

    private static $actions = [];

    private static function check($id)
    {
        if (!isset(self::$actions[$id])) {
            self::$actions[$id] = [];
        }
    }

    public static function add($id, CallableObject $callableAction, $name, $strict = true)
    {
        if (empty($id) || empty($name)) return false;

        self::check($id);

        if ($strict && self::has($id, $name)) {
            return false;
        }

        self::$actions[$id][$name] = $callableAction;
        return true;
    }

    public static function has($id, $name)
    {
        return isset(self::$actions[$id][$name]);
    }

    public static function remove($id, $name)
    {
        if (self::has($id, $name)) {
            unset(self::$actions[$id][$name]);
            return true;
        }
        return false;
    }

    public static function activate($id, array $params = [])
    {
        $return = [];

        if (!empty(self::$actions[$id])) {
            $callableActions = self::$actions[$id];
            foreach ($callableActions as $callableAction) {
                $callableAction->unShiftParams($params);
                $return[] = $callableAction->execute();
            }
        }

        return $return;
    }
}