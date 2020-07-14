<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\LinkCategoryRepository;

class LinkCategoryController extends ApiController
{
    protected $linkCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->linkCategoryRepository = new LinkCategoryRepository();
    }

    public function update(Request $request, $id)
    {
        if ($request->has('sort')) {
            return $this->updateSort($request, $id);
        }

        return $this->responseFail();
    }

    public function updateSort(Request $request, $id)
    {
        $this->linkCategoryRepository->model($id);

        if (!$this->customValidate($request, [
            'link_ids' => 'required|array|exists:links,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->linkCategoryRepository->updateSort($request->input('link_ids'));
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }
}
