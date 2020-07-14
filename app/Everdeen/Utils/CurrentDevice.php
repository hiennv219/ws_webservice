<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-07-23
 * Time: 21:24
 */

namespace Katniss\Everdeen\Utils;

use Katniss\Everdeen\Models\Device;
use Katniss\Everdeen\Models\User;

class CurrentDevice
{
    /**
     * @var User|Device
     */
    private static $device;

    /**
     * @param User|Device $user
     */
    public static function setDevice($device)
    {
        self::$device = $device;
    }

    /**
     * @return User|Device
     */
    public static function getDevice()
    {
        return self::$device;
    }

    public static function getDeviceId()
    {
        if (!empty(self::$device)) {
            if (self::$device instanceof User) {
                return self::$device->id;
            }
            if (self::$device instanceof Device) {
                return self::$device->uuid;
            }
        }
        return null;
    }

    public static function getDeviceSecret()
    {
        if (!empty(self::$device)) {
            if (self::$device instanceof User) {
                return self::$device->password;
            }
            if (self::$device instanceof Device) {
                return self::$device->secret;
            }
        }
        return null;
    }

    public static function getDeviceRealId()
    {
        if (!empty(self::$device)) {
            return self::$device->id;
        }
        return null;
    }
}