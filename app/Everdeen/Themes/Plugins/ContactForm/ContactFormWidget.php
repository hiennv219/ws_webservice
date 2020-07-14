<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 18:36
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class ContactFormWidget extends DefaultWidget
{
    const NAME = 'contact_form.widget';
    const DISPLAY_NAME = 'Contact Form';

    public function viewHomeParams()
    {
        return array_merge(parent::viewHomeParams(), [
            'error_bag' => Extension::errorBag(),
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }
}