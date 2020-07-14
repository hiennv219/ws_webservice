<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-20
 * Time: 08:10
 */

namespace Katniss\Everdeen\Utils\ExtraActions;


class CallableObject
{
    protected $function;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $tmpParams;

    /**
     * CallableObject constructor.
     * @param callable $function
     * @param array $params
     */
    public function __construct($function, array $params = [])
    {
        $this->function = $function;
        $this->params = $params;
        $this->tmpParams = $this->params;
    }

    public function unShiftParam($param)
    {
        array_unshift($this->tmpParams, $param);
    }

    public function unShiftParams(array $params)
    {
        $this->tmpParams = array_merge($params, $this->tmpParams);
    }

    public function pushParam($param)
    {
        $this->tmpParams[] = $param;
    }

    public function pushParams(array $params)
    {
        $this->tmpParams = array_merge($this->tmpParams, $params);
    }

    public function execute($refresh = true)
    {
        $tmpParams = $this->tmpParams;
        if ($refresh) {
            $this->tmpParams = $this->params;
        }

        if (!empty($this->function)) {
            return call_user_func_array($this->function, $tmpParams);
        }

        return false;
    }
}