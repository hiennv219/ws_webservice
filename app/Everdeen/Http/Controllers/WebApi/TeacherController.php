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
use Katniss\Everdeen\Models\Teacher;
use Katniss\Everdeen\Repositories\TeacherRepository;
use Katniss\Everdeen\Utils\DataStructure\Pagination\Pagination;

class TeacherController extends WebApiController
{
    protected $teacherRepository;

    public function __construct()
    {
        parent::__construct();

        $this->teacherRepository = new TeacherRepository();
    }

    public function index(Request $request)
    {
        if ($request->has('q')) {
            return $this->indexCommon($request);
        }

        return $this->responseFail();
    }

    public function indexCommon(Request $request)
    {
        try {
            $teachers = $this->teacherRepository->getSearchCommonPaged($request->input('q'));
            $pagination = new Pagination($teachers);
            $teachers = $teachers->map(function (Teacher $teacher) {
                $user = $teacher->userProfile;
                return [
                    'id' => $teacher->user_id,
                    'url_avatar_thumb' => $user->url_avatar_thumb,
                    'display_name' => $user->display_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'skype_id' => $user->skype_id,
                    'phone' => $user->phone,
                ];
            });
            return $this->responseSuccess([
                'teachers' => $teachers,
                'pagination' => $pagination->toArray(),
            ]);
        } catch (\Exception $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }
}