<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-10-31
 * Time: 18:34
 */

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'category_translations';
    protected $fillable = ['name', 'slug', 'description'];
}