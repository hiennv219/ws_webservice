<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-15
 * Time: 01:57
 */

namespace Katniss\Everdeen\Http\Controllers;


use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\AppConfig;

trait CallbackTrait
{
    private function getCallbackRedirectUrl(Request $request)
    {
        if ($request->session()->has(AppConfig::KEY_CALLBACK_REDIRECT_URL)) {
            $redirectUri = $request->session()->get(AppConfig::KEY_CALLBACK_REDIRECT_URL);
            $request->session()->remove(AppConfig::KEY_CALLBACK_REDIRECT_URL);
            return $redirectUri;
        }
        return null;
    }

    private function setCallbackRedirectUrl(Request $request, $url)
    {
        $request->session()->put(AppConfig::KEY_CALLBACK_REDIRECT_URL, $url);
    }
}