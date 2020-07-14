<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Everdeen\Themes\AdminThemes;

use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Themes\Queue\JsQueue;
use Katniss\Everdeen\Themes\Theme;

abstract class AdminTheme extends Theme
{
    public function __construct()
    {
        parent::__construct(Theme::TYPE_ADMIN);
    }

    public function register($isAuth = false)
    {
        homeThemeMockAdmin();

        parent::register($isAuth);
    }

    protected function registerExtScripts($is_auth = false)
    {
        parent::registerExtScripts($is_auth);

        $this->extJsQueue->add('global_vars', [
            'KATNISS_USER_REQUIRED' => 'true',
        ], JsQueue::TYPE_VAR, ['KATNISS_USER_REQUIRED'], true); // add more global vars to existing ones
        $this->extJsQueue->add('global-app-script', libraryAsset('katniss.admin.js'));
    }
}