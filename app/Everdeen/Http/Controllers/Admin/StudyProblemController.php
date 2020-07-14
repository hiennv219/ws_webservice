<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\StudyProblemRepository;

class StudyProblemController extends AdminController
{
    protected $studyProblemRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'study_problem';
        $this->studyProblemRepository = new StudyProblemRepository();
    }

    public function index(Request $request)
    {
        $studyProblems = $this->studyProblemRepository->getPaged();
        $this->_title(trans('pages.admin_study_problems_title'));
        $this->_description(trans('pages.admin_study_problems_desc'));

        return $this->_index([
            'study_problems' => $studyProblems,
            'pagination' => $this->paginationRender->renderByPagedModels($studyProblems),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function create()
    {
        $this->_title([trans('pages.admin_study_problems_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_study_problems_desc'));

        return $this->_create();
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        $errorRedirect = redirect(adminUrl('study-problems/create'))
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
            $this->studyProblemRepository->create($validateResult->getLocalizedInputs(), $request->input('order'));
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('study-problems'));
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
        $studyProblem = $this->studyProblemRepository->model($id);

        $this->_title([trans('pages.admin_study_problems_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_study_problems_desc'));

        return $this->_edit([
            'study_problem' => $studyProblem,
        ]);
    }

    public function update(Request $request, $id)
    {
        $studyProblem = $this->studyProblemRepository->model($id);

        $redirect = redirect(adminUrl('study-problems/{id}/edit', ['id' => $studyProblem->id]));

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
            $this->studyProblemRepository->update($validateResult->getLocalizedInputs(), $request->input('order'));
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect;
    }

    public function destroy(Request $request, $id)
    {
        $this->studyProblemRepository->model($id);

        $this->_rdrUrl($request, adminUrl('study-problems'), $rdrUrl, $errorRdrUrl);

        try {
            $this->studyProblemRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
