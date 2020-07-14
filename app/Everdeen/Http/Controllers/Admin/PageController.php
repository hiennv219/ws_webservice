<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\PageRepository;
use Katniss\Everdeen\Themes\ThemeFacade;

class PageController extends AdminController
{
    private $pageRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'page';
        $this->pageRepository = new PageRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $pages = $this->pageRepository->getPaged();

        $this->_title(trans('pages.admin_pages_title'));
        $this->_description(trans('pages.admin_pages_desc'));

        return $this->_index([
            'pages' => $pages,
            'pagination' => $this->paginationRender->renderByPagedModels($pages),
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
        $this->_title([trans('pages.admin_pages_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_pages_desc'));

        return $this->_create([
            'templates' => ThemeFacade::pageTemplates(),
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

        $error_redirect = redirect(adminUrl('pages/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $error_redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'featured_image' => 'sometimes|nullable|url',
        ]);
        if ($validator->fails()) {
            return $error_redirect->withErrors($validator);
        }

        try {
            $this->pageRepository->create(
                $request->authUser()->id,
                $request->input('template', ''),
                $request->input('featured_image', ''),
                $validateResult->getLocalizedInputs()
            );
        } catch (KatnissException $ex) {
            return $error_redirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('pages'));
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
        $page = $this->pageRepository->model($id);

        $this->_title([trans('pages.admin_pages_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_pages_desc'));

        return $this->_edit([
            'page' => $page,
            'templates' => ThemeFacade::pageTemplates(),
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
        $page = $this->pageRepository->model($id);

        $redirect = redirect(adminUrl('pages/{id}/edit', ['id' => $page->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:post_translations,slug,' . $page->id . ',post_id',
            'description' => 'sometimes|nullable|max:255',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'featured_image' => 'sometimes|nullable|url',
        ]);
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->pageRepository->update(
                $request->authUser()->id,
                $request->input('template', ''),
                $request->input('featured_image', ''),
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
        $this->pageRepository->model($id);

        $this->_rdrUrl($request, adminUrl('pages'), $rdrUrl, $errorRdrUrl);

        try {
            $this->pageRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
