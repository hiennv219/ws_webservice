<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Repositories\AnnouncementRepository;
use Katniss\Everdeen\Vendors\Zizaco\Entrust\Traits\EntrustUserTrait as OverriddenEntrustUserTrait;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait, OverriddenEntrustUserTrait {
        OverriddenEntrustUserTrait::cachedRoles insteadof EntrustUserTrait;
        OverriddenEntrustUserTrait::save insteadof EntrustUserTrait;
        OverriddenEntrustUserTrait::delete insteadof EntrustUserTrait;
        OverriddenEntrustUserTrait::restore insteadof EntrustUserTrait;
        OverriddenEntrustUserTrait::hasRole insteadof EntrustUserTrait;
        OverriddenEntrustUserTrait::can insteadof EntrustUserTrait;
    }
    use Notifiable;

    const AVATAR_THUMB_WIDTH = 150; // pixels
    const AVATAR_THUMB_HEIGHT = 150; // pixels

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name',
        'name',
        'email',
        'password',
        'url_avatar',
        'url_avatar_thumb',
        'activation_code',

        'gender',
        'skype_id',
        'facebook',
        'phone_code',
        'phone_number',
        'date_of_birth',
        'address',
        'city',
        'nationality',

        'active',
        'setting_id',
        'channel',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_code',
        'active',
        'setting_id',
        'settings',
        'channel',
        'created_at',
        'updated_at',
        'roles',
        'perms',
    ];

    public function getOwnDirectoryAttribute()
    {
        $dir = 'user_' . $this->id;
        makeUserPublicPath($dir);
        return $dir;
    }

    public function getProfilePictureDirectoryAttribute()
    {
        $dir = concatDirectories('user_' . $this->id, 'profile_pictures');
        makeUserPublicPath($dir);
        return $dir;
    }

    public function getCertificateDirectoryAttribute()
    {
        $dir = concatDirectories('user_' . $this->id, 'certificates');
        makeUserPublicPath($dir);
        return $dir;
    }

    public function getMemberSinceAttribute()
    {
        return DateTimeHelper::getInstance()->shortDate($this->attributes['created_at']);
    }

    public function getBirthdayAttribute()
    {
        return empty($this->attributes['date_of_birth']) ?
            '' : DateTimeHelper::getInstance()->shortDate($this->attributes['date_of_birth']);
    }

    public function getAgeAttribute()
    {
        if (empty($this->attributes['date_of_birth']) || $this->attributes['date_of_birth'] == '0000-00-00 00:00:00') {
            return '0';
        }

        return DateTimeHelper::diffYear($this->attributes['date_of_birth']);
    }

    public function getPhoneAttribute()
    {
        return empty($this->attributes['phone_code']) || empty($this->attributes['phone_number']) ?
            '' : '(+' . allCountry($this->attributes['phone_code'], 'calling_code') . ') ' . $this->attributes['phone_number'];
    }

    public function socialProviders()
    {
        return $this->hasMany(UserSocial::class, 'user_id', 'id');
    }

    public function scopeFromSocial($query, $provider, $provider_id, $email = null)
    {
        $query->whereExists(function ($query) use ($provider, $provider_id) {
            $query->select(DB::raw(1))
                ->from('user_socials')
                ->where('provider', $provider)->where('provider_id', $provider_id);
        });
        if (!empty($email)) {
            $query->orWhere('email', $email);
        }
        return $query;
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class, 'id', 'setting_id');
    }

    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class, 'user_id', 'id');
    }

    public function studentProfile()
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    public function agentStudents()
    {
        return $this->hasMany(Student::class, 'agent_id', 'id');
    }

    public function professionalSkills()
    {
        return $this->belongsToMany(ProfessionalSkill::class, 'professional_skills_users', 'user_id', 'skill_id');
    }

    public function educations()
    {
        return $this->hasMany(UserEducation::class, 'user_id', 'id');
    }

    public function certificates()
    {
        return $this->hasMany(UserCertificate::class, 'user_id', 'id');
    }

    public function works()
    {
        return $this->hasMany(UserWork::class, 'user_id', 'id');
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'read_announcements', 'user_id', 'announcement_id');
    }

    public function getCountUnreadAnnouncementsAttribute()
    {
        $announcementRepository = new AnnouncementRepository();
        $countAnnouncements = $announcementRepository->getCountByUser($userId, $this);
        $countReadAnnouncements = $this->announcements()->count();
        $countUnreadAnnouncements = $countAnnouncements - $countReadAnnouncements;
        return $countUnreadAnnouncements > 0 ? $countUnreadAnnouncements : 0;
    }
}