<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Reports\TeacherSalaryReport;
use Katniss\Everdeen\Repositories\SalaryJumpRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;
use Maatwebsite\Excel\Facades\Excel;

class SalaryReportController extends AdminController
{
    protected $salaryJumpRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'salary_report';
        $this->salaryJumpRepository = new SalaryJumpRepository();
    }

    public function index(Request $request)
    {
        if ($request->has('export')) {
            return $this->export($request);
        }

        $salaryJump = $this->salaryJumpRepository->getLast();

        $this->_title(trans('pages.admin_salary_report_title'));
        $this->_description(trans('pages.admin_salary_report_desc'));

        return $this->_index([
            'salary_jump' => $salaryJump,
            'salary_jump_value' => empty($salaryJump) ? '' : $salaryJump->formattedJumpCurrencyNoSign,
            'salary_jump_currency' => settings()->currency,
            'salary_jump_apply_from' => empty($salaryJump) ? '' : $salaryJump->formattedApplyFrom,
            'month_js_format' => DateTimeHelper::shortMonthPickerJsFormat(),
            'number_format_chars' => NumberFormatHelper::getInstance()->getChars(),
        ]);
    }

    public function export(Request $request)
    {
        if (!$this->customValidate($request, [
            'month_year' => 'required|date_format:' . DateTimeHelper::shortMonthFormat(),
        ])
        ) {
            return abort(404);
        }

        try {
            $monthYear = $request->input('month_year');
            $date = DateTimeHelper::getInstance()->fromFormat(
                DateTimeHelper::shortMonthFormat(), $monthYear, true);

            $report = new TeacherSalaryReport($date->format('Y'), $date->format('n'));

            return Excel::create('Teacher_Salary_' . $monthYear, function ($excel) use ($report, $monthYear) {
                $excel->sheet('Sheet 1', function ($sheet) use ($report, $monthYear) {
                    $data = $report->getDataAsFlatArray();
                    array_unshift($data, $report->getHeader());

                    $sheet->cell('A1', function ($cell) use ($report, $monthYear) {
                        $cell->setValue(
                            trans('label.salary_jump') . ' ' .
                            trans('label.applied_for_lc') . ' ' .
                            $monthYear . ': ' .
                            $report->getLastSalaryJump()->formattedJumpCurrency . ' / 1 ' . trans_choice('label.hour_lc', 1)
                        );
                    });

                    $startColumn = 'A';
                    $startRow = 2;
                    $endColumn = chr(count($data[0]) + ord($startColumn) - 1);
                    $endRow = $startRow + count($data) - 1;

                    $sheet->mergeCells('A1:' . $endColumn . '1');
                    $sheet->fromArray($data, null, $startColumn . $startRow, true, false);
                    $sheet->cells($startColumn . $startRow . ':' . $endColumn . $startRow, function ($cells) {
                        $cells->setBackground('#000000');
                        $cells->setFontColor('#ffffff');
                        $cells->setFontWeight('bold');
                    });
                    $sheet->setBorder($startColumn . $startRow . ':' . $endColumn . $endRow, 'thin');
                });
            })->download('xls');
        } catch (KatnissException $ex) {
            return abort(500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jump' => ['required', 'regex:' . NumberFormatHelper::getInstance()->getRegEx(8, 2)],
            'apply_from' => 'required|date_format:' . DateTimeHelper::shortMonthFormat(),
            'currency' => 'required|in:' . implode(',', allCurrencyCodes()),
        ]);
        $errorRedirect = redirect(adminUrl('salary-report'))->withInput();
        if ($validator->fails()) {
            return $errorRedirect->withErrors($validator);
        }

        try {
            $date = DateTimeHelper::getInstance()
                ->fromFormat(DateTimeHelper::shortMonthFormat(), $request->input('apply_from'), true);
            $this->salaryJumpRepository->sync(
                NumberFormatHelper::getInstance()->fromFormat($request->input('jump')),
                $request->input('currency'),
                $date->format('Y'),
                $date->format('n')
            );
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('salary-report'));
    }
}
