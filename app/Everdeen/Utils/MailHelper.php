<?php

namespace Katniss\Everdeen\Utils;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Katniss\Everdeen\Mail\BaseMailable;

class MailHelper
{
    private static $message = null;

    public static function getMessage()
    {
        return self::$message;
    }

    public static function queueSendTemplate($path, $params, $locale = null)
    {
        try {
            Mail::send(new BaseMailable($path, $params, $locale));
            return true;
        } catch (\Exception $ex) {
            self::$message = $ex->getMessage();
            logInfo("Mail Error", ['data' => $ex,]);
            return false;
        }
    }

    public static function sendTemplate($path, $params, $locale = null)
    {
        try {
            Mail::send(new BaseMailable($path, $params, $locale));
            return true;
        } catch (\Exception $ex) {
            self::$message = $ex->getMessage();
            logInfo ("Mail Error", ['data' => $ex,]);
            return false;
        }
    }
}
