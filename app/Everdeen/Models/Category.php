<?php

namespace Katniss\Everdeen\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const TYPE_LINK = 0;
    const TYPE_ARTICLE = 1;
    const TYPE_MEDIA = 2;
    const TYPE_HELP = 3;

    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'categories';
    protected $fillable = ['parent_id', 'order', 'type', 'name', 'slug', 'description'];

    protected $translationForeignKey = 'category_id';
    public $translatedAttributes = ['name', 'slug', 'description'];

    public function getOrderedLinksAttribute()
    {
        return $this->links()->orderBy('order', 'asc')->get();
    }

    public function getOrderedPostsAttribute()
    {
        return $this->posts()->orderBy('order', 'asc')->get();
    }

    public function getOrderedMediaAttribute()
    {
        return $this->media()->orderBy('order', 'asc')->get();
    }

    public function getHtmlDescriptionAttribute()
    {
        if (empty($this->description)) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, htmlspecialchars($this->description))) . '</p>';
    }

    public function scopeOfLink($query)
    {
        return $query->where('type', $this::TYPE_LINK);
    }

    public function parent()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }

    public function links()
    {
        return $this->belongsToMany(Link::class, 'categories_links', 'category_id', 'link_id')
            ->withPivot('order');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'categories_posts', 'category_id', 'post_id')
            ->withPivot('order');
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'categories_media', 'category_id', 'media_id');
    }
}