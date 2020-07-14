<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => Katniss\Everdeen\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id'         =>  env('FACEBOOK_CLIENT_ID'),
        'client_secret'     =>  env('FACEBOOK_CLIENT_SECRET'),
        'redirect'          =>  '',
    ],

    'google' => [
        'client_id'         =>  env('GOOGLE_CLIENT_ID'),
        'client_secret'     =>  env('GOOGLE_CLIENT_SECRET'),
        'redirect'          =>  '',
    ],

    'instagram' => [
        'client_id'         =>  env('INSTAGRAM_CLIENT_ID'),
        'client_secret'     =>  env('INSTAGRAM_CLIENT_SECRET'),
        'redirect'          =>  '',
    ],

    'ortc' => [
        'client_key'         =>  env('ORTC_CLIENT_KEY'),
        'client_secret'     =>  env('ORTC_CLIENT_SECRET'),
        'server'          =>  env('ORTC_SERVER'),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ]
];
