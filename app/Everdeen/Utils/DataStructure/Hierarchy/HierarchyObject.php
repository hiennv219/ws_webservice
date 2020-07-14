<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-06
 * Time: 16:47
 */

namespace Katniss\Everdeen\Utils\DataStructure\Hierarchy;


class HierarchyObject
{
    protected $data;
    protected $isArray;

    public function __construct($data)
    {
        $this->data = $data;
        $this->isArray = is_array($data);
    }

    public function property($name, $value = null)
    {
        if (empty($this->data)) return null;

        if ($this->isArray) {
            if ($value != null) {
                $this->data[$name] = $value;
                return $value;
            }
            return isset($this->data[$name]) ? $this->data[$name] : null;
        }

        if ($value != null) {
            $this->data->{$name} = $value;
            return $value;
        }
        return isset($this->data->{$name}) ? $this->data->{$name} : null;
    }

    public function get()
    {
        return $this->data;
    }

    public function getArray()
    {
        return $this->isArray ? $this->data : json_decode(json_encode($this->data), true);
    }
}