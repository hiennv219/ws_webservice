<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 21:49
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Http\Controllers\CallbackTrait;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Extension;
use Katniss\Everdeen\Utils\InstagramHelper;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\Extension as SocialIntegrationExtension;

class InstagramController extends WebApiController
{
    use CallbackTrait;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('theme')->only('getAccessToken');
    }

    public function getAccessToken(Request $request)
    {
        $redirectUrl = $this->getCallbackRedirectUrl($request);

        $code = $request->input('code');
        if (empty($code)) {
            if (empty($redirectUrl)) {
                return $this->responseFail();
            } else {
                return redirect($redirectUrl);
            }
        }

        $shared = Extension::getSharedData(SocialIntegrationExtension::NAME);

        $client = new \GuzzleHttp\Client();
        $response = $client->post(InstagramHelper::getAccessTokenUrl(), [
            'form_params' => [
                'client_id' => $shared->instagramClientId,
                'client_secret' => $shared->instagramClientSecret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => InstagramHelper::getRedirectUrl(),
                'code' => $code,
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        $data = [
            'code' => $code,
            'access_token' => $result['access_token'],
        ];
        if (empty($redirectUrl)) {
            return $this->responseSuccess($data);
        } else {
            return redirect(strpos($redirectUrl, '?') === false ?
                $redirectUrl . '?' . http_build_query($data)
                : $redirectUrl . '&' . http_build_query($data));
        }
    }

    public function getAuthorize(Request $request)
    {
        $this->setCallbackRedirectUrl($request, $request->input('rdr'));
        return redirect(InstagramHelper::getAuthorizeUrl($request->input('client_id')));
    }
}