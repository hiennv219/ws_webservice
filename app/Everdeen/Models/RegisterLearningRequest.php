<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class RegisterLearningRequest extends Model
{
    const NEWLY = 0;
    const PROCESSED = 1;

    protected $table = 'register_learning_requests';

    protected $fillable = [
        'processed_by_id', 'processed_at',
        'student_id', 'teacher_id',
        'study_level_id',
        'study_problem_id',
        'study_course_id',
        'for_children', 'age_range',
        'learning_targets', 'learning_forms',
        'children_full_name',
        'status',
    ];

    public function getFormattedCreatedAtAttribute()
    {
        return DateTimeHelper::getInstance()->shortDate($this->attributes['created_at']);
    }

    public function getLearningTargetsAttribute()
    {
        if (empty($this->attributes['learning_targets'])) {
            return [];
        }
        return unserialize($this->attributes['learning_targets']);
    }

    public function getLearningFormsAttribute()
    {
        if (empty($this->attributes['learning_forms'])) {
            return [];
        }
        return unserialize($this->attributes['learning_forms']);
    }

    public function getFormattedProcessedAtAttribute()
    {
        return empty($this->attributes['processed_at']) ?
            null : DateTimeHelper::getInstance()->shortDate($this->attributes['processed_at']);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by_id', 'id');
    }

    public function studentProfile()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    public function studentUserProfile()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function teacherProfile()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function teacherUserProfile()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function studyLevel()
    {
        return $this->belongsTo(Meta::class, 'study_level_id', 'id');
    }

    public function studyProblem()
    {
        return $this->belongsTo(Meta::class, 'study_problem_id', 'id');
    }

    public function studyCourse()
    {
        return $this->belongsTo(Meta::class, 'study_course_id', 'id');
    }

    public function scopeNewly($query)
    {
        return $query->where('status', self::NEWLY);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', self::PROCESSED);
    }
}
