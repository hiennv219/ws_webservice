<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Repositories\AnnouncementRepository;
use Katniss\Everdeen\Repositories\RoleRepository;

class AnnouncementController extends AdminController
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
        $searchTitle = $request->input('title', null);
        $searchContent = $request->input('content', null);

        $announcements = $this->announcementRepository->getSearchPaged(
            $searchTitle,
            $searchContent
        );

        $this->_title(trans('pages.admin_announcements_title'));
        $this->_description(trans('pages.admin_announcements_desc'));

        return $this->_index([
            'announcements' => $announcements,
            'pagination' => $this->paginationRender->renderByPagedModels($announcements),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchTitle) || !empty($searchContent),
            'search_title' => $searchTitle,
            'search_content' => $searchContent,
        ]);
    }

    public function create()
    {
        $roleRepository = new RoleRepository();

        $this->_title([trans('pages.admin_announcements_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_announcements_desc'));

        return $this->_create([
            'roles' => $roleRepository->getByHavingStatuses([Role::STATUS_NORMAL]),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|nullable|max:255',
            'content' => 'required',
            'to' => 'required|array',
            'to.all' => 'sometimes|nullable|in:1',
            'to.roles' => 'sometimes|nullable|array',
            'to.users' => 'sometimes|nullable|array',
        ]);

        $errorRedirect = redirect(adminUrl('announcements/create'))->withInput();

        if ($validator->fails()) {
            return $errorRedirect->withErrors($validator);
        }

        try {
            $this->announcementRepository->create(
                $request->authUser()->id,
                $request->input('to'),
                $request->input('content'),
                $request->input('title', '')
            );
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('announcements'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $announcement = $this->announcementRepository->model($id);
        $roleRepository = new RoleRepository();

        $this->_title([trans('pages.admin_announcements_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_announcements_desc'));

        return $this->_edit([
            'announcement' => $announcement,
            'roles' => $roleRepository->getByHavingStatuses([Role::STATUS_NORMAL]),
        ]);
    }

    public function update(Request $request, $id)
    {
        $announcement = $this->announcementRepository->model($id);

        $redirect = redirect(adminUrl('announcements/{id}/edit', ['id' => $announcement->id]));

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|nullable|max:255',
            'content' => 'required',
            'to' => 'required|array',
            'to.all' => 'sometimes|nullable|in:1',
            'to.roles' => 'sometimes|nullable|array',
            'to.users' => 'sometimes|nullable|array',
            'existed_ids' => 'sometimes|nullable|array',
        ]);

        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->announcementRepository->update(
                $request->authUser()->id,
                $request->input('to'),
                $request->input('content'),
                $request->input('title', ''),
                $request->input('existed_ids', [])
            );
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect;
    }

    public function destroy(Request $request, $id)
    {
        $this->announcementRepository->model($id);

        $this->_rdrUrl($request, adminUrl('announcements'), $rdrUrl, $errorRdrUrl);

        try {
            $this->announcementRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
