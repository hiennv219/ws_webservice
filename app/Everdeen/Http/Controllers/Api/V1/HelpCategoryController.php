<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Post;
use Katniss\Everdeen\Repositories\HelpCategoryRepository;

class HelpCategoryController extends ApiController
{
    protected $helpCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->helpCategoryRepository = new HelpCategoryRepository();
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
        $this->helpCategoryRepository->model($id);

        if (!$this->customValidate($request, [
            'help_ids' => 'required|array|exists:posts,id,type,' . Post::TYPE_HELP,
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->helpCategoryRepository->updateSort($request->input('help_ids'));
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }
}
