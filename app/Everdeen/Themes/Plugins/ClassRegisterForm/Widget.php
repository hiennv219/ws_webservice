<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-06
 * Time: 15:37
 */

namespace Katniss\Everdeen\Themes\Plugins\ClassRegisterForm;

use Katniss\Everdeen\Repositories\StudyCourseRepository;
use Katniss\Everdeen\Repositories\StudyLevelRepository;
use Katniss\Everdeen\Repositories\StudyProblemRepository;
use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'class_register_form';
    const DISPLAY_NAME = 'Class Register Form';

    public function __init()
    {
        parent::__init();
    }

    public function register()
    {
        enqueueThemeHeader('<style>
.widget-register-class-form {
background: #999 url("' . ThemeFacade::imageAsset('bg_home_register.jpg') . '") center no-repeat;
background-size: cover;
}
</style>', 'widget_class_register_form');
    }

    public function viewHomeParams()
    {
        $studyLevelRepository = new StudyLevelRepository();
        $studyProblemRepository = new StudyProblemRepository();
        $studyCourseRepository = new StudyCourseRepository();

        return array_merge(parent::viewHomeParams(), [
            'study_levels' => $studyLevelRepository->getAll(),
            'study_problems' => $studyProblemRepository->getAll(),
            'study_courses' => $studyCourseRepository->getAll(),
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }
}