<?php
/**
 * Created by PhpStorm.
 * User: daiduong47
 * Date: 14/01/2017
 * Time: 22:07 PM
 */

namespace Katniss\Everdeen\Themes\Plugins\HomeAds;

use Katniss\Everdeen\Themes\Extension as BaseExtension;

class Extension extends BaseExtension
{
    const NAME = 'home_ads';
    const DISPLAY_NAME = 'Home Ads';
    const DESCRIPTION = 'Display ads on homepage';

    public $url;

    public function register()
    {
        if (isMobileClient() || empty($this->url) || currentUrl() != homeUrl()) return;

        $videoUrl = parseEmbedVideoUrl($this->url);
        enqueueThemeHeader('<style>
.home-ads{position:fixed;top:0;left:0;width:100%;height:100%;display:table;background-color:rgba(0,0,0,.5);z-index: 9999;}
.home-ads .inner{width:100%;height:100%;display:table-cell;text-align:center;vertical-align:middle;}
.home-ads .inner .object img{max-width:80%;max-height:80%}
.home-ads .inner .object .video{max-width:80%;max-height:80%;margin:0 auto}
.home-ads .ads-close{position:absolute;top:15px;right:20px}
@media (min-width: 992px) {
    .home-ads .inner .object img{max-width:800px;max-height:600px}
    .home-ads .inner .object .video{max-width:800px;max-height:600px;margin:0 auto}
}
</style>', 'ext:home_ads');
        $block = '<div class="home-ads" style="display: none"><div class="inner"><a href="#" class="ads-close"><i class="fa fa-close color-white font-24"></i></a><div class="object">{inner}</div></div></div>
<script>
$(function() {
    $(\'.home-ads\').fadeIn(400);
    $(\'.ads-close\').on(\'click\', function(e) {
        e.preventDefault();
        $(this).closest(\'.home-ads\').fadeOut(400);
    });
});
</script>';
        if (!empty($videoUrl)) {
            enqueueThemeFooter(str_replace('{inner}', '<div class="video"><div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="' . $videoUrl . '"></iframe>
                    </div></div>', $block), 'ext:home_ads');
        } else {
            enqueueThemeFooter(str_replace('{inner}', '<img src="' . $this->url . '">', $block), 'ext:home_ads');
        }
    }

    protected function __init()
    {
        parent::__init();

        $this->url = defPr($this->getProperty('url'), '');
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'url' => $this->url,
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();
        return array_merge($fields, [
            'url',
        ]);
    }

    public function validationRules()
    {
        $validationRules = parent::validationRules();
        return array_merge($validationRules, [
            'url' => 'sometimes|nullable|url',
        ]);
    }
}
