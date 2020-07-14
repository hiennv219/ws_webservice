<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\DateTimeHelper;

class DocumentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'my_documents';
    }

    public function index(Request $request)
    {
        if ($request->authUser()->hasRole('student_agent')) {
            abort(401);
        }

        $this->_title(trans('pages.my_documents_title'));
        $this->_description(trans('pages.my_documents_desc'));

        return $this->_index([
            'dateFormat' => DateTimeHelper::shortDateFormat(),
            'timeFormat' => DateTimeHelper::shortTimeFormat(),
        ]);
    }
}
