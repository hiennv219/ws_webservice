<?php

namespace Katniss\Everdeen\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\AppSettings\Extension as AppSettingsExtension;

class ForgotPasswordController extends ViewController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset ContactFormAdminController
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'auth';

        $this->middleware('guest');
    }

    public function getEmail()
    {
        $this->_title(trans('pages.account_password_reset_title'));
        $this->_description(trans('pages.account_password_reset_desc'));

        return $this->_any('password', [
            'app_settings' => AppSettingsExtension::getSharedViewData(),
        ]);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEmail(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }
}
