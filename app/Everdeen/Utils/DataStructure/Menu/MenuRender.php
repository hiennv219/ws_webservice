<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-06
 * Time: 17:48
 */

namespace Katniss\Everdeen\Utils\DataStructure\Menu;

use Katniss\Everdeen\Utils\DataStructure\Hierarchy\Hierarchy;
use Katniss\Everdeen\Utils\DataStructure\Hierarchy\HierarchyItem;
use Katniss\Everdeen\Utils\DataStructure\Hierarchy\HierarchyObject;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;

class MenuRender
{
    public $wrapTag = 'ul';
    public $wrapClass = '';
    public $wrapId = '';
    public $childrenWrapTag = 'ul';
    public $childrenWrapClass = '';
    public $itemTag = 'li';
    public $itemClass = '';
    public $linkClass = '';
    public $activeClass = 'active';
    public $nameClosure = null;

    public function __construct()
    {
    }

    protected function renderName(HierarchyObject $object)
    {
        $name = $object->property('name');
        $before = $object->property('before');
        $after = $object->property('after');
        return empty($this->nameClosure) ? $before . $name . $after : call_user_func($this->nameClosure, $object);
    }

    protected function renderUrl(HierarchyObject $object)
    {
        $url = $object->property('url');
        $name = $this->renderName($object);
        if (!empty($url)) {
            $linkClass = $this->renderClass($this->linkClass, $object->property('link_class'));
            $target = $object->property('blank') == true ? ' target="_blank"' : '';
            return Str::format('<a{0} href="{1}"{2}>{3}</a>', $linkClass, $url, $target, $name);
        }
        return $name;
    }

    protected function renderClass()
    {
        $classes = trim(implode(' ', func_get_args()));
        return !empty($classes) ? ' class="' . $classes . '"' : '';
    }

    protected function renderId($id)
    {
        return !empty($id) ? ' id="' . $id . '"' : '';
    }

    public function render(Menu $menu)
    {
        $hierarchy = new Hierarchy();
        $hierarchy->buildFromStructuredArray($menu->get(), 'item', 'children');
        return $hierarchy->render(
            function (HierarchyItem $hierarchyItem, $renderedHierarchyChildren) {
                $object = $hierarchyItem->getObject();
                $activeClass = '';
                if ($object->property('active') === true) {
                    $activeClass = $this->activeClass;
                    $parent = $hierarchyItem->getParent();
                    while (!empty($parent)) {
                        $parent->getObject()->property('active', true);
                        $parent = $parent->getParent();
                    }
                }
                $itemClass = $this->renderClass($this->itemClass, $object->property('item_class'), $activeClass);
                $itemId = $this->renderId($object->property('item_id'));
                return Str::format(
                    '<{0}{1}{2}>{3}{4}</{0}>',
                    $this->itemTag, $itemId, $itemClass, $this->renderUrl($object), $renderedHierarchyChildren
                );
            },
            function (array $renderedHierarchyChildren) {
                if (count($renderedHierarchyChildren) <= 0) {
                    return '';
                }
                $renderedHierarchyChildren = implode('', $renderedHierarchyChildren);
                return Str::format(
                    '<{0}{1}>{2}</{0}>',
                    $this->childrenWrapTag,
                    $this->renderClass($this->childrenWrapClass),
                    $renderedHierarchyChildren
                );
            },
            function (array $renderedHierarchyItems) {
                if (count($renderedHierarchyItems) <= 0) {
                    return '';
                }
                $renderedHierarchyItems = implode('', $renderedHierarchyItems);
                return Str::format(
                    '<{0}{1}{2}>{3}</{0}>',
                    $this->wrapTag,
                    $this->renderId($this->wrapId),
                    $this->renderClass($this->wrapClass),
                    $renderedHierarchyItems
                );
            }
        );
    }
}