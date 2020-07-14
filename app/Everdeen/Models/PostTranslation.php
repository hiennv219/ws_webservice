<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-10-31
 * Time: 18:34
 */

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'post_translations';
    protected $fillable = ['title', 'slug', 'description', 'content', 'raw_content'];
}