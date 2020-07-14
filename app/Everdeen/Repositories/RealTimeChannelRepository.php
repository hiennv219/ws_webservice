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
use Katniss\Everdeen\Models\RealTimeChannel;
use Katniss\Everdeen\Utils\AppConfig;

class RealTimeChannelRepository extends ModelRepository
{
    public function getById($id)
    {
        return RealTimeChannel::findOrFail($id);
    }

    public function getPaged()
    {
        return RealTimeChannel::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return RealTimeChannel::all();
    }

    public static function getByCode($code)
    {
        return self::where('code', $code)->firstOrFail();
    }

    /**
     * @param int $type
     * @return RealTimeChannel
     * @throws KatnissException
     */
    public function create($type = RealTimeChannel::TYPE_CONVERSATION)
    {
        try {
            return RealTimeChannel::create([
                'type' => $type,
            ]);
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $exception->getMessage() . ')');
        }
    }
}