<?php

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\UserRepository;

class AccountController extends WebApiController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('theme')->only('updatePassword');

        $this->userRepository = new UserRepository();
    }

    public function updatePassword(Request $request)
    {
        $this->userRepository->model($request->authUser());

        if (!$this->customValidate($request, [
            'current_password' => 'required|password',
            'password' => 'required|confirmed|min:6',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->userRepository->updatePassword(
                $request->input('password')
            );

            return $this->responseSuccess();
        } catch (KatnissException $ex) {
            return $this->responseFail();
        }
    }

    public function updateSkypeId(Request $request)
    {
        $this->userRepository->model($request->authUser());

        if (!$this->customValidate($request, [
            'skype_id' => 'required|max:255',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->userRepository->updateSkypeId(
                $request->input('skype_id')
            );

            return $this->responseSuccess();
        } catch (KatnissException $ex) {
            return $this->responseFail();
        }
    }

    public function storeFacebookConnect(Request $request)
    {
        $this->userRepository->model($request->authUser());

        if (!$this->customValidate($request, [
            'id' => 'required',
            'avatar' => 'required|url',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->userRepository->createFacebookConnection(
                $request->input('id'),
                $request->input('avatar')
            );

            return $this->responseSuccess();
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }

    public function storeFacebookDisconnect(Request $request)
    {
        $this->userRepository->model($request->authUser());

        try {
            $this->userRepository->removeFacebookConnection();

            return $this->responseSuccess();
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
