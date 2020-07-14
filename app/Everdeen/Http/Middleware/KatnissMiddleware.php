<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 01:04
 */

namespace Katniss\Everdeen\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\AppOptionHelper;

class KatnissMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        AppOptionHelper::load();
        $request->setAuth($this->auth->check(), $this->auth->user());

        return $next($request);
    }
}