<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:45
 */

namespace Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Models;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'map_markers';
    protected $fillable = ['data', 'name', 'description'];

    protected $translationForeignKey = 'marker_id';
    public $translatedAttributes = ['name', 'description'];

    public function getRawDataAttribute()
    {
        return $this->attributes['data'];
    }

    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }
}