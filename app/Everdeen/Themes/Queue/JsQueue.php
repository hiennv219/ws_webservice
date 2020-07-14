<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-14
 * Time: 19:32
 */

namespace Katniss\Everdeen\Themes\Queue;


use Katniss\Everdeen\Utils\HtmlTag\Html5;

class JsQueue extends AssetQueue
{
    const LIB_JQUERY_NAME = 'jquery';
    const LIB_JQUERY_UI_NAME = 'jquery-ui';
    const LIB_BOOTSTRAP_NAME = 'bootstrap';
    const LIB_JQUERY_UI_BOOTSTRAP_NAME = 'jquery-ui-bootstrap';

    const TYPE_RAW = 'raw';
    const TYPE_VAR = 'var';

    protected $rawValueNames;

    public function __construct()
    {
        parent::__construct();

        $this->rawValueNames = [];
    }

    public function setRawValueName()
    {
        $this->rawValueNames = func_get_args();
    }

    public function add($name, $content, $type = AssetQueue::TYPE_DEFAULT, $rawValueNames = [], $merge = false)
    {
        if ($name == self::LIB_BOOTSTRAP_NAME && $this->existed(self::LIB_JQUERY_UI_NAME)) {
            parent::add(
                self::LIB_JQUERY_UI_BOOTSTRAP_NAME,
                _kExternalLink(self::LIB_JQUERY_UI_BOOTSTRAP_NAME),
                self::TYPE_RAW
            );
        }
        if ($merge && $type == self::TYPE_VAR && $this->existed($name) && $this->queue[$name]['type'] == self::TYPE_VAR) {
            $this->queue[$name]['content'] = array_merge($this->queue[$name]['content'], $content);
            $this->rawValueNames = array_merge($this->rawValueNames, $rawValueNames);
        } else {
            parent::add($name, $content, $type);
            if ($type == self::TYPE_VAR) {
                $this->rawValueNames = $rawValueNames;
            }
        }
        if ($name == self::LIB_JQUERY_UI_NAME && $this->existed(self::LIB_BOOTSTRAP_NAME)) {
            parent::add(
                self::LIB_JQUERY_UI_BOOTSTRAP_NAME,
                _kExternalLink(self::LIB_JQUERY_UI_BOOTSTRAP_NAME),
                self::TYPE_RAW
            );
        }
    }

    public function flush($echo = true)
    {
        $this->output = [];
        foreach ($this->queue as $name => $item) {
            switch ($item['type']) {
                case self::TYPE_RAW:
                    $this->output[] = Html5::jsInline($item['content']);
                    break;
                case self::TYPE_VAR:
                    $content = [];
                    foreach ($item['content'] as $varName => $varValue) {
                        $content[] = 'var ' . $varName . ' = ' . $this->getVarValue($varValue, in_array($varName, $this->rawValueNames)) . ';';
                    }
                    $this->output[] = Html5::jsInline(implode(PHP_EOL, $content));
                    break;
                default:
                    $this->output[] = Html5::js($item['content']);
                    break;
            }
        }

        return parent::flush($echo);
    }

    private function getVarValue($value, $raw = false)
    {
        if (is_string($value)) {
            return $raw ? $value : "'" . $value . "'";
        } elseif (is_array($value)) {
            if (array_keys($value) !== range(0, count($value) - 1)) {
                return $this->getVarValue(json_encode($value), $raw);
            } else {
                $p = [];
                foreach ($value as $item) {
                    $p[] = $this->getVarValue($item, $raw);
                }
                return '[' . implode(',', $p) . ']';
            }
        } elseif (is_object($value)) {
            return $this->getVarValue(json_encode($value), $raw);
        }

        return $value;
    }
}