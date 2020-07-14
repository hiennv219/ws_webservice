<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Classroom;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassroomRepository extends ModelRepository
{
    public function getById($id)
    {
        return Classroom::with(['teacherProfile', 'teacherUserProfile', 'studentProfile', 'studentUserProfile', 'supporter'])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getPaged()
    {
        return Classroom::orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getCountOpening()
    {
        return Classroom::opening()->count();
    }

    public function getCountClosed()
    {
        return Classroom::closed()->count();
    }

    public function getCountAfterDate($date)
    {
        return Classroom::whereDate('created_at', '>=', $date)->count();
    }

    public function getByTeacherPaged($teacherId, $status = Classroom::STATUS_OPENING)
    {
        return Classroom::with(['teacherUserProfile', 'studentUserProfile', 'supporter'])
            ->ofTeacher($teacherId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getByStudentPaged($studentId, $status = Classroom::STATUS_OPENING)
    {
        return Classroom::with(['teacherUserProfile', 'studentUserProfile', 'supporter'])
            ->ofStudent($studentId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getBySupporterPaged($supporterId, $status = Classroom::STATUS_OPENING)
    {
        return Classroom::with(['teacherUserProfile', 'studentUserProfile', 'supporter'])
            ->ofSupporter($supporterId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchOpeningPaged($name = null, $teacher = null, $student = null, $supporter = null)
    {
        $classrooms = Classroom::with(['teacherUserProfile', 'studentUserProfile', 'supporter'])
            ->opening()
            ->orderBy('created_at', 'desc');
        if (!empty($name)) {
            $classrooms->where('name', 'like', '%' . $name . '%');
        }
        if (!empty($teacher)) {
            $classrooms->where('teacher_id', $teacher);
        }
        if (!empty($student)) {
            $classrooms->where('student_id', $student);
        }
        if (!empty($supporter)) {
            $classrooms->where('supporter_id', $supporter);
        }
        return $classrooms->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchClosedPaged($name = null, $teacher = null, $student = null, $supporter = null)
    {
        $classrooms = Classroom::with(['teacherUserProfile', 'studentUserProfile', 'supporter'])
            ->closed()
            ->orderBy('created_at', 'desc');
        if (!empty($name)) {
            $classrooms->where('name', 'like', '%' . $name . '%');
        }
        if (!empty($teacher)) {
            $classrooms->where('teacher_id', $teacher);
        }
        if (!empty($student)) {
            $classrooms->where('student_id', $student);
        }
        if (!empty($supporter)) {
            $classrooms->where('supporter_id', $supporter);
        }
        return $classrooms->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchReadyToClosePaged($name = null, $teacher = null, $student = null, $supporter = null)
    {
        $classrooms = Classroom::with(['teacherUserProfile', 'studentUserProfile', 'supporter'])
            ->readyToClose()
            ->orderBy('created_at', 'desc');
        if (!empty($name)) {
            $classrooms->where('name', 'like', '%' . $name . '%');
        }
        if (!empty($teacher)) {
            $classrooms->where('teacher_id', $teacher);
        }
        if (!empty($student)) {
            $classrooms->where('student_id', $student);
        }
        if (!empty($supporter)) {
            $classrooms->where('supporter_id', $supporter);
        }
        return $classrooms->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Classroom::with(['teacherUserProfile', 'studentUserProfile', 'supporter'])
            ->get();
    }

    public function create($teacherId, $studentId, $supporterId, $name, $duration, $comment, $hours_salary)
    {
        try {
            $classroom = Classroom::create([
                'teacher_id' => $teacherId,
                'student_id' => $studentId,
                'supporter_id' => $supporterId,
                'name' => $name,
                'hours' => NumberFormatHelper::getInstance()->fromFormat($duration),
                'status' => Classroom::STATUS_OPENING,
                'comment' => $comment,
                'hours_salary' => NumberFormatHelper::getInstance()->fromFormat($hours_salary),
            ]);

            logInfo('Classroom created.', $classroom->toArray());

            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($teacherId, $studentId, $supporterId, $name, $duration, $comment, $hours_salary)
    {
        $classroom = $this->model();

        try {
            logInfo('Classroom before updated.', $classroom->toArray());

            $data = [
                'name' => $name,
                'hours' => NumberFormatHelper::getInstance()->fromFormat($duration),
            ];
            if (!empty($teacherId)) {
                $data['teacher_id'] = $teacherId;
            }
            if (!empty($studentId)) {
                $data['student_id'] = $studentId;
            }
            if (!empty($supporterId)) {
                $supporter = User::find($supporterId);
                if (!$supporter->hasRole('supporter')) {
                    throw new \Exception(trans('error.is_not_role_supporter'));
                }
                $data['supporter_id'] = $supporterId;
            }
            $data['comment'] = $comment;
            $data['hours_salary'] = NumberFormatHelper::getInstance()->fromFormat($hours_salary);
            $classroom->update($data);

            logInfo('Classroom updated.', $classroom->toArray());

            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateName($name)
    {
        $classroom = $this->model();

        try {
            logInfo('Classroom before updated.', $classroom->toArray());

            $classroom->update([
                'name' => $name,
            ]);

            logInfo('Classroom updated.', $classroom->toArray());

            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function open()
    {
        $classroom = $this->model();

        try {
            $classroom->update([
                'status' => Classroom::STATUS_OPENING,
            ]);

            logInfo('Classroom opened.', $classroom->toArray());

            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function close($userId)
    {
        $classroom = $this->model();

        try {
            $classroom->update([
                'closed_by' => $userId,
                'closed_at' => date('Y-m-d H:i:s'),
                'status' => Classroom::STATUS_CLOSED,
            ]);

            logInfo('Classroom closed.', $classroom->toArray());

            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $classroom = $this->model();

        try {
            $classroom->delete();

            logInfo('Classroom deleted.', $classroom->toArray());

            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}