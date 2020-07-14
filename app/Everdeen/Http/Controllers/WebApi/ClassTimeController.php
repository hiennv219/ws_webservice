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
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Repositories\ClassTimeRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassTimeController extends WebApiController
{
    protected $classTimeRepository;

    public function __construct()
    {
        parent::__construct();

        $this->classTimeRepository = new ClassTimeRepository();

        $this->middleware('theme')->only('store');
    }

    /**
     * Only for Roles: Teacher/Manager/Admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $classroomRepository = new ClassroomRepository($request->input('classroom'));
        $classroom = $classroomRepository->model();
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

        if ($classroom->spentTime >= $classroom->hours) {
            return $this->responseFail(trans('error.classroom_has_enough_time'));
        }

        if (!$this->customValidate($request, [
            'subject' => 'required|max:255',
            'duration' => ['required', 'regex:' . NumberFormatHelper::getInstance()->getRegEx(8, 2)],
            'start_at' => 'required|date_format:' . DateTimeHelper::compoundFormat('shortDate', ' ', 'shortTime'),
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        $startAt = DateTimeHelper::getInstance()->convertToDatabaseFormat(
            DateTimeHelper::compoundFormat('shortDate', ' ', 'shortTime'), $request->input('start_at'));
        $lastClassTime = $classroom->lastClassTime;
        if (!empty($lastClassTime) && $lastClassTime->start_at > $startAt) {
            return $this->responseFail(trans('error.new_class_time_must_before_last_class_time'));
        }

        $duration = NumberFormatHelper::getInstance()->fromFormat($request->input('duration'));
        $overTime = $classroom->spentTime + $duration - $classroom->hours;
        if ($overTime > 0) {
            return $this->responseFail(trans('error.new_class_time_must_be_in_classroom_time', [
                'overtime' => toFormattedNumber($overTime) . ' ' . trans_choice('label.hour_lc', $overTime),
            ]));
        }

        try {
            $classTimes = $this->classTimeRepository->create(
                $classroom->id,
                $request->input('subject'),
                $duration,
                $startAt,
                $request->input('content', '')
            );

            if ($classTimes instanceof ClassTime) {
                $classTime = $classTimes;
                $classTimePeriodic = null;
            } else {
                $classTime = $classTimes[0];
                $classTimePeriodic = $classTimes[1];
            }

            return $this->responseSuccess([
                'class_time' => [
                    'order' => $classroom->countClassTimes,
                    'id' => $classTime->id,
                    'subject' => $classTime->subject,
                    'hours' => $classTime->hours,
                    'duration' => $classTime->duration . ' ' . trans_choice('label.hour_lc', $classTime->hours),
                    'start_at' => $classTime->inverseFullFormattedStartAt,
                    'month_year_start_at' => date('m-Y', strtotime($classTime->start_at)),
                    'trans_month_year_start_at' => transMonthYear($classTime->start_at),
                    'content' => $classTime->content,
                    'html_content' => $classTime->html_content,
                    'teacher_review' => null,
                    'student_review' => null,
                    'confirmed' => $classTime->isConfirmed,
                ],
                'class_time_periodic' => empty($classTimePeriodic) ? null : [
                    'id' => $classTimePeriodic->id,
                    'is_periodic' => $classTimePeriodic->isPeriodic,
                    'trans_after' => trans('label._periodic_class_review', ['after' => $classTimePeriodic->subject]),
                    'month_year_start_at' => date('m-Y', strtotime($classTimePeriodic->start_at)),
                    'teacher_review' => null,
                    'student_review' => null
                ],
                'overtime' => $overTime,
            ]);
        } catch (KatnissException $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }

    /**
     * Only for Roles: Teacher/Manager/Admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $classTime = $this->classTimeRepository->model($id);

        $classroom = $classTime->classroom;
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
            'subject' => 'required|max:255',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $classTime = $this->classTimeRepository->update(
                $request->input('subject'),
                $request->input('content', '')
            );

            return $this->responseSuccess([
                'class_time' => [
                    'id' => $classTime->id,
                    'subject' => $classTime->subject,
                    'content' => $classTime->content,
                    'html_content' => $classTime->html_content,
                ]
            ]);
        } catch (KatnissException $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }

    public function storeReviews(Request $request, $id)
    {
        $classTime = $this->classTimeRepository->model($id);

        $classroom = $classTime->classroom;
        if (!$classroom->isOpening) {
            abort(404);
        }
        $user = $request->authUser();
        $reviewUserId = null;
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                    abort(404);
                }
            } else {
                $reviewUserId = $classroom->teacher_id;
            }
        } elseif ($user->hasRole('student')) {
            if ($classroom->student_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                    abort(404);
                }
            } else {
                $reviewUserId = $classroom->student_id;
            }
        }
        if ($user->hasRole(['manager', 'admin'])) {
            if ($request->has('teacher')) {
                $reviewUserId = $classroom->teacher_id;
            } elseif ($request->has('student')) {
                $reviewUserId = $classroom->student_id;
            }
        }
        if (empty($reviewUserId)) abort(404);

        if (!$this->customValidate($request, [
            'rate' => 'required|integer|min:1|max:' . count(_k('rates')),
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $review = $this->classTimeRepository->createReview(
                $reviewUserId,
                $request->input('rate'),
                $request->input('review', '')
            );

            return $this->responseSuccess([
                'review' => [
                    'id' => $review->id,
                    'class_time_id' => $id,
                    'user_id' => $review->user_id,
                    'rate' => $review->rate,
                    'trans_rate' => transRate($review->rate),
                    'review' => $review->review,
                    'html_review' => $review->htmlReview,
                ],
                'max_rate' => count(_k('rates')),
            ]);
        } catch (KatnissException $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }

    public function storeRichReviews(Request $request, $id)
    {
        $classTime = $this->classTimeRepository->model($id);

        $classroom = $classTime->classroom;
        if (!$classroom->isOpening) {
            abort(404);
        }
        $user = $request->authUser();
        $reviewUserId = null;
        $forTeacher = false;
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                    abort(404);
                }
            }
            $reviewUserId = $classroom->teacher_id;
            $forTeacher = true;
        } elseif ($user->hasRole('student')) {
            if ($classroom->student_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                    abort(404);
                }
            }
            $reviewUserId = $classroom->student_id;
        }
        if ($user->hasRole(['manager', 'admin'])) {
            if ($request->has('teacher')) {
                $reviewUserId = $classroom->teacher_id;
                $forTeacher = true;
            } elseif ($request->has('student')) {
                $reviewUserId = $classroom->student_id;
            }
        }
        if (empty($reviewUserId)) abort(404);

        if (!$this->customValidate($request, [
            'rate' => 'required|array',
            'rate.*' => 'required|integer|min:1|max:' . count(_k('rates')),
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $review = $this->classTimeRepository->createRichReview(
                $reviewUserId,
                $request->input('rate'),
                $request->input('review', '')
            );

            return $this->responseSuccess([
                'review' => [
                    'id' => $review->id,
                    'class_time_id' => $id,
                    'user_id' => $review->user_id,
                    'rates' => $review->rates,
                    'trans_rates' => transRate($review->rates),
                    'trans_rate_names' => transRateName($review->rates, $forTeacher),
                    'review' => $review->review,
                    'html_review' => $review->htmlReview,
                ],
                'max_rate' => count(_k('rates')),
            ]);
        } catch (KatnissException $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }
}