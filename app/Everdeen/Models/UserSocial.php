<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    const PROVIDER_FACEBOOK = 'facebook';
    const PROVIDER_GOOGLE_PLUS = 'google_plus';
    const ALLOWED_PROVIDERS = [
        self::PROVIDER_FACEBOOK,
        self::PROVIDER_GOOGLE_PLUS,
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_socials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'provider', 'provider_id'];

    /**
     * Returns the user that belongs to this entry.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
