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
use Katniss\Everdeen\Models\Student;
use Katniss\Everdeen\Repositories\StudentRepository;
use Katniss\Everdeen\Utils\DataStructure\Pagination\Pagination;

class StudentController extends WebApiController
{
    protected $studentRepository;

    public function __construct()
    {
        parent::__construct();

        $this->studentRepository = new StudentRepository();
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
            $students = $this->studentRepository->getSearchCommonPaged($request->input('q'));
            $pagination = new Pagination($students);
            $students = $students->map(function (Student $student) {
                $user = $student->userProfile;
                return [
                    'id' => $student->user_id,
                    'url_avatar_thumb' => $user->url_avatar_thumb,
                    'display_name' => $user->display_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'skype_id' => $user->skype_id,
                    'phone' => $user->phone,
                ];
            });
            return $this->responseSuccess([
                'students' => $students,
                'pagination' => $pagination->toArray(),
            ]);
        } catch (\Exception $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }
}