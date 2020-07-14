<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-06
 * Time: 15:37
 */

namespace Katniss\Everdeen\Themes\Plugins\WallNewestArticles;

use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'wall_newest_articles';
    const DISPLAY_NAME = 'Wall Newest Articles';

    protected $numberOfItems;

    public function __init()
    {
        parent::__init();

        $this->numberOfItems = defPr($this->getProperty('number_of_items'), 6);
    }

    public function register()
    {
        enqueueThemeHeader(
            '<style>.latest-article-item,.latest-article-item .image-cover{border-radius: 8px}</style>',
            'widget_wall_newest_articles'
        );
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'number_of_items' => $this->numberOfItems,
        ]);
    }

    public function viewHomeParams()
    {
        $articleRepository = new ArticleRepository();
        $homeTheme = homeTheme();
        return array_merge(parent::viewHomeParams(), [
            'name' => $this->name,
            'articles' => $articleRepository->getLast($this->numberOfItems),
            'default_image' => $homeTheme->options('knowledge_default_article_image'),
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'number_of_items',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'number_of_items' => 'required|min:1',
        ]);
    }
}