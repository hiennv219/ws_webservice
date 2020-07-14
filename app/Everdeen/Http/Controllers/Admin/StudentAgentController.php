<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Repositories\RoleRepository;
use Katniss\Everdeen\Repositories\StudentRepository;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;

class StudentAgentController extends AdminController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'student_agent';
        $this->userRepository = new UserRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchDisplayName = $request->input('display_name', null);
        $searchEmail = $request->input('email', null);
        $searchSkypeId = $request->input('skype_id', null);
        $searchPhoneNumber = $request->input('phone_number', null);
        $users = $this->userRepository->getStudentAgentSearchPaged(
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_student_agents_title'));
        $this->_description(trans('pages.admin_student_agents_desc'));

        return $this->_index([
            'users' => $users,
            'pagination' => $this->paginationRender->renderByPagedModels($users),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchDisplayName) || !empty($searchEmail) || !empty($searchSkypeId) || !empty($searchPhoneNumber),
            'search_display_name' => $searchDisplayName,
            'search_email' => $searchEmail,
            'search_skype_id' => $searchSkypeId,
            'search_phone_number' => $searchPhoneNumber,
        ]);
    }

    public function students(Request $request, $id)
    {
        $user = $this->userRepository->model($id);

        $authUser = $request->authUser();
        $authAgent = $authUser->id == $user->id && !$authUser->hasRole(['admin', 'manager']);

        $searchDisplayName = $request->input('display_name', null);
        $searchEmail = $request->input('email', null);
        $searchSkypeId = $request->input('skype_id', null);
        $searchPhoneNumber = $request->input('phone_number', null);
        $studentRepository = new StudentRepository();
        $students = $studentRepository->getSearchPagedByAgentId(
            $user->id,
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_students_title'));
        $this->_description(trans('pages.admin_students_desc'));

        return $this->_any('students', [
            'user' => $user,
            'auth_agent' => $authAgent,
            'students' => $students,
            'pagination' => $this->paginationRender->renderByPagedModels($students),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchDisplayName) || !empty($searchEmail) || !empty($searchSkypeId) || !empty($searchPhoneNumber),
            'search_display_name' => $searchDisplayName,
            'search_email' => $searchEmail,
            'search_skype_id' => $searchSkypeId,
            'search_phone_number' => $searchPhoneNumber,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->_title([trans('pages.admin_student_agents_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_student_agents_desc'));

        return $this->_create([
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    protected function validator(array $data, array $extra_rules = [])
    {
        return Validator::make($data, array_merge([
            'display_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'name' => 'required|max:255|unique:users,name',
            'password' => 'required|min:6',
            'date_of_birth' => 'sometimes|nullable|date_format:' . DateTimeHelper::shortDateFormat(),
            'gender' => 'required|in:' . implode(',', allGenders()),
            'phone_code' => 'required|in:' . implode(',', allCountryCodes()),
            'phone_number' => 'required|max:255',
            'address' => 'sometimes|nullable|max:255',
            'city' => 'required|max:255',
            'country' => 'required|in:' . implode(',', allCountryCodes()),
            'nationality' => 'required|in:' . implode(',', allCountryCodes()),
            'skype_id' => 'sometimes|nullable|max:255',
            'facebook' => 'sometimes|nullable|max:255|url',
        ], $extra_rules));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        $errorRdr = redirect(adminUrl('student-agents/create'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        try {
            $this->userRepository->createStudentAgentAdmin([
                'display_name' => $request->input('display_name'),
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => $request->input('password'),
                'date_of_birth' => DateTimeHelper::getInstance()
                    ->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $request->input('date_of_birth'), true),
                'gender' => $request->input('gender'),
                'phone_code' => $request->input('phone_code'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address', ''),
                'city' => $request->input('city'),
                'nationality' => $request->input('nationality'),
                'skype_id' => $request->input('skype_id', ''),
                'facebook' => $request->input('facebook', ''),
            ],
                $request->input('country'),
                $request->has('send_welcomed_mail')
            );
        } catch (KatnissException $ex) {
            return $errorRdr->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('student-agents'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $user = $this->userRepository->model($id);

        $this->_title([trans('pages.admin_student_agents_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_student_agents_desc'));

        return $this->_edit([
            'user' => $user,
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->model($id);

        $rdr = redirect(adminUrl('student-agents/{id}/edit', ['id' => $user->id]));

        $validator = $this->validator($request->all(), [
            'password' => 'sometimes|nullable|min:6',
            'name' => ['required', 'max:255', Rule::unique('users', 'name')->ignore($user->id, 'id')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id, 'id')],
        ]);

        if ($validator->fails()) {
            return $rdr->withErrors($validator);
        }

        try {
            $this->userRepository->updateStudentAgentAdmin([
                'display_name' => $request->input('display_name'),
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => $request->input('password', ''),
                'date_of_birth' => DateTimeHelper::getInstance()
                    ->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $request->input('date_of_birth'), true),
                'gender' => $request->input('gender'),
                'phone_code' => $request->input('phone_code'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address', ''),
                'city' => $request->input('city'),
                'nationality' => $request->input('nationality'),
                'skype_id' => $request->input('skype_id', ''),
                'facebook' => $request->input('facebook', ''),
            ],
                $request->input('country')
            );
        } catch (KatnissException $ex) {
            return $rdr->withErrors([$ex->getMessage()]);
        }

        return $rdr;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $this->userRepository->model($id);

        $this->_rdrUrl($request, adminUrl('student-agents'), $rdrUrl, $errorRdrUrl);

        try {
            $this->userRepository->deleteStudentAgent();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
