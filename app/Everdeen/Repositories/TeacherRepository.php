<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Events\PasswordChanged;
use Katniss\Everdeen\Events\UserCreated;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Mail\BaseMailable;
use Katniss\Everdeen\Models\Classroom;
use Katniss\Everdeen\Models\Review;
use Katniss\Everdeen\Models\Teacher;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserSetting;
use Katniss\Everdeen\Utils\AppConfig;

class TeacherRepository extends ModelRepository
{
    public function getById($id)
    {
        return Teacher::with(['userProfile', 'topics', 'topics.translations'])->findOrFail($id);
    }

    public function getPaged()
    {
        return Teacher::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getCountApproved()
    {
        return Teacher::approved()->count();
    }

    public function getCountRegistering()
    {
        return Teacher::where('status', '<>', Teacher::APPROVED)->count();
    }

    public function getCountAfterDate($date)
    {
        return Teacher::whereDate('created_at', '>=', $date)->count();
    }

    public function getApprovedByIds($ids)
    {
        return Teacher::with('userProfile')
            ->approved()
            ->whereIn('user_id', $ids)
            ->get();
    }

    public function getSearchCommonPaged($term = null)
    {
        $teacher = Teacher::with('userProfile')
            ->approved()
            ->orderBy('created_at', 'desc');
        if (!empty($term)) {
            $teacher->whereHas('userProfile', function ($query) use ($term) {
                $query->where('users.id', $term);
                $query->orWhere('users.display_name', 'like', '%' . $term . '%');
                $query->orWhere('users.name', 'like', '%' . $term . '%');
                $query->orWhere('users.email', 'like', '%' . $term . '%');
                $query->orWhere('users.skype_id', 'like', '%' . $term . '%');
                $query->orWhere('users.phone_number', 'like', '%' . $term . '%');
            });
        }
        return $teacher->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchApprovedPaged($displayName = null, $email = null, $skypeId = null, $phoneNumber = null)
    {
        $teacher = Teacher::with('userProfile')
            ->approved()
            ->orderBy('created_at', 'desc');
        if (!empty($displayName) || !empty($email) || !empty($skypeId) || !empty($phoneNumber)) {
            $teacher->whereHas('userProfile', function ($query) use ($displayName, $email, $skypeId, $phoneNumber) {
                if (!empty($displayName)) {
                    $query->where('display_name', 'like', '%' . $displayName . '%');
                }
                if (!empty($email)) {
                    $query->where('email', 'like', '%' . $email . '%');
                }
                if (!empty($skypeId)) {
                    $query->where('skype_id', 'like', '%' . $skypeId . '%');
                }
                if (!empty($phoneNumber)) {
                    $query->where('phone_number', 'like', '%' . $phoneNumber . '%');
                }
            });
        }
        return $teacher->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchRegisteringPaged($displayName = null, $email = null, $skypeId = null, $phoneNumber = null)
    {
        $teacher = Teacher::with('userProfile')
            ->where('status', '<>', Teacher::APPROVED)
            ->orderBy('created_at', 'desc');
        if (!empty($displayName) || !empty($email) || !empty($skypeId) || !empty($phoneNumber)) {
            $teacher->whereHas('userProfile', function ($query) use ($displayName, $email, $skypeId, $phoneNumber) {
                if (!empty($displayName)) {
                    $query->where('display_name', 'like', '%' . $displayName . '%');
                }
                if (!empty($email)) {
                    $query->where('email', 'like', '%' . $email . '%');
                }
                if (!empty($skypeId)) {
                    $query->where('skype_id', 'like', '%' . $skypeId . '%');
                }
                if (!empty($phoneNumber)) {
                    $query->where('phone_number', 'like', '%' . $phoneNumber . '%');
                }
            });
        }
        return $teacher->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getHomeSearchPaged(array $topics = null, $nationality = null, $gender = null)
    {
        $teachers = Teacher::with(['userProfile', 'topics', 'topics.translations'])
            ->approved()
            ->orderBy('created_at', 'asc');

        if (!empty($topics)) {
            $teachers->whereHas('topics', function ($query) use ($topics) {
                $query->whereIn('id', $topics);
            });
        }
        if (!empty($nationality) || !empty($gender)) {
            $teachers->whereHas('userProfile', function ($query) use ($nationality, $gender) {
                if (!empty($nationality)) {
                    $query->where('nationality', $nationality);
                }
                if (!empty($gender)) {
                    $query->where('gender', $gender);
                }
            });
        }

        return $teachers->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Teacher::all();
    }

    protected function generateNameFromEmail($email)
    {
        $emailName = strtok($email, '@');
        $name = $emailName;
        $i = 0;
        while (User::where('name', $name)->count() > 0) {
            $name = $emailName . '-' . (++$i);
        }
        return $name;
    }

    public function create($displayName, $email, $password, $skypeId, $phoneCode, $phoneNumber)
    {
        DB::beginTransaction();
        try {
            $settings = UserSetting::create();

            $user = User::create(array(
                'display_name' => $displayName,
                'email' => $email,
                'name' => $this->generateNameFromEmail($email),
                'password' => bcrypt($password),
                'url_avatar' => appDefaultUserProfilePicture(),
                'url_avatar_thumb' => appDefaultUserProfilePicture(),
                'activation_code' => str_random(32),
                'active' => true,
                'setting_id' => $settings->id,
                'skype_id' => $skypeId,
                'phone_code' => $phoneCode,
                'phone_number' => $phoneNumber,
            ));

            $roleRepository = new RoleRepository();
            $roles = $roleRepository->getByNames(['user', 'teacher'])->pluck('id')->all();
            $user->attachRoles($roles);

            $teacher = Teacher::create([
                'user_id' => $user->id,
            ]);

            event(new UserCreated($user, $password, false,
                array_merge(request()->getTheme()->viewParams(), [
                    BaseMailable::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                    BaseMailable::EMAIL_TO => $email,
                    BaseMailable::EMAIL_TO_NAME => $displayName,
                ])
            ));

            DB::commit();

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createAdmin(array $userAttributes, array $teacherAttributes, array $topics, $country,
                                $isApproved, $approvingUserId, $sendWelcomeMail)
    {
        DB::beginTransaction();
        try {
            $settings = UserSetting::create([
                'country' => $country,
            ]);

            $password = $userAttributes['password'];
            $displayName = $userAttributes['display_name'];
            $email = $userAttributes['email'];
            $userAttributes = array_merge([
                'url_avatar' => appDefaultUserProfilePicture(),
                'url_avatar_thumb' => appDefaultUserProfilePicture(),
                'activation_code' => str_random(32),
                'active' => true,
                'setting_id' => $settings->id,
            ], $userAttributes);
            $userAttributes['password'] = bcrypt($userAttributes['password']);

            $user = User::create($userAttributes);
            $roleRepository = new RoleRepository();
            $roles = $roleRepository->getByNames(['user', 'teacher'])->pluck('id')->all();
            $user->attachRoles($roles);

            $teacherAttributes['user_id'] = $user->id;
            if ($isApproved) {
                $teacherAttributes['status'] = Teacher::APPROVED;
                $teacherAttributes['approving_user_id'] = $approvingUserId;
                $teacherAttributes['approving_at'] = date('Y-m-d');
            } else {
                $teacherAttributes['status'] = Teacher::REQUESTED;
            }
            $teacher = Teacher::create($teacherAttributes);
            $teacher->topics()->attach($topics);

            if ($sendWelcomeMail) {
                event(new UserCreated($user, $password, false,
                    array_merge(request()->getTheme()->viewParams(), [
                        BaseMailable::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                        BaseMailable::EMAIL_TO => $email,
                        BaseMailable::EMAIL_TO_NAME => $displayName,
                    ])
                ));
            }

            DB::commit();

            return $teacher;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateAdmin(array $userAttributes, array $teacherAttributes, array $topics, $country)
    {
        $teacher = $this->model();

        DB::beginTransaction();
        try {
            $teacher->update($teacherAttributes);
            if ($teacher->topics()->count() > 0) {
                $teacher->topics()->sync($topics);
            } else {
                $teacher->topics()->attach($topics);
            }

            $passwordChanged = false;
            $password = $userAttributes['password'];
            if (!empty($password)) {
                $userAttributes['password'] = bcrypt($userAttributes['password']);
            } else {
                unset($userAttributes['password']);
            }
            $displayName = $userAttributes['display_name'];
            $email = $userAttributes['email'];
            $user = $teacher->userProfile;
            $user->update($userAttributes);
            $user->settings()->update([
                'country' => $country,
            ]);

            if ($passwordChanged) {
                event(new PasswordChanged($user, $password,
                    array_merge(request()->getTheme()->viewParams(), [
                        BaseMailable::EMAIL_SUBJECT => '[' . appName() . '] ' .
                            trans('form.action_change') . ' ' . trans('label.password'),
                        BaseMailable::EMAIL_TO => $email,
                        BaseMailable::EMAIL_TO_NAME => $displayName,
                    ])
                ));
            }

            DB::commit();

            return $teacher;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateWhenSigningUp($topics, $aboutMe, $experience, $methodology, $certificates, $otherCertificates, $videoIntroduceUrl)
    {
        $teacher = $this->model();

        try {
            $storedCertificates = [];
            foreach ((array)$certificates as $certificate) {
                $certificate = strtolower($certificate);
                $storedCertificates[$certificate] = $certificate == 'others' ?
                    $otherCertificates : $certificate;
            }
            $teacher->update([
                'about_me' => $aboutMe,
                'experience' => $experience,
                'methodology' => $methodology,
                'video_introduce_url' => $videoIntroduceUrl,
                'certificates' => serialize($storedCertificates),
            ]);
            if ($teacher->topics()->count() > 0) {
                $teacher->topics()->sync($topics);
            } else {
                $teacher->topics()->attach($topics);
            }
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateInformation($topics, $aboutMe, $experience, $methodology, $videoIntroduceUrl, $videoTeachingUrl)
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'about_me' => $aboutMe,
                'experience' => $experience,
                'methodology' => $methodology,
                'video_introduce_url' => $videoIntroduceUrl,
                'video_teaching_url' => $videoTeachingUrl,
            ]);
            if ($teacher->topics()->count() > 0) {
                $teacher->topics()->sync($topics);
            } else {
                $teacher->topics()->attach($topics);
            }
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updatePaymentInfo($country, array $data)
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'payment_info' => serialize([
                    'country' => $country,
                    'data' => $data,
                ]),
            ]);
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateAvailableTimes(array $times, array $rangeFrom = [], array $rangeTo = [])
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'available_times' => serialize([
                    'times' => $times,
                    'range_from' => $rangeFrom,
                    'range_to' => $rangeTo,
                ]),
            ]);
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function view()
    {
        $teacher = $this->model();
        try {
            $teacher->increment('viewed');
            return true;
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_update') . ' (' . $exception->getMessage() . ')');
        }
    }

    public function reject()
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'status' => Teacher::REJECTED,
            ]);
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function approve($approvingUserId)
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'status' => Teacher::APPROVED,
                'approving_user_id' => $approvingUserId,
                'approving_at' => date('Y-m-d H:i:s'),
            ]);
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function fullSchedule()
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'teaching_status' => Teacher::TEACHING_STATUS_FULL_SCHEDULE,
            ]);
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function available()
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'teaching_status' => Teacher::TEACHING_STATUS_AVAILABLE,
            ]);
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $teacher = $this->model();

        try {
            $roleRepository = new RoleRepository();
            $teacher->userProfile->detachRole($roleRepository->getByName('teacher')->id);
            $teacher->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function getReviewOfTeacher($teacherId){
        $classRooms = Classroom::with(['classTimes'])
            ->ofTeacher($teacherId)
            ->get()
            ->toArray();

        $classTimeIds = [];
        foreach ($classRooms as $class){
            foreach ($class["class_times"] as $classTime){
                array_push($classTimeIds, $classTime["id"]);
            }
        }

        return Review::whereHas('classTimes',    function($query) use ($classTimeIds) {
            $query->whereIn('class_time_id', $classTimeIds);
        })->where('user_id','NOT LIKE',$teacherId)
            ->orderBy("created_at", 'desc')

            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);

    }
}