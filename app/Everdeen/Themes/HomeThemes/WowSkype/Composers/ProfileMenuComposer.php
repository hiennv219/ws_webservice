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

class ProfileMenuComposer
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
        $view->with('profile_menu', $this->getMenuRender($this->getMenu()));
    }

    protected function getMenuRender(Menu $menu)
    {
        $menuRender = new MenuRender();
        $menuRender->wrapId = 'nav-profile';
        $menuRender->wrapClass = 'nav nav-pills nav-stacked margin-bottom-20';
        return new HtmlString($menuRender->render($menu));
    }

    protected function getMenu()
    {
        $authUser = authUser();
        $currentUrl = currentUrl();
        $menu = new Menu($currentUrl);
        $menu->add( // add a menu item
            homeUrl('profile/account-information'),
            trans('label.account_information'), '<strong>', '</strong>'
        );
        $menu->add( // add a menu item
            homeUrl('profile/user-information'),
            trans('label.user_information'), '<strong>', '</strong>'
        );
        $menu->add( // add a menu item
            homeUrl('profile/educations-and-works'),
            trans('label.educations_and_works'), '<strong>', '</strong>'
        );
        if ($authUser->hasRole('teacher') && $authUser->teacherProfile->isApproved) {
            $menu->add( // add a menu item
                homeUrl('profile/teacher-information'),
                trans('label.teacher_information'), '<strong>', '</strong>'
            );
            $menu->add( // add a menu item
                homeUrl('profile/teaching-time'),
                trans('label.teaching_time'), '<strong>', '</strong>'
            );
            $menu->add( // add a menu item
                homeUrl('profile/payment-information'),
                trans('label.payment_information'), '<strong>', '</strong>'
            );
        }
        $menu = contentFilter('profile_menu', $menu);
        return $menu;
    }
}