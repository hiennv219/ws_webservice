<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 02:53
 */

namespace Katniss\Everdeen\Themes\HomeThemes\ExampleTheme;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\HomeThemes\ExampleTheme\Controllers\ThemeAdminController;
use Katniss\Everdeen\Themes\HomeThemes\HomeTheme;
use Katniss\Everdeen\Themes\Queue\CssQueue;
use Katniss\Everdeen\Themes\Queue\JsQueue;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class Theme extends HomeTheme
{
    const NAME = 'example';
    const DISPLAY_NAME = 'Example Theme';
    const VIEW = 'example';

    public function __construct()
    {
        parent::__construct();
    }

    public function mockAdmin()
    {
        addFilter('extra_admin_menu', new CallableObject(function (Menu $menu) {
            if (authUser()->hasRole('admin')) {
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('example_theme.page_theme_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu(currentFullUrl());
                $subMenu->add( // add a menu item
                    addExtraUrl('admin/themes/example/options', adminUrl('extra')),
                    trans('example_theme.page_options_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $menu->addSubMenu($subMenu);
            }
            return $menu;
        }), 'theme:example:menu');
        $controllerClass = ThemeAdminController::class;
        addTrigger('extra_route', new CallableObject(function (Request $request) use ($controllerClass) {
            $controller = new $controllerClass;
            switch (strtolower($request->method())) {
                case 'get':
                    return $controller->options($request);
                case 'put':
                    return $controller->updateOptions($request);
            }
            return '';
        }), 'admin/themes/example/options');
    }

    public function register($isAuth = false)
    {
        parent::register($isAuth);
    }

    protected function registerComposers($is_auth = false)
    {
        view()->composer(
            $this->masterPath('index'), Composers\MainMenuComposer::class
        );
        view()->composer(
            $this->pagePath('template_contact'), Composers\ContactTemplateComposer::class
        );
    }

    protected function registerLibStyles($is_auth = false)
    {
        parent::registerLibStyles($is_auth);

        $this->libCssQueue->add(CssQueue::LIB_BOOTSTRAP_NAME, $this->cssAsset('bootstrap.min.css'));
        $this->libCssQueue->add(CssQueue::LIB_FONT_AWESOME_NAME, _kExternalLink(CssQueue::LIB_FONT_AWESOME_NAME));
    }

    protected function registerExtStyles($is_auth = false)
    {
        $this->extCssQueue->add('theme-style', $this->cssAsset('scrolling-nav.css'));

        parent::registerExtStyles($is_auth);
    }

    protected function registerLibScripts($is_auth = false)
    {
        parent::registerLibScripts($is_auth);

        $this->libJsQueue->add(JsQueue::LIB_JQUERY_NAME, $this->jsAsset('jquery.js'));
        $this->libJsQueue->add(JsQueue::LIB_BOOTSTRAP_NAME, $this->jsAsset('bootstrap.min.js'));
        $this->libJsQueue->add('jquery-easing', $this->jsAsset('jquery.easing.min.js'));
    }

    protected function registerExtScripts($is_auth = false)
    {
        $this->extJsQueue->add('theme-script', $this->jsAsset('scrolling-nav.js'));

        parent::registerExtScripts($is_auth);
    }

    public function extensions()
    {
        return [
            // define extension here: extension name => extension class
        ];
    }

    public function placeholders()
    {
        return [
            'default_placeholder' => 'Default Placeholder',
            'pages' => 'Sidebar Pages',
            'articles' => 'Sidebar Articles',
        ];
    }

    public function widgets()
    {
        return [
            // define widget here: widget name => widget class
        ];
    }

    public function pageTemplates()
    {
        return [
            'page_contact' => 'Contact Page',
        ];
    }

    public function articleTemplates()
    {
        return [
            'article_contact' => 'Contact Article',
        ];
    }
}