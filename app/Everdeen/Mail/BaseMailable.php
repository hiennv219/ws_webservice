<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-03-23
 * Time: 22:42
 */

namespace Katniss\Everdeen\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMailable extends Mailable
{
    use Queueable, SerializesModels;

    const EMAIL_FROM = 'x_email_from';
    const EMAIL_FROM_NAME = 'x_email_from_name';
    const EMAIL_SUBJECT = 'x_email_subject';
    const EMAIL_TO = 'x_email_to';
    const EMAIL_TO_NAME = 'x_email_to_name';

    protected $path;

    protected $params;

    protected $locale;

    public function __construct($path, $params = [], $locale = null)
    {
        $this->path = $path;

        if (!isset($params[self::EMAIL_FROM])) {
            $params[self::EMAIL_FROM] = appEmail();
        }
        if (!isset($params[self::EMAIL_FROM_NAME])) {
            $params[self::EMAIL_FROM_NAME] = appName();
        }
        if (!isset($params[self::EMAIL_SUBJECT])) {
            $params[self::EMAIL_SUBJECT] = trans('label.message_from_') . appName();
        }
        if (!empty($locale)) {
            $params['site_locale'] = $locale;
        }

        $this->params = $params;
        $this->locale = $params['site_locale'];
    }

    protected function mailPath()
    {
        return 'emails.' . $this->path . '.' . $this->locale;
    }

    public function build()
    {
        $this->from($this->params[self::EMAIL_FROM], $this->params[self::EMAIL_FROM_NAME]);
        $this->subject($this->params[self::EMAIL_SUBJECT]);
        if (isset($this->params[self::EMAIL_TO_NAME])) {
            $this->to($this->params[self::EMAIL_TO], $this->params[self::EMAIL_TO_NAME]);
        } else {
            $this->to($this->params[self::EMAIL_TO]);
        }
        return $this->view($this->mailPath())
            ->with($this->params);
    }
}