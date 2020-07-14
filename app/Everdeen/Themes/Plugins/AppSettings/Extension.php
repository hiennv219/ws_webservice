<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\AppSettings;

use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Thunder\Shortcode\ShortcodeFacade;

class Extension extends BaseExtension
{
    const NAME = 'app_settings';
    const DISPLAY_NAME = 'App Settings';
    const DESCRIPTION = 'Change app settings';

    public static function getSharedViewData()
    {
        $ext = Extension::getSharedData(self::NAME);
        if (empty($ext)) return null;

        $data = new \stdClass();
        $data->register_enable = $ext->registerEnable;
        return $data;
    }

    protected $registerEnable;
    protected $shortCodeEnable;
    protected $defaultArticleCategory;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        $this->registerEnable = $this->getProperty('register_enable') == 1;
        $this->shortCodeEnable = $this->getProperty('short_code_enable') == 1;
        $this->defaultArticleCategory = $this->getProperty('default_article_category', '');

        $this->makeSharedData([
            'registerEnable',
            'shortCodeEnable',
            'defaultArticleCategory',
        ]);
    }

    public function register()
    {
        if($this->shortCodeEnable) {
            addFilter('post_content', new CallableObject(function ($content) {
                $facade = new ShortcodeFacade();
                $facade = contentFilter('short_code', $facade);
                return $facade->process($content);
            }), 'ext:app_settings');
        }
    }

    public function viewAdminParams()
    {
        $articleCategoryRepository = new ArticleCategoryRepository();

        return array_merge(parent::viewAdminParams(), [
            'register_enable' => $this->registerEnable,
            'short_code_enable' => $this->shortCodeEnable,
            'default_article_category' => $this->defaultArticleCategory,
            'article_categories' => $articleCategoryRepository->getAll(),
        ]);
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'register_enable',
            'short_code_enable',
            'default_article_category',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'register_enable' => 'sometimes|nullable|in:1',
            'short_code_enable' => 'sometimes|nullable|in:1',
            'default_article_category' => 'sometimes|nullable|exists:categories,id,type,' . Category::TYPE_ARTICLE,
        ]);
    }
}