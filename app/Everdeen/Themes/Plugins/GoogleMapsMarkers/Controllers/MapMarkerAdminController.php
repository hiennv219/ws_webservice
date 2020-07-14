<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:43
 */

namespace Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Controllers;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\Admin\AdminController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\PluginControllerTrait;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Extension;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Models\MapMarker;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories\MapMarkerRepository;

class MapMarkerAdminController extends AdminController
{
    use PluginControllerTrait;

    protected $mapMarkerRepository;

    public function __construct()
    {
        parent::__construct();

        $this->mapMarkerRepository = new MapMarkerRepository();
    }

    public function index(Request $request)
    {
        $mapMarkers = $this->mapMarkerRepository->getPaged();

        return $request->getTheme()->resolveExtraView(
            $this->_extra('index', Extension::NAME),
            trans('google_maps_markers.page_map_markers_title'),
            trans('google_maps_markers.page_map_markers_desc'),
            [
                'map_markers' => $mapMarkers,
                'pagination' => $this->paginationRender->renderByPagedModels($mapMarkers),
                'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            ]
        );
    }

    public function create(Request $request)
    {
        return $request->getTheme()->resolveExtraView(
            $this->_extra('create', Extension::NAME),
            trans('google_maps_markers.page_map_markers_title'),
            trans('google_maps_markers.page_map_markers_desc')
        );
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'sometimes|nullable|max:255',
            'description' => 'sometimes|nullable|max:255',
        ]);

        $this->_rdrUrl($request, adminUrl(), $rdrUrl, $errorRdrUrl);

        if ($validateResult->isFailed()) {
            return redirect($errorRdrUrl)
                ->withInput()
                ->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.lat' => 'required|numeric',
            'data.lng' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect($errorRdrUrl)
                ->withInput()
                ->withErrors($validator);
        }

        try {
            $this->mapMarkerRepository->create(
                $request->input('data'),
                $validateResult->getLocalizedInputs()
            );
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)
                ->withInput()
                ->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function edit(Request $request, $id)
    {
        $mapMarker = $this->mapMarkerRepository->model($id);

        $this->_title([trans('pages.admin_links_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_links_desc'));

        return $request->getTheme()->resolveExtraView(
            $this->_extra('edit', Extension::NAME),
            trans('google_maps_markers.page_map_markers_title'),
            trans('google_maps_markers.page_map_markers_desc'),
            [
                'map_marker' => $mapMarker,
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $this->mapMarkerRepository->model($id);

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'sometimes|nullable|max:255',
            'description' => 'sometimes|nullable|max:255',
        ]);

        $this->_rdrUrl($request, adminUrl(), $rdrUrl, $errorRdrUrl);

        if ($validateResult->isFailed()) {
            return redirect($rdrUrl)->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.lat' => 'required|numeric',
            'data.lng' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect($rdrUrl)->withErrors($validator);
        }

        try {
            $this->mapMarkerRepository->update(
                $request->input('data'),
                $validateResult->getLocalizedInputs()
            );
        } catch (KatnissException $ex) {
            return redirect($rdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function destroy(Request $request, $id)
    {
        $this->mapMarkerRepository->model($id);

        $this->_rdrUrl($request, null, $rdrUrl, $errorRdrUrl);

        try {
            $this->mapMarkerRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}