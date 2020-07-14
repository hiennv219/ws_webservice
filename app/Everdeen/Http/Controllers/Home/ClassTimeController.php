<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-11
 * Time: 09:25
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ClassTimeRepository;

class ClassTimeController extends ViewController
{
    protected $classTimeRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'class_time';
        $this->classTimeRepository = new ClassTimeRepository();
    }

    public function confirm(Request $request, $id)
    {
        $classTime = $this->classTimeRepository->model($id);

        $classroom = $classTime->classroom;
        $user = $request->authUser();
        $reviewUserId = null;
        if (!$user->hasRole('student')) {
            abort(404);
        }
        if ($classroom->student_id != $user->id) {
            abort(404);
        }

        try {
            $this->classTimeRepository->confirm();

            return redirect(homeUrl('classrooms/{id}', ['id' => $classroom->id]));
        } catch (KatnissException $exception) {
            abort(500, $exception->getMessage());
            return '';
        }
    }

    public function destroy(Request $request, $id)
    {
        $classTime = $this->classTimeRepository->model($id);
        $classroom = $classTime->classroom;
        $user = $request->authUser();
        $userCanDeleteClassTime = false;
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                    abort(404);
                }
            } else {
                $userCanDeleteClassTime = true;
            }
        } elseif ($user->hasRole('supporter')) {
            if ($classroom->supporter_id != $user->id) {
                if (!$user->hasRole(['manager', 'admin'])) {
                    abort(404);
                }
            } else {
                $userCanDeleteClassTime = true;
            }
        }
        if ($user->hasRole(['manager', 'admin'])) {
            $userCanDeleteClassTime = true;
        }
        if (!$userCanDeleteClassTime
            || !$classroom->isOpening
        ) {
            abort(404);
        }

        $rdrUrl = homeUrl('classrooms/{id}', ['id' => $classroom->id]);

        try {
            $this->classTimeRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($rdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}