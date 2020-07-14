<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\StudyCourseRepository;

class StudyCourseController extends AdminController
{
    protected $studyCourseRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'study_course';
        $this->studyCourseRepository = new StudyCourseRepository();
    }

    public function index(Request $request)
    {
        $studyCourses = $this->studyCourseRepository->getPaged();
        $this->_title(trans('pages.admin_study_courses_title'));
        $this->_description(trans('pages.admin_study_courses_desc'));

        return $this->_index([
            'study_courses' => $studyCourses,
            'pagination' => $this->paginationRender->renderByPagedModels($studyCourses),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function create()
    {
        $this->_title([trans('pages.admin_study_courses_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_study_courses_desc'));

        return $this->_create();
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        $errorRedirect = redirect(adminUrl('study-courses/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $errorRedirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'order' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return $errorRedirect->withErrors($validator);
        }

        try {
            $this->studyCourseRepository->create($validateResult->getLocalizedInputs(), $request->input('order'));
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('study-courses'));
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
        $studyCourse = $this->studyCourseRepository->model($id);

        $this->_title([trans('pages.admin_study_courses_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_study_courses_desc'));

        return $this->_edit([
            'study_course' => $studyCourse,
        ]);
    }

    public function update(Request $request, $id)
    {
        $studyCourse = $this->studyCourseRepository->model($id);

        $redirect = redirect(adminUrl('study-courses/{id}/edit', ['id' => $studyCourse->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'order' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->studyCourseRepository->update($validateResult->getLocalizedInputs(), $request->input('order'));
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect;
    }

    public function destroy(Request $request, $id)
    {
        $this->studyCourseRepository->model($id);

        $this->_rdrUrl($request, adminUrl('study-courses'), $rdrUrl, $errorRdrUrl);

        try {
            $this->studyCourseRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
