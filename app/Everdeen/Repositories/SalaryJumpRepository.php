<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\SalaryJump;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class SalaryJumpRepository extends ModelRepository
{
    public function getById($id)
    {
        return SalaryJump::where('id', $id)->firstOrFail();
    }

    public function getPaged()
    {
        return SalaryJump::orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return SalaryJump::all();
    }

    public function getLast($year = null, $month = null)
    {
        if (empty($year) || empty($month)) {
            return SalaryJump::orderBy('apply_from', 'desc')
                ->take(1)
                ->first();
        }

        return SalaryJump::whereYear('apply_from', '<=', $year)
            ->whereMonth('apply_from', '<=', $month)
            ->orderBy('apply_from', 'desc')
            ->take(1)
            ->first();
    }

    public function sync($jump, $currency, $year, $month)
    {
        try {
            $salaryJump = SalaryJump::whereYear('apply_from', $year)
                ->whereMonth('apply_from', $month)
                ->first();
            if (!empty($salaryJump)) {
                $salaryJump->update([
                    'jump' => $jump,
                    'currency' => $currency,
                ]);
            } else {
                if ($month < 10) {
                    $month = '0' . $month;
                }
                $salaryJump = SalaryJump::create([
                    'jump' => $jump,
                    'currency' => $currency,
                    'apply_from' => "$year-$month-01 00:00:00",
                ]);
            }

            return $salaryJump;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }
}