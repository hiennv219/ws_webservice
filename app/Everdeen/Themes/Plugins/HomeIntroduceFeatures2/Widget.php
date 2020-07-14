<?php
namespace Katniss\Everdeen\Themes\Plugins\HomeIntroduceFeatures2;

use Katniss\Everdeen\Themes\Plugins\BaseLinks\Widget as BaseLinks;

class Widget extends BaseLinks
{
    const NAME = 'home_introduce_features_2';
    const DISPLAY_NAME = 'Home Introduce Features 2';

    public function register()
    {
        enqueueThemeHeader('<style>
#home-introduce-features-2 .panel-default {
    background: rgba(255,255,255,0); /* For browsers that do not support gradients */
    background: -webkit-linear-gradient(rgba(255,255,255,1), rgba(245,245,245,1)); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(rgba(255,255,255,1), rgba(245,245,245,1)); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(rgba(255,255,255,1), rgba(245,245,245,1)); /* For Firefox 3.6 to 15 */
    background: linear-gradient(rgba(255,255,255,1), rgba(245,245,245,1)); /* Standard syntax */
}
</style>', 'widget_home_introduce_features_3');
    }
}
