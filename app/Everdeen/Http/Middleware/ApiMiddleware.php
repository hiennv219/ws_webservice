<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 05:02
 */

namespace Katniss\Everdeen\Http\Middleware;

use Closure;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\UserAppRepository;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\SettingsFacade;
use Katniss\Everdeen\Models\UserApp;

class ApiMiddleware
{
    protected function checkUserApp(Request $request)
    {
        if (!$request->has('_app')) {
            abort(404);
        }

        $app = $request->input('_app');
        if (is_string($app)) {
            $app = json_decode($app, true);
            if (empty($app) || empty($app['id']) || empty($app['secret'])) {
                abort(404);
            }
        }
        $userAppRepository = new UserAppRepository();
        $userApp = $userAppRepository->getByIdAndSecret($app['id'], $app['secret']);
        if (empty($userApp)) {
            abort(404);
        }

        auth()->setUser($userApp->user);
    }

    protected function checkSettings(Request $request)
    {
        if (!SettingsFacade::fromApi($request)) {
            SettingsFacade::fromUser($request);
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->checkUserApp($request);
        $this->checkSettings($request);

        setCurrentLocale(SettingsFacade::getLocale());

        return $next($request);
    }
}