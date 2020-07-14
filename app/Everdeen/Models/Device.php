<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 21:02
 */

namespace Katniss\Everdeen\Models;


use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use UuidTrait;

    protected $table = 'devices';

    protected $fillable = ['uuid', 'secret'];

    protected $uuids = ['uuid'];
}