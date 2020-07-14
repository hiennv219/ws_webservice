<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\UserRepository;

class AccountController extends AdminController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'my_account';
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        $this->_title(trans('pages.my_account_title'));
        $this->_description(trans('pages.my_account_desc'));

        return $this->_view();
    }

    public function update(Request $request)
    {
        $this->userRepository->model($request->authUser());

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|password',
            'display_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->authUser()->id . ',id',
            'name' => 'required|max:255|unique:users,name,' . $request->authUser()->id . ',id',
            'password' => 'sometimes|nullable|confirmed|min:6',
        ]);

        $redirect = redirect(meUrl('account'));

        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->userRepository->update(
                $request->input('name'),
                $request->input('display_name'),
                $request->input('email'),
                $request->input('password', '')
            );
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect->with('successes', [trans('error.success')]);
    }
}
