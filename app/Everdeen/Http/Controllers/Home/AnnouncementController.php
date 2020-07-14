<?php

namespace Katniss\Everdeen\Http\Controllers\Home;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\AnnouncementRepository;

class AnnouncementController extends ViewController
{
    protected $announcementRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'announcement';
        $this->announcementRepository = new AnnouncementRepository();
    }

    public function index(Request $request)
    {
        $searchStatus = $request->input('status', null);
        $statuses = [
            'all' => [
                'current' => trans('label.all'),
                'label' => trans('label.all'),
                'url' => homeUrl('announcements') . '?status=all',
            ],
            'unread' => [
                'current' => '<span class="color-slave">' . trans('label.status_unread') . '</span>',
                'label' => '<span class="color-slave"><i class="fa fa-eye-slash"></i> &nbsp; ' . trans('label.status_unread') . '</span>',
                'url' => homeUrl('announcements') . '?status=unread',
            ],
            'read' => [
                'current' => '<span class="color-master">' . trans('label.status_read') . '</span>',
                'label' => '<span class="color-master"><i class="fa fa-bullhorn"></i> &nbsp; ' . trans('label.status_read') . '</span>',
                'url' => homeUrl('announcements') . '?status=read',
            ],
        ];
        $currentStatus = array_key_exists($searchStatus, $statuses) ? $searchStatus : 'all';

        $user = $request->authUser();
        $announcements = $this->announcementRepository->getSearchPagedByUser($userId, $user, $searchStatus);
        $readIds = $user->announcements->pluck('id')->all();

        $this->_title(trans('pages.home_announcements_title'));
        $this->_description(trans('pages.home_announcements_desc'));

        return $this->_index([
            'announcements' => $announcements,
            'pagination' => $this->paginationRender->renderByPagedModels($announcements),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'read_ids' => $readIds,

            'statuses' => $statuses,
            'current_status' => $currentStatus,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->has('read')) {
            return $this->read($request, $id);
        }

        abort('404');
        return '';
    }

    protected function read(Request $request, $id)
    {
        $this->announcementRepository->model($id);

        $this->_rdrUrl($request, homeUrl('announcements'), $rdrUrl, $errorRdrUrl);

        try {
            $this->announcementRepository->read($request->authUser()->id);
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
