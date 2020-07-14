<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-06
 * Time: 15:37
 */

namespace Katniss\Everdeen\Themes\Plugins\ArticleCategories;

use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'article_categories';
    const DISPLAY_NAME = 'Article Categories';

    public function register()
    {
        enqueueThemeHeader(
            '<style>.widget-article-categories ul.list-group{margin-bottom: 0;}</style>',
            'widget_article_categories'
        );
    }

    public function viewHomeParams()
    {
        $categoryRepository = new ArticleCategoryRepository();
        return array_merge(parent::viewHomeParams(), [
            'name' => $this->name,
            'categories' => $categoryRepository->getAll(),
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }
}