<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-13
 * Time: 16:18
 */

namespace Katniss\Everdeen\Reports;


abstract class Report
{
    protected $data;

    public function __construct()
    {
        $this->data = [];

        $this->prepare();
    }

    public function getData()
    {
        return $this->data;
    }

    public function hasData()
    {
        return count($this->data) > 0;
    }

    protected abstract function prepare();

    protected abstract function getDataAsFlatArray();

    protected abstract function getHeader();
}