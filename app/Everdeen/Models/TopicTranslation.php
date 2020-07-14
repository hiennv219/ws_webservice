<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 23:39
 */

namespace Katniss\Everdeen\Models;


use Illuminate\Database\Eloquent\Model;

class TopicTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'topic_translations';
    protected $fillable = ['name', 'description'];
}