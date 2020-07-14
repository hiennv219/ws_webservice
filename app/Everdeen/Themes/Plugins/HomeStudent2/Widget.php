<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\HomeStudent2;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'home_student_2';
    const DISPLAY_NAME = 'Home Student 2';

    public $videoUrl1;
    public $videoUrl2;

    public function __init()
    {
        parent::__init();

        $this->videoUrl1 = defPr($this->getProperty('video_url_1'), '');
        $this->videoUrl2 = defPr($this->getProperty('video_url_2'), '');
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'video_url_1' => $this->videoUrl1,
            'video_url_2' => $this->videoUrl2,
        ]);
    }

    public function viewHomeParams()
    {
        $hasVideo1 = !empty($this->videoUrl1);
        $videoUrl1 = $this->videoUrl1;
        if ($hasVideo1) {
            $videoUrl1 = parseEmbedVideoUrl($videoUrl1);
            if (empty($videoUrl1)) $hasVideo1 = false;
        }
        $hasVideo2 = !empty($this->videoUrl2);
        $videoUrl2 = $this->videoUrl2;
        if ($hasVideo2) {
            $videoUrl2 = parseEmbedVideoUrl($videoUrl2);
            if (empty($videoUrl2)) $hasVideo2 = false;
        }

        return array_merge(parent::viewHomeParams(), [
            'has_video_1' => $hasVideo1,
            'video_url_1' => $videoUrl1,
            'has_video_2' => $hasVideo2,
            'video_url_2' => $videoUrl2,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'video_url_1',
            'video_url_2',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::fields(), [
            'video_url_1' => 'sometimes|nullable|url',
            'video_url_2' => 'sometimes|nullable|url',
        ]);
    }
}