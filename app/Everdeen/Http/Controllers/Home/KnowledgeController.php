<?php

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Repositories\HelpCategoryRepository;
use Katniss\Everdeen\Repositories\HelpRepository;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class KnowledgeController extends ViewController
{
    protected $categoryRepository;
    protected $articleRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'knowledge';
        $this->categoryRepository = new ArticleCategoryRepository();
        $this->articleRepository = new ArticleRepository();
    }

    public function index(Request $request)
    {
        return $this->_index([

        ]);
    }
}
