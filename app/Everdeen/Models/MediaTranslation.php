<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-10-31
 * Time: 18:34
 */

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class MediaTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'media_translations';
    protected $fillable = ['title', 'description'];
}