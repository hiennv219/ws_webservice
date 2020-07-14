<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-19
 * Time: 13:35
 */

namespace Katniss\Everdeen\Themes\Plugins\AnalyticServices;

use Katniss\Everdeen\Utils\AssetHelper;
use Katniss\Everdeen\Utils\HtmlTag\Html5;
use Katniss\Everdeen\Themes\Extension as BaseExtension;

class Extension extends BaseExtension
{
    const NAME = 'analytic_services';
    const DISPLAY_NAME = 'Analytic Services';
    const DESCRIPTION = 'Set up Analytic Services';

    public $cacheEnable;
    public $gaEnable;
    public $gaId;
    public $gaAsync;
    public $mixPanelEnable;
    public $mixPanelToken;

    public function rawGaScript()
    {
        return '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');
ga(\'create\', \'' . $this->gaId . '\', \'auto\');
ga(\'send\', \'pageview\');';
    }

    public function gaScript()
    {
        return '<!-- Google Analytics --><script>' . $this->rawGaScript() . '</script><!-- End Google Analytics -->';
    }

    public function rawGaScriptAsync()
    {
        return 'window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
ga(\'create\', \'' . $this->gaId . '\', \'auto\');
ga(\'send\', \'pageview\');';
    }

    public function gaJsAsync()
    {
        return Html5::js('//www.google-analytics.com/analytics.js', true);
    }

    public function gaScriptAsync()
    {
        return '<!-- Google Analytics -->
<script>' . $this->rawGaScriptAsync() . '</script>
' . $this->gaJsAsync() . '
<!-- End Google Analytics -->';
    }

    public function rawMixPanelScript()
    {
        return '(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
mixpanel.init(\'' . $this->mixPanelToken . '\');
mixpanel.track(\'pageview\');';
    }

    public function mixPanelScript()
    {
        return '<!-- start Mixpanel --><script>' . $this->rawMixPanelScript() . '</script><!-- end Mixpanel -->';
    }

    public function register()
    {
        if (!$this->cacheEnable) {
            if ($this->gaEnable) {
                enqueueThemeFooter($this->gaAsync ? $this->gaScriptAsync() : $this->gaScript(), 'ga_script');
            }
            if ($this->mixPanelEnable) {
                enqueueThemeFooter($this->mixPanelScript(), 'mix_panel_script');
            }
        } else {
            enqueueThemeFooter(Html5::js(AssetHelper::jsUrl($this::NAME)), $this::NAME);
            if ($this->gaEnable && $this->gaAsync) {
                enqueueThemeFooter($this->gaJsAsync(), 'ga_js_async');
            }
        }
    }

    public function cacheScripts()
    {
        if ($this->cacheEnable) {
            $cache = '';
            if ($this->gaEnable) {
                $cache .= ($this->gaAsync ? $this->rawGaScriptAsync() : $this->rawGaScript()) . PHP_EOL;
            }
            if ($this->mixPanelEnable) {
                $cache .= $this->rawMixPanelScript() . PHP_EOL;
            }
            if (!empty($cache)) {
                AssetHelper::cacheJs($this::NAME, $cache);
            }
        }
    }

    protected function __init()
    {
        parent::__init();

        $this->cacheEnable = !empty($this->data['cache_enable']) && $this->data['cache_enable'] == 1;

        $this->gaEnable = !empty($this->data['ga_enable']) && $this->data['ga_enable'] == 1;
        $this->gaId = empty($this->data['ga_id']) ? 'UA-0000000-0' : $this->data['ga_id'];
        $this->gaAsync = !empty($this->data['ga_async']) && $this->data['ga_async'] == 1;

        $this->mixPanelEnable = !empty($this->data['mix_panel_enable']) && $this->data['mix_panel_enable'] == 1;
        $this->mixPanelToken = empty($this->data['mix_panel_token']) ? '' : $this->data['mix_panel_token'];

        if (!$this->gaEnable && !$this->mixPanelEnable) {
            $this->cacheEnable = false;
        }
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'cache_enable' => $this->cacheEnable,

            'ga_enable' => $this->gaEnable,
            'ga_id' => $this->gaId,
            'ga_async' => $this->gaAsync,

            'mix_panel_enable' => $this->mixPanelEnable,
            'mix_panel_token' => $this->mixPanelToken,
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();
        return array_merge($fields, [
            'cache_enable',
            'ga_enable',
            'ga_id',
            'ga_async',
            'mix_panel_enable',
            'mix_panel_token',
        ]);
    }

    public function validationRules()
    {
        $validationRules = parent::validationRules();
        return array_merge($validationRules, [
            'cache_enable' => 'sometimes|nullable|in:1',
            'ga_enable' => 'sometimes|nullable|in:1',
            'ga_id' => 'required_if:ga_enable,1',
            'ga_async' => 'sometimes|nullable|in:1',
            'mix_panel_enable' => 'sometimes|nullable|in:1',
            'mix_panel_token' => 'required_if:mix_panel_enable,1',
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