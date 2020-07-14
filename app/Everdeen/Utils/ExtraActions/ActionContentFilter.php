<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 09:50
 */

namespace Katniss\Everdeen\Utils\ExtraActions;

class ActionContentFilter
{
    private static $filters = [];

    private static function check($id)
    {
        if (!isset(self::$filters[$id])) {
            self::$filters[$id] = [];
        }
    }

    public static function add($id, CallableObject $callableFilter, $name, $strict = true)
    {
        if (empty($id) || empty($name)) return false;

        self::check($id);

        if ($strict && self::has($id, $name)) {
            return false;
        }

        self::$filters[$id][$name] = $callableFilter;
        return true;
    }

    public static function has($id, $name)
    {
        return isset(self::$filters[$id][$name]);
    }

    public static function remove($id, $name)
    {
        if (self::has($id, $name)) {
            unset(self::$filters[$id][$name]);
            return true;
        }
        return false;
    }

    /**
     * @param string $id
     * @param string|mixed $content
     * @return mixed
     */
    public static function flush($id, $content, array $params = [])
    {
        self::check($id);

        $callableFilters = self::$filters[$id];
        foreach ($callableFilters as $callableFilter) {
            $callableFilter->unShiftParam($content);
            $callableFilter->pushParams($params);
            $content = $callableFilter->execute();
        }

        return $content;
    }
}