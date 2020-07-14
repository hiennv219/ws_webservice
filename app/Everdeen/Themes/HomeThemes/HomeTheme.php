<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Everdeen\Themes\HomeThemes;

use Katniss\Everdeen\Themes\Queue\JsQueue;
use Katniss\Everdeen\Themes\Theme;
use Katniss\Everdeen\Themes\WidgetsFacade;
use Katniss\Everdeen\Utils\AppConfig;

abstract class HomeTheme extends Theme
{
    protected $options;
    protected $localizedOptions;
    protected $currentLocalizedOptions;

    public function __construct()
    {
        parent::__construct(Theme::TYPE_HOME);

        $this->options = getOption('theme_' . $this::NAME, []);

        $this->localizedOptions = [];
        $this->currentLocalizedOptions = [];
        if (isset($this->options[AppConfig::KEY_LOCALE_INPUT])) {
            $locale = currentLocaleCode();
            $fallbackLocale = config('translatable.fallback_locale');
            $this->localizedOptions = $this->options[AppConfig::KEY_LOCALE_INPUT];
            unset($this->options[AppConfig::KEY_LOCALE_INPUT]);
            if (isset($this->localizedOptions[$locale])) {
                $this->currentLocalizedOptions = $this->localizedOptions[$locale];
            } elseif (!empty($this->localizedOptions[$fallbackLocale])) {
                $this->currentLocalizedOptions = $this->localizedOptions[$fallbackLocale];
            }
        }
    }

    public function options($key = null, $default = null, $locale = null, $userFallback = false, $isArray = false, $indexKey = null)
    {
        if (is_array($key)) {
            $this->options = array_merge($this->options, $key);
            setOption('theme_' . $this::NAME, $this->options, 'theme:h:' . $this::NAME);
            return $this->options;
        }

        if (empty($key)) return $this->options;

        if (empty($locale)) {
            return !$isArray ?
                defArrItem($this->options, $key, $default) : defArrItem($this->options[$key], $indexKey, $default);
        }

        if (isset($this->localizedOptions[$locale]) && isset($this->localizedOptions[$locale][$key])) {
            return !$isArray ? $this->localizedOptions[$locale][$key] : defArrItem($this->localizedOptions[$locale][$key], $indexKey, $default);
        }

        if ($userFallback && isset($this->currentLocalizedOptions[$key])) {
            return !$isArray ? $this->currentLocalizedOptions[$key] : defArrItem($this->currentLocalizedOptions[$key], $indexKey, $default);
        }

        return $default;
    }

    public function mockAdmin()
    {
    }

    protected function registerWidgets($is_auth = false)
    {
        WidgetsFacade::init();
        WidgetsFacade::register();
    }

    public function placeholders()
    {
        return [];
    }

    public function widgets()
    {
        return [];
    }

    public function pageTemplates()
    {
        return [];
    }

    public function pageTemplateView($pageTemplateName, $default = 'page.show')
    {
        if (!empty($pageTemplateName) && view()->exists($this->page($pageTemplateName))) {
            return $pageTemplateName;
        }
        return $default;
    }

    public function articleTemplates()
    {
        return [];
    }

    public function articleTemplateView($articleTemplateName, $default = 'article.show')
    {
        if (!empty($articleTemplateName) && view()->exists($this->page($articleTemplateName))) {
            return $articleTemplateName;
        }
        return $default;
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