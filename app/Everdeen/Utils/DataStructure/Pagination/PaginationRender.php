<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-08
 * Time: 18:46
 */

namespace Katniss\Everdeen\Utils\DataStructure\Pagination;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class PaginationRender
{
    protected $default = [
        'query' => 'page',
        'wrapClass' => 'pagination',
        'firstClass' => 'first',
        'firstText' => '&laquo;',
        'lastClass' => 'last',
        'lastText' => '&raquo;',
        'prevClass' => 'prev',
        'prevText' => '&lsaquo;',
        'nextClass' => 'next',
        'nextText' => '&rsaquo;',
        'pageClass' => 'page',
        'activeClass' => 'active',
        'disabledClass' => 'disabled',
    ];

    public $query = 'page';
    public $wrapClass = 'pagination';
    public $firstClass = 'first';
    public $firstText = '&laquo;';
    public $lastClass = 'last';
    public $lastText = '&raquo;';
    public $prevClass = 'prev';
    public $prevText = '&lsaquo;';
    public $nextClass = 'next';
    public $nextText = '&rsaquo;';
    public $pageClass = 'page';
    public $activeClass = 'active';
    public $disabledClass = 'disabled';

    protected $renderedPagination;

    public function __construct()
    {
    }

    public function setDefault($name, $value, $sync = true)
    {
        if (isset($this->default[$name])) {
            $this->default[$name] = $value;
            if ($sync) {
                $this->{$name} = $value;
            }
        }
    }

    public function reset()
    {
        foreach ($this->default as $name => $value) {
            $this->{$name} = $value;
        }
    }

    public function getRenderedPagination()
    {
        return $this->renderedPagination;
    }

    public function renderByPagedModels($pagedCollection, $maxPageShow = AppConfig::DEFAULT_PAGINATION_ITEMS)
    {
        return $this->render(new Pagination($pagedCollection, $maxPageShow));
    }

    public function render(Pagination $pagination, $reset = true)
    {
        $this->renderedPagination = $pagination->toArray();
        $request = request();
        $menu = new Menu();
        $menu->add(
            $request->fullUrlWithQuery([$this->query => $this->renderedPagination['first']]),
            $this->firstText, '', '', $this->renderedPagination['at_first'] ? "$this->firstClass $this->disabledClass" : $this->firstClass,
            '', '', trans('pagination.page_first')
        );
        $menu->add(
            $request->fullUrlWithQuery([$this->query => $this->renderedPagination['prev']]),
            $this->prevText, '', '', $this->renderedPagination['at_first'] ? "$this->prevClass $this->disabledClass" : $this->prevClass,
            '', '', trans('pagination.page_prev')
        );
        for ($i = $this->renderedPagination['range']['start']; $i <= $this->renderedPagination['range']['end']; ++$i) {
            $menu->add(
                $request->fullUrlWithQuery([$this->query => $i]),
                $i, '', '', $i == $this->renderedPagination['current'] ? "$this->pageClass $this->activeClass" : $this->pageClass,
                '', '', trans('pagination._page', ['number' => $i])
            );
        }
        $menu->add(
            $request->fullUrlWithQuery([$this->query => $this->renderedPagination['next']]),
            $this->nextText, '', '', $this->renderedPagination['at_last'] ? "$this->nextClass $this->disabledClass" : $this->nextClass,
            '', '', trans('pagination.page_next')
        );
        $menu->add(
            $request->fullUrlWithQuery([$this->query => $this->renderedPagination['last']]),
            $this->lastText, '', '', $this->renderedPagination['at_last'] ? "$this->lastClass $this->disabledClass" : $this->lastClass,
            '', '', trans('pagination.page_last')
        );
        $menuRender = new MenuRender();
        $menuRender->wrapClass = $this->wrapClass;
        $rendered = new HtmlString($menuRender->render($menu));
        if ($reset) $this->reset();
        return $rendered;
    }
}