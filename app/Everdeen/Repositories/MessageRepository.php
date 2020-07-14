<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 20:07
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Message;
use Katniss\Everdeen\Utils\AppConfig;

class MessageRepository extends ModelRepository
{
    public function getById($id)
    {
        return Message::findOrFail($id);
    }

    public function getPaged()
    {
        return Message::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Message::all();
    }

    public function getPreviousByConversation($conversationId, &$conversation, $currentMessageId = null)
    {
        $conversationRepository = new ConversationRepository($conversationId);
        $conversation = $conversationRepository->model();
        $messages = $conversation->messages();
        if (!empty($currentMessageId)) {
            $messages->where('id', '<', $currentMessageId);
        }
        return $messages->take(AppConfig::DEFAULT_ITEMS_PER_PAGE)
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function create($conversationId, $content, $isAuth, $deviceId)
    {
        $attributes = [
            'conversation_id' => $conversationId,
            'content' => $content,
        ];
        if ($isAuth) {
            $attributes['user_id'] = $deviceId;
        } else {
            $attributes['device_id'] = $deviceId;
        }
        try {
            return Message::create($attributes);
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $exception->getMessage() . ')');
        }
    }
}