<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 21:20
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Device;

class DeviceRepository
{
    public function getByUuidAndSecret($id, $secret)
    {
        return Device::where('uuid', $id)->where('secret', $secret)->first();
    }

    /**
     * @return Device
     * @throws KatnissException
     */
    public function create()
    {
        try {
            return Device::create([
                'secret' => bcrypt(str_random()),
            ]);
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $exception->getMessage() . ')');
        }
    }
}