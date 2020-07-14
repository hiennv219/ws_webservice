<?php

namespace Katniss\Everdeen\Http\Middleware;

use Closure;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\DeviceRepository;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Utils\CurrentDevice;
use Messaging\Device;
use Messaging\User;

class DeviceMiddleware
{
    private $deviceId;
    private $deviceSecret;
    private $needStoreCookie;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->needStoreCookie = false;

        $this->deviceFromCookie($request);

        if (!isAuth()) {
            if (empty($this->deviceId) || empty($this->deviceSecret)) {
                $this->createDevice();
            } else {
                $this->checkDevice();
            }
        } else {
            $authUser = authUser();
            CurrentDevice::setDevice($authUser);
            if ($this->deviceId != CurrentDevice::getDeviceId()
                || $this->deviceSecret != CurrentDevice::getDeviceSecret()
            ) {
                $this->deviceId = CurrentDevice::getDeviceId();
                $this->deviceSecret = CurrentDevice::getDeviceSecret();
                $this->needStoreCookie = true;
            }
        }

        if ($this->needStoreCookie) {
            return $next($request)
                ->withCookie(cookie()->forever('device', json_encode([
                    'id' => $this->deviceId,
                    'secret' => $this->deviceSecret,
                ])));
        }

        return $next($request);
    }

    private function deviceFromCookie(Request $request)
    {
        $device = $request->cookie('device', null);
        if (!empty($device)) {
            $device = json_decode($device);
            $this->deviceId = $device->id;
            $this->deviceSecret = $device->secret;
        }
    }

    private function checkDevice()
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getByNameAndHashedPassword($this->deviceId, $this->deviceSecret);
        if (!empty($user)) {
            CurrentDevice::setDevice($user);
            return;
        }
        $deviceRepository = new DeviceRepository();
        $device = $deviceRepository->getByUuidAndSecret($this->deviceId, $this->deviceSecret);
        if (!empty($device)) {
            CurrentDevice::setDevice($device);
            return;
        }
        $this->createDevice();
    }

    private function createDevice()
    {
        $deviceRepository = new DeviceRepository();
        $device = $deviceRepository->create();
        CurrentDevice::setDevice($device);
        $this->deviceId = CurrentDevice::getDeviceId();
        $this->deviceSecret = CurrentDevice::getDeviceSecret();
        $this->needStoreCookie = true;
    }
}
