<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 21:40
 */

namespace Katniss\Everdeen\Utils;


class InstagramHelper
{
    public static function getRedirectAuthorizeUrl($clientId, $redirectUrl)
    {
        $query = http_build_query([
            'client_id' => $clientId,
            'rdr' => $redirectUrl,
        ]);
        return webApiUrl('instagram/authorize') . '?' . $query;
    }

    public static function getRedirectUrl()
    {
        return webApiUrl('instagram/access-token');
    }

    public static function getAuthorizeUrl($clientId)
    {
        return 'https://api.instagram.com/oauth/authorize/?client_id=' . $clientId . '&scope=basic+public_content&redirect_uri=' . urlencode(self::getRedirectUrl()) . '&response_type=code';
    }

    public static function getAccessTokenUrl()
    {
        return 'https://api.instagram.com/oauth/access_token';
    }
}