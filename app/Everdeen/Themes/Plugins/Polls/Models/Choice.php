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

class Choice extends Model
{
    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'poll_choices';
    protected $fillable = ['poll_id', 'votes', 'order', 'name'];

    protected $translationForeignKey = 'choice_id';
    public $translatedAttributes = ['name'];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id', 'id');
    }
}