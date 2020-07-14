<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 21:02
 */

namespace Katniss\Everdeen\Themes\HomeThemes\ExampleTheme\Composers;

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
            homeUrl('example/social-sharing'),
            trans('example_theme.social_sharing')
        );
        $menu->add(
            homeUrl('example/facebook-comments'),
            trans('example_theme.facebook_comment')
        );
        $menu->add(
            homeUrl('example/widgets'),
            trans('example_theme.example_widget')
        );
        $menu->add(
            homeUrl('example/my-settings'),
            trans('pages.my_settings_title')
        );
        $menu->add(
            homeUrl('example/pages'),
            trans_choice('label.page', 2)
        );
        $menu->add(
            homeUrl('example/articles'),
            trans_choice('label.article', 2)
        );
        $menu->add(
            homeUrl('example/public-conversation'),
            trans('example_theme.public_conversation')
        );
        $menu = contentFilter('main_menu', $menu);
        return $menu;
    }
}