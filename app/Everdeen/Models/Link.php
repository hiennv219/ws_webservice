<?php

namespace Katniss\Everdeen\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use Translatable;

    public $useTranslationFallback = true;

    protected $table = 'links';
    protected $fillable = ['image', 'name', 'url', 'description'];

    protected $translationForeignKey = 'link_id';
    public $translatedAttributes = ['name', 'url', 'description'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_links', 'link_id', 'category_id');
    }
}