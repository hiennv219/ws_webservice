<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\AppOptionRepository;
use Katniss\Everdeen\Utils\AppConfig;

class AppOptionController extends AdminController
{
    protected $appOptionRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'app_option';
        $this->appOptionRepository = new AppOptionRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $options = $this->appOptionRepository->getPaged();

        $this->_title(trans('pages.admin_app_options_title'));
        $this->_description(trans('pages.admin_app_options_desc'));

        return $this->_index([
            'options' => $options,
            'pagination' => $this->paginationRender->renderByPagedModels($options),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'value_max_length' => AppConfig::TINY_SHORTEN_TEXT_LENGTH,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $appOption = $this->appOptionRepository->model($id);

        $this->_title([trans('pages.admin_app_options_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_app_options_desc'));

        return $this->_edit([
            'app_option' => $appOption,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $appOption = $this->appOptionRepository->model($id);

        $validator = Validator::make($request->all(), [
            'raw_value' => 'required',
        ]);

        $rdr = redirect(adminUrl('app-options/{id}/edit', ['id' => $appOption->id]));

        if ($validator->fails()) {
            return $rdr->withErrors($validator);
        }

        $this->appOptionRepository->update($request->input('raw_value'));

        return $rdr;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $this->appOptionRepository->model($id);

        $this->_rdrUrl($request, adminUrl('app-options'), $rdrUrl, $errorRdrUrl);

        try {
            $this->appOptionRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
