<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Events\UserCreated;
use Katniss\Everdeen\Events\PasswordChanged;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Mail\BaseMailable;
use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Models\Student;
use Katniss\Everdeen\Models\Teacher;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserSetting;
use Katniss\Everdeen\Models\UserSocial;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\Storage\StorePhotoByCropperJs;

class UserRepository extends ModelRepository
{
    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function getByIdLoosely($id)
    {
        return User::find($id);
    }

    public function getPaged()
    {
        return User::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchPaged($displayName = null, $email = null, $skypeId = null, $phoneNumber = null)
    {
        $users = User::with(['roles'])->orderBy('created_at', 'desc');
        if (!empty($displayName)) {
            $users->where('display_name', 'like', '%' . $displayName . '%');
        }
        if (!empty($email)) {
            $users->where('email', 'like', '%' . $email . '%');
        }
        if (!empty($skypeId)) {
            $users->where('skype_id', 'like', '%' . $skypeId . '%');
        }
        if (!empty($phoneNumber)) {
            $users->where('phone_number', 'like', '%' . $phoneNumber . '%');
        }
        return $users->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getStudentAgentSearchPaged($displayName = null, $email = null, $skypeId = null, $phoneNumber = null)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('roles.name', 'student_agent');
        })->orderBy('created_at', 'desc');
        if (!empty($displayName)) {
            $users->where('display_name', 'like', '%' . $displayName . '%');
        }
        if (!empty($email)) {
            $users->where('email', 'like', '%' . $email . '%');
        }
        if (!empty($skypeId)) {
            $users->where('skype_id', 'like', '%' . $skypeId . '%');
        }
        if (!empty($phoneNumber)) {
            $users->where('phone_number', 'like', '%' . $phoneNumber . '%');
        }
        return $users->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSupporterSearchCommonPaged($term = null)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('roles.name', 'supporter');
        });
        if (!empty($term)) {
            $users->where(function ($query) use ($term) {
                $query->where('id', $term)
                    ->orWhere('display_name', 'like', '%' . $term . '%')
                    ->orWhere('name', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    ->orWhere('skype_id', 'like', '%' . $term . '%')
                    ->orWhere('phone_number', 'like', '%' . $term . '%');
            });
        }
        return $users->orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAuthorSearchCommonPaged($term = null)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('roles.name', 'teacher');
            $query->orWhere('roles.name', 'editor');
            $query->orWhere('roles.name', 'admin');
        });
        if (!empty($term)) {
            $users->where(function ($query) use ($term) {
                $query->where('id', $term)
                    ->orWhere('display_name', 'like', '%' . $term . '%')
                    ->orWhere('name', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    ->orWhere('skype_id', 'like', '%' . $term . '%')
                    ->orWhere('phone_number', 'like', '%' . $term . '%');
            });
        }
        return $users->orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getNormalRoleSearchCommonPaged($term = null)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('roles.status', Role::STATUS_NORMAL);
        });
        if (!empty($term)) {
            $users->where(function ($query) use ($term) {
                $query->where('id', $term)
                    ->orWhere('display_name', 'like', '%' . $term . '%')
                    ->orWhere('name', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    ->orWhere('skype_id', 'like', '%' . $term . '%')
                    ->orWhere('phone_number', 'like', '%' . $term . '%');
            });
        }
        return $users->orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return User::all();
    }

    public function getBySocial($provider, $providerId, $providerEmail)
    {
        return User::fromSocial($provider, $providerId, $providerEmail)->first();
    }

    public function getLikeName($name)
    {
        return User::where('name', 'like', $name . '%')->get();
    }

    public function getByNameAndHashedPassword($name, $hashedPassword)
    {
        return User::where('name', $name)->where('password', $hashedPassword)->first();
    }

    /**
     * @param $name
     * @param $displayName
     * @param $email
     * @param $password
     * @param array|null $roles
     * @param bool $sendWelcomeMail
     * @param null $urlAvatar
     * @param null $urlAvatarThumb
     * @param null $social
     * @return User
     * @throws KatnissException
     */
    public function create($name, $displayName, $email, $password, array $roles = null, $sendWelcomeMail = true,
                           $urlAvatar = null, $urlAvatarThumb = null, $social = null)
    {
        DB::beginTransaction();
        try {
            $settings = UserSetting::create();

            $user = User::create(array(
                'display_name' => $displayName,
                'email' => $email,
                'name' => $name,
                'password' => bcrypt($password),
                'url_avatar' => empty($urlAvatar) ? appDefaultUserProfilePicture() : $urlAvatar,
                'url_avatar_thumb' => empty($urlAvatarThumb) ? appDefaultUserProfilePicture() : $urlAvatarThumb,
                'activation_code' => str_random(32),
                'active' => false,
                'setting_id' => $settings->id,
            ));

            if (empty($roles)) {
                $roleRepository = new RoleRepository();
                $roles = [$roleRepository->getByName('user')->id];
            }
            $user->attachRoles($roles);

            if ($user->hasRole('teacher')) {
                Teacher::create([
                    'user_id' => $user->id,
                ]);
            }
            if ($user->hasRole('student')) {
                Student::create([
                    'user_id' => $user->id,
                ]);
            }

            if (!empty($social)) {
                $user->socialProviders()->save(new UserSocial($social));
            }

            if ($sendWelcomeMail) {
                event(new UserCreated($user, $password, !empty($social),
                    array_merge(request()->getTheme()->viewParams(), [
                        BaseMailable::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                        BaseMailable::EMAIL_TO => $email,
                        BaseMailable::EMAIL_TO_NAME => $displayName,
                    ])
                ));
            }

            DB::commit();

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($name, $displayName, $email, $password, array $roles = null)
    {
        $user = $this->model();
        DB::beginTransaction();
        try {
            $passwordChanged = false;
            $user->display_name = $displayName;
            $user->email = $email;
            if (!empty($password)) {
                $user->password = bcrypt($password);
                $passwordChanged = true;
            }
            $user->name = $name;
            $user->save();

            if ($roles != null) {
                $hiddenRoles = $user->roles()->where('status', Role::STATUS_HIDDEN)->get();
                if ($hiddenRoles->count() > 0) {
                    $hiddenRoles = $hiddenRoles->pluck('id')->all();
                    $roles = array_merge($roles, $hiddenRoles);
                }
                $user->roles()->sync($roles);
            }

            if ($user->hasRole('teacher')) {
                if (Teacher::where('user_id', $user->id)->count() <= 0) {
                    Teacher::create([
                        'user_id' => $user->id,
                    ]);
                }
            } else {
                if (Teacher::where('user_id', $user->id)->count() > 0) {
                    throw new \Exception(trans('error.cannot_remove_current_teacher_role'));
                }
            }
            if ($user->hasRole('student')) {
                if (Student::where('user_id', $user->id)->count() <= 0) {
                    Student::create([
                        'user_id' => $user->id,
                    ]);
                }
            } else {
                if (Student::where('user_id', $user->id)->count() > 0) {
                    throw new \Exception(trans('error.cannot_remove_current_student_role'));
                }
            }

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
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createAdmin(array $userAttributes, $country, array $roles = null, $sendWelcomeMail = false)
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

            if (empty($roles)) {
                $roleRepository = new RoleRepository();
                $roles = [$roleRepository->getByName('user')->id];
            }
            $user->attachRoles($roles);

            if ($user->hasRole('teacher')) {
                Teacher::create([
                    'user_id' => $user->id,
                ]);
            }
            if ($user->hasRole('student')) {
                Student::create([
                    'user_id' => $user->id,
                ]);
            }

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

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateAdmin(array $userAttributes, $country, array $roles = null)
    {
        $user = $this->model();

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
            $user->update($userAttributes);
            $user->settings()->update([
                'country' => $country,
            ]);

            if ($roles != null) {
                $hiddenRoles = $user->roles()->where('status', Role::STATUS_HIDDEN)->get();
                if ($hiddenRoles->count() > 0) {
                    $hiddenRoles = $hiddenRoles->pluck('id')->all();
                    $roles = array_merge($roles, $hiddenRoles);
                }
                $user->roles()->sync($roles);
            }

            if ($user->hasRole('teacher')) {
                if (Teacher::where('user_id', $user->id)->count() <= 0) {
                    Teacher::create([
                        'user_id' => $user->id,
                    ]);
                }
            } else {
                if (Teacher::where('user_id', $user->id)->count() > 0) {
                    throw new \Exception(trans('error.cannot_remove_current_teacher_role'));
                }
            }
            if ($user->hasRole('student')) {
                if (Student::where('user_id', $user->id)->count() <= 0) {
                    Student::create([
                        'user_id' => $user->id,
                    ]);
                }
            } else {
                if (Student::where('user_id', $user->id)->count() > 0) {
                    throw new \Exception(trans('error.cannot_remove_current_student_role'));
                }
            }

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

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createStudentAgentAdmin(array $userAttributes, $country, $sendWelcomeMail = false)
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
            $roles = [$roleRepository->getByName('student_agent')->id];
            $user->attachRoles($roles);

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

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateStudentAgentAdmin(array $userAttributes, $country)
    {
        $user = $this->model();

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

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updatePassword($password)
    {
        $user = $this->model();
        DB::beginTransaction();
        try {
            $user->password = bcrypt($password);
            $user->save();
            event(new PasswordChanged($user, $password,
                array_merge(request()->getTheme()->viewParams(), [
                    BaseMailable::EMAIL_SUBJECT => '[' . appName() . '] ' .
                        trans('form.action_change') . ' ' . trans('label.password'),
                    BaseMailable::EMAIL_TO => $user->email,
                    BaseMailable::EMAIL_TO_NAME => $user->display_name,
                ])
            ));

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateSkypeId($skypeId)
    {
        $user = $this->model();
        try {
            $user->skype_id = $skypeId;
            $user->save();
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateAvatarByCropperJs($imageRealPath, $imageCropData)
    {
        $user = $this->model();

        $urlAvatar = null;
        $urlAvatarThumb = null;

        try {
            $storePhoto = new StorePhotoByCropperJs($imageRealPath, $imageCropData);
            $storePhoto->move(userPublicPath($user->profilePictureDirectory), randomizeFilename());
            $urlAvatar = publicUrl($storePhoto->getTargetFileRealPath());

            $storePhoto = $storePhoto->duplicate(userPublicPath($user->profilePictureDirectory), randomizeFilename('thumb'));
            $storePhoto->resize(User::AVATAR_THUMB_WIDTH, User::AVATAR_THUMB_HEIGHT);
            $storePhoto->save();
            $urlAvatarThumb = publicUrl($storePhoto->getTargetFileRealPath());
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.application') . ' (' . $ex->getMessage() . ')');
        }

        try {
            $user->url_avatar = $urlAvatar;
            $user->url_avatar_thumb = $urlAvatarThumb;
            $user->save();

            return $user;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateAttributes(array $attributes)
    {
        $user = $this->model();

        try {
            $user->update($attributes);
            return $user;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createFacebookConnection($providerId, $avatar)
    {
        $user = $this->model();

        DB::beginTransaction();
        try {
            $user->socialProviders()->save(new UserSocial([
                'provider' => UserSocial::PROVIDER_FACEBOOK,
                'provider_id' => $providerId,
            ]));
            $user->url_avatar = $avatar;
            $user->url_avatar_thumb = $avatar;
            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function removeFacebookConnection()
    {
        $user = $this->model();

        DB::beginTransaction();
        try {
            $user->socialProviders()
                ->where('provider', UserSocial::PROVIDER_FACEBOOK)
                ->delete();
            $user->url_avatar = appDefaultUserProfilePicture();
            $user->url_avatar_thumb = appDefaultUserProfilePicture();
            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function hasFacebookConnected()
    {
        $user = $this->model();
        return $user->socialProviders()
                ->where('provider', UserSocial::PROVIDER_FACEBOOK)
                ->count() > 0;
    }

    public function delete()
    {
        $user = $this->model();

        if ($user->id == authUser()->id) {
            throw new KatnissException(trans('error._cannot_delete', ['reason' => trans('error.is_current_user')]));
        }
        if ($user->hasRole('owner')) {
            throw new KatnissException(trans('error._cannot_delete', ['reason' => trans('error.is_role_owner')]));
        }
        if ($user->hasRole('teacher')) {
            throw new KatnissException(trans('error._cannot_delete', ['reason' => trans('error.cannot_remove_current_teacher_role')]));
        }
        if ($user->hasRole('student')) {
            throw new KatnissException(trans('error._cannot_delete', ['reason' => trans('error.cannot_remove_current_student_role')]));
        }

        try {
            $user->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function deleteStudentAgent()
    {
        $user = $this->model();

        try {
            $roleRepository = new RoleRepository();
            $user->detachRole($roleRepository->getByName('student_agent')->id);
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}