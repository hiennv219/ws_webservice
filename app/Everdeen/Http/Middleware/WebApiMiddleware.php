<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 16:32
 */

namespace Katniss\Everdeen\Http\Middleware;


use Katniss\Everdeen\Http\Request;

class WebApiMiddleware extends ViewMiddleware
{
    protected function checkForceLocale(Request $request)
    {
        return false;
    }
}