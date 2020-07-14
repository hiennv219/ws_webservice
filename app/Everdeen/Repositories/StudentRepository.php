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
use Katniss\Everdeen\Models\Student;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserSetting;
use Katniss\Everdeen\Utils\AppConfig;

class StudentRepository extends ModelRepository
{
    public function getById($id)
    {
        return Student::with('userProfile')
            ->findOrFail($id);
    }

    public function getPaged()
    {
        return Student::with('userProfile')
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getCountApproved()
    {
        return Student::approved()->count();
    }

    public function getCountRegistering()
    {
        return Student::where('status', '<>', Student::APPROVED)->count();
    }

    public function getCountAfterDate($date)
    {
        return Student::whereDate('created_at', '>=', $date)->count();
    }

    public function getSearchCommonPaged($term = null)
    {
        $teacher = Student::with('userProfile')
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
        $student = Student::with(['userProfile', 'learningRequest'])
            ->approved()
            ->orderBy('created_at', 'desc');
        if (!empty($displayName) || !empty($email) || !empty($skypeId) || !empty($phoneNumber)) {
            $student->whereHas('userProfile', function ($query) use ($displayName, $email, $skypeId, $phoneNumber) {
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
        return $student->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchPagedByAgentId($agentId, $displayName = null, $email = null, $skypeId = null, $phoneNumber = null)
    {
        $student = Student::with(['userProfile', 'learningRequest'])
            ->where('agent_id', $agentId)
            ->orderBy('created_at', 'desc');
        if (!empty($displayName) || !empty($email) || !empty($skypeId) || !empty($phoneNumber)) {
            $student->whereHas('userProfile', function ($query) use ($displayName, $email, $skypeId, $phoneNumber) {
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
        return $student->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchRegisteringPaged($displayName = null, $email = null, $skypeId = null, $phoneNumber = null)
    {
        $student = Student::with(['userProfile', 'learningRequest'])
            ->where('status', '<>', Student::APPROVED)
            ->orderBy('created_at', 'desc');
        if (!empty($displayName) || !empty($email) || !empty($skypeId) || !empty($phoneNumber)) {
            $student->whereHas('userProfile', function ($query) use ($displayName, $email, $skypeId, $phoneNumber) {
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
        return $student->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Student::with('userProfile')
            ->all();
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

    public function create($displayName, $email, $password, $phoneCode, $phoneNumber, $agent = null)
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
                'phone_code' => $phoneCode,
                'phone_number' => $phoneNumber,
            ));
            $user->save();

            $roleRepository = new RoleRepository();
            $roles = $roleRepository->getByNames(['user', 'student'])->pluck('id')->all();
            $user->attachRoles($roles);

            if (!empty($agent)) {
                $agentUser = User::find($agent);
                if (!$agentUser->hasRole('student_agent')) {
                    $agent = null;
                }
            }

            $student = Student::create([
                'user_id' => $user->id,
                'agent_id' => $agent,
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

    public function createAdmin(array $userAttributes, $country,
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
            $roles = $roleRepository->getByNames(['user', 'student'])->pluck('id')->all();
            $user->attachRoles($roles);

            $studentAttributes = ['user_id' => $user->id];
            if ($isApproved) {
                $studentAttributes['status'] = Student::APPROVED;
                $studentAttributes['approving_user_id'] = $approvingUserId;
                $studentAttributes['approving_at'] = date('Y-m-d');
            } else {
                $studentAttributes['status'] = Student::REQUESTED;
            }
            $student = Student::create($studentAttributes);

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

            return $student;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateAdmin(array $userAttributes, $country)
    {
        $student = $this->model();

        DB::beginTransaction();
        try {
            $passwordChanged = false;
            $password = $userAttributes['password'];
            if (!empty($password)) {
                $userAttributes['password'] = bcrypt($userAttributes['password']);
            } else {
                unset($userAttributes['password']);
            }
            $displayName = $userAttributes['display_name'];
            $email = $userAttributes['email'];
            $user = $student->userProfile;
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

            return $student;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function reject()
    {
        $student = $this->model();

        try {
            $student->update([
                'status' => Student::REJECTED,
            ]);
            return $student;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function approve($approvingUserId)
    {
        $student = $this->model();

        try {
            $student->update([
                'status' => Student::APPROVED,
                'approving_user_id' => $approvingUserId,
                'approving_at' => date('Y-m-d H:i:s'),
            ]);
            return $student;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $student = $this->model();

        try {
            $roleRepository = new RoleRepository();
            $student->userProfile->detachRole($roleRepository->getByName('student')->id);
            $student->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}