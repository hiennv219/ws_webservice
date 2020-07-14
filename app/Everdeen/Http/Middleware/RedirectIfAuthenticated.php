<?php

namespace Katniss\Everdeen\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Katniss\Http\Middleware\RedirectIfAuthenticated as BaseRedirectIfAuthenticated;

class RedirectIfAuthenticated extends BaseRedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = Auth::guard($guard);
        if ($auth->check()) {
            $redirect_url = null;
            $user = $auth->user();
            $activatePath = homePath('auth/activate');
            $inactivePath = homePath('auth/inactive');
            if (!$user->active) {
                if (!$request->is($activatePath . '/*') && !$request->is($inactivePath)) {
                    $redirect_url = homePath('auth/inactive', [], $user->settings->locale);
                }
            } else {
                $redirect_url = redirectUrlAfterLogin($user);
            }

            if (!empty($redirect_url)) {
                return redirect($redirect_url);
            }
        }

        return $next($request);
    }
}
