<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 18:36
 */

namespace Katniss\Everdeen\Themes\Plugins\SocialIntegration;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\Extension as SocialIntegrationExtension;
use Larabros\Elogram\Client;
use League\OAuth2\Client\Token\AccessToken;

class InstagramWallWidget extends DefaultWidget
{
    const NAME = 'social_integration.instagram_wall_widget';
    const DISPLAY_NAME = 'Instagram Wall';

    protected $username;
    protected $numOfColumns;
    protected $numOfItems;

    protected $shared;

    public function __init()
    {
        parent::__init();

        $this->username = defPr($this->getProperty('username'), '');
        $this->numOfColumns = defPr($this->getProperty('num_of_columns'), 3);
        $this->numOfItems = defPr($this->getProperty('num_of_items'), 9);

        $this->shared = Extension::getSharedData(SocialIntegrationExtension::NAME);
    }

    public function register()
    {
        if (!empty($this->username)) {
            enqueueThemeHeader(
                '<style>
.widget-instagram-wall .list{margin-bottom:5px;}
.widget-instagram-wall .list .item{float:left;padding-left:0;padding-right:0;width:' . number_format(100 / $this->numOfColumns, 8) . '%}
</style>',
                'instagram_wall_style'
            );
            enqueueThemeFooter(
                '<script>
    $(function() {
        $(\'.widget-instagram-wall .next\').on(\'click\', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $container = $this.closest(\'.widget-instagram-wall\').find(\'.list\');
            var api = new KatnissApi(true);
            var params = {
                id: $this.attr(\'data-widget-id\'),
                max_id: $this.attr(\'data-max-id\')
            };
            params[KATNISS_EXTRA_ROUTE_PARAM] = \'web-api/instagram-wall-widget/id\';
            
            $this.addClass(\'hide\');
            $this.prev().removeClass(\'hide\');
            api.get(\'extra\', params, function(isFailed, data, messages) {
                $this.prev().addClass(\'hide\');
                if(!isFailed) {
                    if(data.instagram_media.length > 0) {
                        $container.removeClass(\'hide\');
                        var media;
                        for(var index in data.instagram_media) {
                            media = data.instagram_media[index];
                            $container.append(\'<div id="instagram-\' + media.id + \'" class="item">\' + 
                                \'<a target="_blank" href="\' + media.link + \'" title="\' + media.caption.text + \'">\' +
                                \'<img class="img-responsive" src="\' + media.images.low_resolution.url + \'" alt="\' + media.caption.text + \'">\' +
                                \'</a></div>\');
                        }
                        $this.attr(\'data-max-id\', media.id);
                    }
                    
                    if(data.loading == true) {
                        $this.removeClass(\'hide\');
                    }
                }
            });
        }).trigger(\'click\');
    });
</script>',
                'instagram_wall_script'
            );
        }
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'username' => $this->username,
            'num_of_items' => $this->numOfItems,
            'num_of_columns' => $this->numOfColumns,
        ]);
    }

    public function viewHomeParams()
    {
        return array_merge(parent::viewHomeParams(), $this->getInstagramData());
    }

    public function getInstagramData($maxId = null)
    {
        $instagramUser = null;
        $instagramMedia = collect([]);
        $loading = true;

        if (!empty($this->username)) {
            $client = new Client($this->shared->instagramClientId, $this->shared->instagramClientSecret);
            $client->setAccessToken(new AccessToken(['access_token' => $this->shared->instagramAccessToken]));
            $instagramUser = $client->users()->find($this->username);
            if (!empty($instagramUser)) {
                $instagramUser = $instagramUser->get();
                $instagramMedia = $client->users()->getMedia($instagramUser['id'], $this->numOfItems + 1, null, $maxId)->get();
                $loading = $instagramMedia->count() > $this->numOfItems;
                if ($loading) {
                    $instagramMedia->pop();
                }
            }
        }

        return [
            'loading' => $loading,
            'instagram_media' => $instagramMedia,
            'instagram_user' => $instagramUser,
        ];
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'username',
            'num_of_items',
            'num_of_columns',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'username' => 'required',
            'num_of_items' => 'required|integer|min:1|max:20',
            'num_of_columns' => 'required|integer|min:1|max:6',
        ]);
    }
}