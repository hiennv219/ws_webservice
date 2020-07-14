<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 17:13
 */

namespace Katniss\Everdeen\Listeners;

use Katniss\Everdeen\Events\ClassTimeCreated;
use Katniss\Everdeen\Utils\MailHelper;

class ClassTimeCreatedEmailing
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
     * @param  ClassTimeCreated $event
     * @return void
     */
    public function handle(ClassTimeCreated $event)
    {
        MailHelper::queueSendTemplate('confirm_class_time', $event->getParamsForMailing(), $event->locale);
    }
}