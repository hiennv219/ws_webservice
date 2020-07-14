<?php

namespace Katniss\Everdeen\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Katniss\Everdeen\Events\PasswordChanged;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResetPasswordController extends ViewController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset ContactFormAdminController
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $subject;
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'auth';
        $this->subject = '[' . appName() . '] ' . trans('pages.account_password_reset_title');
        $this->redirectTo = homePath('auth/inactive');

        $this->middleware('guest');
    }

    public function getReset(Request $request, $token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException();
        }

        $this->_title(trans('pages.account_password_reset_title'));
        $this->_description(trans('pages.account_password_reset_desc'));

        return $this->_any('reset')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordChanged($user, $password));

        $this->guard()->login($user);
    }
}
