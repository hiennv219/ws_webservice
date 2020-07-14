<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 00:28
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm\Controllers;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Repositories\ContactFormRepository;

class ContactFormHomeController extends ViewController
{
    protected $contactFormRepository;

    public function __construct()
    {
        parent::__construct();

        $this->contactFormRepository = new ContactFormRepository();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|max:255',
            'phone' => 'required_without_all:email|max:255',
            'email' => 'required_without_all:phone|email|max:255',
            'website' => 'sometimes|nullable|url|max:255',
            'address' => 'sometimes|nullable|max:255',
        ]);

        $this->_rdrUrl($request, homeUrl(), $rdrUrl, $errorRdrUrl);

        if ($validator->fails()) {
            return redirect($rdrUrl)
                ->withInput()
                ->withErrors($validator, $request->input('error_bag'));
        }

        try {
            $this->contactFormRepository->create(
                $request->input('full_name'),
                $request->input('phone', ''),
                $request->input('email', ''),
                $request->input('message'),
                $request->input('address', ''),
                $request->input('website', '')
            );
        } catch (KatnissException $ex) {
            return redirect($rdrUrl)
                ->withInput()
                ->withErrors([$ex->getMessage()], $request->input('error_bag'));
        }

        return redirect($rdrUrl);
    }
}