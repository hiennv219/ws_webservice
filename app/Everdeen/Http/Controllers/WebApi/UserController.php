<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 22:36
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Utils\DataStructure\Pagination\Pagination;

class UserController extends WebApiController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->userRepository = new UserRepository();
    }

    public function index(Request $request)
    {
        if ($request->has('normal_role')) {
            return $this->indexNormalRole($request);
        }

        $this->responseFail();
    }

    public function indexSupporter(Request $request)
    {
        if ($request->has('q')) {
            return $this->indexSupporterCommon($request);
        }

        return $this->responseFail();
    }

    public function indexSupporterCommon(Request $request)
    {
        try {
            $users = $this->userRepository->getSupporterSearchCommonPaged($request->input('q'));
            $pagination = new Pagination($users);
            $users = $users->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'url_avatar_thumb' => $user->url_avatar_thumb,
                    'display_name' => $user->display_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'skype_id' => $user->skype_id,
                    'phone' => $user->phone,
                ];
            });
            return $this->responseSuccess([
                'supporters' => $users,
                'pagination' => $pagination->toArray(),
            ]);
        } catch (\Exception $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }

    public function indexAuthor(Request $request)
    {
        if ($request->has('q')) {
            return $this->indexAuthorCommon($request);
        }

        return $this->responseFail();
    }

    public function indexAuthorCommon(Request $request)
    {
        try {
            $users = $this->userRepository->getAuthorSearchCommonPaged($request->input('q'));
            $pagination = new Pagination($users);
            $users = $users->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'url_avatar_thumb' => $user->url_avatar_thumb,
                    'display_name' => $user->display_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'skype_id' => $user->skype_id,
                    'phone' => $user->phone,
                ];
            });
            return $this->responseSuccess([
                'authors' => $users,
                'pagination' => $pagination->toArray(),
            ]);
        } catch (\Exception $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }

    public function indexNormalRole(Request $request)
    {
        if ($request->has('q')) {
            return $this->indexNormalRoleCommon($request);
        }

        return $this->responseFail();
    }

    public function indexNormalRoleCommon(Request $request)
    {
        try {
            $users = $this->userRepository->getNormalRoleSearchCommonPaged($request->input('q'));
            $pagination = new Pagination($users);
            $users = $users->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'url_avatar_thumb' => $user->url_avatar_thumb,
                    'display_name' => $user->display_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'skype_id' => $user->skype_id,
                    'phone' => $user->phone,
                ];
            });
            return $this->responseSuccess([
                'users' => $users,
                'pagination' => $pagination->toArray(),
            ]);
        } catch (\Exception $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }

    public function getCsrfToken()
    {
        return $this->responseSuccess([
            'csrf_token' => csrf_token()
        ]);
    }

    public function getQuickLogin(Request $request)
    {
        if (!$this->customValidate($request, [
            'id' => 'required',
            'password' => 'required',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }


        if (!auth()->attempt($request->only('id', 'password'))) {
            return $this->responseFail();
        }

        return $this->responseSuccess([
            'csrf_token' => csrf_token(),
            'user' => auth()->user()
        ]);
    }
}