<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 17:01
 */

namespace Katniss\Everdeen\Events;

use Illuminate\Queue\SerializesModels;
use Katniss\Everdeen\Models\User;

class PasswordChanged extends Event
{
    use SerializesModels;

    public $user;
    public $password;

    /**
     * Create a new event instance.
     *
     * @param  User $user
     * @return void
     */
    public function __construct(User $user, $password, array $params = [], $locale = null)
    {
        parent::__construct($params, $locale);
        $this->user = $user;
        $this->password = $password;
    }

    public function getParamsForMailing()
    {
        return array_merge([
            'id' => $this->user->id,
            'display_name' => $this->user->display_name,
            'email' => $this->user->email,
            'name' => $this->user->name,
            'password' => $this->password,
        ], $this->params);
    }
}