<?php

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Reports\TeacherSalaryReport;
use Katniss\Everdeen\Utils\DateTimeHelper;

class SalaryReportController extends WebApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('theme')->only('index');
    }

    public function index(Request $request)
    {
        if (!$this->customValidate($request, [
            'month_year' => 'required|date_format:' . DateTimeHelper::shortMonthFormat(),
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $date = DateTimeHelper::getInstance()->fromFormat(
                DateTimeHelper::shortMonthFormat(), $request->input('month_year'), true);

            $report = new TeacherSalaryReport($date->format('Y'), $date->format('n'));

            if (!$report->hasData()) {
                return $this->responseFail();
            }

            return $this->responseSuccess([
                'report' => $report->getData(),
                'jump' => $report->getLastSalaryJump()->formattedJumpCurrency . ' / 1 ' . trans_choice('label.hour_lc', 1),
            ]);
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
