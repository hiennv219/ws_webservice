<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-26
 * Time: 16:15
 */

namespace Katniss\Everdeen\Themes\Plugins\SocialIntegration;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\Controllers\InstagramWallWidgetWebApiController;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Utils\InstagramHelper;

class Extension extends BaseExtension
{
    const NAME = 'social_integration';
    const DISPLAY_NAME = 'Social Integration';
    const DESCRIPTION = 'Integrate Social Functions into website';

    public static function getSharedViewData()
    {
        $ext = Extension::getSharedData(self::NAME);
        if (empty($ext)) return null;

        $data = new \stdClass();
        $data->facebook_enable = $ext->facebookEnable;
        $data->social_login_enable = $ext->facebookLoginEnable || $ext->googleLoginEnable;
        $data->facebook_login_enable = $ext->facebookLoginEnable;
        $data->google_login_enable = $ext->googleLoginEnable;
        return $data;
    }

    protected $facebookEnable;
    protected $facebookAppId;
    protected $facebookLoginEnable;
    protected $facebookCommentEnable;
    protected $facebookCommentColorScheme;
    protected $facebookCommentColorSchemeValues = [
        'light', 'dark',
    ];
    protected $facebookCommentNumPosts;
    protected $facebookCommentOrderBy;
    protected $facebookCommentOrderByValues = [
        'social', 'reversing_time', 'time'
    ];
    protected $facebookCommentMobile;
    protected $facebookCommentMobileValues = [
        'auto', 'yes', 'no'
    ];
    protected $facebookCommentWidth;
    protected $facebookCommentWidthUnit;
    protected $facebookCommentWidthUnitValues = [
        'px', '%'
    ];
    protected $facebookLikeEnable;
    protected $facebookLikeLayout;
    protected $facebookLikeLayoutValues = [
        'box_count', 'button_count', 'button', 'standard'
    ];
    protected $facebookShareEnable;
    protected $facebookShareLayout;
    protected $facebookShareLayoutValues = [
        'box_count', 'button_count', 'button', 'link', 'icon_link', 'icon'
    ];
    protected $facebookRecommendEnable;
    protected $facebookRecommendLayout;
    protected $facebookRecommendLayoutValues = [
        'box_count', 'button_count', 'button', 'standard'
    ];
    protected $facebookSendEnable;
    protected $facebookSaveEnable;
    protected $facebookFollowEnable;

    protected $twitterEnable;
    protected $twitterShareEnable;

    protected $linkedInEnable;
    protected $linkedInShareEnable;
    protected $linkedInShareCountMode;
    protected $linkedInShareCountModeValues = [
        'right', 'top', 'no_count'
    ];

    protected $googleEnable;
    protected $googleLoginEnable;
    protected $googleShareEnable;
    protected $googleShareButtonSize;
    protected $googleShareButtonSizeValues = [
        'small', 'medium', 'standard', 'tall'
    ];
    protected $googleShareButtonAnnotation;
    protected $googleShareButtonAnnotationValues = [
        'inline', 'bubble', 'none'
    ];
    protected $googleShareButtonWidth;

