<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Student;
use Katniss\Everdeen\Models\Teacher;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassroomController extends AdminController
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
        $searchName = $request->input('name', null);
        $searchTeacher = $request->input('teacher', null);
        $searchStudent = $request->input('student', null);
        $searchSupporter = $request->input('supporter', null);
        $classrooms = $this->classroomRepository->getSearchOpeningPaged(
            $searchName,
            $searchTeacher,
            $searchStudent,
            $searchSupporter
        );

        $this->_title(trans('pages.admin_opening_classrooms_title'));
        $this->_description(trans('pages.admin_opening_classrooms_desc'));

        return $this->_any('index_opening', [
            'classrooms' => $classrooms,
            'pagination' => $this->paginationRender->renderByPagedModels($classrooms),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'salary_jump_currency' => settings()->currency,

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchName) || !empty($searchTeacher) || !empty($searchStudent) || !empty($searchSupporter),
            'search_name' => $searchName,
            'search_teacher' => $searchTeacher,
            'search_student' => $searchStudent,
            'search_supporter' => $searchSupporter,
        ]);
    }

    public function indexClosed(Request $request)
    {
        $searchName = $request->input('name', null);
        $searchTeacher = $request->input('teacher', null);
        $searchStudent = $request->input('student', null);
        $searchSupporter = $request->input('supporter', null);
        $classrooms = $this->classroomRepository->getSearchClosedPaged(
            $searchName,
            $searchTeacher,
            $searchStudent,
            $searchSupporter
        );

        $this->_title(trans('pages.admin_closed_classrooms_title'));
        $this->_description(trans('pages.admin_closed_classrooms_desc'));

        return $this->_any('index_closed', [
            'classrooms' => $classrooms,
            'pagination' => $this->paginationRender->renderByPagedModels($classrooms),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'salary_jump_currency' => settings()->currency,

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchName) || !empty($searchTeacher) || !empty($searchStudent) || !empty($searchSupporter),
            'search_name' => $searchName,
            'search_teacher' => $searchTeacher,
            'search_student' => $searchStudent,
            'search_supporter' => $searchSupporter,
        ]);
    }

    public function indexReady(Request $request)
    {
        $searchName = $request->input('name', null);
        $searchTeacher = $request->input('teacher', null);
        $searchStudent = $request->input('student', null);
        $searchSupporter = $request->input('supporter', null);
        $classrooms = $this->classroomRepository->getSearchReadyToClosePaged(
            $searchName,
            $searchTeacher,
            $searchStudent,
            $searchSupporter
        );

        $this->_title(trans('pages.admin_closed_classrooms_title'));
        $this->_description(trans('pages.admin_closed_classrooms_desc'));

        return $this->_any('index_ready', [
            'classrooms' => $classrooms,
            'pagination' => $this->paginationRender->renderByPagedModels($classrooms),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'salary_jump_currency' => settings()->currency,

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchName) || !empty($searchTeacher) || !empty($searchStudent) || !empty($searchSupporter),
            'search_name' => $searchName,
            'search_teacher' => $searchTeacher,
            'search_student' => $searchStudent,
            'search_supporter' => $searchSupporter,
        ]);
    }

    public function create(Request $request)
    {
        $this->_title([trans('pages.admin_classrooms_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_classrooms_desc'));

        return $this->_create([
            'redirect_url' => $request->input(AppConfig::KEY_REDIRECT_URL),
            'number_format_chars' => NumberFormatHelper::getInstance()->getChars(),
            'salary_jump_currency' => settings()->currency,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher' => 'required|exists:teachers,user_id,status,' . Teacher::APPROVED,
            'student' => 'required|exists:students,user_id,status,' . Student::APPROVED,
            'supporter' => 'required|exists:users,id',
            'name' => 'required|max:255',
            'duration' => ['required', 'regex:' . NumberFormatHelper::getInstance()->getRegEx(8, 2)],
            'hours_salary' => ['required', 'regex:' . NumberFormatHelper::getInstance()->getRegEx(10, 2)],
        ]);
        $errorRedirect = redirect(adminUrl('classrooms/create'))->withInput();
        if ($validator->fails()) {
            return $errorRedirect->withErrors($validator);
        }

        try {
            $this->classroomRepository->create(
                $request->input('teacher'),
                $request->input('student'),
                $request->input('supporter'),
                $request->input('name'),
                $request->input('duration'),
                $request->input('comment'),
                $request->input('hours_salary')
            );
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('opening-classrooms'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);

        $this->_rdrUrl($request, adminUrl('opening-classrooms'), $rdrUrl, $errorRdrUrl);

        $this->_title([trans('pages.admin_classrooms_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_classrooms_desc'));

        return $this->_edit([
            'redirect_url' => $classroom->isOpening ? $rdrUrl : adminUrl('closed-classrooms'),
            'classroom' => $classroom,
            'number_format_chars' => NumberFormatHelper::getInstance()->getChars(),
            'salary_jump_currency' => settings()->currency,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->has('open')) {
            return $this->open($request, $id);
        } elseif ($request->has('close')) {
            return $this->close($request, $id);
        }

        $this->classroomRepository->model($id);

        $redirect = redirect(adminUrl('classrooms/{id}/edit', ['id' => $id]));

        $validator = Validator::make($request->all(), [
            'teacher' => 'sometimes|nullable|exists:teachers,user_id,status,' . Teacher::APPROVED,
            'student' => 'sometimes|nullable|exists:students,user_id,status,' . Student::APPROVED,
            'supporter' => 'sometimes|nullable|exists:users,id',
            'name' => 'required|max:255',
            'duration' => ['required', 'regex:' . NumberFormatHelper::getInstance()->getRegEx(8, 2)],
            'hours_salary' => ['required', 'regex:' . NumberFormatHelper::getInstance()->getRegEx(10, 2)],
        ]);

        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->classroomRepository->update(
                $request->input('teacher', ''),
                $request->input('student', ''),
                $request->input('supporter', ''),
                $request->input('name'),
                $request->input('duration'),
                $request->input('comment'),
                $request->input('hours_salary')
            );
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect;
    }

    protected function open(Request $request, $id)
    {
        $this->classroomRepository->model($id);

        $this->_rdrUrl($request, adminUrl('opening-classrooms'), $rdrUrl, $errorRdrUrl);

        try {
            $this->classroomRepository->open();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function close(Request $request, $id)
    {
        $this->classroomRepository->model($id);

        $this->_rdrUrl($request, adminUrl('closed-classrooms'), $rdrUrl, $errorRdrUrl);

        try {
            $this->classroomRepository->close($request->authUser()->id);
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function destroy(Request $request, $id)
    {
        $this->classroomRepository->model($id);

        $this->_rdrUrl($request, adminUrl('classrooms'), $rdrUrl, $errorRdrUrl);

        try {
            $this->classroomRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
