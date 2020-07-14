<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:43
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls\Controllers;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\Polls\Repositories\PollRepository;

class PollWebApiController extends WebApiController
{
    protected $pollRepository;

    public function __construct()
    {
        parent::__construct();

        $this->pollRepository = new PollRepository();
    }

    public function update(Request $request, $id)
    {
        if ($request->has('sort')) {
            return $this->updateOrder($request, $id);
        }
        if ($request->has('votes')) {
            return $this->updateVotes($request, $id);
        }

        return $this->responseSuccess();
    }

    public function updateOrder(Request $request, $id)
    {
        $this->pollRepository->model($id);

        if (!$this->customValidate($request, [
            'choice_ids' => 'required|array|exists:poll_choices,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->pollRepository->updateSort($request->input('choice_ids'));
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }

    public function updateVotes(Request $request, $id)
    {
        $poll = $this->pollRepository->model($id);

        if (!$this->customValidate($request, [
            'choice_ids' => 'required|array|exists:poll_choices,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->pollRepository->updateVotes($request->input('choice_ids'));
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }

        $totalVotes = 0;
        $choices = [];
        foreach ($poll->orderedChoices as $choice) {
            $choices[] = [
                'name' => $choice->name,
                'votes' => $choice->votes,
            ];
            $totalVotes += $choice->votes;
        }

        return $this->responseSuccess([
            'choices' => $choices,
            'total_votes' => $totalVotes
        ]);
    }
}