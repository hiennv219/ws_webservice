<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-09-28
 * Time: 13:47
 */

namespace Katniss\Everdeen\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Katniss\Everdeen\Utils\AppConfig;

class Authenticate extends BaseAuthenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($guards);

            if (!$this->auth->user()->active) {
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                }

                $activatePath = homePath('auth/activate');
                $inactivePath = homePath('auth/inactive');
                if (!$request->is($activatePath . '/*') && !$request->is($inactivePath)) {
                    return redirect($inactivePath);
                }
            }
        }
        catch (AuthenticationException $exception) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                session([AppConfig::KEY_REDIRECT_URL => $request->fullUrl()]);
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}