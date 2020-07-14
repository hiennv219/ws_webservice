<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 18:48
 */

namespace Katniss\Everdeen\Themes\Plugins\OpenGraphTags;

use Katniss\Everdeen\Utils\HtmlTag\Html5;

class OgAudio
{
    public $url;
    public $secureUrl;
    public $type; // mime type

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function render()
    {
        $output = Html5::metaProperty('og:audio', $this->url);
        if (!empty($this->secureUrl)) {
            $output .= Html5::metaProperty('og:audio:secure_url', $this->secureUrl);
        }
        if (!empty($this->type)) {
            $output .= Html5::metaProperty('og:audio:type', $this->type);
        }
        return $output;
    }
}