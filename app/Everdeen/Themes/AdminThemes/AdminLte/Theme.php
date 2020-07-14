<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 02:57
 */

namespace Katniss\Everdeen\Themes\AdminThemes\AdminLte;

use Katniss\Everdeen\Themes\AdminThemes\AdminTheme;
use Katniss\Everdeen\Themes\Queue\CssQueue;
use Katniss\Everdeen\Themes\Queue\JsQueue;


class Theme extends AdminTheme
{
    const NAME = 'admin_lte';
    const DISPLAY_NAME = 'AdminLte';
    const VIEW = 'admin_lte';

    public function __construct()
    {
        parent::__construct();

        $homeTheme = homeTheme();

        $this->description = $homeTheme->description();
        $this->title = $homeTheme->title();
        $this->titleRoot = $this->title;
        $this->keywords = $homeTheme->keywords();
    }

    protected function registerComposers($is_auth = false)
    {
        view()->composer(
            $this->masterPath('admin_menu'), Composers\AdminMenuComposer::class
        );
    }

    protected function registerLibStyles($is_auth = false)
    {
        parent::registerLibStyles($is_auth);

        $this->libCssQueue->add(CssQueue::LIB_SOURCE_SANS_PRO_NAME, _kExternalLink(CssQueue::LIB_SOURCE_SANS_PRO_NAME));
        $this->libCssQueue->add(CssQueue::LIB_BOOTSTRAP_NAME, _kExternalLink(CssQueue::LIB_BOOTSTRAP_NAME));
        $this->libCssQueue->add(CssQueue::LIB_FONT_AWESOME_NAME, _kExternalLink(CssQueue::LIB_FONT_AWESOME_NAME));
    }

    protected function registerExtStyles($is_auth = false)
    {
        $this->extCssQueue->add('theme-style', $this->cssAsset('AdminLTE.min.css'));
        $this->extCssQueue->add('theme-skin', $this->cssAsset('skins/skin-blue.min.css'));
        $this->extCssQueue->add('theme-fix', $this->cssAsset('extra.css'));

        parent::registerExtStyles($is_auth);
    }

    protected function registerLibScripts($is_auth = false)
    {
        parent::registerLibScripts($is_auth);

        $this->libJsQueue->add(JsQueue::LIB_JQUERY_NAME, _kExternalLink(JsQueue::LIB_JQUERY_NAME));
        $this->libJsQueue->add(JsQueue::LIB_BOOTSTRAP_NAME, _kExternalLink(JsQueue::LIB_BOOTSTRAP_NAME));
        $this->libJsQueue->add(JsQueue::LIB_JQUERY_UI_NAME, _kExternalLink(JsQueue::LIB_JQUERY_UI_NAME));
        $this->libJsQueue->add('slim-scroll', libraryAsset('slimScroll/jquery.slimscroll.min.js'));
        $this->libJsQueue->add('fast-click', libraryAsset('fastclick/fastclick.min.js'));
    }

    protected function registerExtScripts($is_auth = false)
    {
        $this->extJsQueue->add('theme-script', $this->jsAsset('app.min.js'));

        parent::registerExtScripts($is_auth);
    }

    public function resolveErrorView($code, $originalPath = null)
    {
        $onAuthViewPath = empty($originalPath)
            || beginsWith($originalPath, homePath('auth'))
            || beginsWith($originalPath, homePath('me'))
            || !isAuth();

        $viewInstance = view();
        $view = $this->error($onAuthViewPath ? 'auth.' . $code : $code);
        if (!$viewInstance->exists($view)) {
            $view = $this->error($onAuthViewPath ? 'auth.common' : 'common');
            if (!$viewInstance->exists($view)) {
                $view = 'errors.' . $code;
                if (!$viewInstance->exists($view)) {
                    $view = 'errors.common';
                    if (!$viewInstance->exists($view)) {
                        return false;
                    }
                }
            }
        }
        return $view;
    }
}