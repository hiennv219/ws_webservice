<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 00:28
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm\Controllers;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\Admin\AdminController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\PluginControllerTrait;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Extension;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Repositories\ContactFormRepository;
use Katniss\Everdeen\Utils\AppConfig;

class ContactFormAdminController extends AdminController
{
    use PluginControllerTrait;

    protected $contactFormRepository;

    public function __construct()
    {
        parent::__construct();

        $this->contactFormRepository = new ContactFormRepository();
    }

    public function index(Request $request)
    {
        $contactForms = $this->contactFormRepository->getPaged();

        return $request->getTheme()->resolveExtraView(
            $this->_extra('index', Extension::NAME),
            trans('contact_form.page_contact_forms_title'),
            trans('contact_form.page_contact_forms_desc'),
            [
                'contact_forms' => $contactForms,
                'pagination' => $this->paginationRender->renderByPagedModels($contactForms),
                'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
                'message_length' => AppConfig::TITLE_SHORTEN_TEXT_LENGTH,
            ]
        );
    }

    public function destroy(Request $request, $id)
    {
        $this->contactFormRepository->model($id);

        $this->_rdrUrl($request, null, $rdrUrl, $errorRdrUrl);

        try {
            $this->contactFormRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}