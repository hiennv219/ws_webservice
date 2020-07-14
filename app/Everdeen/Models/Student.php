<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    const REQUESTED = 0;
    const APPROVED = 1;
    const REJECTED = -1;

    protected $table = 'students';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = [
        'user_id', 'agent_id', 'approving_user_id', 'approving_at', 'status'
    ];

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

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'user_id', 'teacher_id');
    }

    public function learningRequest()
    {
        return $this->hasOne(RegisterLearningRequest::class, 'student_id', 'user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Student::APPROVED);
    }

    public function scopeRequested($query)
    {
        return $query->where('status', Student::REQUESTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Student::REJECTED);
    }
}
