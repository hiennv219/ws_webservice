<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 20:44
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support;

use Illuminate\Support\Str as BaseStr;

class Str extends BaseStr
{
    public static function format($value)
    {
        $args = func_get_args();
        return preg_replace_callback('/\{(\d+)\}/',
            function ($match) use ($args) {
                // might want to add more error handling here...
                return $args[$match[1] + 1];
            },
            $value
        );
    }
}