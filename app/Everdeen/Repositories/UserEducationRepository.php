<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\UserEducation;
use Katniss\Everdeen\Utils\AppConfig;

class UserEducationRepository extends ModelRepository
{
    public function getById($id)
    {
        return UserEducation::findOrFail($id);
    }

    public function getPaged()
    {
        return UserEducation::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return UserEducation::all();
    }

    public function create($userId, $school, $field,
                           $startMonth = null, $startYear = null,
                           $endMonth = null, $endYear = null,
                           $description = '')
    {
        try {
            $work = UserEducation::create([
                'user_id' => $userId,
                'school' => $school,
                'field' => $field,
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

    public function update($userId, $school, $field,
                           $startMonth = null, $startYear = null,
                           $endMonth = null, $endYear = null,
                           $description = '')
    {
        $education = $this->model();
        try {
            $education->update([
                'user_id' => $userId,
                'school' => $school,
                'field' => $field,
                'start_month' => $startMonth,
                'start_year' => $startYear,
                'end_month' => $endMonth,
                'end_year' => $endYear,
                'description' => $description,
            ]);

            return $education;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $education = $this->model();

        try {
            $education->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}