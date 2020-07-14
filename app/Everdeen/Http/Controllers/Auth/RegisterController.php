<?php

namespace Katniss\Everdeen\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Events\UserCreated;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Mail\BaseMailable;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Themes\Plugins\AppSettings\Extension as AppSettingsExtension;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\Extension as SocialIntegrationExtension;

class RegisterController extends ViewController
{
    /*
    |--------------------------------------------------------------------------
    | Register ContactFormAdminController
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'auth';
        $this->redirectTo = homePath('auth/inactive');

        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @param boolean $fromSocial
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $fromSocial = false)
    {
        $rules = [
            'display_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'name' => 'required|max:255|unique:users,name',
            'password' => 'required|confirmed|min:6',
        ];
        if ($fromSocial) {
            $rules['provider'] = 'required';
            $rules['provider_id'] = 'required';
            $rules['url_avatar'] = 'required|url';
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @param boolean $fromSocial
     * @return User|boolean
     */
    protected function create(array $data, $fromSocial = false)
    {
        $social = $fromSocial ? [
            'provider' => $data['provider'],
            'provider_id' => $data['provider_id'],
        ] : null;

        $userRepository = new UserRepository();

        try {
            return $userRepository->create(
                $data['name'],
                $data['display_name'],
                $data['email'],
                $data['password'],
                null,
                true,
                $data['url_avatar'],
                $data['url_avatar'],
                $social
            );

        } catch (KatnissException $ex) {
            return false;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $viewData = AppSettingsExtension::getSharedViewData();
        if (!$viewData->register_enable) {
            abort(404);
        }

        $this->_title(trans('pages.account_register_title'));
        $this->_description(trans('pages.account_register_desc'));

        return $this->_any('register', [
            'social_integration' => SocialIntegrationExtension::getSharedViewData(),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        $errorRdr = redirect(homeUrl('auth/register'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $storedUser = $this->create($request->all());
        if ($storedUser) {
            $this->guard()->login($storedUser);
        } else {
            return $errorRdr->withErrors([trans('auth.register_failed_system_error')]);
        }

        return redirect($this->redirectPath());
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showSocialRegistrationForm(Request $request)
    {
        $viewData = AppSettingsExtension::getSharedViewData();
        if (!$viewData->register_enable) {
            abort(404);
        }
        $viewData = SocialIntegrationExtension::getSharedViewData();
        if (empty($viewData) || !$viewData->social_login_enable) {
            abort(404);
        }

        $this->_title(trans('pages.account_register_title'));
        $this->_description(trans('pages.account_register_desc'));

        return $this->_any('register_social');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function socialRegister(Request $request)
    {
        $viewData = SocialIntegrationExtension::getSharedViewData();
        if (empty($viewData) || !$viewData->social_login_enable) {
            abort(404);
        }

        $validator = $this->validator($request->all(), true);

        $errorRdr = redirect(homeUrl('auth/register/social'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $storedUser = $this->create($request->all(), true);
        if ($storedUser) {

            event(new UserCreated($storedUser, $request->input('password'), true,
                array_merge($this->_params(), [
                    BaseMailable::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                    BaseMailable::EMAIL_TO => $storedUser->email,
                    BaseMailable::EMAIL_TO_NAME => $storedUser->display_name,

                    'provider' => ucfirst($request->input('provider')),
                    'provider_id' => $request->input('provider_id'),
                ])
            ));

            $this->guard()->login($storedUser);
        } else {
            return $errorRdr->withErrors([trans('auth.register_failed_system_error')]);
        }

        return redirect($this->redirectPath());
    }
}
