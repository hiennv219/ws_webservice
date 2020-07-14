<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 20:57
 */

namespace Katniss\Everdeen\Models;

use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!empty($model->uuids)) {
                foreach ((array)$model->uuids as $uuidKey) {
                    $model->{$uuidKey} = Uuid::uuid1();
                }
            }
        });
    }
}