<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-07-02
 * Time: 12:59
 */

namespace Katniss\Everdeen\Vendors\Laravel\Socialite\Two;

use Laravel\Socialite\Two\FacebookProvider as BaseFacebookProvider;
use Laravel\Socialite\Two\User;

class FacebookProvider extends BaseFacebookProvider
{
    public function mapUserToObject(array $user)
    {
        $avatarUrl = $this->graphUrl.'/'.$this->version.'/'.$user['id'].'/picture';

        return (new User)->setRaw($user)->map([
            'id' => $user['id'], 'nickname' => null, 'name' => isset($user['name']) ? $user['name'] : null,
            'email' => isset($user['email']) ? $user['email'] : null, 'avatar' => $avatarUrl.'?type=large',
            'avatar_original' => $avatarUrl.'?width=1920',
        ]);
    }
}