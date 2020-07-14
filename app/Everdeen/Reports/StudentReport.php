<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-21
 * Time: 02:30
 */

namespace Katniss\Everdeen\Reports;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Student;

class StudentReport extends Report
{
    public function getHeader()
    {
        return [
            '#',
            trans('label.display_name'),
            trans('label.name'),
            trans('label.gender'),
            trans('label.birthday'),
            trans('label.email'),
            'Skype ID',
            trans('label.phone'),
            trans('label.address'),
            trans('label.city'),
            trans('label.country'),
            trans('label.nationality'),
        ];
    }

    public function getDataAsFlatArray()
    {
        $flat = [];
        $order = 0;
        foreach ($this->data as $item) {
            $flat[] = [
                ++$order,
                $item->display_name,
                $item->name,
                trans('label.gender_' . $item->gender),
                date('Y-m-d', strtotime($item->date_of_birth)),
                $item->email,
                $item->skype_id,
                '(' . allCountry($item->phone_code, 'calling_code') . ') ' . $item->phone_number,
                $item->address,
                $item->city,
                allCountry($item->country, 'name'),
                allCountry($item->nationality, 'name'),
            ];
        }
        return $flat;
    }

    public function prepare()
    {
        try {
            $this->data = DB::table('students')
                ->select([
                    'users.display_name',
                    'users.name',
                    'users.gender',
                    'users.date_of_birth',
                    'users.email',
                    'users.skype_id',
                    'users.phone_code',
                    'users.phone_number',
                    'users.address',
                    'users.city',
                    'user_settings.country',
                    'users.nationality',
                ])
                ->join('users', 'users.id', '=', 'students.user_id')
                ->join('user_settings', 'user_settings.id', '=', 'users.setting_id')
                ->where('students.status', Student::APPROVED)
                ->orderBy('users.display_name', 'asc')
                ->get();
        } catch (\Exception $exception) {
            throw new KatnissException($exception->getMessage());
        }
    }
}