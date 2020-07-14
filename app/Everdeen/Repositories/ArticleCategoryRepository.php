<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Themes\Extension;
use Katniss\Everdeen\Themes\Plugins\AppSettings\Extension as AppSettingsExtension;
use Katniss\Everdeen\Utils\AppConfig;

class ArticleCategoryRepository extends CategoryRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Category::TYPE_ARTICLE, $id);
    }

    public function getPaged()
    {
        return Category::with(['translations', 'parent', 'parent.translations'])
            ->where('type', $this->type)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getBySlugWithTranslated($slug)
    {
        return Category::with('translations')
            ->where('type', $this->type)
            ->whereTranslation('slug', $slug)
            ->firstOrFail();
    }

    public function getExceptDefault()
    {
        $appSettings = Extension::getSharedData(AppSettingsExtension::NAME);
        return Category::where('type', $this->type)->where('id', '<>', $appSettings->defaultArticleCategory)->get();
    }

    public function delete()
    {
        $category = $this->model();
        if ($category->posts()->count() > 0) {
            throw new KatnissException(trans('error.category_not_empty'));
        }
        $appSettings = Extension::getSharedData(AppSettingsExtension::NAME);
        if ($category->id == $appSettings->defaultArticleCategory) {
            throw new KatnissException(trans('error.category_delete_default'));
        }

        return parent::delete();
    }
}