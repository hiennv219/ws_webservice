<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 08:36
 */

namespace Katniss\Everdeen\Events;


use Illuminate\Queue\SerializesModels;
use Katniss\Everdeen\Models\User;

class UserCreated extends Event
{
    use SerializesModels;

    public $user;
    public $password;
    public $fromSocial;

    /**
     * Create a new event instance.
     *
     * @param  User $user
     * @return void
     */
    public function __construct(User $user, $password, $fromSocial = false, array $params = [], $locale = null)
    {
        parent::__construct($params, $locale);
        $this->user = $user;
        $this->password = $password;
        $this->fromSocial = $fromSocial;
    }

    public function getParamsForMailing()
    {
        return array_merge([
            'id' => $this->user->id,
            'display_name' => $this->user->display_name,
            'email' => $this->user->email,
            'name' => $this->user->name,
            'password' => $this->password,
            'activation_code' => $this->user->activation_code,
            'url_activate' => homeUrl('auth/activate/{id}/{activation_code}', ['id' => $this->user->id, 'activation_code' => $this->user->activation_code], $this->locale),
        ], $this->params);
    }
}