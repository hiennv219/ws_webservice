<?php

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\MediaCategoryRepository;

class MediaCategoryController extends WebApiController
{
    protected $mediaCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->mediaCategoryRepository = new MediaCategoryRepository();
    }

    public function update(Request $request, $id)
    {
        if ($request->has('sort')) {
            return $this->updateSort($request, $id);
        }

        $category = $this->mediaCategoryRepository->model($id);

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:category_translations,slug,' . $category->id . ',category_id',
        ]);

        if ($validateResult->isFailed()) {
            return $this->responseFail($validateResult->getFailed());
        }

        $parentId = intval($request->input('parent'), 0);
        if ($parentId != 0) {
            if (!$this->customValidate($request, [
                'parent' => 'sometimes|nullable|exists:categories,id,type,' . Category::TYPE_MEDIA
            ])
            ) {
                return $this->responseFail($this->getValidationErrors());
            }
        }

        try {
            $this->mediaCategoryRepository->update($parentId, $validateResult->getLocalizedInputs());
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }

    protected function updateSort(Request $request, $id)
    {
        $this->mediaCategoryRepository->model($id);

        if (!$this->customValidate($request, [
            'media_ids' => 'required|array|exists:media,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->mediaCategoryRepository->updateSort($request->input('media_ids'));
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }
}
