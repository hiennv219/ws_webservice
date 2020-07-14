<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 22:36
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Classroom;
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Repositories\ClassTimeRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassroomController extends WebApiController
{
    protected $classroomRepository;

    public function __construct()
    {
        parent::__construct();

        $this->classroomRepository = new ClassroomRepository();
    }

    public function show(Request $request, $id)
    {
        if ($request->has('monthly_class_times')) {
            return $this->monthlyClassTimes($request, $id);
        }

        return $this->responseFail();
    }

    public function monthlyClassTimes(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                	abort(404);
                }
            }
        } elseif ($user->hasRole('student')) {
            if ($classroom->student_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                	abort(404);
                }
            }
        } elseif ($user->hasRole('supporter')) {
            if ($classroom->supporter_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                	abort(404);
                }
            }
        }

        if (!$this->customValidate($request, [
            'year' => 'required',
            'month' => 'required',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        $classTimesOfMonth = $classroom->getClassTimesOfMonth(intval($request->input('year')), intval($request->input('month')));
        $countAllClassTimes = $classroom->getCountTillClassTimesOfMonth(intval($request->input('year')), intval($request->input('month')));
        $countLastMonthClassTimes = $classTimesOfMonth->count();
        $hasPreviousMonthClassTimes = false;
        $previousYear = false;
        $previousMonth = false;
        if ($countLastMonthClassTimes > 0) {
            $datetime = new \DateTime($classTimesOfMonth[0]->start_at);
            $datetime->sub(new \DateInterval('P1M'));
            $previousYear = $datetime->format('Y');
            $previousMonth = $datetime->format('m');
            $lastClassTimeBeforeMonth = $classroom->getLastClassTimeBeforeMonth($previousYear, $previousMonth);
            $hasPreviousMonthClassTimes = !empty($lastClassTimeBeforeMonth);
            if($hasPreviousMonthClassTimes) {
                $datetime = new \DateTime($lastClassTimeBeforeMonth->start_at);
                $previousYear = $datetime->format('Y');
                $previousMonth = $datetime->format('m');
            }
        }

        return $this->responseSuccess([
            'class_times' => $classTimesOfMonth->map(function (ClassTime $classTime) use ($classroom) {
                $reviews = $classTime->reviews;
                $teacherReview = $reviews->where('user_id', $classroom->teacher_id)->first();
                $studentReview = $reviews->where('user_id', $classroom->student_id)->first();
                return !$classTime->isPeriodic ? [
                    'id' => $classTime->id,
                    'is_periodic' => $classTime->isPeriodic,
                    'subject' => $classTime->subject,
                    'hours' => $classTime->hours,
                    'duration' => $classTime->duration . ' ' . trans_choice('label.hour_lc', $classTime->hours),
                    'start_at' => $classTime->inverseFullFormattedStartAt,
                    'month_year_start_at' => date('m-Y', strtotime($classTime->start_at)),
                    'trans_month_year_start_at' => transMonthYear($classTime->start_at),
                    'content' => $classTime->content,
                    'html_content' => $classTime->html_content,
                    'teacher_review' => empty($teacherReview) ? null : [
                        'id' => $teacherReview->id,
                        'class_time_id' => $classTime->id,
                        'user_id' => $teacherReview->user_id,
                        'rate' => $teacherReview->rate,
                        'trans_rate' => transRate($teacherReview->rate),
                        'review' => $teacherReview->review,
                        'html_review' => $teacherReview->htmlReview,
                    ],
                    'student_review' => empty($studentReview) ? null : [
                        'id' => $studentReview->id,
                        'class_time_id' => $classTime->id,
                        'user_id' => $studentReview->user_id,
                        'rate' => $studentReview->rate,
                        'trans_rate' => transRate($studentReview->rate),
                        'review' => $studentReview->review,
                        'html_review' => $studentReview->htmlReview,
                    ],
                    'confirmed' => $classTime->isConfirmed,
                ] : [
                    'id' => $classTime->id,
                    'is_periodic' => $classTime->isPeriodic,
                    'trans_after' => trans('label._periodic_class_review', ['after' => $classTime->subject]),
                    'month_year_start_at' => date('m-Y', strtotime($classTime->start_at)),
                    'teacher_review' => empty($teacherReview) ? null : [
                        'id' => $teacherReview->id,
                        'class_time_id' => $classTime->id,
                        'user_id' => $teacherReview->user_id,
                        'rates' => $teacherReview->rates,
                        'trans_rates' => transRate($teacherReview->rates),
                        'trans_rate_names' => transRateName($teacherReview->rates, true),
                        'review' => $teacherReview->review,
                        'html_review' => $teacherReview->htmlReview,
                    ],
                    'student_review' => empty($studentReview) ? null : [
                        'id' => $studentReview->id,
                        'class_time_id' => $classTime->id,
                        'user_id' => $studentReview->user_id,
                        'rates' => $studentReview->rates,
                        'trans_rates' => transRate($studentReview->rates),
                        'trans_rate_names' => transRateName($studentReview->rates, false),
                        'review' => $studentReview->review,
                        'html_review' => $studentReview->htmlReview,
                    ],
                ];
            }),
            'max_rate' => count(_k('rates')),
            'stats' => [
                'sum_hours' => $classTimesOfMonth->sum('hours'),
                'month_year' => date('m-Y', strtotime($classTimesOfMonth[0]->start_at)),
                'trans_month_year' => transMonthYear($classTimesOfMonth[0]->start_at),
            ],
            'class_time_order_end' => $countAllClassTimes,
            'has_previous_month_class_times' => $hasPreviousMonthClassTimes,
            'previous_year' => $previousYear,
            'previous_month' => $previousMonth,
        ]);
    }

    /**
     * Only for Roles: Teacher/Manager/Admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->has('only_name')) {
            return $this->updateName($request, $id);
        }

        return $this->responseFail();
    }

    public function updateName(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        if (!$classroom->isOpening) {
            abort(404);
        }
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                	abort(404);
                }
            }
        }

        if (!$this->customValidate($request, [
            'name' => 'required|max:255',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $classroom = $this->classroomRepository->updateName(
                $request->input('name')
            );

            return $this->responseSuccess([
                'classroom' => [
                    'name' => $classroom->name,
                ]
            ]);
        } catch (KatnissException $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }
}