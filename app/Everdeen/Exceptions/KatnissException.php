<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 20:01
 */

namespace Katniss\Everdeen\Exceptions;


class KatnissException extends \Exception
{
    protected $attachedData;

    public function __construct($message = '', $attachedData = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->attachedData = $attachedData;
    }

    public function getAttachedData()
    {
        return $this->attachedData;
    }
}