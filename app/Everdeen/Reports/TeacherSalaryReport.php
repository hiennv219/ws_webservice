<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-13
 * Time: 16:10
 */

namespace Katniss\Everdeen\Reports;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Models\SalaryJump;
use Katniss\Everdeen\Repositories\SalaryJumpRepository;

class TeacherSalaryReport extends Report
{
    protected $year;
    protected $month;

    /**
     * @var SalaryJump
     */
    protected $lastSalaryJump;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;

        parent::__construct();
    }

    public function getLastSalaryJump()
    {
        return $this->lastSalaryJump;
    }

    public function getHeader()
    {
        return [
            '#',
            trans('label.display_name'),
            trans('label.email'),
            'Skype ID',
            trans('label.phone'),
            trans('label.classroom'),
            trans('label.teaching_hours'),
            trans('label.hours_confirmed'),
            trans('label.hours_salary') . ' (' . settings()->currency . ' / 1 ' . trans_choice('label.hour_lc', 1) . ')',
            trans('label.total') . ' (' . settings()->currency . ')',
        ];
    }

    public function getDataAsFlatArray()
    {
        $flat = [];
        $order = 0;
        foreach ($this->data as $item) {
            $flat[] = [
                ++$order,
                $item['teacher']['display_name'],
                $item['teacher']['email'],
                $item['teacher']['skype_id'],
                $item['teacher']['phone'],
                $item['class_name'],
                $item['hours'],
                $item['hours_confirmed'],
                $item['salary_jump'],
                $item['total'],
            ];
        }
        return $flat;
    }

    public function prepare()
    {
        try {
            $salaryJumpRepository = new SalaryJumpRepository();
            $this->lastSalaryJump = $salaryJumpRepository->getLast($this->year, $this->month);
            if (empty($this->lastSalaryJump)) {
                return;
            }

            $classTimes = DB::table('class_times')
                ->select([
                    DB::raw('SUM(' . DB::getTablePrefix() . 'class_times.hours) as hours'),
                    'classrooms.teacher_id',
                    DB::raw('CONCAT('.DB::getTablePrefix().'classrooms.id, \'::\', '.DB::getTablePrefix().'classrooms.name) as friendly_name'),
                    'classrooms.hours_salary',
                    'classrooms.id'
                ])
                ->join('classrooms', 'classrooms.id', '=', 'class_times.classroom_id')
                ->whereYear('class_times.start_at', $this->year)
                ->whereMonth('class_times.start_at', $this->month)
                ->where('class_times.confirmed', ClassTime::CONFIRMED_TRUE)
                ->groupBy('classrooms.teacher_id', 'classrooms.id', 'classrooms.name','classrooms.hours_salary','classrooms.hours')
                ->get();

            $classTimesNotConfirmed = DB::table('class_times')
                ->select([
                    DB::raw('SUM(' . DB::getTablePrefix() . 'class_times.hours) as hours'),
                    'classrooms.teacher_id',
                    DB::raw('CONCAT('.DB::getTablePrefix().'classrooms.id, \'::\', '.DB::getTablePrefix().'classrooms.name) as friendly_name'),
                    'classrooms.hours_salary',
                    'classrooms.id'
                ])
                ->join('classrooms', 'classrooms.id', '=', 'class_times.classroom_id')
                ->whereYear('class_times.start_at', $this->year)
                ->whereMonth('class_times.start_at', $this->month)
                ->groupBy('classrooms.teacher_id', 'classrooms.id', 'classrooms.name','classrooms.hours_salary','classrooms.hours')
                ->get();

            if ($classTimes->count() <= 0) {
                return;
            }

            $teachers = DB::table('teachers')
                ->select([
                    'users.id',
                    'users.name',
                    'users.display_name',
                    'users.email',
                    'users.skype_id',
                    'users.phone_number',
                    'users.phone_code'
                ])
                ->join('users', 'users.id', '=', 'teachers.user_id')
                ->whereIn('teachers.user_id', $classTimes->pluck('teacher_id')->all())
                ->get();
            $total = 0;
            foreach ($classTimes as $classTime) {
                $teacher = $teachers->where('id', $classTime->teacher_id)->first();
                $hoursNotConfirm = 0;
                foreach ($classTimesNotConfirmed as $classTimeNotConfirmed){
                    if($classTime->teacher_id == $classTimeNotConfirmed->teacher_id && $classTime->id == $classTimeNotConfirmed->id){
                        $hoursNotConfirm = $classTimeNotConfirmed->hours;
                    }
                }
                $this->data[] = [
                    'teacher' => [
                        'id' => $teacher->id,
                        'home_url' => homeUrl('teachers/{id}', ['id' => $teacher->id]),
                        'name' => $teacher->name,
                        'display_name' => $teacher->display_name,
                        'email' => $teacher->email,
                        'skype_id' => empty($teacher->skype_id) ? '' : $teacher->skype_id,
                        'phone' => empty($teacher->phone_code) || empty($teacher->phone_number) ?
                            '' : '(+' . allCountry($teacher->phone_code, 'calling_code') . ') ' . $teacher->phone_number,
                    ],
                    'class_name' => $classTime->friendly_name,
                    'hours' => toFormattedNumber($hoursNotConfirm),
                    'hours_confirmed' => toFormattedNumber($classTime->hours),
                    'salary_jump' => toFormattedCurrency($classTime->hours_salary),
                    'total' => toFormattedCurrency($classTime->hours_salary * $hoursNotConfirm),
                ];
                $total += $classTime->hours_salary * $classTime->hours;
            }
        } catch (\Exception $ex) {
            throw new KatnissException($ex->getMessage());
        }
    }
}