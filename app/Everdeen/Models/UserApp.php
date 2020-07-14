<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;

class UserApp extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_apps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'secret', 'name', 'version'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
