<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 21:02
 */

namespace Katniss\Everdeen\Themes\HomeThemes\WowSkype\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class FooterLowerComposer
{
    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $homeTheme = homeTheme();
        $currentLocale = currentLocaleCode();
        $view->with('home_name', $homeTheme->options('home_name', '', $currentLocale, true));

        $social = $homeTheme->options('social_facebook', '');
        if(!empty($social) && $homeTheme->options('social_facebook_sb') == 1) {
            $view->with('social_facebook', $social);
        }
        $social = $homeTheme->options('social_twitter', '');
        if(!empty($social) && $homeTheme->options('social_twitter_sb') == 1) {
            $view->with('social_twitter', $social);
        }
        $social = $homeTheme->options('social_instagram', '');
        if(!empty($social) && $homeTheme->options('social_instagram_sb') == 1) {
            $view->with('social_instagram', $social);
        }
        $social = $homeTheme->options('social_gplus', '');
        if(!empty($social) && $homeTheme->options('social_gplus_sb') == 1) {
            $view->with('social_gplus', $social);
        }
        $social = $homeTheme->options('social_youtube', '');
        if(!empty($social) && $homeTheme->options('social_youtube_sb') == 1) {
            $view->with('social_youtube', $social);
        }
        $social = $homeTheme->options('social_skype', '');
        if(!empty($social) && $homeTheme->options('social_skype_sb') == 1) {
            $view->with('social_skype', $social);
        }
    }
}