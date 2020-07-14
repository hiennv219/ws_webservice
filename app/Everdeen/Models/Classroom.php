<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Classroom extends Model
{
    const STATUS_OPENING = 1;
    const STATUS_CLOSED = 0;

    protected $table = 'classrooms';

    protected $fillable = [
        'closed_by', 'closed_at',
        'student_id', 'teacher_id', 'supporter_id', 'status', 'hours', 'name',
        'comment', 'hours_salary'
    ];

    public function getIsOpeningAttribute()
    {
        return $this->attributes['status'] == self::STATUS_OPENING;
    }

    public function getDurationAttribute()
    {
        return toFormattedNumber($this->attributes['hours']);
    }

    public function getSpentTimeAttribute()
    {
        return $this->classTimes()->sum('hours');
    }

    public function getSpentTimeDurationAttribute()
    {
        return toFormattedNumber($this->spentTime);
    }

    public function teacherProfile()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function teacherUserProfile()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function studentProfile()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    public function studentUserProfile()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function supporter()
    {
        return $this->belongsTo(User::class, 'supporter_id', 'id');
    }

    public function classTimes()
    {
        return $this->hasMany(ClassTime::class, 'classroom_id', 'id');
    }

    public function getLastClassTimeAttribute()
    {
        return $this->classTimes()->orderBy('start_at', 'desc')->take(1)->first();
    }

    public function getFirstClassTimeAttribute()
    {
        return $this->classTimes()->orderBy('start_at', 'asc')->take(1)->first();
    }

    public function getCountClassTimesAttribute()
    {
        return $this->classTimes()->where('type', ClassTime::TYPE_NORMAL)->count();
    }

    public function getClassTimesOfLastMonthAttribute()
    {
        $lastClassTime = $this->lastClassTime;
        if (empty($lastClassTime)) return collect([]);

        $time = strtotime($lastClassTime->start_at);
        return $this->getClassTimesOfMonth(date('Y', $time), date('m', $time));
    }

    public function scopeOpening($query)
    {
        return $query->where('status', self::STATUS_OPENING);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeReadyToClose($query)
    {
        return $query->join(DB::raw('(select 
                classroom_id, 
                sum(hours) as sum_hours 
                from '.DB::getTablePrefix().'class_times
                group by classroom_id) as tmp_class_times'), DB::raw('tmp_class_times.classroom_id'), '=', 'classrooms.id')
            ->where('status', self::STATUS_OPENING)
            ->where('classrooms.hours', '<=', DB::raw('tmp_class_times.sum_hours'));
    }

    public function scopeOfTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeOfStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeOfSupporter($query, $supporterId)
    {
        return $query->where('supporter_id', $supporterId);
    }

    public function getClassTimesOfMonth($year, $month)
    {
        return $this->classTimes()
            ->with('reviews')
            ->orderBy('start_at', 'desc')
            ->orderBy('id', 'desc')
            ->whereYear('start_at', $year)
            ->whereMonth('start_at', $month)
            ->get();
    }

    public function getLastClassTimeBeforeMonth($year, $month)
    {
        return $this->classTimes()
            ->orderBy('start_at', 'desc')
            ->orderBy('id', 'desc')
            ->where(function($query) use ($year, $month) {
            	$query->whereYear('start_at', '<', $year)
	            ->orWhere(function($query) use ($year, $month) {
	            	$query->whereYear('start_at', $year)->whereMonth('start_at', '<=', $month);
	            });
            })
            ->take(1)
            ->first();
    }

    public function getCountClassTimesOfMonth($year, $month)
    {
        return $this->classTimes()
            ->where('type', ClassTime::TYPE_NORMAL)
            ->whereYear('start_at', $year)
            ->whereMonth('start_at', $month)
            ->count();
    }

    public function getCountTillClassTimesOfMonth($year, $month)
    {
        return $this->classTimes()
            ->where('type', ClassTime::TYPE_NORMAL)
            ->where(function($query) use ($year, $month) {
            	$query->whereYear('start_at', '<', $year)
	            ->orWhere(function($query) use ($year, $month) {
	            	$query->whereYear('start_at', $year)->whereMonth('start_at', '<=', $month);
	            });
            })
            ->count();
    }
}
