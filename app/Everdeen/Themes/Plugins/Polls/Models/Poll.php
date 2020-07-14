<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:45
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls\Models;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'polls';
    protected $fillable = ['multi_choice', 'name', 'description'];

    protected $translationForeignKey = 'poll_id';
    public $translatedAttributes = ['name', 'description'];

    public function choices()
    {
        return $this->hasMany(Choice::class, 'poll_id', 'id');
    }

    public function getOrderedChoicesAttribute()
    {
        return $this->choices()->orderBy('order', 'asc')->get();
    }
}