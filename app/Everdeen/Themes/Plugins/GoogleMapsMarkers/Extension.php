<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Controllers\MapMarkerAdminController;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories\MapMarkerRepository;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Thunder\Shortcode\ShortcodeFacade;

class Extension extends BaseExtension
{
    const NAME = 'google_maps_markers';
    const DISPLAY_NAME = 'Google Maps Markers';
    const DESCRIPTION = 'Enable to manage and embed maps with markers into website';
    const EDITABLE = false;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        _kWidgets([MapMarkerWidget::NAME => MapMarkerWidget::class]);
    }

    public function register()
    {
        addFilter('extra_admin_menu', new CallableObject(function (Menu $menu) {
            if (authUser()->hasRole('admin')) {
                $menu->add( // add a menu item
                    addExtraUrl('admin/google-maps-markers', adminUrl('extra')),
                    trans('google_maps_markers.page_map_markers_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
            }
            return $menu;
        }), 'ext:google_maps_markers:menu');

        addFilter('short_code', new CallableObject(function (ShortcodeFacade $facade) {
            $facade->addHandler('map_marker', function (ShortcodeInterface $s) {
                static $mapMarkerCount = 0;
                ++$mapMarkerCount;
                $id = $s->getParameter('id');
                if (!empty($id)) {
                    try {
                        $layoutId = 'map-marker-' . $mapMarkerCount;
                        return self::enqueueMapMarkerLayout($id, $layoutId, '#' . $layoutId . ' .map');
                    } catch (\Exception $exception) {
                    }
                }
                return Str::format('[map_marker id="{0}"]', $id);
            });
            return $facade;
        }), 'ext:google_maps_markers');

        addExtraRouteResourceTriggers('admin/google-maps-markers', MapMarkerAdminController::class);

        enqueueThemeHeader('<style>.default-google-maps-marker .map{width: 100%;height: 300px;margin:5px 0}</style>', 'google_maps_markers_widget');
        enqueueThemeFooter('<script src="' . _kExternalLink('google-maps-js-api') . '?key=' . config('services.google_maps.api_key') . '&language=' . currentLocaleCode() . '&region=' . settings()->country . '"></script>', 'google_maps_js_api');
        enqueueThemeFooter('<script src="' . libraryAsset('google_maps_markers.js') . '"></script>', 'google_maps_markers_js');
    }

    public static function enqueueMapMarkerLayout($mapMarkerId, $layoutId, $mapSelector)
    {
        $mapMarkerRepository = new MapMarkerRepository($mapMarkerId);
        $mapMarker = $mapMarkerRepository->model();
        $mapMarkerData = $mapMarker->data;
        $mapMarkerName = !empty($mapMarker->name) ? $mapMarker->name : $mapMarkerData->address;
        $mapMarkerDescription = $mapMarker->description;
        $mapMarkerLatitude = $mapMarkerData->lat;
        $mapMarkerLongitude = $mapMarkerData->lng;

        self::enqueueMapMarkerJs(
            $mapSelector,
            $mapMarkerName,
            $mapMarkerLatitude,
            $mapMarkerLongitude,
            $layoutId
        );

        return '<div id="' . $layoutId . '" class="default-google-maps-marker"><div class="map"></div></div>';
    }

    public static function enqueueMapMarkerJs($mapSelector, $name, $lat, $lng, $queueName = '')
    {
        enqueueThemeFooter('<script>
    $(function(){
        createGoogleMapsMarker($(\'' . $mapSelector . '\'), {
            zoom: 15,
            markCenter: true,
            center: {
                lat: ' . $lat . ',
                lng: ' . $lng . '
            },
            centerName: \'' . htmlspecialchars($name, ENT_QUOTES) . '\'
        });
    });
</script>', $queueName);
    }
}