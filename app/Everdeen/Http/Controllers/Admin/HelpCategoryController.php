<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\HelpCategoryRepository;

class HelpCategoryController extends AdminController
{
    private $helpCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'help_category';
        $this->helpCategoryRepository = new HelpCategoryRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = $this->helpCategoryRepository->getPaged();

        $this->_title(trans('pages.admin_help_categories_title'));
        $this->_description(trans('pages.admin_help_categories_desc'));

        return $this->_index([
            'categories' => $categories,
            'pagination' => $this->paginationRender->renderByPagedModels($categories),
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
        $this->_title([trans('pages.admin_help_categories_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_help_categories_desc'));

        return $this->_create([
            'categories' => $this->helpCategoryRepository->getAll(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required',
            'slug' => 'required|unique:category_translations,slug',
        ]);

        $errorRedirect = redirect(adminUrl('help-categories/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $errorRedirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'order' => 'required|integer|min:0',
        ]);

        if($validator->fails()) {
            return $errorRedirect->withErrors($validator);
        }

        try {
            $this->helpCategoryRepository->create(0, $validateResult->getLocalizedInputs(), $request->input('order'));
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('help-categories'));
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
        $category = $this->helpCategoryRepository->model($id);

        $this->_title([trans('pages.admin_help_categories_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_help_categories_desc'));

        return $this->_edit([
            'category' => $category,
            'categories' => $this->helpCategoryRepository->getAll(),
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
        $category = $this->helpCategoryRepository->model($id);

        $redirect = redirect(adminUrl('help-categories/{id}/edit', ['id' => $category->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required',
            'slug' => 'required|unique:category_translations,slug,' . $category->id . ',category_id',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'order' => 'required|integer|min:0',
        ]);

        if($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->helpCategoryRepository->update(0, $validateResult->getLocalizedInputs(), $request->input('order'));
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
        $this->helpCategoryRepository->model($id);

        $this->_rdrUrl($request, adminUrl('help-categories'), $rdrUrl, $errorRdrUrl);

        try {
            $this->helpCategoryRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function sort(Request $request, $id)
    {
        $category = $this->helpCategoryRepository->model($id);

        $this->_title([trans('pages.admin_help_categories_title'), trans('form.action_sort')]);
        $this->_description(trans('pages.admin_help_categories_desc'));

        return $this->_any('sort', [
            'category' => $category,
            'helps' => $category->orderedPosts,
        ]);
    }
}
