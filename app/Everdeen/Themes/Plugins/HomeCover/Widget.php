<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\HomeCover;

use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Everdeen\Utils\AppConfig;

class Widget extends DefaultWidget
{
    const NAME = 'home_cover';
    const DISPLAY_NAME = 'Home Cover';

    public $content;
    public $link;
    public $videoUrl;
    public $image;

    public function __init()
    {
        parent::__init();

        $this->content = $this->getProperty('content');
        $this->link = $this->getProperty('link');
        $this->videoUrl = defPr($this->getProperty('video_url'), '');
        $this->image = defPr($this->getProperty('image'), '');
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'image' => $this->image,
            'video_url' => $this->videoUrl,
            'extended_localizing_path' => ThemeFacade::commonPluginPath($this::NAME, 'admin_localizing')
        ]);
    }

    public function register()
    {
        enqueueThemeHeader(
            '
<style>
    #home-cover .cover-text{float:left}
    #home-cover .cover-media{float:right}
    #home-cover .cover-media .cover-media-play-table{position:absolute;display:table;width:100%;height:100%}
    #home-cover .cover-media .cover-media-play-cell{display:table-cell;vertical-align:middle;text-align:center}
    @media (max-width: 991px) {
        #home-cover .cover-text{width:100%!important;padding-right:0;margin-top:10px}
        #home-cover .cover-media{width:100%!important;}
    }
</style>',
            'widget_home_cover'
        );
        enqueueThemeFooter('
<script>
    $(function() {
        $(\'#home-cover .cover-media .cover-media-video\').fancybox({
            openEffect  : \'none\',
            closeEffect : \'none\',
            helpers: {
                overlay: {
                    locked: false
                },
                media : {}
            }
        });
    });
</script>', 'widget_home_cover');
    }

    public function viewHomeParams()
    {
        $hasImage = !empty($this->image);
        $hasVideo = !empty($this->videoUrl);
        $videoUrl = $this->videoUrl;
        if ($hasVideo) {
            $videoUrl = parseEmbedVideoUrl($videoUrl);
            if (empty($videoUrl)) $hasVideo = false;
        }
        $hasMedia = $hasImage && $hasVideo;
        $noMedia = !$hasImage && !$hasVideo;
        $hasText = !empty($this->content) || !empty($this->link);

        return array_merge(parent::viewHomeParams(), [
            'image' => $this->image,
            'video_url' => $videoUrl,
            'content' => $this->content,
            'link' => $this->link,
            'has_image' => $hasImage,
            'has_video' => $hasVideo,
            'has_media' => $hasMedia,
            'no_media' => $noMedia,
            'has_text' => $hasText,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'image',
            'video_url',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::fields(), [
            'image' => 'sometimes|nullable|url',
            'video_url' => 'sometimes|nullable|url',
        ]);
    }

    public function localizedFields()
    {
        return array_merge(parent::localizedFields(), [
            'content',
            'link',
        ]);
    }

    public function localizedValidationRules()
    {
        return array_merge(parent::localizedFields(), [
            'link' => 'sometimes|nullable|url',
        ]);
    }
}