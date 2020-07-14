<?php
namespace Katniss\Everdeen\Themes\Plugins\HomeLearningSteps;

use Katniss\Everdeen\Themes\Plugins\BaseLinks\Widget as BaseLinks;

class Widget extends BaseLinks
{
    const NAME = 'home_learning_steps';
    const DISPLAY_NAME = 'Home Learning Steps';

    public function register()
    {
        enqueueThemeHeader(
            '
<style>
    #home-learning-steps .box-center span{margin-top:-9px}
    #home-learning-steps .step-image-box{position:relative;z-index:1}
    #home-learning-steps .step-dash hr{position:relative;z-index:0;margin-top:-32px;border-style: dashed}
    #home-learning-steps .step-dash hr.pull-left{margin-left:-5px}
    #home-learning-steps .step-dash hr.pull-right{margin-right:-5px}
    #home-learning-steps .step-col-first .step-dash hr.pull-left{display: none}
    #home-learning-steps .step-col-last .step-dash hr.pull-right{display: none}
    @media (max-width: 991px) {
        #home-learning-steps .step-col-first .step-dash hr.pull-left{display: inherit}
        #home-learning-steps .step-col-last .step-dash hr.pull-right{display: inherit}
        #home-learning-steps .step-dash hr.pull-left{margin-left:0}
        #home-learning-steps .step-dash hr.pull-right{margin-right:0}
    }
</style>',
            'widget_home_learning_steps'
        );
    }
}
