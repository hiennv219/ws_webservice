<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-14
 * Time: 23:44
 */

namespace Katniss\Everdeen\Themes\Queue;

use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class TextQueue extends AssetQueue
{
    const TYPE_CALLABLE_OBJECT = 'callable_object';

    public function __construct()
    {
        parent::__construct();
    }

    public function add($name, $content, $type = AssetQueue::TYPE_DEFAULT)
    {
        if (empty($name)) {
            $name = count($this->queue);
        }
        if (is_a($content, CallableObject::class)) {
            $type = self::TYPE_CALLABLE_OBJECT;
        }
        parent::add($name, $content, $type);
    }

    public function flush($echo = true)
    {
        $this->output = [];
        foreach ($this->queue as $name => $item) {
            if ($item['type'] == self::TYPE_CALLABLE_OBJECT) {
                $this->output[] = $item['content']->execute();
            } else {
                $this->output[] = $item['content'];
            }
        }

        return parent::flush($echo);
    }
}