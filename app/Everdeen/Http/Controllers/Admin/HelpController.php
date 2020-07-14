<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Repositories\HelpCategoryRepository;
use Katniss\Everdeen\Repositories\HelpRepository;
use Katniss\Everdeen\Themes\ThemeFacade;

class HelpController extends AdminController
{
    private $helpRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'help';
        $this->helpRepository = new HelpRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $helps = $this->helpRepository->getPaged();

        $this->_title(trans('pages.admin_helps_title'));
        $this->_description(trans('pages.admin_helps_desc'));

        return $this->_index([
            'helps' => $helps,
            'pagination' => $this->paginationRender->renderByPagedModels($helps),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $helpCategoryRepository = new HelpCategoryRepository();

        $this->_title([trans('pages.admin_helps_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_helps_desc'));

        return $this->_create([
            'categories' => $helpCategoryRepository->getAll(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:post_translations,slug',
            'description' => 'sometimes|nullable|max:255',
        ]);

        $error_redirect = redirect(adminUrl('helps/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $error_redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'category' => 'required|exists:categories,id,type,' . Category::TYPE_HELP,
        ]);
        if ($validator->fails()) {
            return $error_redirect->withErrors($validator);
        }

        try {
            $this->helpRepository->create(
                $request->authUser()->id,
                null,
                null,
                $validateResult->getLocalizedInputs(),
                [$request->input('category')]
            );
        } catch (KatnissException $ex) {
            return $error_redirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('helps'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $help = $this->helpRepository->model($id);
        $helpCategoryRepository = new HelpCategoryRepository();

        $this->_title([trans('pages.admin_helps_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_helps_desc'));

        return $this->_edit([
            'help' => $help,
            'help_category' => $help->categories()->first(),
            'categories' => $helpCategoryRepository->getAll(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $page = $this->helpRepository->model($id);

        $redirect = redirect(adminUrl('helps/{id}/edit', ['id' => $page->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:post_translations,slug,' . $page->id . ',post_id',
            'description' => 'sometimes|nullable|max:255',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'category' => 'required|exists:categories,id,type,' . Category::TYPE_HELP,
        ]);
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->helpRepository->update(
                $request->authUser()->id,
                null,
                null,
                $validateResult->getLocalizedInputs(),
                [$request->input('category')]
            );
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }
        return $redirect;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $this->helpRepository->model($id);

        $this->_rdrUrl($request, adminUrl('helps'), $rdrUrl, $errorRdrUrl);

        try {
            $this->helpRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
