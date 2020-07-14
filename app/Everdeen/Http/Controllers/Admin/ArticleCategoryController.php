<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;

class ArticleCategoryController extends AdminController
{
    private $articleCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'article_category';
        $this->articleCategoryRepository = new ArticleCategoryRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = $this->articleCategoryRepository->getPaged();

        $this->_title(trans('pages.admin_article_categories_title'));
        $this->_description(trans('pages.admin_article_categories_desc'));

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
        $this->_title([trans('pages.admin_article_categories_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_article_categories_desc'));

        return $this->_create([
            'categories' => $this->articleCategoryRepository->getAll(),
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

        $errorRedirect = redirect(adminUrl('article-categories/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $errorRedirect->withErrors($validateResult->getFailed());
        }

        $parentId = intval($request->input('parent'), 0);
        if ($parentId != 0) {
            $validator = Validator::make($request->all(), [
                'parent' => 'sometimes|nullable|exists:categories,id,type,' . Category::TYPE_ARTICLE,
            ]);
            if ($validator->fails()) {
                return $errorRedirect->withErrors($validator);
            }
        }

        try {
            $this->articleCategoryRepository->create($parentId, $validateResult->getLocalizedInputs());
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('article-categories'));
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
        $category = $this->articleCategoryRepository->model($id);

        $this->_title([trans('pages.admin_article_categories_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_article_categories_desc'));

        return $this->_edit([
            'category' => $category,
            'categories' => $this->articleCategoryRepository->getAll(),
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
        $category = $this->articleCategoryRepository->model($id);

        $redirect = redirect(adminUrl('article-categories/{id}/edit', ['id' => $category->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required',
            'slug' => 'required|unique:category_translations,slug,' . $category->id . ',category_id',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $parent_id = intval($request->input('parent'), 0);
        if ($parent_id != 0) {
            $validator = Validator::make($request->all(), [
                'parent' => 'sometimes|nullable|exists:categories,id,type,' . Category::TYPE_ARTICLE
            ]);
            if ($validator->fails()) {
                return $redirect->withErrors($validator);
            }
        }

        try {
            $this->articleCategoryRepository->update($parent_id, $validateResult->getLocalizedInputs());
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
        $this->articleCategoryRepository->model($id);

        $this->_rdrUrl($request, adminUrl('article-categories'), $rdrUrl, $errorRdrUrl);

        try {
            $this->articleCategoryRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
