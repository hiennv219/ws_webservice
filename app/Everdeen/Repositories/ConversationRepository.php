<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 19:59
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Conversation;
use Katniss\Everdeen\Models\Device;
use Katniss\Everdeen\Models\RealTimeChannel;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\AppConfig;

class ConversationRepository extends ModelRepository
{
    public function getById($id)
    {
        return Conversation::findOrFail($id);
    }

    public function getPaged()
    {
        return Conversation::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Conversation::all();
    }

    public function create($type = Conversation::TYPE_PUBLIC)
    {
        $channelRepository = new RealTimeChannelRepository();
        $channel = $channelRepository->create(RealTimeChannel::TYPE_CONVERSATION);

        try {
            return Conversation::create([
                'channel_id' => $channel->id,
                'type' => $type,
            ]);
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $exception->getMessage() . ')');
        }
    }

    public function updateCurrentDevice()
    {
        $conversation = $this->model();
        $currentDevice = device();
        try {
            if ($currentDevice instanceof User) {
                if ($conversation->users()->where('id', deviceRealId())->count() <= 0) {
                    $conversation->users()->attach(deviceRealId());
                }
            } elseif ($currentDevice instanceof Device) {
                $colors = $conversation->devices
                    ->pluck('pivot.color', 'id')
                    ->all();

                if (array_key_exists($currentDevice->id, $colors)) return true;

                unset($colors[$currentDevice->id]);
                $color = rgbToHex();
                while (in_array($color, $colors)) {
                    $color = rgbToHex();
                }
                $conversation->devices()->attach($currentDevice->id, [
                    'color' => $color
                ]);
            }
            return true;
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_update') . ' (' . $exception->getMessage() . ')');
        }
    }
}