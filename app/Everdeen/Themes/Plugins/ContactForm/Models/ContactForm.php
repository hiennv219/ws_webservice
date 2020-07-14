<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 11:02
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm\Models;


use Illuminate\Database\Eloquent\Model;

class ContactForm extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contact_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'address',
        'phone',
        'email',
        'website',
        'message',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}