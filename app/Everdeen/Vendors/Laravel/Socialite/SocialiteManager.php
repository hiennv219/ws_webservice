<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-07-02
 * Time: 12:50
 */

namespace Katniss\Everdeen\Vendors\Laravel\Socialite;

use Katniss\Everdeen\Vendors\Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\SocialiteManager as BaseSocialiteManager;

class SocialiteManager extends BaseSocialiteManager
{
    public function createFacebookDriver()
    {
        $config = $this->app['config']['services.facebook'];

        return $this->buildProvider(
            FacebookProvider::class, $config
        );
    }
}