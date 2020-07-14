<?php
/**
 * Created by PhpStorm.
 * User: daiduong47
 * Date: 14/01/2017
 * Time: 22:07 PM
 */

namespace Katniss\Everdeen\Themes\Plugins\TawktoChatBoxServices;

use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Utils\AssetHelper;
use Katniss\Everdeen\Utils\HtmlTag\Html5;

class Extension extends BaseExtension
{
    const NAME = 'tawkto_chatbox_services';
    const DISPLAY_NAME = 'Tawk.to Chatbox Services';
    const DESCRIPTION = 'Set up Tawkto Chatbox Services';
    const DEFAULT_TAWKTO_ID = '54f6736dbd5fa428704c651a';

    public $cacheEnable;
    public $chatboxEnable;
    public $chatboxAsync;
    public $chatboxId;

    public function register()
    {
        if (!isMobileClient()) {
            if ($this->chatboxEnable == 1) {
                if ($this->cacheEnable == 1) {
                    enqueueThemeFooter(Html5::js(AssetHelper::jsUrl($this::NAME)), $this::NAME);
                } else {
                    enqueueThemeFooter($this->rawChatboxScript(), $this::NAME);
                }
            }
        }
    }

    public function chatboxScript()
    {
        if ($this->chatboxAsync) {
            $async = 'true';
        } else {
            $async = 'false';
        }
        return 'var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=' . $async . ';
                s1.src=\'https://embed.tawk.to/' . $this->chatboxId . '/default\';
                s1.charset=\'UTF-8\';
                s1.setAttribute(\'crossorigin\',\'*\');
                s0.parentNode.insertBefore(s1,s0);
                })();';
    }

    public function rawChatboxScript()
    {
        return '<!--Start of Tawk.to Script--> <script type="text/javascript">' . $this->chatboxScript() . '</script> <!--End of Tawk.to Script-->';
    }

    public function cacheScripts()
    {
        if ($this->cacheEnable) {
            $cache = '';
            if ($this->chatboxEnable) {
                $cache .= $this->chatboxScript() . PHP_EOL;
            }
            if (!empty($cache)) {
                AssetHelper::cacheJs($this::NAME, $cache);
            }
        }
    }

    protected function __init()
    {
        parent::__init();

        $this->cacheEnable = defPr($this->getProperty('cache_enable'), 0);

        $this->chatboxEnable = defPr($this->getProperty('chatbox_enable'), 0);
        $this->chatboxAsync = defPr($this->getProperty('chatbox_async'), 0);
        $this->chatboxId = defPr($this->getProperty('chatbox_id'), $this::DEFAULT_TAWKTO_ID);

        if (!$this->chatboxEnable) {
            $this->cacheEnable = false;
        }
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'cache_enable' => $this->cacheEnable,
            'chatbox_enable' => $this->chatboxEnable,
            'chatbox_id' => $this->chatboxId,
            'chatbox_async' => $this->chatboxAsync,
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();
        return array_merge($fields, [
            'cache_enable',
            'chatbox_enable',
            'chatbox_id',
            'chatbox_async',
        ]);
    }

    public function validationRules()
    {
        $validationRules = parent::validationRules();
        return array_merge($validationRules, [
            'cache_enable' => 'sometimes|nullable|in:1',
            'chatbox_enable' => 'sometimes|nullable|in:1',
            'chatbox_async' => 'sometimes|nullable|in:1',
            'chatbox_id' => 'required_if:chatbox_enable,1',
        ]);
    }

    public function save(array $data = [], array $localizedData = [])
    {
        $result = parent::save($data, $localizedData);

        if ($result === true) {
            $extension = new Extension();
            $extension->cacheScripts();
        }

        return $result;
    }
}
