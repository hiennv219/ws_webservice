<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Repositories\RoleRepository;

class RoleController extends AdminController
{
    protected $roleRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'role';
        $this->roleRepository = new RoleRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = $this->roleRepository->getPaged();

        $this->_title(trans('pages.admin_roles_title'));
        $this->_description(trans('pages.admin_roles_desc'));

        return $this->_index([
            'roles' => $roles,
            'pagination' => $this->paginationRender->renderByPagedModels($roles),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }
}
