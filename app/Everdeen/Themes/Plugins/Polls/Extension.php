<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Themes\Plugins\Polls\Controllers\ChoiceAdminController;
use Katniss\Everdeen\Themes\Plugins\Polls\Controllers\PollAdminController;
use Katniss\Everdeen\Themes\Plugins\Polls\Controllers\PollWebApiController;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class Extension extends BaseExtension
{
    const NAME = 'polls';
    const DISPLAY_NAME = 'Polls';
    const DESCRIPTION = 'Enable to embed polls to layout and manage polls and choices';
    const EDITABLE = false;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        _kWidgets([PollWidget::NAME => PollWidget::class]);
    }

    public function register()
    {
        addFilter('extra_admin_menu', new CallableObject(function (Menu $menu) {
            if (authUser()->hasRole('admin')) {
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('polls.page_polls_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu(currentFullUrl());
                $subMenu->add( // add a menu item
                    addExtraUrl('admin/polls', adminUrl('extra')),
                    trans('polls.page_polls_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $subMenu->add( // add a menu item
                    addExtraUrl('admin/poll-choices', adminUrl('extra')),
                    trans('polls.page_poll_choices_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $menu->addSubMenu($subMenu);
            }
            return $menu;
        }), 'ext:polls:menu');

        addTrigger('extra_route', new CallableObject(function (Request $request) {
            $controllerClass = PollWebApiController::class;
            $controller = new $controllerClass;
            if (strtolower($request->method()) == 'put') {
                return $controller->update($request, $request->input('id'));
            }
            return '';
        }), 'web-api/polls/id');
        addExtraRouteResourceTriggers('admin/polls', PollAdminController::class);
        addTrigger('extra_route', new CallableObject(function (Request $request) {
            $controllerClass = PollAdminController::class;
            $controller = new $controllerClass;
            if (strtolower($request->method()) == 'get') {
                return $controller->sort($request, $request->input('id'));
            }
            return '';
        }), 'admin/polls/id/sort');
        addExtraRouteResourceTriggers('admin/poll-choices', ChoiceAdminController::class);
    }
}