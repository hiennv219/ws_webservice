<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-08
 * Time: 20:29
 */

namespace Katniss\Everdeen\Utils\DataStructure\Hierarchy;

class Hierarchy
{
    /**
     * @var array
     */
    private $roots;

    /**
     * Hierarchy constructor.
     */
    public function __construct()
    {
        $this->roots = [];
    }

    /**
     * @return array|HierarchyItem
     */
    public function get()
    {
        return $this->roots;
    }

    public function buildFromList($data, $objectKey, $parentKey)
    {
        $this->roots = [];

        $objects = [];
        foreach ($data as $object) {
            $object = new HierarchyObject($object);
            $objects[$object->property($objectKey)] = $object;
        }

        $childrenObjects = [];
        foreach ($objects as $object) {
            if (empty($object->property($parentKey))
                || !array_key_exists($object->property($parentKey), $objects)
            ) {
                $this->roots[] = new HierarchyItem($object);
            } else {
                $childrenObjects[] = $object;
            }
        }

        while (count($childrenObjects) > 0) {
            $childrenObject = array_shift($childrenObjects);
            $pushed = false;
            foreach ($this->roots as $root) {
                if ($root->tryToPush($childrenObject, $objectKey, $parentKey)) {
                    $pushed = true;
                }
            }
            if (!$pushed) {
                array_push($childrenObjects, $childrenObject);
            }
        }
    }

    public function buildFromStructuredArray($data, $objectKey, $childrenKey)
    {
        $this->roots = [];

        foreach ($data as $item) {
            $children = isset($item[$childrenKey]) ? $item[$childrenKey] : [];
            $item = new HierarchyItem(new HierarchyObject($item[$objectKey]));
            $item->push($children, $objectKey, $childrenKey);
            $this->roots[] = $item;
        }
    }

    public function render($itemRenderClosure, $childrenWrapperRenderClosure, $allWrapperRenderClosure = null, $sortClosure = null)
    {
        $roots = empty($sortClosure) ? $this->roots : call_user_func($sortClosure, $this->roots);
        $rendered = [];
        foreach ($roots as $item) {
            $rendered[] = $item->render($itemRenderClosure, $childrenWrapperRenderClosure, $sortClosure);
        }

        return !empty($allWrapperRenderClosure) ? call_user_func($allWrapperRenderClosure, $rendered) : $rendered;
    }

    public function toArray($sortClosure = null)
    {
        $roots = empty($sortClosure) ? $this->roots : call_user_func($sortClosure, $this->roots);
        $array = [];
        foreach ($roots as $item) {
            $array[] = $item->toArray($sortClosure);
        }
        return $array;
    }
}