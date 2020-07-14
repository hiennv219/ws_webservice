<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-11
 * Time: 09:25
 */

namespace Katniss\Everdeen\Http\Controllers\Home;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Classroom;
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Reports\ClassroomStudentReport;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ClassroomController extends ViewController
{
    protected $classroomRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'classroom';
        $this->classroomRepository = new ClassroomRepository();
    }

    public function indexOpening(Request $request)
    {
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            $classrooms = $this->classroomRepository->getByTeacherPaged($user->id);
        } elseif ($user->hasRole('student')) {
            $classrooms = $this->classroomRepository->getByStudentPaged($user->id);
        } elseif ($user->hasRole('supporter')) {
            $classrooms = $this->classroomRepository->getBySupporterPaged($user->id);
        } else {
            abort(404);
            die();
        }

        return $this->_any('index_opening', [
            'classrooms' => $classrooms,
            'pagination' => $this->paginationRender->renderByPagedModels($classrooms),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function indexClosed(Request $request)
    {
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            $classrooms = $this->classroomRepository->getByTeacherPaged($user->id, Classroom::STATUS_CLOSED);
        } elseif ($user->hasRole('student')) {
            $classrooms = $this->classroomRepository->getByStudentPaged($user->id, Classroom::STATUS_CLOSED);
        } elseif ($user->hasRole('supporter')) {
            $classrooms = $this->classroomRepository->getBySupporterPaged($user->id, Classroom::STATUS_CLOSED);
        } else {
            abort(404);
            die();
        }

        return $this->_any('index_closed', [
            'classrooms' => $classrooms,
            'pagination' => $this->paginationRender->renderByPagedModels($classrooms),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->has('export')) {
            return $this->export($request, $id);
        }

        $classroom = $this->classroomRepository->model($id);
        $user = $request->authUser();
        $isOwner = false;
        $canClassroomEdit = false;
        $userCanCloseClassroom = false;
        $canExportClassroom = false;
        $canAddTeacherReview = false;
        $canAddStudentReview = false;
        $canConfirmClassTime = false;

        $canAccessAnyway = $user->hasRole(['student_visor', 'manager', 'admin']);
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                if (!$canAccessAnyway) {
                    abort(404);
                }
            } else {
                $isOwner = true;
                $canClassroomEdit = true;
                $canAddTeacherReview = true;
            }
        } elseif ($user->hasRole('student')) {
            if ($classroom->student_id != $user->id) {
                if (!$canAccessAnyway) {
                    abort(404);
                }
            } else {
                $isOwner = true;
                $canAddStudentReview = true;
                $canExportClassroom = true;
                $canConfirmClassTime = true;
            }
        } elseif ($user->hasRole('supporter')) {
            if ($classroom->supporter_id != $user->id) {
                if (!$canAccessAnyway) {
                    abort(404);
                }
            } else {
                $isOwner = true;
                $userCanCloseClassroom = true;
                $canExportClassroom = true;
            }
        }
        if ($canAccessAnyway) {
            $canExportClassroom = true;
            if ($user->hasRole(['manager', 'admin'])) {
                $canClassroomEdit = true;
                $userCanCloseClassroom = true;
                $canAddTeacherReview = true;
                $canAddStudentReview = true;
            }
        }
        $canClassroomClose = $userCanCloseClassroom
            && $classroom->isOpening
            && $classroom->spentTime >= $classroom->hours;

        $lastMonthClassTimes = $classroom->classTimesOfLastMonth;
        $countLastMonthClassTimes = $lastMonthClassTimes->where('type', ClassTime::TYPE_NORMAL)->count();
        $hasPreviousMonthClassTimes = false;
        $previousYear = false;
        $previousMonth = false;
        if ($countLastMonthClassTimes > 0) {
            $datetime = new \DateTime($lastMonthClassTimes[0]->start_at);
            $datetime->sub(new \DateInterval('P1M'));
            $previousYear = $datetime->format('Y');
            $previousMonth = $datetime->format('m');
            $hasPreviousMonthClassTimes = $classroom->getCountClassTimesOfMonth($previousYear, $previousMonth) > 0;
        }
        $countAllClassTimes = $classroom->countClassTimes;

        $theme = $request->getTheme();

        return $this->_show([
            'classrooms_url' => $isOwner ?
                ($classroom->isOpening ?
                    homeUrl('opening-classrooms') : homeUrl('closed-classrooms'))
                : ($classroom->isOpening ?
                    adminUrl('opening-classrooms') : adminUrl('closed-classrooms')),
            'classroom' => $classroom,
            'class_times' => $lastMonthClassTimes->sort(function ($a, $b) {
                if ($a->start_at == $b->start_at) {
                    return $a->id > $b->id;
                }
                return $a->start_at > $b->start_at;
            }), // need sorted again
            'class_time_order_start' => $countAllClassTimes - $countLastMonthClassTimes + 1,
            'has_previous_month_class_times' => $hasPreviousMonthClassTimes,
            'previous_year' => $previousYear,
            'previous_month' => $previousMonth,
            'can_classroom_edit' => $canClassroomEdit && $classroom->isOpening,
            'can_classroom_close' => $canClassroomClose,
            'can_classroom_export' => $canExportClassroom,
            'can_add_teacher_review' => $canAddTeacherReview,
            'can_add_student_review' => $canAddStudentReview,
            'can_confirm_class_time' => $canConfirmClassTime,
            'teacher' => $classroom->teacherProfile,
            'student' => $classroom->studentProfile,
            'supporter' => $classroom->supporter,
            'date_js_format' => DateTimeHelper::compoundJsFormat('shortDate', ' ', 'shortTime'),
            'number_format_chars' => NumberFormatHelper::getInstance()->getChars(),
            'max_rate' => count(_k('rates')),
            'ss_skype_id' => $theme->options('ss_skype_id', ''),
            'ss_skype_name' => $theme->options('ss_skype_name', ''),
        ]);
    }

    public function export(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        $user = $request->authUser();
        if ($user->hasRole('student')) {
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
        if (!$user->hasRole(['manager', 'admin'])) {
            abort(404);
        }

        try {
            $report = new ClassroomStudentReport($classroom->id, $classroom->teacher_id);

            return Excel::create('Classroom_Student_' . Str::slug($classroom->name, '_'), function ($excel) use ($report, $classroom) {
                $excel->sheet('Sheet 1', function ($sheet) use ($report, $classroom) {
                    $data = $report->getDataAsFlatArray();
                    array_unshift($data, $report->getHeader());

                    $sheet->cell('A1', function ($cell) use ($report, $classroom) {
                        $cell->setValue(
                            trans_choice('label.classroom', 1) . ': ' .
                            $classroom->name .
                            ' (#' . $classroom->id . ')'
                        );
                    });

                    $sheet->cell('A2', function ($cell) use ($report, $classroom) {
                        $cell->setValue(
                            trans_choice('label.teacher', 1) . ': ' .
                            $classroom->teacherUserProfile->display_name .
                            ' (#' . $classroom->teacherUserProfile->id . ')'
                        );
                    });

                    $sheet->cell('A3', function ($cell) use ($report, $classroom) {
                        $cell->setValue(
                            trans('label.class_duration') . ': ' .
                            $classroom->duration . ' ' . trans_choice('label.hour_lc', $classroom->hours)
                        );
                    });

                    $sheet->cell('A4', function ($cell) use ($report, $classroom) {
                        $cell->setValue(
                            trans('label.class_spent_time') . ': ' .
                            toFormattedNumber($report->getSumHours()) . ' ' . trans_choice('label.hour_lc', $report->getSumHours())
                        );
                    });

                    $startColumn = 'A';
                    $startRow = 5;
                    $endColumn = chr(count($data[0]) + ord($startColumn) - 1);
                    $endRow = $startRow + count($data) - 1;

                    $sheet->mergeCells('A1:' . $endColumn . '1');
                    $sheet->mergeCells('A2:' . $endColumn . '2');
                    $sheet->mergeCells('A3:' . $endColumn . '3');
                    $sheet->mergeCells('A4:' . $endColumn . '4');
                    $sheet->fromArray($data, null, $startColumn . $startRow, true, false);
                    $sheet->cells($startColumn . $startRow . ':' . $endColumn . $startRow, function ($cells) {
                        $cells->setBackground('#000000');
                        $cells->setFontColor('#ffffff');
                        $cells->setFontWeight('bold');
                    });
                    $sheet->cells($startColumn . $startRow . ':' . $endColumn . $endRow, function ($cells) {
                        $cells->setValignment('top');
                    });
                    $sheet->setBorder($startColumn . $startRow . ':' . $endColumn . $endRow, 'thin');
                    $sheet->getStyle($startColumn . $startRow . ':' . $endColumn . $endRow)->getAlignment()->setWrapText(true);
                });
            })->download('xls');
        } catch (KatnissException $ex) {
            return abort(500);
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->has('close')) {
            return $this->close($request, $id);
        }

        abort(404);
        return '';
    }

    protected function close(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        $user = $request->authUser();
        $userCanCloseClassroom = false;
        if ($user->hasRole('supporter')) {
            if ($classroom->supporter_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                    abort(404);
                }
            } else {
                $userCanCloseClassroom = true;
            }
        }
        if ($user->hasRole(['manager', 'admin'])) {
            $userCanCloseClassroom = true;
        }
        if (!$userCanCloseClassroom
            || !$classroom->isOpening
            || $classroom->spentTime < $classroom->hours
        ) {
            abort(404);
        }

        $this->_rdrUrl($request, homeUrl('classrooms'), $rdrUrl, $errorRdrUrl);

        try {
            $this->classroomRepository->close($user->id);
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}