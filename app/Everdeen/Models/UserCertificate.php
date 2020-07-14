<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class UserCertificate extends Model
{
    protected $table = 'user_certificates';

    protected $fillable = ['user_id', 'type', 'provided_by', 'provided_at', 'description', 'image', 'meta'];

    public function getProvidedAtAttribute()
    {
        return empty($this->attributes['provided_at']) || $this->attributes['provided_at'] == '0000-00-00 00:00:00' ?
            '' : DateTimeHelper::getInstance()->shortDate($this->attributes['provided_at']);
    }

    public function getMetaAttribute()
    {
        return empty($this->attributes['meta']) ?
            [] : unserialize($this->attributes['meta']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
