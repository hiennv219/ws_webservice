<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-28
 * Time: 19:34
 */

namespace Katniss\Everdeen\Utils;


class MultipleLocaleValidationResult
{
    private $data;

    private $failed;

    public function __construct()
    {
        $this->data = [];
        $this->failed = [];
    }

    /**
     * @param string|\Illuminate\Contracts\Validation\Validator $validator
     */
    public function fails($validator)
    {
        $this->failed[] = $validator;
    }

    public function getFailed()
    {
        return $this->isFailed() ? $this->failed[0] : null;
    }

    public function isFailed()
    {
        return count($this->failed) > 0;
    }

    public function set($locale, $data)
    {
        $this->data[$locale] = $data;
    }

    public function has($locale)
    {
        return isset($this->data[$locale]);
    }

    public function get($locale)
    {
        return $this->has($locale) ? $this->data[$locale] : null;
    }

    public function getLocalizedInputs()
    {
        return $this->data;
    }

    public function getLocales()
    {
        return array_keys($this->data);
    }
}