<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 00:28
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm\Controllers;

use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Repositories\ContactFormRepository;

class ContactFormWebApiController extends WebApiController
{
    protected $contactFormRepository;

    public function __construct()
    {
        parent::__construct();

        $this->contactFormRepository = new ContactFormRepository();
    }

    public function show(Request $request, $id)
    {
        $contactForm = $this->contactFormRepository->model($id);

        return $this->responseSuccess($contactForm);
    }
}