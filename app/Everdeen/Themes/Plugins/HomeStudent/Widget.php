<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\HomeStudent;

use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Everdeen\Utils\AppConfig;

class Widget extends DefaultWidget
{
    const NAME = 'home_student';
    const DISPLAY_NAME = 'Home Student';

    public $videoUrl;
    public $picture1;
    public $review1;
    public $picture2;
    public $review2;
    public $picture3;
    public $review3;

    public function __init()
    {
        parent::__init();

        $this->videoUrl = defPr($this->getProperty('video_url'), '');
        $this->picture1 = defPr($this->getProperty('picture_1'), '');
        $this->review1 = defPr($this->getProperty('review_1'), '');
        $this->picture2 = defPr($this->getProperty('picture_2'), '');
        $this->review2 = defPr($this->getProperty('review_2'), '');
        $this->picture3 = defPr($this->getProperty('picture_3'), '');
        $this->review3 = defPr($this->getProperty('review_3'), '');
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'video_url' => $this->videoUrl,
            'picture_1' => $this->picture1,
            'picture_2' => $this->picture2,
            'picture_3' => $this->picture3,
        ]);
    }

    public function register()
    {
        enqueueThemeHeader(
            '
<style>
#home-student .student-review-list{padding-left:20px;padding-right:20px}
#home-student .student-review-list .student-review-item{position: relative;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;padding: 5px 10px;margin-bottom: 16px}
#home-student .student-review-list .student-review-item:last-child{margin-bottom:0}
#home-student .student-review-list .student-review-item > img{position: absolute;top:-13px}
#home-student .student-review-list .student-review-item.left{padding-left:35px}
#home-student .student-review-list .student-review-item.left > img{left:-30px}
#home-student .student-review-list .student-review-item.right{padding-right:35px}
#home-student .student-review-list .student-review-item.right > img{right:-30px}
@media (max-width: 767px) {
#home-student .embed-responsive{margin-bottom:16px}
}
</style>',
            'widget_home_student'
        );
    }

    public function viewHomeParams()
    {
        $hasVideo = !empty($this->videoUrl);
        $videoUrl = $this->videoUrl;
        if ($hasVideo) {
            $videoUrl = parseEmbedVideoUrl($videoUrl);
            if (empty($videoUrl)) $hasVideo = false;
        }

        $reviews = [];
        if (!empty($this->picture1) && !empty($this->review1)) {
            $reviews[] = [
                'picture' => $this->picture1,
                'review' => $this->review1,
            ];
        }
        if (!empty($this->picture2) && !empty($this->review2)) {
            $reviews[] = [
                'picture' => $this->picture2,
                'review' => $this->review2,
            ];
        }
        if (!empty($this->picture3) && !empty($this->review3)) {
            $reviews[] = [
                'picture' => $this->picture3,
                'review' => $this->review3,
            ];
        }
        $hasReviews = count($reviews) > 0;

        return array_merge(parent::viewHomeParams(), [
            'video_url' => $videoUrl,
            'has_video' => $hasVideo,
            'reviews' => $reviews,
            'has_reviews' => $hasReviews,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'video_url',
            'picture_1',
            'picture_2',
            'picture_3',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::fields(), [
            'video_url' => 'sometimes|nullable|url',
            'picture_1' => 'sometimes|nullable|url',
            'picture_2' => 'sometimes|nullable|url',
            'picture_3' => 'sometimes|nullable|url',
        ]);
    }

    public function localizedFields()
    {
        return array_merge(parent::localizedFields(), [
            'review_1',
            'review_2',
            'review_3',
        ]);
    }
}