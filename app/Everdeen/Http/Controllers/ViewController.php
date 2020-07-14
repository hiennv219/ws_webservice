<?php

namespace Katniss\Everdeen\Http\Controllers;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\DataStructure\Pagination\PaginationRender;

class ViewController extends KatnissController
{
    use ViewControllerTrait;

    protected $paginationRender;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('theme');

        $this->viewPath = '';
        $this->paginationRender = new PaginationRender();
    }

    protected function _rdrUrl(Request $request, $url, &$rdrUrl, &$errorRdrUrl)
    {
        if (empty($url)) {
            $url = $request->fullUrl();
        }
        $errorRdrUrl = $rdrUrl = $url;
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $errorRdrUrl = $rdrUrl = $rdr;
        }
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_ON_ERROR_URL, '');
        if (!empty($rdr)) {
            $errorRdrUrl = $rdr;
        }
    }

    public function error(Request $request, $code)
    {
        $params = $request->all();
        $params['code'] = $code;
        $headers = [];
        if (isset($params['headers'])) {
            $headers = (array)$params['headers'];
            unset($params['headers']);
        }
        if (!isset($params['message'])) {
            $params['message'] = trans('error.unknown');
        }
        if (!isset($params['original_path'])) {
            $params['original_path'] = null;
        }

        $view = $request->getTheme()->resolveErrorView($code, $params['original_path']);
        if ($view !== false) {
            return response()->view($view, $params, $code, $headers);
        }
        return '';
    }

    protected function startWizard()
    {
        $wizardName = uniqid('', true);
        $wizardKey = wizardKey($wizardName);
        $this->currentRequest->session()->put($wizardName, $wizardKey);
        return [
            'name' => $wizardName,
            'key' => $wizardKey,
        ];
    }

    protected function checkWizard($no404 = null)
    {
        $wizardName = $this->currentRequest->input(AppConfig::KEY_WIZARD_NAME);
        $wizardKey = $this->currentRequest->input(AppConfig::KEY_WIZARD_KEY);
        $innerWizardKey = $this->currentRequest->session()->get($wizardName);
        if (!(isValidWizardKey($wizardKey, $wizardName) && $innerWizardKey == $wizardKey)) {
            if ($no404 == null || $no404 === false) {
                abort(404);
            } elseif ($no404 === true) {
                return false;
            }
        }
        return [
            'name' => $wizardName,
            'key' => $wizardKey,
        ];
    }

    protected function endWizard()
    {
        $wizardName = $this->currentRequest->input(AppConfig::KEY_WIZARD_NAME);
        $this->currentRequest->session()->pull($wizardName);
    }
}
