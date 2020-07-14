<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Conversation extends Model
{
    const TYPE_PUBLIC = 0; // auth + anonymous
    const TYPE_DIRECT = 1; // 1-1 auth
    const TYPE_GROUP = 2; // * auth
    const TYPE_SUPPORT = 3; // *anonymous-1 auth

    protected $table = 'conversations';

    protected $fillable = ['channel_id', 'type'];

    public function getIsPublicAttribute()
    {
        return $this->attributes['type'] == $this::TYPE_PUBLIC;
    }

    public function getIsDirectAttribute()
    {
        return $this->attributes['type'] == $this::TYPE_DIRECT;
    }

    public function getIsGroupAttribute()
    {
        return $this->attributes['type'] == $this::TYPE_GROUP;
    }

    public function getIsSupportAttribute()
    {
        return $this->attributes['type'] == $this::TYPE_SUPPORT;
    }

    public function channel()
    {
        return $this->belongsTo(RealTimeChannel::class, 'channel_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversations_users', 'conversation_id', 'user_id');
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'conversations_devices', 'conversation_id', 'device_id')
            ->withPivot('color');
    }
}
