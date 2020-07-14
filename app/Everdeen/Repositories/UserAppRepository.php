<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-12
 * Time: 18:32
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Models\UserApp;
use Katniss\Everdeen\Utils\AppConfig;

class UserAppRepository extends ModelRepository
{
    public function getById($id)
    {
        return UserApp::findOrFail($id);
    }

    public function getPaged()
    {
        return UserApp::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return UserApp::all();
    }

    public function getByIdAndSecret($id, $secret)
    {
        return UserApp::where('id', $id)->where('secret', $secret)->first();
    }
}