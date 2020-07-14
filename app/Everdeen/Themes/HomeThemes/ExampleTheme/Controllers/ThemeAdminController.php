<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-13
 * Time: 17:27
 */

namespace Katniss\Everdeen\Themes\HomeThemes\ExampleTheme\Controllers;


use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Http\Controllers\Admin\AdminController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories\MapMarkerRepository;

class ThemeAdminController extends AdminController
{
    public function updateOptions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_map_marker_id' => 'required|exists:map_markers,id',
        ]);

        $rdrResponse = redirect(addExtraUrl('admin/themes/example/options', adminUrl('extra')));

        if ($validator->fails()) {
            return $rdrResponse->withErrors($validator);
        }

        homeTheme()->options([
            'default_map_marker_id' => $request->input('default_map_marker_id'),
        ]);

        return $rdrResponse;
    }

    public function options(Request $request)
    {
        $homeTheme = homeTheme();

        $isMapMarkerEnable = isActivatedExtension('google_maps_markers');
        $defaultMapMarkerId = 0;
        $mapMarkers = null;
        if ($isMapMarkerEnable) {
            $mapMarkerRepository = new MapMarkerRepository();
            $mapMarkers = $mapMarkerRepository->getAll();
            $defaultMapMarkerId = $homeTheme->options('default_map_marker_id', 0);
        }

        return $request->getTheme()->resolveExtraView(
            'home_themes.example.admin.options',
            trans('example_theme.page_options_title'),
            trans('example_theme.page_options_desc'),
            [
                'home_theme' => $homeTheme,
                'is_map_marker_enable' => $isMapMarkerEnable,
                'default_map_marker_id' => $defaultMapMarkerId,
                'map_markers' => $mapMarkers,
            ]
        );
    }
}