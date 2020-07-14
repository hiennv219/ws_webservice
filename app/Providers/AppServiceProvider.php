<?php

namespace Katniss\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Katniss\Everdeen\Utils\ReCaptcha;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('reCaptcha', function ($attribute, $value, $parameters, $validator) {
            $reCaptcha = new ReCaptcha("6Lc8BYcUAAAAAMAKVOr1Fu95LLDQs5XdhCzFlIEk");
            $response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"],$value);
            return $response != null && $response->success;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
