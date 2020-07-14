<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 20:10
 */

namespace Katniss\Everdeen\Models;


use Illuminate\Database\Eloquent\Model;

class RealTimeChannel extends Model
{
    use UuidTrait;

    const TYPE_CONVERSATION = 0;

    protected $table = 'realtime_channels';

    protected $fillable = ['code'];

    protected $uuids = ['code'];
}