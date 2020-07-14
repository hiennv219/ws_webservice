<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:58
 */

namespace Katniss\Everdeen\Repositories;


use Illuminate\Database\Eloquent\Model;

abstract class ModelRepository
{
    protected $model;

    public function __construct($id = null)
    {
        $this->model($id);
    }

    public function model($id = null)
    {
        if (!empty($id)) {
            $this->model = $id instanceof Model ? $id : $this->getById($id);
        }
        return $this->model;
    }

    public abstract function getById($id);

    public abstract function getPaged();

    public abstract function getAll();
}