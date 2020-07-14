<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class AppOption extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'app_options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'data_type', 'value'
    ];

    public function getRawValueAttribute()
    {
        return $this->attributes['value'];
    }

    public function setRawValueAttribute($value)
    {
        $this->attributes['value'] = trim($value);
    }

    public function setValueAttribute($value)
    {
        $value = escapeObject($value, $type);
        $this->attributes['value'] = $value;
        $this->attributes['data_type'] = $type;
    }

    public function getValueAttribute()
    {
        return fromEscapedObject($this->attributes['value'], $this->attributes['data_type']);
    }
}
