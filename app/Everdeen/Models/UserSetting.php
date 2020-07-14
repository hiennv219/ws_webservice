<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'locale', 'country', 'timezone', 'currency', 'number_format',
        'first_day_of_week', 'long_date_format', 'short_date_format', 'long_time_format', 'short_time_format'
    ];
}
