<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:09
 */

namespace Katniss\Everdeen\Models;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    const TYPE_PHOTO = 0;
    const TYPE_VIDEO = 1;

    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'media';
    protected $fillable = ['url', 'type', 'title', 'description'];

    protected $translationForeignKey = 'media_id';
    public $translatedAttributes = ['title', 'description'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_media', 'media_id', 'category_id');
    }
}