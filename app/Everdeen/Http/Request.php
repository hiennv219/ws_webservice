<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-06
 * Time: 09:28
 */

namespace Katniss\Everdeen\Http;

use Illuminate\Http\Request as BaseRequest;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Themes\Theme;

class Request extends BaseRequest
{
    /**
     * @var bool
     */
    protected $isAuth;

    /**
     * @var User
     */
    protected $authUser;

    /**
     * @var \stdClass
     */
    protected $urlPathInfo;

    /**
     * @var Theme
     */
    protected $theme;

    public function isAuth()
    {
        return $this->isAuth;
    }

    public function authUser()
    {
        return $this->authUser;
    }

    public function setAuth($isAuth = false, User $authUser = null)
    {
        $this->isAuth = $isAuth;
        $this->authUser = $isAuth ? $authUser : null;
        if ($isAuth) {
            $this->authUser->load(['roles', 'roles.perms', 'settings']);
        }
    }

    public function getUrlPathInfo()
    {
        return $this->urlPathInfo;
    }

    public function checkUrlPathInfo()
    {
        return !empty($this->urlPathInfo);
    }

    public function resolveUrlPathInfo()
    {
        $this->urlPathInfo = new \stdClass();
        $this->urlPathInfo->locale = in_array($this->segment(1), allSupportedLocaleCodes());
        $this->urlPathInfo->api = false;
        $this->urlPathInfo->webApi = false;
        $this->urlPathInfo->admin = false;
        $this->urlPathInfo->home = false;

        $apiPath = 'api'; // can be changed
        if ($this->is($apiPath, $apiPath . '/*')) {
            $this->urlPathInfo->api = true;
            return;
        }
        $webApiPath = 'web-api'; // can be changed

        $this->urlPathInfo->webApi = $this->is($webApiPath, $webApiPath . '/*');

        $adminPaths = _k('paths_use_admin_theme');
        foreach ($adminPaths as $adminPath) {
            $adminPath = !$this->urlPathInfo->webApi ? homePath($adminPath) : $webApiPath . '/' . $adminPath;
            if ($this->is($adminPath, $adminPath . '/*')) {
                $this->urlPathInfo->admin = true;
                break;
            }
        }

        $this->urlPathInfo->home = !$this->urlPathInfo->admin;
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param Theme $theme
     */
    public function setTheme(Theme $theme)
    {
        $this->theme = $theme;
    }

    public function checkTheme()
    {
        return !empty($this->theme);
    }
}