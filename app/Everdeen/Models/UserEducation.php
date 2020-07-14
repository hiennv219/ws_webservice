<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class UserEducation extends Model
{
    protected $table = 'user_educations';

    protected $fillable = [
        'user_id', 'school', 'field', 'description',
        'start_month', 'start_year',
        'end_month', 'end_year',
    ];

    public function renderDuration($from = null, $to = null)
    {
        $start = empty($this->attributes['start_year']) ?
            null : (empty($this->attributes['start_month']) ?
                $this->attributes['start_year']
                : DateTimeHelper::getInstance()->shortMonth(
                    $this->attributes['start_year'] . '-' . str_pad($this->attributes['start_month'], 2, '0', STR_PAD_LEFT) . '-01 00:00:00')
            );
        if (!empty($start)) {
            $start = $from . ' ' . $start;
        }
        $end = empty($this->attributes['end_year']) ?
            null : (empty($this->attributes['end_month']) ?
                $this->attributes['end_year']
                : DateTimeHelper::getInstance()->shortMonth(
                    $this->attributes['end_year'] . '-' . str_pad($this->attributes['end_month'], 2, '0', STR_PAD_LEFT) . '-01 00:00:00')
            );
        if (!empty($end)) {
            $end = $to . ' ' . $end;
        }
        return $start . ' ' . $end;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
