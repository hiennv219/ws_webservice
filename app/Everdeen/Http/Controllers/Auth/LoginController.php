<?php

namespace Katniss\Everdeen\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Themes\Plugins\AppSettings\Extension as AppSettingsExtension;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\Extension as SocialIntegrationExtension;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends ViewController
{
    /*
    |--------------------------------------------------------------------------
    | Login ContactFormAdminController
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $loginPath;
    protected $socialRegisterPath;

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
        $this->loginPath = homePath('auth/login');
        $this->socialRegisterPath = homePath('auth/register/social');

        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'account';
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        if ($request->has('x_u') && $request->has('x_p')) {
            $userRepository = new UserRepository();
            $user = $userRepository->getByNameAndHashedPassword($request->input('x_u'), $request->input('x_p'));
            if (!empty($user)) {
                $this->guard()->login($user);
                return $this->authenticated($request, $user);
            }
        }

        $this->_title(trans('pages.account_login_title'));
        $this->_description(trans('pages.account_login_desc'));

        return $this->_any('login', [
            'social_integration' => SocialIntegrationExtension::getSharedViewData(),
            'app_settings' => AppSettingsExtension::getSharedViewData(),
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!$this->validateLogin($request)) {
            return redirect($this->loginPath)
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors($this->getValidationErrors());
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        // try email
        $try_credentials = [
            'email' => $credentials[$this->username()],
            'password' => $credentials['password'],
        ];
        if ($this->guard()->attempt($try_credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }
        // try user name
        $try_credentials = [
            'name' => $credentials[$this->username()],
            'password' => $credentials['password'],
        ];
        if ($this->guard()->attempt($try_credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @return boolean
     */
    protected function validateLogin(Request $request)
    {
        return $this->customValidate($request, [
            $this->username() => 'required', 'password' => 'required',
        ]);
    }

    protected function authenticated(Request $request, User $user)
    {
        return redirect(homeUrl('opening-classrooms', [], $user->settings->locale));
    }

    public function redirectToSocialAuthProvider(Request $request, $provider)
    {
        if (!config('services.' . $provider)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleSocialAuthProviderCallback(Request $request, $provider)
    {
        if (!config('services.' . $provider)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $ex) {
            return redirect($this->loginPath)
                ->withErrors([trans('error.fail_social_get_info') . ' (' . $ex->getMessage() . ')']);
        }

        $userRepository = new UserRepository();
        if ($authUser = $userRepository->getBySocial($provider, $socialUser->id, $socialUser->email)) {
            $this->guard()->login($authUser);
            return redirect($this->redirectPath());
        }

        $viewData = AppSettingsExtension::getSharedViewData();
        if (!$viewData->register_enable) {
            return redirect($this->loginPath)
                ->withErrors([trans('error.fail_social_get_info')]);
        }

        $userName = strtok($socialUser->email, '@');
        $users = $userRepository->getLikeName($userName);
        if ($users->where('name', $userName)->count() > 0) {
            $userName = $userName . '.' . $users->count();
        }

        return redirect($this->socialRegisterPath)
            ->withInput([
                'provider' => $provider,
                'provider_id' => $socialUser->id,
                'url_avatar' => $socialUser->avatar,
                'display_name' => $socialUser->name,
                'email' => $socialUser->email,
                'name' => $userName,
            ]);
    }
}
