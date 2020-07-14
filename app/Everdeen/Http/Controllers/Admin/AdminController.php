<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-08
 * Time: 19:16
 */

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Katniss\Everdeen\Http\Controllers\ViewController;

class AdminController extends ViewController
{
    public $_currentUser;

    public function __construct()
    {
        parent::__construct();
        $this->paginationRender->reset();
        $this->paginationRender->setDefault('wrapClass', 'pagination pagination-sm no-margin pull-right');

        $this->middleware(function ($request, $next) {
            view()->share('__current_user__', Auth::user());
            view()->share('__can_be_del__', (function(){
                $roles = Auth::user()->roles;
                foreach ($roles as $role){
                    if($role->id <= 1){
                        return true;
                    }
                }
                return false;
            })() );
            return $next($request);
        });
    }


}