<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Reports\TeacherPeriodicReviewsReport;
use Katniss\Everdeen\Reports\TeacherReport;
use Katniss\Everdeen\Repositories\ClassTimeRepository;
use Katniss\Everdeen\Repositories\TeacherRepository;
use Katniss\Everdeen\Repositories\TopicRepository;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends AdminController
{
    protected $teacherRepository;

    protected $classTimeRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'teacher';
        $this->teacherRepository = new TeacherRepository();
        $this->classTimeRepository = new ClassTimeRepository();
    }

    public function indexApproved(Request $request)
    {
        if ($request->has('export')) {
            return $this->exportApproved($request);
        }

        $searchDisplayName = $request->input('display_name', null);
        $searchEmail = $request->input('email', null);
        $searchSkypeId = $request->input('skype_id', null);
        $searchPhoneNumber = $request->input('phone_number', null);
        $teachers = $this->teacherRepository->getSearchApprovedPaged(
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_approved_teachers_title'));
        $this->_description(trans('pages.admin_approved_teachers_desc'));

        return $this->_any('index_approved', [
            'teachers' => $teachers,
            'pagination' => $this->paginationRender->renderByPagedModels($teachers),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchDisplayName) || !empty($searchEmail) || !empty($searchSkypeId) || !empty($searchPhoneNumber),
            'search_display_name' => $searchDisplayName,
            'search_email' => $searchEmail,
            'search_skype_id' => $searchSkypeId,
            'search_phone_number' => $searchPhoneNumber,
        ]);
    }

    public function exportApproved(Request $request)
    {
        try {
            $report = new TeacherReport();

            return Excel::create('Teacher_List', function ($excel) use ($report) {
                $excel->sheet('Sheet 1', function ($sheet) use ($report) {
                    $data = $report->getDataAsFlatArray();
                    array_unshift($data, $report->getHeader());

                    $sheet->cell('A1', function ($cell) {
                        $cell->setValue(
                            trans_choice('label.teacher', 2)
                        );
                    });

                    $startColumn = 'A';
                    $startRow = 2;
                    $endColumn = chr(count($data[0]) + ord($startColumn) - 1);
                    $endRow = $startRow + count($data) - 1;

                    $sheet->mergeCells('A1:' . $endColumn . '1');
                    $sheet->fromArray($data, null, $startColumn . $startRow, true, false);
                    $sheet->cells($startColumn . $startRow . ':' . $endColumn . $startRow, function ($cells) {
                        $cells->setBackground('#000000');
                        $cells->setFontColor('#ffffff');
                        $cells->setFontWeight('bold');
                    });
                    $sheet->setBorder($startColumn . $startRow . ':' . $endColumn . $endRow, 'thin');
                });
            })->download('xls');
        } catch (KatnissException $ex) {
            return abort(500, $ex->getMessage());
        }
    }

    public function indexRegistering(Request $request)
    {
        $searchDisplayName = $request->input('display_name', null);
        $searchEmail = $request->input('email', null);
        $searchSkypeId = $request->input('skype_id', null);
        $searchPhoneNumber = $request->input('phone_number', null);
        $teachers = $this->teacherRepository->getSearchRegisteringPaged(
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_registering_teachers_title'));
        $this->_description(trans('pages.admin_registering_teachers_desc'));

        return $this->_any('index_registering', [
            'teachers' => $teachers,
            'pagination' => $this->paginationRender->renderByPagedModels($teachers),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'clear_search_url' => $request->url(),
            'on_searching' => !empty($searchDisplayName) || !empty($searchEmail) || !empty($searchSkypeId) || !empty($searchPhoneNumber),
            'search_display_name' => $searchDisplayName,
            'search_email' => $searchEmail,
            'search_skype_id' => $searchSkypeId,
            'search_phone_number' => $searchPhoneNumber,
        ]);
    }

    public function create(Request $request)
    {
        $topicRepository = new TopicRepository();

        return $this->_create([
            'redirect_url' => $request->input(AppConfig::KEY_REDIRECT_URL),
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
            'topics' => $topicRepository->getAll(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // user
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
            // teacher
            'topics' => 'required|array|exists:topics,id',
            'about_me' => 'sometimes|nullable|min:200',
            'experience' => 'sometimes|nullable|min:100',
            'methodology' => 'sometimes|nullable|min:100',
            'video_introduce_url' => 'sometimes|nullable|max:255|url',
            'video_teaching_url' => 'sometimes|nullable|max:255|url',
        ]);

        $errorRdr = redirect(adminUrl('teachers/create'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        try {
            $this->teacherRepository->createAdmin([
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
            ], [
                'about_me' => $request->input('about_me'),
                'experience' => $request->input('experience'),
                'methodology' => $request->input('methodology'),
                'video_introduce_url' => $request->input('video_introduce_url'),
                'video_teaching_url' => $request->input('video_teaching_url')
            ], $request->input('topics'),
                $request->input('country'),
                $request->has('is_approved'),
                $request->authUser()->id,
                $request->has('send_welcomed_mail'));
        } catch (KatnissException $ex) {
            return $errorRdr->withErrors([$ex->getMessage()]);
        }

        return redirect($request->has('is_approved') ? adminUrl('approved-teachers') : adminUrl('registering-teachers'));
    }

    public function edit(Request $request, $id)
    {
        $teacher = $this->teacherRepository->model($id);

        $topicRepository = new TopicRepository();

        return $this->_edit([
            'redirect_url' => $teacher->isApproved ? adminUrl('approved-teachers') : adminUrl('registering-teachers'),
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
            'topics' => $topicRepository->getAll(),
            'teacher_topic_ids' => $teacher->topics->pluck('id')->all(),
            'user' => $teacher->userProfile,
            'teacher' => $teacher,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->has('reject')) {
            return $this->reject($request, $id);
        }
        if ($request->has('approve')) {
            return $this->approve($request, $id);
        }
        if ($request->has('full_schedule')) {
            return $this->fullSchedule($request, $id);
        }
        if ($request->has('available')) {
            return $this->available($request, $id);
        }

        $this->teacherRepository->model($id);

        $validator = Validator::make($request->all(), [
            // user
            'display_name' => 'required|max:255',
            'name' => ['required', 'max:255', Rule::unique('users', 'name')->ignore($id, 'id')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id, 'id')],
            'password' => 'sometimes|nullable|min:6',
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
            // teacher
            'topics' => 'required|array|exists:topics,id',
            'about_me' => 'sometimes|nullable|min:200',
            'experience' => 'sometimes|nullable|min:100',
            'methodology' => 'sometimes|nullable|min:100',
            'video_introduce_url' => 'sometimes|nullable|max:255|url',
            'video_teaching_url' => 'sometimes|nullable|max:255|url'
        ]);

        $errorRdr = redirect(adminUrl('teachers/{id}/edit', ['id' => $id]))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        try {
            $this->teacherRepository->updateAdmin([
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
            ], [
                'about_me' => $request->input('about_me'),
                'experience' => $request->input('experience'),
                'methodology' => $request->input('methodology'),
                'video_introduce_url' => $request->input('video_introduce_url'),
                'video_teaching_url' => $request->input('video_teaching_url')
            ], $request->input('topics'),
                $request->input('country'));
        } catch (KatnissException $ex) {
            return $errorRdr->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('teachers/{id}/edit', ['id' => $id]));
    }

    protected function reject(Request $request, $id)
    {
        $this->teacherRepository->model($id);

        $this->_rdrUrl($request, adminUrl('approved-teachers'), $rdrUrl, $errorRdrUrl);

        try {
            $this->teacherRepository->reject();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function approve(Request $request, $id)
    {
        $this->teacherRepository->model($id);

        $this->_rdrUrl($request, adminUrl('registering-teachers'), $rdrUrl, $errorRdrUrl);

        try {
            $this->teacherRepository->approve($request->authUser()->id);
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function fullSchedule(Request $request, $id)
    {
        $this->teacherRepository->model($id);

        $this->_rdrUrl($request, adminUrl('approved-teachers'), $rdrUrl, $errorRdrUrl);

        try {
            $this->teacherRepository->fullSchedule();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function available(Request $request, $id)
    {
        $this->teacherRepository->model($id);

        $this->_rdrUrl($request, adminUrl('approved-teachers'), $rdrUrl, $errorRdrUrl);

        try {
            $this->teacherRepository->available();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function destroy(Request $request, $id)
    {
        $this->teacherRepository->model($id);

        $this->_rdrUrl($request, adminUrl('teachers'), $rdrUrl, $errorRdrUrl);

        try {
            $this->teacherRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function review(Request $request, $id){
        if ($request->has('export')) {
            return $this->exportApproved($request);
        }
        $reviews = $this->teacherRepository->getReviewOfTeacher($id);

        $this->_title(trans('pages.admin_teacher_review_title'));
        $this->_description(trans('pages.admin_teacher_review_desc'));

        return $this->_any('review', [
            'reviews' => $reviews,
            'pagination' => $this->paginationRender->renderByPagedModels($reviews),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order']
        ]);
    }
}
