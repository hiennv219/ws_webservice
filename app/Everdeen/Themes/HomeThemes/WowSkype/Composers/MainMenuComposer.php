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

class MainMenuComposer
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
        $view->with('main_menu', $this->getMenuRender($this->getMenu()));
    }

    protected function getMenuRender(Menu $menu)
    {
        $menuRender = new MenuRender();
        $menuRender->wrapClass = 'nav navbar-nav';
        return new HtmlString($menuRender->render($menu));
    }

    protected function getMenu()
    {
        $currentUrl = currentUrl();
        $menu = new Menu($currentUrl);
        $menu->add( // add a menu item
            homeUrl(),
            trans('pages.home_title'), '', '', 'hidden'
        );
        $menu->add(
            homeUrl('teachers'),
            trans_choice('label.teacher', 2)
        );
        $menu->add(
            homeUrl('knowledge'),
            trans('pages.home_knowledge_title')
        );

        $hasSubMenu = false;
        $subMenu = new Menu();
        $homeTheme = homeTheme();
        $social = $homeTheme->options('social_facebook', '');
        if (!empty($social) && $homeTheme->options('social_facebook_sw') == 1) {
            $subMenu->add(
                $social,
                '<i class="fa fa-facebook-square font-20 text-middle"></i> &nbsp; ' . trans('label.us_connect_facebook')
            );
            $subMenu->setTargetBlank();
            $hasSubMenu = true;
        }
        $social = $homeTheme->options('social_twitter', '');
        if (!empty($social) && $homeTheme->options('social_twitter_sw') == 1) {
            $subMenu->add(
                $social,
                '<i class="fa fa-twitter-square font-20 text-middle"></i> &nbsp; ' . trans('label.us_follow_twitter')
            );
            $subMenu->setTargetBlank();
            $hasSubMenu = true;
        }
        $social = $homeTheme->options('social_instagram', '');
        if (!empty($social) && $homeTheme->options('social_instagram_sw') == 1) {
            $subMenu->add(
                $social,
                '<i class="fa fa-instagram font-20 text-middle"></i> &nbsp; ' . trans('label.us_follow_instagram')
            );
            $subMenu->setTargetBlank();
            $hasSubMenu = true;
        }
        $social = $homeTheme->options('social_gplus', '');
        if (!empty($social) && $homeTheme->options('social_gplus_sw') == 1) {
            $subMenu->add(
                $social,
                '<i class="fa fa-google-plus-square font-20 text-middle"></i> &nbsp; ' . trans('label.us_connect_gplus')
            );
            $subMenu->setTargetBlank();
            $hasSubMenu = true;
        }
        $social = $homeTheme->options('social_youtube', '');
        if (!empty($social) && $homeTheme->options('social_youtube_sw') == 1) {
            $subMenu->add(
                $social,
                '<i class="fa fa-youtube-square font-20 text-middle"></i> &nbsp; ' . trans('label.us_watch_youtube')
            );
            $subMenu->setTargetBlank();
            $hasSubMenu = true;
        }
        $social = $homeTheme->options('social_skype', '');
        if (!empty($social) && $homeTheme->options('social_skype_sw') == 1) {
            $subMenu->add(
                $social,
                '<i class="fa fa-skype font-20 text-middle"></i> &nbsp; ' . trans('label.us_talk_skype')
            );
            $subMenu->setTargetBlank();
            $hasSubMenu = true;
        }
        if ($hasSubMenu) {
            $menu->add(
                '#',
                trans('wow_skype_theme.world')
            );
            $menu->addSubMenu($subMenu);
        }

        $menu->add(
            homeUrl('helps'),
            trans('label.about_us')
        );
        $menu = contentFilter('main_menu', $menu);
        return $menu;
    }
}