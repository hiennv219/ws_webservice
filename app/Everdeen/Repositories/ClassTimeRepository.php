<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Events\ClassTimeCreated;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Mail\BaseMailable;
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Utils\AppConfig;

class ClassTimeRepository extends ModelRepository
{
    public function getById($id)
    {
        return ClassTime::where('id', $id)->firstOrFail();
    }

    public function getPaged()
    {
        return ClassTime::orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return ClassTime::all();
    }

    public function create($classroomId, $subject, $hours, $startAt, $content = null)
    {
        DB::beginTransaction();
        try {
            $classTime = ClassTime::create([
                'classroom_id' => $classroomId,
                'subject' => $subject,
                'content' => $content,
                'hours' => $hours,
                'start_at' => $startAt,
                'confirmed' => ClassTime::CONFIRMED_FALSE,
            ]);

            $countClassTimes = $classTime->classroom->countClassTimes;
            if ($countClassTimes % _k('periodic_class_time') == 0) {
                $classTimePeriodic = ClassTime::create([
                    'classroom_id' => $classroomId,
                    'subject' => $countClassTimes,
                    'content' => '',
                    'hours' => 0,
                    'start_at' => $startAt,
                    'type' => ClassTime::TYPE_PERIODIC,
                ]);
            }

            $studentUserProfile = $classTime->classroom->studentUserProfile;
            event(new ClassTimeCreated($studentUserProfile, $classTime, array_merge(request()->getTheme()->viewParams(), [
                BaseMailable::EMAIL_SUBJECT => '[' . appName() . '] ' . trans_choice('label.class_time', 1) . ': ' . $classTime->subject,
                BaseMailable::EMAIL_TO => $studentUserProfile->email,
                BaseMailable::EMAIL_TO_NAME => $studentUserProfile->display_name,
            ])));

            logInfo('Class time created.', $classTime->toArray());

            DB::commit();
            return empty($classTimePeriodic) ? $classTime : [$classTime, $classTimePeriodic];
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createReview($userId, $rate, $review = null)
    {
        $classTime = $this->model();

        try {
            $review = $classTime->reviews()->create([
                'user_id' => $userId,
                'rate' => $rate,
                'rates' => serialize([]),
                'review' => $review,
            ]);

            logInfo('Class time reviewed.', [
                'class_time_id' => $classTime->id,
                'review' => $review->toArray(),
            ]);

            return $review;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createRichReview($userId, array $rates, $review = null)
    {
        $classTime = $this->model();

        try {
            $review = $classTime->reviews()->create([
                'user_id' => $userId,
                'rates' => serialize($rates),
                'review' => $review,
            ]);

            logInfo('Class time rich reviewed.', [
                'class_time_id' => $classTime->id,
                'review' => $review->toArray(),
            ]);

            return $review;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($subject, $content = null)
    {
        $classTime = $this->model();

        try {
            logInfo('Class time before updated.', $classTime->toArray());

            $classTime->update([
                'subject' => $subject,
                'content' => $content,
            ]);

            logInfo('Class time updated.', $classTime->toArray());

            return $classTime;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function confirm()
    {
        $classTime = $this->model();

        try {
            logInfo('Class time before updated.', $classTime->toArray());

            $classTime->update([
                'confirmed' => ClassTime::CONFIRMED_TRUE,
            ]);

            logInfo('Class time updated.', $classTime->toArray());

            return $classTime;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $classTime = $this->model();

        DB::beginTransaction();
        try {
            $nextClassTimes = ClassTime::where('classroom_id', $classTime->classroom_id)
                ->where('start_at', '>=', $classTime->start_at)
                ->where('id', '>', $classTime->id)
                ->orderBy('start_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
            if ($nextClassTimes->count() > 1) {
                throw new \Exception(trans('error._cannot_delete', ['reason' => trans('error.not_last_class_time')]));
            } elseif ($nextClassTimes->count() > 0) {
                $nextClassTime = $nextClassTimes->first();
                if ($nextClassTime->type == ClassTime::TYPE_PERIODIC) {
                    $nextClassTime->delete();
                } else {
                    throw new \Exception(trans('error._cannot_delete', ['reason' => trans('error.not_last_class_time')]));
                }
            }
            $classTime->delete();

            logInfo('Class time deleted.', $classTime->toArray());

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}