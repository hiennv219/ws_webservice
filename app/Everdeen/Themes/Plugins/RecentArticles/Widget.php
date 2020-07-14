<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\RecentArticles;

use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'recent_articles';
    const DISPLAY_NAME = 'Recent Articles';

    protected $numberOfItems;

    public function __init()
    {
        parent::__init();

        $this->numberOfItems = defPr($this->getProperty('number_of_items'), 10);
    }

    public function register()
    {
        enqueueThemeHeader('<style>
.widget-recent-articles ul{font-size:13px}
.widget-recent-articles ul i{font-size:20px;vertical-align:middle;margin-right:5px;margin-left:2px}
.widget-recent-articles ul a:hover span{font-weight:600}
</style>', 'widget_recent_articles');
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
        return array_merge(parent::viewHomeParams(), [
            'articles' => $articleRepository->getLast($this->numberOfItems),
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
            'number_of_items' => 'sometimes|nullable|integer|min:1',
        ]);
    }
}