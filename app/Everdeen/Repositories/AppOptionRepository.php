<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:22
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\AppOption;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\AppOptionHelper;

class AppOptionRepository extends ModelRepository
{
    public function getById($id)
    {
        return AppOptionHelper::getById($id, true);
    }

    public function getPaged()
    {
        return AppOption::paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return AppOptionHelper::all();
    }

    public function update($rawValue)
    {
        $appOption = $this->model();
        DB::beginTransaction();
        try {
            $appOption->rawValue = $rawValue;
            $appOption->save();
            DB::commit();
            return $appOption;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $appOption = $this->model();
        try {
            $appOption->delete();
            return $appOption;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }
}