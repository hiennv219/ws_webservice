<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 17:57
 */

namespace Katniss\Everdeen\Utils;


use Illuminate\Support\Facades\Storage;

class AssetHelper
{
    private static function generateJsFileName($name)
    {
        return $name . '.js';
    }

    public static function jsUrl($name)
    {
        return asset('assets/cache/js/' . self::generateJsFileName($name));
    }

    public static function cacheJs($name, $content)
    {
        Storage::disk('cache_assets')->put('js/' . self::generateJsFileName($name), $content);
    }
}