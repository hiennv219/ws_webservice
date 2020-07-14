<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-08
 * Time: 20:30
 */

namespace Katniss\Everdeen\Utils\DataStructure\Hierarchy;

class HierarchyItem
{
    /**
     * @var HierarchyItem
     */
    private $parent;

    /**
     * @var int
     */
    private $level;

    /**
     * @var HierarchyObject
     */
    private $object;

    /**
     * @var array
     */
    private $children;

    /**
     * HierarchyItem constructor.
     * @param HierarchyObject $object
     */
    public function __construct($object, $level = 1, $parent = null)
    {
        $this->object = $object;
        $this->children = [];
        $this->level = $level;
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param HierarchyObject $object
     * @param string $objectKey
     * @param string $parentKey
     * @return bool
     */
    public function tryToPush($object, $objectKey, $parentKey)
    {
        if ($object->property($parentKey) == $this->object->property($objectKey)) {
            $this->children[] = new HierarchyItem($object, $this->level + 1, $this);
            return true;
        }

        foreach ($this->children as $child) {
            if ($child->tryToPush($object, $objectKey, $parentKey)) {
                return true;
            }
        }

        return false;
    }

    public function push($data, $objectKey, $childrenKey)
    {
        foreach ($data as $item) {
            $children = isset($item[$childrenKey]) ? $item[$childrenKey] : [];
            $item = new HierarchyItem(new HierarchyObject($item[$objectKey]), $this->level + 1, $this);
            $item->push($children, $objectKey, $childrenKey);
            $this->children[] = $item;
        }
    }

    public function render($itemRenderClosure, $childrenWrapperRenderClosure, $sortClosure = null)
    {
        $children = empty($sortClosure) ? $this->children : call_user_func($sortClosure, $this->children);
        $renderedChildren = [];
        foreach ($children as $child) {
            $renderedChildren[] = $child->render($itemRenderClosure, $childrenWrapperRenderClosure, $sortClosure = null);
        }

        return call_user_func($itemRenderClosure,
            $this,
            call_user_func($childrenWrapperRenderClosure, $renderedChildren, $this)
        );
    }

    public function toArray($sortClosure = null)
    {
        $children = empty($sortClosure) ? $this->children : call_user_func($sortClosure, $this->children);
        $childrenArray = [];
        foreach ($children as $child) {
            $childrenArray[] = $child->toArray($sortClosure);
        }

        $array = [
            'object' => $this->object->getArray(),
            'children' => $childrenArray,
            'level' => $this->level,
        ];
        return $array;
    }
}