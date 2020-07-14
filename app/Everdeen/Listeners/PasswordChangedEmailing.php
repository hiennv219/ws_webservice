<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 17:13
 */

namespace Katniss\Everdeen\Listeners;

use Katniss\Everdeen\Utils\MailHelper;
use Katniss\Everdeen\Events\PasswordChanged;

class PasswordChangedEmailing
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PasswordChanged $event
     * @return void
     */
    public function handle(PasswordChanged $event)
    {
        MailHelper::queueSendTemplate('user_password_changed', $event->getParamsForMailing(), $event->locale);
    }
}