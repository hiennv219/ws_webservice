<?php

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;

class WidgetController extends WebApiController
{
    protected $widgetRepository;

    public function __construct()
    {
        parent::__construct();

        $this->widgetRepository = new ThemeWidgetRepository();
    }

    public function sort(Request $request)
    {
        $homeTheme = homeTheme();
        if (!$this->customValidate($request, [
            'placeholder' => 'required|in:' . implode(',', array_keys($homeTheme->placeholders())),
            'widget_ids' => 'required|array|exists:theme_widgets,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->widgetRepository->updateSort($request->input('widget_ids'), $request->input('placeholder'));
        } catch (KatnissException $ex) {
            $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }
}
