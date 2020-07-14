<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:09
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Katniss\Everdeen\Http\Controllers\ViewController;

class HomepageController extends ViewController
{
    public function index()
    {

        return $this->_any('home');
    }
}