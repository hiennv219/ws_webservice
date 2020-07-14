<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 22:48
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Illuminate\Database\Eloquent\Collection;
use Katniss\Everdeen\Http\Controllers\ViewControllerTrait;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ConversationRepository;
use Katniss\Everdeen\Themes\Queue\JsQueue;

class ConversationController extends WebApiController
{
    use ViewControllerTrait;

    protected $conversationRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'conversation';
        $this->conversationRepository = new ConversationRepository();

        $this->middleware('theme')->only('show');
        $this->middleware('device');
    }

    public function show(Request $request, $id)
    {
        if ($request->has('messages')) {
            return $this->messages($request, $id);
        }

        return $this->responseFail();
    }

    protected function messages(Request $request, $id)
    {
        $conversation = $this->conversationRepository->model($id);
        $users = collect([]);
        $devices = collect([]);
        if ($conversation->isPublic) {
            $this->conversationRepository->updateCurrentDevice();
            $users = $conversation->users()->get();
            $devices = $conversation->devices()->get();
        } elseif ($conversation->isDirect) {
            $users = $conversation->users;
            if (!$request->isAuth()
                || $users->count() != 2
                || $users->where('id', $request->authUser()->id)->count() <= 0
            ) {
                abort(404);
            }
        } elseif ($conversation->isGroup) {
            $users = $conversation->users;
            if (!$request->isAuth()
                || $users->where('id', $request->authUser()->id)->count() <= 0
            ) {
                abort(404);
            }
        }

        if ($request->isAuth()) {
            $users = $this->changeFirstPosition($users, $request->authUser()->id);
        } else {
            $devices = $this->changeFirstPosition($devices, deviceRealId());
        }

        $jsQueue = new JsQueue();
        $jsQueue->add('global-vars', [
            'ORTC_SERVER' => config('services.ortc.server'),
            'ORTC_CLIENT_ID' => session()->getId(),
            'ORTC_CLIENT_KEY' => config('services.ortc.client_key'),
            'ORTC_CLIENT_SECRET' => config('services.ortc.client_secret'),
            'CONVERSATION_ID' => $conversation->id,
            'CONVERSATION_CHANNEL' => $conversation->channel->code,
            'CURRENT_DEVICE_ID' => deviceId() . '', // force convert to string
            'CURRENT_DEVICE_REAL_ID' => deviceRealId(),
            'CONVERSATION_USERS' => json_encode($users->pluck('url_avatar_thumb', 'id')->all()),
            'CONVERSATION_DEVICES' => json_encode($devices->pluck('pivot.color', 'id')->all()),
            'IS_TYPING_LABEL' => trans('label.is_typing'),
            'ANONYMOUS_LABEL' => trans('label.anonymous'),
        ], JsQueue::TYPE_VAR, ['CONVERSATION_USERS', 'CONVERSATION_DEVICES']);
        $jsQueue = $jsQueue->flush(false);

        return $this->_any('messages', [
            'conversation' => $conversation,
            'conversation_users' => $users,
            'conversation_devices' => $devices,
            'js_queue' => $jsQueue,
        ]);
    }

    protected function changeFirstPosition(Collection $collection, $itemId)
    {
        if ($collection->count() <= 0) return $collection;

        $searchIndex = $collection->search(function ($item) use ($itemId) {
            return $item->id == $itemId;
        });
        if ($searchIndex === false) return $collection;

        $collection->prepend($collection->splice($searchIndex, 1)->first());
        return $collection;
    }
}