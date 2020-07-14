<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 16:19
 */

namespace Katniss\Everdeen\Themes\HomeThemes\WowSkype;


use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\HomeThemes\HomeTheme;
use Katniss\Everdeen\Themes\HomeThemes\WowSkype\Controllers\ThemeAdminController;
use Katniss\Everdeen\Themes\Queue\CssQueue;
use Katniss\Everdeen\Themes\Queue\JsQueue;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class Theme extends HomeTheme
{
    const NAME = 'wow_skype';
    const DISPLAY_NAME = 'Wow Skype';
    const VIEW = 'wow_skype';

    public function __construct()
    {
        parent::__construct();

        $currentLocale = currentLocaleCode();
        $this->description = $this->options('home_description', $this->description, $currentLocale, true);
        $this->title = $this->options('home_name', $this->title, $currentLocale, true);
        $this->titleRoot = $this->title;
        $this->keywords = $this->options('site_keywords', $this->keywords);
    }

    public function mockAdmin()
    {
        addFilter('extra_admin_menu', new CallableObject(function (Menu $menu) {
            if (authUser()->hasRole('admin')) {
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('wow_skype_theme.page_theme_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu(currentFullUrl());
                $subMenu->add( // add a menu item
                    addExtraUrl('admin/themes/wow_skype/options', adminUrl('extra')),
                    trans('wow_skype_theme.page_options_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $menu->addSubMenu($subMenu);
            }
            return $menu;
        }), 'theme:wow_skype:menu');
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
        }), 'admin/themes/wow_skype/options');
    }

    public function register($isAuth = false)
    {
        parent::register($isAuth);

        $knowledgeCoverImage = $this->options('knowledge_cover_image', '');
        if (!empty($knowledgeCoverImage)) {
            if (request()->is(homePath('knowledge'))) {
                addFilter('open_graph_tags_before_render', new CallableObject(function ($data) use ($knowledgeCoverImage) {
                    $data['og:image'] = $knowledgeCoverImage;
                    return $data;
                }), 'articles_view_single');
            }
            addPlace('knowledge_cover', new CallableObject(function () use ($knowledgeCoverImage) {
                return '<div class="image-cover image-cover-top height-500" style="background-image: url(' . $knowledgeCoverImage . ')"></div>';
            }), 'knowledge_cover_image');
        }
    }

    protected function registerComposers($is_auth = false)
    {
        view()->composer(
            $this->masterPath('header_nav_full'), Composers\MainMenuComposer::class
        );
        view()->composer(
            $this->masterPath('profile'), Composers\ProfileMenuComposer::class
        );
        view()->composer(
            $this->masterPath('master'), Composers\FooterComposer::class
        );
        view()->composer(
            $this->masterPath('footer_lower'), Composers\FooterLowerComposer::class
        );
    }

    protected function registerLibStyles($is_auth = false)
    {
        parent::registerLibStyles($is_auth);

        $this->libCssQueue->add(CssQueue::LIB_OPEN_SANS_NAME, _kExternalLink(CssQueue::LIB_OPEN_SANS_NAME));
        $this->libCssQueue->add(CssQueue::LIB_BOOTSTRAP_NAME, _kExternalLink(CssQueue::LIB_BOOTSTRAP_NAME));
        $this->libCssQueue->add(CssQueue::LIB_FONT_AWESOME_NAME, _kExternalLink(CssQueue::LIB_FONT_AWESOME_NAME));
        $this->libCssQueue->add('fancybox', libraryAsset('fancybox/jquery.fancybox.css'));
    }

    protected function registerExtStyles($is_auth = false)
    {
        $this->extCssQueue->add('theme-style', $this->cssAsset('style.css'));

        parent::registerExtStyles($is_auth);
    }

    protected function registerLibScripts($is_auth = false)
    {
        parent::registerLibScripts($is_auth);

        $this->libJsQueue->add(JsQueue::LIB_JQUERY_NAME, _kExternalLink(JsQueue::LIB_JQUERY_NAME));
        $this->libJsQueue->add(JsQueue::LIB_BOOTSTRAP_NAME, _kExternalLink(JsQueue::LIB_BOOTSTRAP_NAME));
        $this->libJsQueue->add('fancybox', libraryAsset('fancybox/jquery.fancybox.pack.js'));
        $this->libJsQueue->add('fancybox-media', libraryAsset('fancybox/helpers/jquery.fancybox-media.js'));
    }

    protected function registerExtScripts($is_auth = false)
    {
        $this->extJsQueue->add('theme-script', $this->jsAsset('script.js'));

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
            'homepage' => 'Homepage',
            'knowledge_middle' => 'Knowledge Page - Middle',
            'knowledge_bottom_left' => 'Knowledge Page - Bottom - Left',
            'knowledge_bottom_right' => 'Knowledge Page - Bottom - Right',
            'knowledge_bottom_middle' => 'Knowledge Page - Bottom - Middle',
            'article_sidebar_right' => 'Right Sidebar on Article Page',
            'footer_links' => 'Footer Links',
        ];
    }

    public function widgets()
    {
        return [
            // define widget here: widget name => widget class
        ];
    }
}