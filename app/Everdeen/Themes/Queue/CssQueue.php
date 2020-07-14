<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-15
 * Time: 00:01
 */

namespace Katniss\Everdeen\Themes\Queue;


use Katniss\Everdeen\Utils\HtmlTag\Html5;

class CssQueue extends AssetQueue
{
    const LIB_OPEN_SANS_NAME = 'open-sans-css';
    const LIB_SOURCE_SANS_PRO_NAME = 'source-sans-pro-css';
    const LIB_BOOTSTRAP_NAME = 'bootstrap-css';
    const LIB_FONT_AWESOME_NAME = 'font-awesome-css';

    const TYPE_RAW = 'raw';
    const TYPE_VAR = 'var';

    public function __construct()
    {
        parent::__construct();
    }

    public function flush($echo = true)
    {
        $this->output = [];

        $this->output = [];
        foreach ($this->queue as $name => $item) {
            switch ($item['type']) {
                case self::TYPE_RAW:
                    $this->output[] = Html5::cssInline($item['content']);
                    break;
                case self::TYPE_VAR:
                    $content = [];
                    foreach ($item['content'] as $css) {
                        $content[] = $this->getNames($css['names']) . '{' . $this->getProperties($css['properties']) . '}';
                    }
                    $this->output[] = Html5::cssInline(implode(PHP_EOL, $content));
                    break;
                default:
                    $this->output[] = Html5::css($item['content']);
                    break;
            }
        }

        return parent::flush($echo);
    }

    private function getNames($names)
    {
        return implode(',', $names);
    }

    private function getProperties($properties)
    {
        $p = [];
        foreach ($properties as $name => $value) {
            $p[] = $name . ':' . (is_string($value) ? "'" . $value . "'" : $value) . '';
        }
        return implode(';', $p);
    }
}