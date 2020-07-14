<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:45
 */

namespace Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Models;


use Illuminate\Database\Eloquent\Model;

class MapMarkerTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'map_marker_translations';
    protected $fillable = ['name', 'description'];
}