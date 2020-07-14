<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 21:49
 */

namespace Katniss\Everdeen\Themes\Plugins\SocialIntegration\Controllers;

use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\InstagramWallWidget;

class InstagramWallWidgetWebApiController extends WebApiController
{
    public function show(Request $request, $id)
    {
        $widgetRepository = new ThemeWidgetRepository($id);
        $themeWidget = $widgetRepository->model();
        if ($themeWidget->name != InstagramWallWidget::NAME || !$themeWidget->checkWidget()) {
            abort(404);
        }

        $data = $themeWidget->widget()->getInstagramData($request->input('max_id', null));

        return $this->responseSuccess($data);
    }
}