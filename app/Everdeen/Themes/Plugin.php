<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-28
 * Time: 23:06
 */

namespace Katniss\Everdeen\Themes;


use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Utils\AppConfig;

abstract class Plugin
{
    const NAME = '';
    const DISPLAY_NAME = '';
    const DESCRIPTION = '';
    const THEME_ONLY = false;
    const THEME_HOME = true;
    const EDITABLE = true;
    const TRANSLATABLE = false;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $localizedData;

    /**
     * @var array
     */
    protected $currentLocalizedData;

    public function __construct()
    {
        $this->__init();
    }

    public function getName()
    {
        return $this::NAME;
    }

    public function getDisplayName()
    {
        return $this::DISPLAY_NAME;
    }

    public function getDescription()
    {
        return $this::DESCRIPTION;
    }

    public function getTheme()
    {
        return $this::THEME_ONLY;
    }

    public function isTranslatable()
    {
        return $this::TRANSLATABLE;
    }

    public function isEditable()
    {
        return $this::EDITABLE;
    }

    protected function toDataConstructAsJson(array $data = [], array $localizedData = [])
    {
        return json_encode($this->toDataConstruct($data, $localizedData));
    }

    protected function toDataConstruct(array $data = [], array $localizedData = [])
    {
        $htmlFields = $this->htmlFields();
        foreach ($data as $field => &$value) {
            if (array_key_exists($field, $htmlFields)) {
                $value = $this->getValueFromHtmlField($value, $htmlFields[$field]);
            }
        }

        if (!$this::TRANSLATABLE) return $data;

        $htmlFields = $this->localizedHtmlFields();
        foreach ($localizedData as $locale => &$values) {
            foreach ($values as $field => &$value) {
                if (array_key_exists($field, $htmlFields)) {
                    $value = $this->getValueFromHtmlField($value, $htmlFields[$field]);
                }
            }
        }

        return array_merge($data, [AppConfig::KEY_LOCALE_INPUT => $localizedData]);
    }

    protected function fromDataConstruct(array $data)
    {
        if ($this::TRANSLATABLE) {
            $this->localizedData = [];
            $this->currentLocalizedData = [];
            if (isset($data[AppConfig::KEY_LOCALE_INPUT])) {
                $locale = currentLocaleCode();
                $fallbackLocale = config('translatable.fallback_locale');
                $this->localizedData = $data[AppConfig::KEY_LOCALE_INPUT];
                unset($data[AppConfig::KEY_LOCALE_INPUT]);
                if (isset($this->localizedData[$locale])) {
                    $this->currentLocalizedData = $this->localizedData[$locale];
                } elseif (!empty($this->localizedData[$fallbackLocale])) {
                    $this->currentLocalizedData = $this->localizedData[$fallbackLocale];
                }
            }
        }

        $this->data = $data;
    }

    private function getValueFromHtmlField($value, $config)
    {
        return clean($value, $config);
    }

    public function getProperty($name, $locale = '', $isArray = false, $indexKey = null)
    {
        if (!$this::EDITABLE) abort(404);

        if (empty($locale)) {
            if (isset($this->data[$name])) return !$isArray ? $this->data[$name] : defArrItem($this->data[$name], $indexKey, '');
            if ($this::TRANSLATABLE && isset($this->currentLocalizedData[$name])) {
                return !$isArray ? $this->currentLocalizedData[$name] : defArrItem($this->currentLocalizedData[$name], $indexKey, '');
            }
            return '';
        }

        if (!$this::TRANSLATABLE) return '';

        return isset($this->localizedData[$locale]) && isset($this->localizedData[$locale][$name]) ?
            (!$isArray ? $this->localizedData[$locale][$name] : defArrItem($this->localizedData[$locale][$name], $indexKey, '')) : '';
    }

    public function register()
    {
    }

    protected function __init()
    {
    }

    public function validationRules()
    {
        if (!$this::EDITABLE) abort(404);

        return [];
    }

    public function localizedValidationRules()
    {
        if (!$this::EDITABLE || !$this::TRANSLATABLE) abort(404);

        return [];
    }

    public function fields()
    {
        if (!$this::EDITABLE) abort(404);

        return [];
    }

    public function localizedFields()
    {
        if (!$this::EDITABLE || !$this::TRANSLATABLE) abort(404);

        return [];
    }

    public function htmlFields()
    {
        return [];
    }

    public function localizedHtmlFields()
    {
        if (!$this::TRANSLATABLE) abort(404);

        return [];
    }

    public function view($name)
    {
        if (!$this::THEME_ONLY) {
            return ThemeFacade::commonPluginPath($this::NAME, $name);
        }
        $theme = $this::THEME_HOME ? homeTheme() : adminTheme();
        return $theme->pluginPath($this::NAME, $name);
    }

    public function viewAdmin()
    {
        if (!$this::EDITABLE) abort(404);

        return $this->view('admin');
    }

    public function viewAdminParams()
    {
        if (!$this::EDITABLE) abort(404);

        return [];
    }
}