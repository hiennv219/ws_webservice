<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 18:47
 */

namespace Katniss\Everdeen\Themes\Plugins\OpenGraphTags;

use Katniss\Everdeen\Utils\HtmlTag\Html5;

class OgImage
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
        $output = PHP_EOL . Html5::metaProperty('og:image', $this->url);
        if (!empty($this->secureUrl)) {
            $output .= PHP_EOL . Html5::metaProperty('og:image:secure_url', $this->secureUrl);
        }
        if (!empty($this->type)) {
            $output .= PHP_EOL . Html5::metaProperty('og:image:type', $this->type);
        }
        if (!empty($this->width)) {
            $output .= PHP_EOL . Html5::metaProperty('og:image:width', $this->width);
        }
        if (!empty($this->height)) {
            $output .= PHP_EOL . Html5::metaProperty('og:image:height', $this->height);
        }
        return $output;
    }
}