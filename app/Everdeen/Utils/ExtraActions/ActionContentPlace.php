<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 10:24
 */

namespace Katniss\Everdeen\Utils\ExtraActions;

class ActionContentPlace
{
    private static $places = [];

    private static function check($id)
    {
        if (!isset(self::$places[$id])) {
            self::$places[$id] = [];
        }
    }

    public static function add($id, CallableObject $callablePlace, $name, $strict = true)
    {
        if (empty($id) || empty($name)) return false;

        self::check($id);

        if ($strict && self::has($id, $name)) {
            return false;
        }

        self::$places[$id][$name] = $callablePlace;
        return true;
    }

    public static function has($id, $name)
    {
        return isset(self::$places[$id][$name]);
    }

    public static function remove($id, $name)
    {
        if (self::has($id, $name)) {
            unset(self::$places[$id][$name]);
            return true;
        }
        return false;
    }

    public static function flush($id, array $params = [])
    {
        $place = '';
        if (!empty(self::$places[$id])) {
            $callablePlaces = self::$places[$id];
            foreach ($callablePlaces as $callablePlace) {
                $callablePlace->unShiftParams($params);
                $place .= $callablePlace->execute();
            }
        }
        return $place;
    }
}