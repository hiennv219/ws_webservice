<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\RegisterLearningRequestRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;

class LearningRequestController extends AdminController
{
    protected $learningRequestRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'learning_request';
        $this->learningRequestRepository = new RegisterLearningRequestRepository();
    }

    public function indexRegistering(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'created_at' => 'sometimes|nullable|date_format:' . DateTimeHelper::shortDateFormat(),
        ]);
        if ($validator->fails()) {
            $searchCreatedAt = null;
        } else {
            $searchCreatedAt = $request->input('created_at', null);
        }
        if (!empty($searchCreatedAt)) {
            $searchCreatedAt = DateTimeHelper::getInstance()->fromFormat(
                DateTimeHelper::shortDateFormat(), $searchCreatedAt
            );
        }
        $learningRequests = $this->learningRequestRepository->getSearchNewlyPaged(
            empty($searchCreatedAt) ? null : $searchCreatedAt->format('Y-m-d')
        );

        $this->_title(trans('pages.admin_register_learning_requests_title'));
        $this->_description(trans('pages.admin_register_learning_requests_desc'));

        return $this->_any('index_registering', [
            'learning_requests' => $learningRequests,
            'pagination' => $this->paginationRender->renderByPagedModels($learningRequests),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchCreatedAt),
            'search_created_at' => empty($searchCreatedAt) ?
                null : $searchCreatedAt->format(DateTimeHelper::shortDateFormat()),
        ]);
    }

    public function indexProcessed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'created_at' => 'sometimes|nullable|date_format:' . DateTimeHelper::shortDateFormat(),
        ]);
        if ($validator->fails()) {
            $searchCreatedAt = null;
        } else {
            $searchCreatedAt = $request->input('created_at', null);
        }
        if (!empty($searchCreatedAt)) {
            $searchCreatedAt = DateTimeHelper::getInstance()->fromFormat(
                DateTimeHelper::shortDateFormat(), $searchCreatedAt
            );
        }
        $learningRequests = $this->learningRequestRepository->getSearchProcessedPaged(
            empty($searchCreatedAt) ? null : $searchCreatedAt->format('Y-m-d')
        );

        $this->_title(trans('pages.admin_processed_learning_requests_title'));
        $this->_description(trans('pages.admin_processed_learning_requests_desc'));

        return $this->_any('index_processed', [
            'learning_requests' => $learningRequests,
            'pagination' => $this->paginationRender->renderByPagedModels($learningRequests),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchCreatedAt),
            'search_created_at' => empty($searchCreatedAt) ?
                null : $searchCreatedAt->format(DateTimeHelper::shortDateFormat()),
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->has('process')) {
            return $this->process($request, $id);
        }

        abort(404);
        return '';
    }

    protected function process(Request $request, $id)
    {
        $this->learningRequestRepository->model($id);

        $this->_rdrUrl($request, adminUrl('register-learning-requests'), $rdrUrl, $errorRdrUrl);

        try {
            $this->learningRequestRepository->process($request->authUser()->id);
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
