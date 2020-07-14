<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\LinkCategoryRepository;
use Katniss\Everdeen\Repositories\LinkRepository;

class LinkController extends AdminController
{
    protected $linkRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'link';
        $this->linkRepository = new LinkRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $links = $this->linkRepository->getPaged();

        $this->_title(trans('pages.admin_links_title'));
        $this->_description(trans('pages.admin_links_desc'));

        return $this->_index([
            'links' => $links,
            'pagination' => $this->paginationRender->renderByPagedModels($links),
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
        $categoryRepository = new LinkCategoryRepository();

        $this->_title([trans('pages.admin_links_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_links_desc'));

        return $this->_create([
            'categories' => $categoryRepository->getAll()
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
            'name' => 'required',
            'url' => 'required', // no need to confirm link is an url, for trickly use
        ]);

        $error_redirect = redirect(adminUrl('links/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $error_redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'required|exists:categories,id,type,' . Category::TYPE_LINK,
            'image' => 'sometimes|nullable|url',
        ]);
        if ($validator->fails()) {
            return $error_redirect->withErrors($validator);
        }

        try {
            $this->linkRepository->create(
                $request->input('image', ''),
                $request->input('categories', []),
                $validateResult->getLocalizedInputs()
            );
        } catch (KatnissException $ex) {
            return $error_redirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('links'));
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
        $link = $this->linkRepository->model($id);
        $categoryRepository = new LinkCategoryRepository();

        $this->_title([trans('pages.admin_links_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_links_desc'));

        return $this->_edit([
            'link' => $link,
            'link_categories' => $link->categories,
            'categories' => $categoryRepository->getAll(),
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
        $link = $this->linkRepository->model($id);

        $redirect = redirect(adminUrl('links/{id}/edit', ['id' => $link->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required',
            'url' => 'required',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'required|exists:categories,id,type,' . Category::TYPE_LINK,
            'image' => 'sometimes|nullable|url',
        ]);
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->linkRepository->update(
                $request->input('image', ''),
                $request->input('categories', []),
                $validateResult->getLocalizedInputs()
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
        $this->linkRepository->model($id);
        $this->_rdrUrl($request, adminUrl('links'), $rdrUrl, $errorRdrUrl);

        try {
            $this->linkRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }
        return redirect($rdrUrl);
    }
}
