<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\ExtraHtml;

use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Everdeen\Utils\AppConfig;

class Widget extends DefaultWidget
{
    const NAME = 'extra_html';
    const DISPLAY_NAME = 'Extra HTML';

    public $content;

    public function __init()
    {
        parent::__init();

        $this->content = $this->getProperty('content');
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'extended_localizing_path' => ThemeFacade::commonPluginPath($this::NAME, 'admin_localizing')
        ]);
    }

    public function render()
    {
        return sprintf('<div id="%s" class="widget-extra-html"><h4>%s</h4>%s</div>', $this->getHtmlId(), $this->name, $this->content);
    }

    public function localizedFields()
    {
        return array_merge(parent::localizedFields(), [
            'content',
        ]);
    }

    public function localizedHtmlFields()
    {
        return array_merge(parent::localizedHtmlFields(), [
            'content' => AppConfig::DEFAULT_HTML_CLEAN_SETTING,
        ]);
    }
}