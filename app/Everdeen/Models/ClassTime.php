<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class ClassTime extends Model
{
    const TYPE_NORMAL = 0;
    const TYPE_PERIODIC = 1;

    const CONFIRMED_TRUE = 1;
    const CONFIRMED_FALSE = 0;

    protected $table = 'class_times';

    protected $fillable = [
        'classroom_id', 'subject', 'content', 'hours', 'start_at', 'confirmed', 'type',
    ];

    public function getFormattedStartAtAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->compound('shortDate', ' ', 'shortTime', $this->attributes['start_at']);
    }

    public function getFullFormattedStartAtAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->compound('longDate', ' ', 'shortTime', $this->attributes['start_at']);
    }

    public function getFullFormattedStartAtDateAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->longDate($this->attributes['start_at']);
    }

    public function getInverseFullFormattedStartAtAttribute()
    {
        return empty($this->attributes['start_at']) ?
            '' : DateTimeHelper::getInstance()->compound('shortTime', ' ', 'longDate', $this->attributes['start_at']);
    }

    public function getDurationAttribute()
    {
        return toFormattedNumber($this->attributes['hours']);
    }

    public function getIsConfirmedAttribute()
    {
        return $this->attributes['confirmed'] == self::CONFIRMED_TRUE;
    }

    public function getIsPeriodicAttribute()
    {
        return $this->attributes['type'] == self::TYPE_PERIODIC;
    }

    public function getHtmlContentAttribute()
    {
        if (empty($this->attributes['content'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', preg_split('/\r*\n/', htmlspecialchars($this->attributes['content']))) . '</p>';
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'class_times_reviews', 'class_time_id', 'review_id');
    }
}
