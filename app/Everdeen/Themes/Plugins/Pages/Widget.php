<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-06
 * Time: 15:37
 */

namespace Katniss\Everdeen\Themes\Plugins\Pages;

use Katniss\Everdeen\Repositories\PageRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'pages';
    const DISPLAY_NAME = 'Pages';

    public function register()
    {
        enqueueThemeHeader(
            '<style>.widget-pages ul.list-group{margin-bottom: 0;}</style>',
            'widget_pages'
        );
    }

    public function viewHomeParams()
    {
        $pageRepository = new PageRepository();
        return array_merge(parent::viewHomeParams(), [
            'name' => $this->name,
            'pages' => $pageRepository->getAll(),
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }
}