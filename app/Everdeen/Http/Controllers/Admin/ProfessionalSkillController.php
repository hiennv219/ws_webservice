<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ProfessionalSkillRepository;

class ProfessionalSkillController extends AdminController
{
    protected $professionalSkillRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'professional_skill';
        $this->professionalSkillRepository = new ProfessionalSkillRepository();
    }

    public function index(Request $request)
    {
        $professionalSkills = $this->professionalSkillRepository->getPaged();
        $this->_title(trans('pages.admin_professional_skills_title'));
        $this->_description(trans('pages.admin_professional_skills_desc'));

        return $this->_index([
            'professional_skills' => $professionalSkills,
            'pagination' => $this->paginationRender->renderByPagedModels($professionalSkills),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function create()
    {
        $this->_title([trans('pages.admin_professional_skills_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_professional_skills_desc'));

        return $this->_create();
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        $errorRedirect = redirect(adminUrl('professional-skills/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $errorRedirect->withErrors($validateResult->getFailed());
        }

        try {
            $this->professionalSkillRepository->create($validateResult->getLocalizedInputs());
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('professional-skills'));
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
        $professionalSkill = $this->professionalSkillRepository->model($id);

        $this->_title([trans('pages.admin_professional_skills_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_professional_skills_desc'));

        return $this->_edit([
            'professional_skill' => $professionalSkill,
        ]);
    }

    public function update(Request $request, $id)
    {
        $professionalSkill = $this->professionalSkillRepository->model($id);

        $redirect = redirect(adminUrl('professional-skills/{id}/edit', ['id' => $professionalSkill->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        try {
            $this->professionalSkillRepository->update($validateResult->getLocalizedInputs());
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect;
    }

    public function destroy(Request $request, $id)
    {
        $this->professionalSkillRepository->model($id);

        $this->_rdrUrl($request, adminUrl('professional-skills'), $rdrUrl, $errorRdrUrl);

        try {
            $this->professionalSkillRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
