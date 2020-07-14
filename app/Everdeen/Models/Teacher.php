<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const REQUESTED = 0;
    const APPROVED = 1;
    const REJECTED = -1;
    const TEACHING_STATUS_AVAILABLE = 1;
    const TEACHING_STATUS_FULL_SCHEDULE = 2;

    protected $table = 'teachers';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = [
        'user_id', 'viewed', 'approving_user_id', 'approving_at', 'teaching_status', 'status',
        'video_teaching_url', 'video_introduce_url',
        'about_me', 'experience', 'methodology',
        'available_times', 'certificates', 'payment_info'
    ];

    public function getHtmlAboutMeAttribute()
    {
        if (empty($this->attributes['about_me'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, htmlspecialchars($this->attributes['about_me']))) . '</p>';
    }

    public function getHtmlExperienceAttribute()
    {
        if (empty($this->attributes['experience'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, htmlspecialchars($this->attributes['experience']))) . '</p>';
    }

    public function getHtmlMethodologyAttribute()
    {
        if (empty($this->attributes['methodology'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, htmlspecialchars($this->attributes['methodology']))) . '</p>';
    }

    public function getCertificatesAttribute()
    {
        if (empty($this->attributes['certificates'])) {
            return [];
        }
        return unserialize($this->attributes['certificates']);
    }

    public function getAvailableTimesAttribute()
    {
        if (empty($this->attributes['available_times'])) {
            return ['times' => [], 'range_from' => '', 'range_to' => ''];
        }
        return unserialize($this->attributes['available_times']);
    }

    public function getPaymentInfoAttribute()
    {
        if (empty($this->attributes['payment_info'])) {
            return [];
        }
        return unserialize($this->attributes['payment_info']);
    }

    public function getIsApprovedAttribute()
    {
        return $this->attributes['status'] == self::APPROVED;
    }

    public function userProfile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approvingUser()
    {
        return $this->belongsTo(User::class, 'approving_user_id', 'id');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'topics_teachers', 'teacher_id', 'topic_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'user_id', 'teacher_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Teacher::APPROVED);
    }

    public function scopeRequested($query)
    {
        return $query->where('status', Teacher::REQUESTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Teacher::REJECTED);
    }
}
