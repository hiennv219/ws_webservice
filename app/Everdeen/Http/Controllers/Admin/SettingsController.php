<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Http\Request;

class SettingsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'my_settings';
    }

    public function index(Request $request)
    {
        $this->_title(trans('pages.my_settings_title'));
        $this->_description(trans('pages.my_settings_desc'));

        return $this->_view([
            'settings' => app('settings'),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|in:' . implode(',', allCountryCodes()),
            'locale' => 'required|in:' . implode(',', allSupportedLocaleCodes()),
            'timezone' => 'required',
            'currency' => 'required|in:' . implode(',', allCurrencyCodes()),
            'number_format' => 'required|in:' . implode(',', allNumberFormats()),
            'first_day_of_week' => 'required|integer|min:0|max:6',
            'long_date_format' => 'required|integer|min:0|max:3',
            'short_date_format' => 'required|integer|min:0|max:3',
            'long_time_format' => 'required|integer|min:0|max:4',
            'short_time_format' => 'required|integer|min:0|max:4',
        ]);

        if ($validator->fails()) {
            return redirect(meUrl('settings'))
                ->withErrors($validator);
        }

        $settings = settings();
        $settings->setLocale($request->input('locale'));
        $settings->setCountry($request->input('country'));
        $settings->setTimezone($request->input('timezone'));
        $settings->setCurrency($request->input('currency'));
        $settings->setNumberFormat($request->input('number_format'));
        $settings->setFirstDayOfWeek($request->input('first_day_of_week'));
        $settings->setLongDateFormat($request->input('long_date_format'));
        $settings->setShortDateFormat($request->input('short_date_format'));
        $settings->setLongTimeFormat($request->input('long_time_format'));
        $settings->setShortTimeFormat($request->input('short_time_format'));

        $settings->storeUser();
        $settings->storeSession();
        return $settings->storeCookie(redirect(meUrl('settings', [], $settings->getLocale())));
    }

    public function updateTimezone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timezone' => 'required',
        ]);

        $this->_rdrUrl($request, meUrl('settings'), $rdrUrl, $errRdrUrl);

        if ($validator->fails()) {
            return redirect($errRdrUrl)
                ->withErrors($validator);
        }

        $settings = settings();
        $settings->setTimezone($request->input('timezone'));

        $settings->storeUser();
        $settings->storeSession();

        return $settings->storeCookie(redirect($rdrUrl));
    }
}
