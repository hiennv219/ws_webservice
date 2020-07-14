<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 16:16
 */

namespace Katniss\Everdeen\Themes\Plugins\ArticleCategory;

use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Themes\Widget as BaseWidget;

class Widget extends BaseWidget
{
    const NAME = 'article_category';
    const DISPLAY_NAME = 'Article Category';

    protected $image;
    protected $categoryId;
    protected $showButton;
    protected $showArticles;
    protected $numberOfItems;

    protected function __init()
    {
        parent::__init();

        $this->image = defPr($this->getProperty('image'), '');
        $this->categoryId = defPr($this->getProperty('category_id'), '');
        $this->showButton = defPr($this->getProperty('show_button'), 0);
        $this->showArticles = defPr($this->getProperty('show_articles'), 0);
        $this->numberOfItems = defPr($this->getProperty('number_of_items'), 10);
    }

    public function register()
    {
        enqueueThemeHeader('<style>
.widget-article-category ul{font-size:13px}
.widget-article-category ul i{font-size:20px;vertical-align:middle;margin-right:5px;margin-left:2px}
.widget-article-category ul a:hover span{font-weight:600}
</style>', 'widget_article_category');
    }

    public function viewAdminParams()
    {
        $categoryRepository = new ArticleCategoryRepository();

        return array_merge(parent::viewAdminParams(), [
            'image' => $this->image,
            'category_id' => $this->categoryId,
            'categories' => $categoryRepository->getAll(),
            'show_button' => $this->showButton,
            'show_articles' => $this->showArticles,
            'number_of_items' => $this->numberOfItems,
        ]);
    }

    public function viewHomeParams()
    {
        $category = null;
        $articles = [];
        if (!empty($this->categoryId)) {
            $categoryRepository = new ArticleCategoryRepository();
            $category = $categoryRepository->getByIdWithTranslated($this->categoryId);
            if ($this->showArticles == 1) {
                $articleRepository = new ArticleRepository();
                $articles = $articleRepository->getLastPublishedByCategory($this->numberOfItems, $categoryId, $category);
            }
        }
        return array_merge(parent::viewHomeParams(), [
            'image' => $this->image,
            'category' => $category,
            'show_button' => $this->showButton,
            'show_articles' => $this->showArticles,
            'articles' => $articles,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'image',
            'category_id',
            'show_button',
            'show_articles',
            'number_of_items',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'image' => 'sometimes|nullable|url',
            'category_id' => 'required|exists:categories,id,type,' . Category::TYPE_ARTICLE,
            'show_button' => 'sometimes|nullable|in:1',
            'show_articles' => 'sometimes|nullable|in:1',
            'number_of_items' => 'sometimes|nullable|integer|min:1',
        ]);
    }
}