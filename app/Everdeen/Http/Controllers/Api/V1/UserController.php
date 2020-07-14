<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\UserRepository;

class UserController extends ApiController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->userRepository = new UserRepository();
    }

    public function postAvatarUsingCropperJs(Request $request, $id)
    {
        $this->userRepository->model($id);

        if (!$this->customValidate($request, [
            'cropper_image_file' => 'required',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $user = $this->userRepository->updateAvatarByCropperJs(
                $request->file('cropper_image_file')->getRealPath(),
                $request->input('cropper_image_data')
            );

            return $this->responseSuccess([
                'store_path' => $user->url_avatar,
                'store_path_thumb' => $user->url_avatar_thumb,
            ]);
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