    protected $instagramEnable;
    protected $instagramClientId;
    protected $instagramClientSecret;
    protected $instagramAccessToken;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        $this->facebookEnable = $this->getProperty('facebook_enable') == 1;
        $this->facebookAppId = defPr($this->getProperty('facebook_app_id'), config('services.facebook.client_id'));
        $this->facebookLoginEnable = $this->getProperty('facebook_login_enable') == 1;
        $this->facebookCommentEnable = $this->getProperty('facebook_comment_enable') == 1;
        $this->facebookCommentColorScheme = defPr($this->getProperty('facebook_comment_color_scheme'), 'light');
        $this->facebookCommentNumPosts = defPr($this->getProperty('facebook_comment_num_posts'), 10);
        $this->facebookCommentOrderBy = defPr($this->getProperty('facebook_comment_order_by'), 'social');
        $this->facebookCommentMobile = defPr($this->getProperty('facebook_comment_mobile'), 'auto');
        $this->facebookCommentWidth = defPr($this->getProperty('facebook_comment_width'), '100');
        $this->facebookCommentWidthUnit = defPr($this->getProperty('facebook_comment_width_unit'), '%');
        $this->facebookLikeEnable = $this->getProperty('facebook_like_enable') == 1;
        $this->facebookLikeLayout = defPr($this->getProperty('facebook_like_layout'), 'button_count');
        $this->facebookShareEnable = $this->getProperty('facebook_share_enable') == 1;
        $this->facebookShareLayout = defPr($this->getProperty('facebook_share_layout'), 'button_count');
        $this->facebookRecommendEnable = $this->getProperty('facebook_recommend_enable') == 1;
        $this->facebookRecommendLayout = defPr($this->getProperty('facebook_recommend_layout'), 'button_count');
        $this->facebookSendEnable = $this->getProperty('facebook_send_enable') == 1;
        $this->facebookSaveEnable = $this->getProperty('facebook_save_enable') == 1;

        $this->twitterEnable = $this->getProperty('twitter_enable') == 1;
        $this->twitterShareEnable = $this->getProperty('twitter_share_enable') == 1;

        $this->linkedInEnable = $this->getProperty('linkedin_enable') == 1;
        $this->linkedInShareEnable = $this->getProperty('linkedin_share_enable') == 1;
        $this->linkedInShareCountMode = defPr($this->getProperty('linkedin_share_count_mode'), 'horizontal');

        $this->googleEnable = $this->getProperty('google_enable') == 1;
        $this->googleLoginEnable = $this->getProperty('google_login_enable') == 1;
        $this->googleShareEnable = $this->getProperty('google_share_enable') == 1;
        $this->googleShareButtonSize = defPr($this->getProperty('google_share_button_size'), 'medium');
        $this->googleShareButtonAnnotation = defPr($this->getProperty('google_share_button_annotation'), 'bubble');
        $this->googleShareButtonWidth = defPr($this->getProperty('google_share_button_width'), 300);

        $this->instagramEnable = $this->getProperty('instagram_enable') == 1;
        $this->instagramClientId = defPr($this->getProperty('instagram_client_id'), config('services.instagram.client_id'));
        $this->instagramClientSecret = defPr($this->getProperty('instagram_client_secret'), config('services.instagram.client_secret'));
        $this->instagramAccessToken = defPr($this->getProperty('instagram_access_token'), '');

        if ($this->instagramEnable && !empty($this->instagramAccessToken)) {
            _kWidgets([InstagramWallWidget::NAME => InstagramWallWidget::class]);
        }

