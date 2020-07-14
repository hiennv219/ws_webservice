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

class ClassroomStudentReport extends Report
{
    protected $classRoomId;
    protected $teacherId;
    protected $sumHours;

    public function __construct($classroomId, $teacherId)
    {
        $this->classRoomId = $classroomId;
        $this->teacherId = $teacherId;

        parent::__construct();
    }

    public function getSumHours()
    {
        return $this->sumHours;
    }

    public function getHeader()
    {
        return [
            '#',
            trans('label.start_at'),
            trans('label.class_duration') . ' (' . trans_choice('label.hour', 2) . ')',
            trans('label.subject'),
            trans('label.content'),
            trans('label.teacher_review'),
        ];
    }

    public function getDataAsFlatArray()
    {
        $flat = [];
        $order = 0;
        foreach ($this->data as $item) {
            $flat[] = [
                ++$order,
                $item['start_at'],
                $item['duration'],
                $item['subject'],
                $item['content'],
                $item['teacher_review'],
            ];
        }
        return $flat;
    }

    public function prepare()
    {
        try {
            $classTimes = ClassTime::with('reviews')->where('classroom_id', $this->classRoomId)->get();
            $this->sumHours = $classTimes->sum('hours');
            $maxRate = count(_k('rates'));
            $this->data = $classTimes->map(function (ClassTime $classTime) use ($maxRate) {
                $review = $classTime->reviews->where('user_id', $this->teacherId)->first();
                $teacherReview = trans('label.no_teacher_review_yet');
                if ($classTime->type == ClassTime::TYPE_PERIODIC) {
                    if (!empty($review)) {
                        $rates = $review->rates;
                        $transRates = transRate($rates);
                        $teacherReview = [];
                        foreach ($rates as $name => $rate) {
                            $teacherReview[] = trans('label.student_' . $name . '_rate') . ': ' . $rate . '/' . $maxRate . ' (' . $transRates[$name] . ')';
                        }
                        $teacherReview = implode(', ', $teacherReview) . "\r\n" . $review->review;
                    }
                    return [
                        'duration' => '',
                        'start_at' => '',
                        'subject' => trans('label._periodic_class_review', ['after' => $classTime->subject]),
                        'content' => '',
                        'teacher_review' => $teacherReview,
                    ];
                }

                if (!empty($review)) {
                    $teacherReview = trans('label.rating') . ': ' . $review->rate . '/' . $maxRate . ' (' . transRate($review->rate) . ')' . "\r\n" . $review->review;
                }
                return [
                    'duration' => $classTime->duration,
                    'start_at' => $classTime->formattedStartAt,
                    'subject' => $classTime->subject,
                    'content' => $classTime->content,
                    'teacher_review' => $teacherReview,
                ];
            });
        } catch (\Exception $ex) {
            throw new KatnissException($ex->getMessage());
        }
    }
}