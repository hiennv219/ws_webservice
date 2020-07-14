<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\TopicRepository;

class TopicController extends AdminController
{
    protected $topicRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'topic';
        $this->topicRepository = new TopicRepository();
    }

    public function index(Request $request)
    {
        $topics = $this->topicRepository->getPaged();
        $this->_title(trans('pages.admin_topics_title'));
        $this->_description(trans('pages.admin_topics_desc'));

        return $this->_index([
            'topics' => $topics,
            'pagination' => $this->paginationRender->renderByPagedModels($topics),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function create()
    {
        $this->_title([trans('pages.admin_topics_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_topics_desc'));

        return $this->_create();
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        $errorRedirect = redirect(adminUrl('topics/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $errorRedirect->withErrors($validateResult->getFailed());
        }

        try {
            $this->topicRepository->create($validateResult->getLocalizedInputs());
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('topics'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $topic = $this->topicRepository->model($id);

        $this->_title([trans('pages.admin_topics_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_topics_desc'));

        return $this->_edit([
            'topic' => $topic,
        ]);
    }

    public function update(Request $request, $id)
    {
        $topic = $this->topicRepository->model($id);

        $redirect = redirect(adminUrl('topics/{id}/edit', ['id' => $topic->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        try {
            $this->topicRepository->update($validateResult->getLocalizedInputs());
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect;
    }

    public function destroy(Request $request, $id)
    {
        $this->topicRepository->model($id);

        $this->_rdrUrl($request, adminUrl('topics'), $rdrUrl, $errorRdrUrl);

        try {
            $this->topicRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
