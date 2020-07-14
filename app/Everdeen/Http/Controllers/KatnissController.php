<?php

namespace Katniss\Everdeen\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\AppOptionHelper;
use Katniss\Everdeen\Utils\MultipleLocaleValidationResult;
use Katniss\Http\Controllers\Controller;

class KatnissController extends Controller
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $validationErrors;

    /**
     * @var Request
     */
    protected $currentRequest;

    public function __construct()
    {
        $this->validationErrors = collect([]);
        $this->currentRequest = request();
    }

    /**
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return MultipleLocaleValidationResult
     */
    public function validateMultipleLocaleInputs(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $result = new MultipleLocaleValidationResult();
        $supportedLocaleCodes = supportedLocaleCodesOfInputTabs();
        $localizedInputs = (array)$request->input(AppConfig::KEY_LOCALE_INPUT, []);
        foreach ($localizedInputs as $locale => $inputs) {
            if (!in_array($locale, $supportedLocaleCodes) || isEmptyArray($inputs)) {
                continue;
            }

            $validator = Validator::make($inputs, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                $result->fails($validator);
            } else {
                $result->set($locale, $inputs);
            }
        }


        $locales = $result->getLocales();
        if (!empty($locales) && !in_array(AppConfig::INTERNATIONAL_LOCALE_CODE, $locales)) {
            if (count($locales) != count(supportedLocaleCodesOfInputTabs()) - 1) {
                $result->fails(trans('error.default_locale_inputs_must_be_set'));
            }
        }

        return $result;
    }

    public function customValidate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->validationErrors = collect([]);
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->validationErrors = $validator->errors();
            return false;
        }

        return true;
    }

    protected function getValidationErrors()
    {
        return $this->validationErrors->all();
    }

    protected function getFirstValidationError()
    {
        return $this->validationErrors->first();
    }

    public function extra(Request $request)
    {
        if (!$request->has(AppConfig::KEY_EXTRA_ROUTE)) {
            abort(404);
        }
        return doTrigger('extra_route', $request->input(AppConfig::KEY_EXTRA_ROUTE), [$request]);
    }
}