        $this->makeSharedData([
            'facebookEnable',
            'facebookLoginEnable',
            'facebookCommentEnable',
            'googleLoginEnable',
            'instagramClientId',
            'instagramClientSecret',
            'instagramAccessToken',
        ]);
    }

    public function viewAdminParams()
    {
        $request = request();

        return array_merge(parent::viewAdminParams(), [
            'facebook_enable' => $this->facebookEnable,
            'facebook_app_id' => $this->facebookAppId,
            'facebook_login_enable' => $this->facebookLoginEnable,
            'facebook_comment_enable' => $this->facebookCommentEnable,
            'facebook_comment_color_scheme' => $this->facebookCommentColorScheme,
            'facebook_comment_color_scheme_values' => $this->facebookCommentColorSchemeValues,
            'facebook_comment_num_posts' => $this->facebookCommentNumPosts,
            'facebook_comment_order_by' => $this->facebookCommentOrderBy,
            'facebook_comment_order_by_values' => $this->facebookCommentOrderByValues,
            'facebook_like_enable' => $this->facebookLikeEnable,
            'facebook_like_layout' => $this->facebookLikeLayout,
            'facebook_like_layout_values' => $this->facebookLikeLayoutValues,
            'facebook_share_enable' => $this->facebookShareEnable,
            'facebook_share_layout' => $this->facebookShareLayout,
            'facebook_share_layout_values' => $this->facebookShareLayoutValues,
            'facebook_recommend_enable' => $this->facebookRecommendEnable,
            'facebook_recommend_layout' => $this->facebookRecommendLayout,
            'facebook_recommend_layout_values' => $this->facebookRecommendLayoutValues,
            'facebook_send_enable' => $this->facebookSendEnable,
            'facebook_save_enable' => $this->facebookSaveEnable,
            'twitter_enable' => $this->twitterEnable,
            'twitter_share_enable' => $this->twitterShareEnable,
            'linkedin_enable' => $this->linkedInEnable,
            'linkedin_share_enable' => $this->linkedInShareEnable,
            'linkedin_share_count_mode' => $this->linkedInShareCountMode,
            'linkedin_share_count_mode_values' => $this->linkedInShareCountModeValues,
            'google_enable' => $this->googleEnable,
            'google_login_enable' => $this->googleLoginEnable,
            'google_share_enable' => $this->googleShareEnable,
            'google_share_button_size' => $this->googleShareButtonSize,
            'google_share_button_size_values' => $this->googleShareButtonSizeValues,
            'google_share_button_annotation' => $this->googleShareButtonAnnotation,
            'google_share_button_annotation_values' => $this->googleShareButtonAnnotationValues,
            'google_share_button_width' => $this->googleShareButtonWidth,
            'instagram_enable' => $this->instagramEnable,
            'instagram_client_id' => $this->instagramClientId,
            'instagram_client_secret' => $this->instagramClientSecret,
            'instagram_access_token' => $request->has('access_token') ? $request->input('access_token') : $this->instagramAccessToken,
            'instagram_redirect_authorize_url' => InstagramHelper::getRedirectAuthorizeUrl($this->instagramClientId, currentFullUrl()),
        ]);
    }

    protected function facebookJsSdk()
    {
        return '<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
    FB.init({
        appId      : \'' . $this->facebookAppId . '\',
        xfbml      : true,
        version    : \'v2.8\'
    });
    FB.AppEvents.logPageView();
};
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = \'//connect.facebook.net/' . currentFullLocaleCode() . '/sdk.js\';
    fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));
