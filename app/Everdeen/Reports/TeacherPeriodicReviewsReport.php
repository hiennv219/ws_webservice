<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-13
 * Time: 18:58
 */

namespace Katniss\Everdeen\Reports;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\ClassTime;

class TeacherPeriodicReviewsReport extends Report
{
    protected $teacherIds;

    public function __construct($teacherIds)
    {
        $this->teacherIds = (array)$teacherIds;

        parent::__construct();
    }

    public function getHeader()
    {
        // TODO: Implement getHeader() method.
    }

    public function getDataAsFlatArray()
    {
        // TODO: Implement getDataAsFlatArray() method.
    }

    public function prepare()
    {
        try {
            $studentReviews = DB::table('classrooms')
                ->select([
                    'classrooms.teacher_id',
                    'class_times.type',
                    'reviews.user_id',
                    'reviews.rate',
                    'reviews.rates',
                ])
                ->join('class_times', 'class_times.classroom_id', '=', 'classrooms.id')
                ->join('class_times_reviews', 'class_times_reviews.class_time_id', '=', 'class_times.id')
                ->join('reviews', 'reviews.id', '=', 'class_times_reviews.review_id')
                ->where('class_times.type', ClassTime::TYPE_PERIODIC)
                ->whereIn('classrooms.teacher_id', $this->teacherIds)
                ->whereNotIn('reviews.user_id', $this->teacherIds)
                ->get();
            $periodicReviews = $studentReviews->map(function ($item) {
                $rates = unserialize($item->rates);
                $rates['teacher_id'] = $item->teacher_id;
                $rates['user_id'] = $item->user_id;
                return $rates;
            });
            foreach ($this->teacherIds as $teacherId) {
                $this->data[$teacherId] = $periodicReviews->where('teacher_id', $teacherId);
            }
        } catch (\Exception $exception) {
            throw new KatnissException($exception->getMessage());
        }
    }
}