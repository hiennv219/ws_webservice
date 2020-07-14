<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:45
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls\Models;


use Illuminate\Database\Eloquent\Model;

class PollTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'poll_translations';
    protected $fillable = ['name', 'description'];
}