<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-20
 * Time: 14:29
 */

namespace Katniss\Everdeen\Themes\HomeThemes\ConversationTheme;

use Katniss\Everdeen\Themes\HomeThemes\HomeTheme;
use Katniss\Everdeen\Themes\Queue\JsQueue;

class Theme extends HomeTheme
{
    const NAME = 'conversation';
    const DISPLAY_NAME = 'Conversation Theme';
    const VIEW = 'conversation';

    public function __construct()
    {
        parent::__construct();
    }

    protected function registerWidgets($is_auth = false)
    {
        // make widgets not available
    }

    protected function registerExtScripts($is_auth = false)
    {
        parent::registerExtScripts($is_auth);

        $this->extJsQueue->add('global_vars', [
            'KATNISS_USER_REQUIRED' => 'false',
        ], JsQueue::TYPE_VAR, ['KATNISS_USER_REQUIRED'], true);
        $this->extJsQueue->add('global-app-script', libraryAsset('katniss.home.js'));
    }
}