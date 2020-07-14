<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\UserWork;
use Katniss\Everdeen\Utils\AppConfig;

class UserWorkRepository extends ModelRepository
{
    public function getById($id)
    {
        return UserWork::findOrFail($id);
    }

    public function getPaged()
    {
        return UserWork::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return UserWork::all();
    }

    public function create($userId, $company, $position,
                           $startMonth = 0, $startYear = 0,
                           $endMonth = 0, $endYear = 0,
                           $description = '')
    {
        try {
            $work = UserWork::create([
                'user_id' => $userId,
                'company' => $company,
                'position' => $position,
                'start_month' => $startMonth,
                'start_year' => $startYear,
                'end_month' => $endMonth,
                'end_year' => $endYear,
                'description' => $description,
            ]);

            return $work;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($userId, $company, $position,
                           $startMonth = 0, $startYear = 0,
                           $endMonth = 0, $endYear = 0,
                           $description = '')
    {
        $work = $this->model();
        try {
            $work->update([
                'user_id' => $userId,
                'company' => $company,
                'position' => $position,
                'start_month' => $startMonth,
                'start_year' => $startYear,
                'end_month' => $endMonth,
                'end_year' => $endYear,
                'description' => $description,
            ]);

            return $work;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $work = $this->model();

        try {
            $work->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}