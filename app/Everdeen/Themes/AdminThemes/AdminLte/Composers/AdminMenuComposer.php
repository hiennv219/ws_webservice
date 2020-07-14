<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-24
 * Time: 15:39
 */

namespace Katniss\Everdeen\Themes\AdminThemes\AdminLte\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class AdminMenuComposer
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
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('admin_menu', $this->getMenuRender($this->getMenu()));
    }

    protected function getMenuRender(Menu $menu)
    {
        $menuRender = new MenuRender();
        $menuRender->wrapClass = 'sidebar-menu';
        $menuRender->childrenWrapClass = 'treeview-menu';
        return new HtmlString($menuRender->render($menu));
    }

    protected function getMenu()
    {
        $currentUrl = currentUrl();
        $user = authUser();
        $menu = new Menu($currentUrl);
        if ($user->can('access-admin')) {
            // Dashboard
            $menu->add(  // add an example menu item which have sub menu
                '#',
                trans('pages.admin_dashboard_title'),
                '<i class="fa fa-dashboard"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
            );
            $subMenu = new Menu($currentUrl);
            $subMenu->add( // add a menu item
                adminUrl(),
                trans('pages.admin_dashboard_title'),
                '<i class="fa fa-circle-o"></i> <span>', '</span>'
            );
            // My Account
            $subMenu->add( // add a menu item
                meUrl('account'),
                trans('pages.my_account_title'),
                '<i class="fa fa-circle-o"></i> <span>', '</span>'
            );
            // My Settings
            $subMenu->add( // add a menu item
                meUrl('settings'),
                trans('pages.my_settings_title'),
                '<i class="fa fa-circle-o"></i> <span>', '</span>'
            );
            if (!$user->hasRole('student_agent')) {
                // File Manager
                $subMenu->add( // add a menu item
                    adminUrl('my-documents'),
                    trans('pages.my_documents_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
            }
            $menu->addSubMenu($subMenu);

            if ($user->hasRole('student_agent')) {
                $menu->add(  // add an example menu item which have sub menu
                    adminUrl('student-agents/{id}/students', ['id' => $user->id]),
                    trans('pages.admin_students_title'),
                    '<i class="fa fa-users"></i> <span>', '</span>'
                );
            }

            if ($user->hasRole('admin')) {
                // System Settings
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_system_settings_title'),
                    '<i class="fa fa-cog"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu($currentUrl);
                $subMenu->add( // add a menu item
                    adminUrl('app-options'),
                    trans('pages.admin_app_options_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add( // add a menu item
                    adminUrl('extensions'),
                    trans('pages.admin_extensions_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add( // add a menu item
                    adminUrl('widgets'),
                    trans('pages.admin_widgets_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_ui_lang_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subSubMenu = new Menu($currentUrl);
                $subSubMenu->add( // add a menu item
                    adminUrl('ui-lang/php'),
                    trans('pages.admin_ui_lang_php_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subSubMenu->add( // add a menu item
                    adminUrl('ui-lang/email'),
                    trans('pages.admin_ui_lang_email_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->addSubMenu($subSubMenu);
                $menu->addSubMenu($subMenu);

                // Users
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_users_title'),
                    '<i class="fa fa-user"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu($currentUrl);
                $subMenu->add( // add a menu item
                    adminUrl('user-roles'),
                    trans('pages.admin_roles_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add( // add a menu item
                    adminUrl('users'),
                    trans('pages.admin_users_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $menu->addSubMenu($subMenu);

                //Links
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_links_title'),
                    '<i class="fa fa-external-link"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu($currentUrl);
                $subMenu->add( //add a menu item
                    adminUrl('link-categories'),
                    trans('pages.admin_link_categories_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add( //add a menu item
                    adminUrl('links'),
                    trans('pages.admin_links_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $menu->addSubMenu($subMenu);
                //Media
//                $menu->add(  // add an example menu item which have sub menu
//                    '#',
//                    trans('pages.admin_media_title'),
//                    '<i class="fa fa-photo"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
//                );
//                $subMenu = new Menu($currentUrl);
//                $subMenu->add( //add a menu item
//                    adminUrl('media-categories'),
//                    trans('pages.admin_media_categories_title'),
//                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
//                );
//                $subMenu->add( //add a menu item
//                    adminUrl('media-items'),
//                    trans('pages.admin_media_items_title'),
//                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
//                );
//                $menu->addSubMenu($subMenu);
                $menu->add(  // add an example menu item which have sub menu
                    adminUrl('announcements'),
                    trans('pages.admin_announcements_title'),
                    '<i class="fa fa-bullhorn"></i> <span>', '</span>'
                );
            }

            if ($user->hasRole(['admin', 'editor'])) {
                //Posts
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_posts_title'),
                    '<i class="fa fa-align-justify"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu($currentUrl);
//                $subMenu->add( //add a menu item
//                    adminUrl('pages'),
//                    trans('pages.admin_pages_title'),
//                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
//                );
                $subMenu->add( //add a menu item
                    adminUrl('help-categories'),
                    trans('pages.admin_help_categories_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add( //add a menu item
                    adminUrl('helps'),
                    trans('pages.admin_helps_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add( //add a menu item
                    adminUrl('article-categories'),
                    trans('pages.admin_article_categories_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_articles_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subSubMenu = new Menu($currentUrl);
                $subSubMenu->add( // add a menu item
                    adminUrl('published-articles'),
                    trans('pages.admin_published_articles_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subSubMenu->add( // add a menu item
                    adminUrl('teacher-articles'),
                    trans('pages.admin_teacher_articles_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->addSubMenu($subSubMenu);
                $menu->addSubMenu($subMenu);
            }

            if ($user->hasRole(['admin', 'manager', 'student_visor'])) {
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_learning_title'),
                    '<i class="fa fa-graduation-cap"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu($currentUrl);
                if ($user->hasRole(['admin', 'manager'])) {
                    $subMenu->add( //add a menu item
                        adminUrl('study-levels'),
                        trans('pages.admin_study_levels_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subMenu->add( //add a menu item
                        adminUrl('study-problems'),
                        trans('pages.admin_study_problems_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subMenu->add( //add a menu item
                        adminUrl('study-courses'),
                        trans('pages.admin_study_courses_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subMenu->add( //add a menu item
                        adminUrl('professional-skills'),
                        trans('pages.admin_professional_skills_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subMenu->add( //add a menu item
                        adminUrl('topics'),
                        trans('pages.admin_topics_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subMenu->add( //add a menu item
                        adminUrl('student-agents'),
                        trans('pages.admin_student_agents_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subMenu->add(  // add an example menu item which have sub menu
                        '#',
                        trans('pages.admin_teachers_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                    );
                    $subSubMenu = new Menu($currentUrl);
                    $subSubMenu->add( // add a menu item
                        adminUrl('approved-teachers'),
                        trans('pages.admin_approved_teachers_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subSubMenu->add( // add a menu item
                        adminUrl('registering-teachers'),
                        trans('pages.admin_registering_teachers_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                    $subMenu->addSubMenu($subSubMenu);
                }
                $subMenu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_students_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subSubMenu = new Menu($currentUrl);
                $subSubMenu->add( // add a menu item
                    adminUrl('approved-students'),
                    trans('pages.admin_approved_students_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subSubMenu->add( // add a menu item
                    adminUrl('registering-students'),
                    trans('pages.admin_registering_students_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->addSubMenu($subSubMenu);
                $subMenu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_learning_requests_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subSubMenu = new Menu($currentUrl);
                $subSubMenu->add( // add a menu item
                    adminUrl('register-learning-requests'),
                    trans('pages.admin_register_learning_requests_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subSubMenu->add( // add a menu item
                    adminUrl('processed-learning-requests'),
                    trans('pages.admin_processed_learning_requests_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->addSubMenu($subSubMenu);
                $subMenu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('pages.admin_classrooms_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subSubMenu = new Menu($currentUrl);
                $subSubMenu->add( // add a menu item
                    adminUrl('opening-classrooms'),
                    trans('pages.admin_opening_classrooms_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subSubMenu->add( // add a menu item
                    adminUrl('ready-classrooms'),
                    trans('pages.admin_ready_classrooms_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subSubMenu->add( // add a menu item
                    adminUrl('closed-classrooms'),
                    trans('pages.admin_closed_classrooms_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->addSubMenu($subSubMenu);
                if ($user->hasRole(['admin', 'manager'])) {
                    $subMenu->add( //add a menu item
                        adminUrl('salary-report'),
                        trans('pages.admin_salary_report_title'),
                        '<i class="fa fa-circle-o"></i> <span>', '</span>'
                    );
                }
                $menu->addSubMenu($subMenu);
            }
        }
        $extraMenu = $this->getExtraMenu();
        if ($extraMenu->has()) {
            $menu->add(  // add an example menu item which have sub menu
                '#',
                trans('pages.admin_extra_title'),
                '<i class="fa fa-folder"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
            );
            $menu->addSubMenu($extraMenu);
        }
        $menu = contentFilter('admin_menu', $menu);
        return $menu;
    }

    protected function getExtraMenu()
    {
        $menu = new Menu(currentFullUrl());
        $menu = contentFilter('extra_admin_menu', $menu);
        return $menu;
    }
}