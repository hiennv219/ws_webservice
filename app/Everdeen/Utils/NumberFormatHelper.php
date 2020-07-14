<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-13
 * Time: 18:20
 */

namespace Katniss\Everdeen\Utils;


class NumberFormatHelper
{
    const FILTER_FORMAT_CURRENCY = 'format_currency';
    const FILTER_FROM_FORMAT_CURRENCY = 'from_format_currency';
    const DEFAULT_NUMBER_OF_DECIMAL_POINTS = 2;

    /**
     * @var int
     */
    public static $NUMBER_OF_DECIMAL_POINTS;

    /**
     * @var NumberFormatHelper
     */
    private static $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new NumberFormatHelper();
        }
        return self::$instance;
    }

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $currencyCode;

    private function __construct()
    {
        $settings = settings();
        $this->type = $settings->getNumberFormat();
        $this->currencyCode = $settings->getCurrency();

        $this->modeNormal();
    }

    public function modeInt()
    {
        $this->mode(0);
    }

    public function modeNormal()
    {
        $this->mode(self::DEFAULT_NUMBER_OF_DECIMAL_POINTS);
    }

    /**
     * @param int $numberOfDecimalPoints
     */
    public function mode($numberOfDecimalPoints)
    {
        self::$NUMBER_OF_DECIMAL_POINTS = $numberOfDecimalPoints;
    }

    /**
     * @param float $number
     * @param string $originalCurrencyCode
     * @return string
     */
    public function formatCurrency($number, $originalCurrencyCode = null, $noSign = false)
    {
        if (empty($originalCurrencyCode)) {
            $originalCurrencyCode = $this->currencyCode;
        }
        $number = floatval($number);
        $number = contentFilter(self::FILTER_FORMAT_CURRENCY, $number, [$originalCurrencyCode]);
        if ($noSign) {
            return $this->format($number);
        }
        return $this->format($number) . ' ' . $this->currencyCode;
    }

    /**
     * @param float $number
     * @return string
     */
    public function format($number)
    {
        $number = floatval($number);
        switch ($this->type) {
            case 'point_comma':
                return $this->formatPointComma($number);
            case 'point_space':
                return $this->formatPointSpace($number);
            case 'comma_point':
                return $this->formatCommaPoint($number);
            case 'comma_space':
                return $this->formatCommaSpace($number);
            default:
                return $number;
        }
    }

    /**
     * @param string $formattedCurrency
     * @param string $originalCurrencyCode
     * @return float
     */
    public function fromFormatCurrency($formattedCurrency, $originalCurrencyCode = null)
    {
        if (empty($originalCurrencyCode)) {
            $originalCurrencyCode = $this->currencyCode;
        }
        $number = $this->fromFormat($formattedCurrency);
        $number = contentFilter(self::FILTER_FROM_FORMAT_CURRENCY, $number, [$originalCurrencyCode]);
        return $number;
    }

    /**
     * @param string $formattedNumber
     * @return float
     */
    public function fromFormat($formattedNumber)
    {
        $formattedNumber = str_replace(' ' . $this->currencyCode, '', $formattedNumber);
        switch ($this->type) {
            case 'point_comma':
            case 'point_space':
                return $this->fromFormatPoint($formattedNumber);
            case 'comma_point':
            case 'comma_space':
                return $this->fromFormatComma($formattedNumber);
            default:
                return floatval($formattedNumber);
        }
    }

    public function getRegEx($totalLength, $pointLength)
    {
        $restLength = $totalLength - $pointLength;
        $groupMax = $restLength % 3 == 0 ? intval($restLength / 3 - 1) : intval($restLength / 3);
        $chars = $this->getCharsForRegEx();
        return "/^(\d{0,3}|\d{1,3}($chars[1]\d{3}){1,$groupMax})($chars[0]\d{0,$pointLength}){0,1}$/";
    }

    public function getChars()
    {
        switch ($this->type) {
            case 'comma_point':
                return [',', '.'];
            case 'comma_space':
                return [',', ' '];
            case 'point_comma':
                return ['.', ','];
            case 'point_space':
                return ['.', ' '];
            default:
                return ['.', ','];
        }
    }

    public function getCharsForRegEx()
    {
        switch ($this->type) {
            case 'comma_point':
                return ['\,', '\.'];
            case 'comma_space':
                return ['\,', '[ ]'];
            case 'point_comma':
                return ['\.', '\,'];
            case 'point_space':
                return ['\.', '[ ]'];
            default:
                return ['\.', '\,'];
        }
    }

    /**
     * @param float $number
     * @return string
     */
    public function formatPointComma($number)
    {
        return number_format($number, self::$NUMBER_OF_DECIMAL_POINTS, '.', ',');
    }

    /**
     * @param float $number
     * @return string
     */
    public function formatPointSpace($number)
    {
        return number_format($number, self::$NUMBER_OF_DECIMAL_POINTS, '.', ' ');
    }

    /**
     * @param string $formattedNumber
     * @return float
     */
    public function fromFormatPoint($formattedNumber)
    {
        return floatval(preg_replace('/[^\d\.]+/', '', $formattedNumber));
    }

    /**
     * @param float $number
     * @return string
     */
    public function formatCommaPoint($number)
    {
        return number_format($number, self::$NUMBER_OF_DECIMAL_POINTS, ',', '.');
    }

    /**
     * @param float $number
     * @return string
     */
    public function formatCommaSpace($number)
    {
        return number_format($number, self::$NUMBER_OF_DECIMAL_POINTS, ',', ' ');
    }

    /**
     * @param string $formattedNumber
     * @return float
     */
    public function fromFormatComma($formattedNumber)
    {
        return floatval(str_replace(',', '.', preg_replace('/[^\d\,]+/', '', $formattedNumber)));
    }

    public static function doFormat($number, $type)
    {
        switch ($type) {
            case 'point_comma':
                return self::getInstance()->formatPointComma($number);
            case 'point_space':
                return self::getInstance()->formatPointSpace($number);
            case 'comma_point':
                return self::getInstance()->formatCommaPoint($number);
            case 'comma_space':
                return self::getInstance()->formatCommaSpace($number);
            default:
                return self::getInstance()->format($number);
        }
    }

    public static function doFromFormat($formattedNumber, $type)
    {
        switch ($type) {
            case 'point_comma':
            case 'point_space':
                return self::getInstance()->fromFormatPoint($formattedNumber);
            case 'comma_point':
            case 'comma_space':
                return self::getInstance()->fromFormatComma($formattedNumber);
            default:
                return self::getInstance()->fromFormat($formattedNumber);
        }
    }
}