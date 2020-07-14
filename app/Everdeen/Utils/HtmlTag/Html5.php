<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 00:30
 */

namespace Katniss\Everdeen\Utils\HtmlTag;


class Html5
{
    public static function js($src, $async = false)
    {
        return '<script' . ($async ? ' async' : '') . ' src="' . $src . '"></script>';
    }

    public static function jsInline($content)
    {
        return '<script>
' . $content . '
</script>';
    }

    public static function css($href)
    {
        return '<link rel="stylesheet" href="' . $href . '">';
    }

    public static function cssInline($content)
    {
        return '<style>
' . $content . '
</style>';
    }

    public static function metaProperty($property, $content)
    {
        return '<meta property="' . $property . '" content="' . $content . '">';
    }

    public static function metaName($name, $content)
    {
        return '<meta name="' . $name . '" content="' . $content . '">';
    }

    public static function title($title)
    {
        return '<title>' . $title . '</title>';
    }

    public static function frame($src)
    {
        return '<iframe src="' . $src . '"></iframe>';
    }
}