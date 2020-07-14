<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\StudyLevelRepository;

class StudyLevelController extends AdminController
{
    protected $studyLevelRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'study_level';
        $this->studyLevelRepository = new StudyLevelRepository();
    }

    public function index(Request $request)
    {
        $studyLevels = $this->studyLevelRepository->getPaged();
        $this->_title(trans('pages.admin_study_levels_title'));
        $this->_description(trans('pages.admin_study_levels_desc'));

        return $this->_index([
            'study_levels' => $studyLevels,
            'pagination' => $this->paginationRender->renderByPagedModels($studyLevels),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function create()
    {
        $this->_title([trans('pages.admin_study_levels_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_study_levels_desc'));

        return $this->_create();
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        $errorRedirect = redirect(adminUrl('study-levels/create'))
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
            $this->studyLevelRepository->create($validateResult->getLocalizedInputs(), $request->input('order'));
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('study-levels'));
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
        $studyLevel = $this->studyLevelRepository->model($id);

        $this->_title([trans('pages.admin_study_levels_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_study_levels_desc'));

        return $this->_edit([
            'study_level' => $studyLevel,
        ]);
    }

    public function update(Request $request, $id)
    {
        $studyLevel = $this->studyLevelRepository->model($id);

        $redirect = redirect(adminUrl('study-levels/{id}/edit', ['id' => $studyLevel->id]));

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
            $this->studyLevelRepository->update($validateResult->getLocalizedInputs(), $request->input('order'));
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect;
    }

    public function destroy(Request $request, $id)
    {
        $this->studyLevelRepository->model($id);

        $this->_rdrUrl($request, adminUrl('study-levels'), $rdrUrl, $errorRdrUrl);

        try {
            $this->studyLevelRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
