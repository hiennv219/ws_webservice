<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 21:02
 */

namespace Katniss\Everdeen\Models;


use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class SalaryJump extends Model
{
    use UuidTrait;

    protected $table = 'salary_jumps';

    protected $fillable = ['jump', 'currency', 'apply_from'];

    public function getFormattedJumpAttribute()
    {
        return toFormattedNumber($this->attributes['jump']);
    }

    public function getFormattedJumpCurrencyAttribute()
    {
        return toFormattedCurrency($this->attributes['jump'], $this->attributes['currency']);
    }

    public function getFormattedJumpCurrencyNoSignAttribute()
    {
        return toFormattedCurrency($this->attributes['jump'], $this->attributes['currency'], true);
    }

    public function getFormattedApplyFromAttribute()
    {
        return empty($this->attributes['apply_from']) ?
            '' : DateTimeHelper::getInstance()->shortMonth($this->attributes['apply_from']);
    }
}