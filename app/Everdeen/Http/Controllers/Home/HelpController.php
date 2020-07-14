<?php

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\HelpCategoryRepository;
use Katniss\Everdeen\Repositories\HelpRepository;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class HelpController extends ViewController
{
    protected $categoryRepository;
    protected $helpRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'help';
        $this->categoryRepository = new HelpCategoryRepository();
        $this->helpRepository = new HelpRepository();
    }

    public function index(Request $request)
    {
        return $this->show($request, null);
    }

    public function show(Request $request, $slug)
    {
        $emptySlug = empty($slug);
        $categories = $this->categoryRepository->getAll();
        $currentHelp = null;
        $categoriesMenu = null;
        if ($categories->count() > 0) {
            $categoriesMenu = new Menu();
            foreach ($categories as $category) {
                $categoriesMenu->add(
                    'javascript:void(0)',
                    $category->name,
                    '<strong>', '</strong>', '', 'help-item help-category'
                );
                $helps = $category->posts()->with('translations')->get()->sortBy('pivot.order');
                if ($helps->count() > 0) {
                    if ($emptySlug) {
                        $currentHelp = $helps->first();
                    }
                    $subMenu = new Menu($request->fullUrl());
                    foreach ($helps as $help) {
                        if (!$emptySlug && $help->translations->where('slug', $slug)->count() > 0) {
                            $currentHelp = $help;
                        }
                        $subMenu->add( // add a menu item
                            homeUrl('helps/{slug}', ['slug' => $help->slug]),
                            $help->title,
                            '', '', '', 'help-item help-article', '', '',
                            !empty($currentHelp) && $currentHelp->id == $help->id
                        );
                    }
                    $categoriesMenu->addSubMenu($subMenu);
                }
            }
        }
        if (empty($currentHelp)) {
            abort(404);
        }

        if (!empty($categoriesMenu)) {
            $menuRender = new MenuRender();
            $menuRender->wrapTag = 'div';
            $menuRender->wrapClass = 'list-group';
            $menuRender->childrenWrapTag = 'div';
            $menuRender->childrenWrapClass = 'list-group big';
            $menuRender->itemTag = 'div';
            $menuRender->linkClass = 'list-group-item';
            $categoriesMenu = new HtmlString($menuRender->render($categoriesMenu));
        }

        $this->_title($currentHelp->title);
        $this->_description(htmlShorten($currentHelp->content));

        return $this->_show([
            'categories' => $categories,
            'help' => $currentHelp,
            'categories_menu' => $categoriesMenu,
        ]);
    }
}
