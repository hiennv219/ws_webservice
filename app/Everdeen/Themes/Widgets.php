<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 09:32
 */

namespace Katniss\Everdeen\Themes;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;

class Widgets extends Plugins
{
    protected $themeWidgets;

    public function __construct()
    {
        parent::__construct(array_merge(_kWidgets(), homeThemeWidgets()));
    }

    public function init()
    {
        $widgetRepository = new ThemeWidgetRepository();
        $this->themeWidgets = $widgetRepository->getActive(array_keys($this->defines));
    }

    public function register()
    {
        foreach ($this->themeWidgets as $themeWidget) {
            $themeWidget->register();
        }
    }

    public function display($placeholder, $before = '', $after = '', $default = '')
    {
        $themeWidgets = $this->themeWidgets->where('placeholder', $placeholder)->sortBy('order');
        $countThemeWidgets = $themeWidgets->count();
        $output = $countThemeWidgets > 0 ? $before : $default;
        foreach ($themeWidgets as $themeWidget) {
            $output .= $themeWidget->render();
        }
        return new HtmlString($countThemeWidgets > 0 ? $output . $after : $output);
    }
}