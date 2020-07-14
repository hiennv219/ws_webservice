<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-06
 * Time: 15:31
 */

namespace Katniss\Everdeen\Themes\Plugins\DefaultWidget;

use Katniss\Everdeen\Themes\Widget as BaseWidget;

class Widget extends BaseWidget
{
    const NAME = 'default_widget';
    const DISPLAY_NAME = 'Default PollWidget';
    const TRANSLATABLE = true;

    public $name = '';
    public $description = '';

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    protected function __init()
    {
        parent::__init();

        $this->name = $this->getProperty('name');
        $this->description = $this->getProperty('description');
    }

    public function viewHomeParams()
    {
        return array_merge(parent::viewHomeParams(), [
            'name' => $this->name,
            'description' => $this->description,
        ]);
    }

    public function render()
    {
        return '';
    }

    public function localizedFields()
    {
        return array_merge(parent::localizedFields(), [
            'name',
            'description'
        ]);
    }

    public function localizedValidationRules()
    {
        return array_merge(parent::localizedValidationRules(), [
            'name' => 'sometimes|nullable|max:255',
            'description' => 'sometimes|nullable|max:255',
        ]);
    }
}