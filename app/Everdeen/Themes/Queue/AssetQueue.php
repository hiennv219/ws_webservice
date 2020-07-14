<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-14
 * Time: 19:31
 */

namespace Katniss\Everdeen\Themes\Queue;


abstract class AssetQueue
{
    const TYPE_DEFAULT = '';

    protected $queue;
    protected $output;

    public function __construct()
    {
        $this->queue = [];
        $this->output = [];
    }

    public function add($name, $content, $type = AssetQueue::TYPE_DEFAULT)
    {
        $this->queue[$name] = [
            'type' => $type,
            'content' => $content,
        ];
    }

    public function remove($name)
    {
        if (isset($this->queue[$name])) {
            unset($this->queue[$name]);
        }
    }

    public function existed($name)
    {
        return isset($this->queue[$name]);
    }

    public function flush($echo = true)
    {
        if ($echo) {
            echo implode(PHP_EOL, $this->output);
            return true;
        }

        return implode(PHP_EOL, $this->output);
    }
}