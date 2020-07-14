<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:43
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls\Controllers;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\Admin\AdminController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\PluginControllerTrait;
use Katniss\Everdeen\Themes\Plugins\Polls\Extension;
use Katniss\Everdeen\Themes\Plugins\Polls\Repositories\PollRepository;

class PollAdminController extends AdminController
{
    use PluginControllerTrait;

    protected $pollRepository;

    public function __construct()
    {
        parent::__construct();

        $this->pollRepository = new PollRepository();
    }

    public function index(Request $request)
    {
        $polls = $this->pollRepository->getPaged();

        return $request->getTheme()->resolveExtraView(
            $this->_extra('poll.index', Extension::NAME),
            trans('polls.page_polls_title'),
            trans('polls.page_polls_desc'),
            [
                'polls' => $polls,
                'pagination' => $this->paginationRender->renderByPagedModels($polls),
                'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            ]
        );
    }

    public function create(Request $request)
    {
        return $request->getTheme()->resolveExtraView(
            $this->_extra('poll.create', Extension::NAME),
            trans('polls.page_polls_title'),
            trans('polls.page_polls_desc')
        );
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
            'description' => 'sometimes|nullable|max:255',
        ]);

        $this->_rdrUrl($request, adminUrl(), $rdrUrl, $errorRdrUrl);

        if ($validateResult->isFailed()) {
            return redirect($errorRdrUrl)
                ->withInput()
                ->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'multi_choice' => 'sometimes|nullable|in:1',
        ]);
        if ($validator->fails()) {
            return redirect($errorRdrUrl)
                ->withInput()
                ->withErrors($validator);
        }

        try {
            $this->pollRepository->create(
                $validateResult->getLocalizedInputs(),
                $request->input('multi_choice', 0) == 1
            );
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)
                ->withInput()
                ->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function edit(Request $request, $id)
    {
        $poll = $this->pollRepository->model($id);

        $this->_title([trans('pages.admin_links_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_links_desc'));

        return $request->getTheme()->resolveExtraView(
            $this->_extra('poll.edit', Extension::NAME),
            trans('polls.page_polls_title'),
            trans('polls.page_polls_desc'),
            [
                'poll' => $poll,
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $this->pollRepository->model($id);

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
            'description' => 'sometimes|nullable|max:255',
        ]);

        $this->_rdrUrl($request, adminUrl(), $rdrUrl, $errorRdrUrl);

        if ($validateResult->isFailed()) {
            return redirect($rdrUrl)->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'multi_choice' => 'sometimes|nullable|in:1',
        ]);
        if ($validator->fails()) {
            return redirect($rdrUrl)->withErrors($validator);
        }

        try {
            $this->pollRepository->update(
                $validateResult->getLocalizedInputs(),
                $request->input('multi_choice', 0) == 1
            );
        } catch (KatnissException $ex) {
            return redirect($rdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function destroy(Request $request, $id)
    {
        $this->pollRepository->model($id);

        $this->_rdrUrl($request, null, $rdrUrl, $errorRdrUrl);

        try {
            $this->pollRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function sort(Request $request, $id)
    {
        $poll = $this->pollRepository->model($id);

        $this->_title([trans('pages.admin_link_categories_title'), trans('form.action_sort')]);
        $this->_description(trans('pages.admin_link_categories_desc'));

        return $request->getTheme()->resolveExtraView(
            'plugins.polls.admin.poll.sort',
            trans('polls.page_polls_title'),
            trans('polls.page_polls_desc'),
            [
                'poll' => $poll,
                'choices' => $poll->orderedChoices,
            ]
        );
    }
}