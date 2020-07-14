<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Models\Media;
use Katniss\Everdeen\Repositories\MediaCategoryRepository;
use Katniss\Everdeen\Repositories\MediaRepository;

class MediaController extends AdminController
{
    protected $mediaRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'media';
        $this->mediaRepository = new MediaRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $mediaItems = $this->mediaRepository->getPaged();

        $this->_title(trans('pages.admin_media_items_title'));
        $this->_description(trans('pages.admin_media_items_desc'));

        return $this->_index([
            'media_items' => $mediaItems,
            'pagination' => $this->paginationRender->renderByPagedModels($mediaItems),
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
        $categoryRepository = new MediaCategoryRepository();

        $this->_title([trans('pages.admin_media_items_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_media_items_desc'));

        return $this->_create([
            'categories' => $categoryRepository->getAll(),
            'types' => [
                Media::TYPE_PHOTO => trans_choice('label.photo', 1),
                Media::TYPE_VIDEO => trans_choice('label.video', 1)
            ],
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
            'description' => 'sometimes|nullable|max:255',
        ]);

        $error_redirect = redirect(adminUrl('media-items/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $error_redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'sometimes|nullable|exists:categories,id,type,' . Category::TYPE_MEDIA,
            'url' => 'required|url',
            'type' => 'required|in:' . implode(',', [Media::TYPE_PHOTO, Media::TYPE_VIDEO]),
        ]);
        if ($validator->fails()) {
            return $error_redirect->withErrors($validator);
        }

        try {
            $this->mediaRepository->create(
                $request->input('url'),
                $request->input('type'),
                $request->input('categories', []),
                $validateResult->getLocalizedInputs()
            );
        } catch (KatnissException $ex) {
            return $error_redirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('media-items'));
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
        $media = $this->mediaRepository->model($id);
        $categoryRepository = new MediaCategoryRepository();

        $this->_title([trans('pages.admin_media_items_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_media_items_desc'));

        return $this->_edit([
            'media' => $media,
            'media_categories' => $media->categories,
            'categories' => $categoryRepository->getAll(),
            'types' => [
                Media::TYPE_PHOTO => trans_choice('label.photo', 1),
                Media::TYPE_VIDEO => trans_choice('label.video', 1)
            ],
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
        $media = $this->mediaRepository->model($id);

        $redirect = redirect(adminUrl('media-items/{id}/edit', ['id' => $media->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'title' => 'required|max:255',
            'description' => 'sometimes|nullable|max:255',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'sometimes|nullable|exists:categories,id,type,' . Category::TYPE_MEDIA,
            'url' => 'required|url',
            'type' => 'required|in:' . implode(',', [Media::TYPE_PHOTO, Media::TYPE_VIDEO]),
        ]);
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->mediaRepository->update(
                $request->input('url'),
                $request->input('type'),
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
        $this->mediaRepository->model($id);
        $this->_rdrUrl($request, adminUrl('media-items'), $rdrUrl, $errorRdrUrl);

        try {
            $this->mediaRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }
        return redirect($rdrUrl);
    }
}
