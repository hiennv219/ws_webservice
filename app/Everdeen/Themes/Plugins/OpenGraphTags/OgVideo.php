<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 18:47
 */

namespace Katniss\Everdeen\Themes\Plugins\OpenGraphTags;

use Katniss\Everdeen\Utils\HtmlTag\Html5;

class OgVideo
{
    public $url;
    public $secureUrl;
    public $type; // mime type
    public $width;
    public $height;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function render()
    {
        $output = Html5::metaProperty('og:video', $this->url);
        if (!empty($this->secureUrl)) {
            $output .= Html5::metaProperty('og:video:secure_url', $this->secureUrl);
        }
        if (!empty($this->type)) {
            $output .= Html5::metaProperty('og:video:type', $this->type);
        }
        if (!empty($this->width)) {
            $output .= Html5::metaProperty('og:video:width', $this->width);
        }
        if (!empty($this->height)) {
            $output .= Html5::metaProperty('og:video:height', $this->height);
        }
        return $output;
    }
}