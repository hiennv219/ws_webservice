<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 22:48
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;


use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\MessageRepository;
use Katniss\Everdeen\Themes\Theme;
use Katniss\Everdeen\Utils\AppConfig;

class MessageController extends WebApiController
{
    protected $messageRepository;

    public function __construct()
    {
        parent::__construct();

        $this->messageRepository = new MessageRepository();

        $this->middleware('device');
    }

    public function index(Request $request)
    {
        if ($request->has('previous')) {
            return $this->previous($request);
        }
        return $this->responseFail();
    }

    public function store(Request $request)
    {
        if (!$this->customValidate($request, [
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|max:255',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $message = $this->messageRepository->create(
                $request->input('conversation_id'),
                $request->input('content'),
                $request->isAuth(),
                deviceRealId()
            );

            return $this->responseSuccess([
                'message' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'is_owner' => $message->isOwner,
                    'device_id' => $message->device_id,
                    'user_id' => $message->user_id,
                ],
                'device_id' => deviceId(),
            ]);
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }

    protected function previous(Request $request)
    {
        if (!$this->customValidate($request, [
            'conversation_id' => 'required|exists:conversations,id',
            'message_id' => 'sometimes|nullable|exists:messages,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        $messages = $this->messageRepository->getPreviousByConversation(
            $request->input('conversation_id'),
            $conversation,
            $request->input('message_id', null)
        );

        if ($conversation->isDirect) {
            $users = $conversation->users;
            if (!$request->isAuth()
                || $users->count() != 2
                || $users->where('id', $request->authUser()->id)->count() <= 0
            ) {
                abort(404);
            }
        } elseif ($conversation->isGroup) {
            if (!$request->isAuth()
                || $conversation->users()->where('id', $request->authUser()->id)->count() <= 0
            ) {
                abort(404);
            }
        }

        $result = [];
        foreach ($messages as $message) {
            $result[] = [
                'id' => $message->id,
                'content' => $message->content,
                'is_owner' => $message->isOwner,
                'device_id' => $message->device_id,
                'user_id' => $message->user_id,
            ];
        }
        return $this->responseSuccess([
            'messages' => $result,
            'max_messages' => AppConfig::DEFAULT_ITEMS_PER_PAGE,
        ]);
    }
}