</script>';
    }

    protected function twitterJsSdk()
    {
        return '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>';
    }

    protected function linkedInJsSdk()
    {
        return '<script src="//platform.linkedin.com/in.js">lang:' . currentFullLocaleCode() . '</script>';
    }

    protected function googleJsSdk()
    {
        return '<script src="https://apis.google.com/js/platform.js" async defer>{lang: \'' . currentLocaleCode() . '\'}</script>';
    }

    public function register()
    {
        $sharing_buttons = [];

        if ($this->facebookEnable) {
            enqueueThemeFooter($this->facebookJsSdk(), 'facebook_js_sdk');
            addFilter('open_graph_tags_before_render', new CallableObject(function ($data) {
                $data['fb:app_id'] = $this->facebookAppId;
                return $data;
            }), 'ext:social_integration:fb_app_id');

            if ($this->facebookCommentEnable) {
                $color_scheme = $this->facebookCommentColorScheme;
                $num_posts = $this->facebookCommentNumPosts;
                $order_by = $this->facebookCommentOrderBy;
                $mobile = $this->facebookCommentMobile;
                $width = $this->facebookCommentWidth;
                $widthUnit = $this->facebookCommentWidthUnit;
                addPlace('facebook_comments', new CallableObject(function ($url) use ($color_scheme, $num_posts, $order_by, $mobile, $width, $widthUnit) {
                    $color_scheme = ' data-colorscheme="' . $color_scheme . '"';
                    $num_posts = ' data-numposts="' . $num_posts . '"';
                    $order_by = ' data-order-by="' . $order_by . '"';
                    if ($mobile != 'auto') {
                        $mobile = ' data-mobile="' . ($mobile == 'yes' ? 'true' : 'false') . '"';
                    }
                    $width = ' data-width="' . $width . $widthUnit . '"';
                    return '<div class="fb-comments" data-href="' . $url . '"' . $color_scheme . $num_posts . $order_by . $mobile . $width . '"></div>';
                }), 'ext:social_integration:render_facebook_comments');
            }

            if ($this->facebookLikeEnable || $this->facebookShareEnable || $this->facebookRecommendEnable || $this->facebookSendEnable) {
                enqueueThemeHeader('<style>.fb_iframe_widget > span{vertical-align: baseline!important;}</style>', 'facebook_css_fixed');
            }

            if ($this->facebookLikeEnable) {
                $sharing_buttons['facebook_like'] = '<div class="fb-like" data-href="{sharing_url}" data-layout="' . $this->facebookLikeLayout . '" data-action="like" data-show-faces="false" data-share="false"></div>';
            }

            if ($this->facebookShareEnable) {
                $sharing_buttons['facebook_share'] = '<div class="fb-share-button" data-href="{sharing_url}" data-layout="' . $this->facebookShareLayout . '"></div>';
            }

            if ($this->facebookRecommendEnable) {
                $sharing_buttons['facebook_recommend'] = '<div class="fb-like" data-href="{sharing_url}" data-layout="' . $this->facebookRecommendLayout . '" data-action="recommend" data-show-faces="false" data-share="false"></div>';
            }

            if ($this->facebookSendEnable) {
                $sharing_buttons['facebook_send'] = '<div class="fb-send" data-href="{sharing_url}"></div>';
            }

            if ($this->facebookSaveEnable) {
                $sharing_buttons['facebook_save'] = '<div class="fb-save" data-uri="{sharing_url}" data-size="small"></div>';
            }
        }

        if ($this->twitterEnable) {
            enqueueThemeFooter($this->twitterJsSdk(), 'twitter_js_sdk');
            if ($this->twitterShareEnable) {
                $sharing_buttons['twitter_tweet'] = '<a href="https://twitter.com/intent/tweet" class="twitter-share-button" data-url="{sharing_url}" data-lang="' . currentLocaleCode() . '">Tweet</a>';
            }
        }

        if ($this->linkedInEnable) {
            enqueueThemeFooter($this->linkedInJsSdk(), 'linkedin_js_sdk');
            if ($this->linkedInShareEnable) {
                if ($this->linkedInShareCountMode != 'nocount') {
                    $sharing_buttons['linkedin_share'] = '<script type="IN/Share" data-url="{sharing_url}" data-counter="' . $this->linkedInShareCountMode . '"></script>';
                } else {
                    $sharing_buttons['linkedin_share'] = '<script type="IN/Share" data-url="{sharing_url}"></script>';
                }
            }
        }

        if ($this->googleEnable) {
            enqueueThemeFooter($this->googleJsSdk(), 'google_plus_js_sdk');
            if ($this->googleShareEnable) {
                if ($this->googleShareButtonAnnotation == 'inline') {
                    $sharing_buttons['google_share'] = '<div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300" data-href="{sharing_url}"></div>';
                } else {
                    $sharing_buttons['google_share'] = '<div class="g-plusone" data-size="' . $this->googleShareButtonSize . '" data-annotation="' . $this->googleShareButtonAnnotation . '" data-href="{sharing_url}"></div>';
                }
            }
        }

        addPlace('sharing_buttons', new CallableObject(function ($sharing_url) use ($sharing_buttons) {
            $buttons = contentFilter('sharing_buttons', $sharing_buttons);
            if (count($buttons) > 0) {
                array_walk($buttons, function (&$button) use ($sharing_url) {
                    $button = str_replace('{sharing_url}', $sharing_url, $button);
                });
                return '<ul class="list-inline"><li>' . implode('</li><li>', $buttons) . '</li></ul>';
            }

            return '';
        }), 'ext:social_integration:render_sharing_buttons');

        if ($this->instagramEnable && !empty($this->instagramAccessToken)) {
            addTrigger('extra_route', new CallableObject(function (Request $request) {
                $controllerClass = InstagramWallWidgetWebApiController::class;
                $controller = new $controllerClass;
                if (strtolower($request->method()) == 'get') {
                    return $controller->show($request, $request->input('id'));
                }
                return '';
            }), 'web-api/instagram-wall-widget/id');
        }
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'facebook_enable',
            'facebook_app_id',
            'facebook_login_enable',
            'facebook_comment_enable',
            'facebook_comment_color_scheme',
            'facebook_comment_num_posts',
            'facebook_comment_order_by',
            'facebook_like_enable',
            'facebook_like_layout',
            'facebook_share_enable',
            'facebook_share_layout',
            'facebook_recommend_enable',
            'facebook_recommend_layout',
            'facebook_send_enable',
            'facebook_save_enable',
            'twitter_enable',
            'twitter_share_enable',
            'linkedin_enable',
            'linkedin_share_enable',
            'linkedin_share_count_mode',
            'google_enable',
            'google_login_enable',
            'google_share_enable',
            'google_share_button_size',
            'google_share_button_annotation',
            'google_share_button_width',
            'instagram_enable',
            'instagram_client_id',
            'instagram_client_secret',
            'instagram_access_token',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'facebook_enable' => 'sometimes|nullable|in:1',
            'facebook_app_id' => 'required_if:facebook_enable,1',
            'facebook_login_enable' => 'sometimes|nullable|in:1',
            'facebook_comment_enable' => 'sometimes|nullable|in:1',
            'facebook_comment_color_scheme' => 'required_if:facebook_comment_enable,1|in:' . implode(',', $this->facebookCommentColorSchemeValues),
            'facebook_comment_num_posts' => 'required_if:facebook_comment_enable,1|min:1',
            'facebook_comment_order_by' => 'required_if:facebook_comment_enable,1|in:' . implode(',', $this->facebookCommentOrderByValues),
            'facebook_like_enable' => 'sometimes|nullable|in:1',
            'facebook_like_layout' => 'required_if:facebook_like_enable,1|in:' . implode(',', $this->facebookLikeLayoutValues),
            'facebook_share_enable' => 'sometimes|nullable|in:1',
            'facebook_share_layout' => 'required_if:facebook_share_enable,1|in:' . implode(',', $this->facebookShareLayoutValues),
            'facebook_recommend_enable' => 'sometimes|nullable|in:1',
            'facebook_recommend_layout' => 'required_if:facebook_recommend_enable,1|in:' . implode(',', $this->facebookRecommendLayoutValues),
            'facebook_send_enable' => 'sometimes|nullable|in:1',
            'facebook_save_enable' => 'sometimes|nullable|in:1',
            'twitter_enable' => 'sometimes|nullable|in:1',
            'twitter_share_enable' => 'sometimes|nullable|in:1',
            'linkedin_enable' => 'sometimes|nullable|in:1',
            'linkedin_share_enable' => 'sometimes|nullable|in:1',
            'linkedin_share_count_mode' => 'required_if:linkedin_share_enable,1|in:' . implode(',', $this->linkedInShareCountModeValues),
            'google_enable' => 'sometimes|nullable|in:1',
            'google_login_enable' => 'sometimes|nullable|in:1',
            'google_share_enable' => 'sometimes|nullable|in:1',
            'google_share_button_size' => 'required_if:google_share_enable,1|in:' . implode(',', $this->googleShareButtonSizeValues),
            'google_share_button_annotation' => 'required_if:google_share_enable,1|in:' . implode(',', $this->googleShareButtonAnnotationValues),
            'google_share_button_width' => 'required_if:google_share_button_annotation,inline|integer|min:1',
            'instagram_enable' => 'sometimes|nullable|in:1',
            'instagram_client_id' => 'required_if:instagram_enable,1',
            'instagram_client_secret' => 'required_if:instagram_enable,1',
            'instagram_access_token' => 'required_if:instagram_enable,1',
        ]);
    }
}