<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:05
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Reports\TeacherPeriodicReviewsReport;
use Katniss\Everdeen\Repositories\TeacherRepository;
use Katniss\Everdeen\Repositories\TopicRepository;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Utils\DataStructure\Pagination\PaginationRender;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class TeacherController extends ViewController
{
    protected $teacherRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'teacher';
        $this->teacherRepository = new TeacherRepository();
    }

    #region Sign up
    public function getSignUp(Request $request)
    {
        if ($request->isAuth() && $request->authUser()->hasRole('teacher')) {
            return redirect(homeUrl());
        }

        $theme = $request->getTheme();

        $this->_title(trans('pages.home_teacher_sign_up_title'));
        $this->_description(trans('pages.home_teacher_sign_up_desc'));

        return $this->_any('sign_up', [
            'skype_id' => $theme->options('ts_skype_id', ''),
            'skype_name' => $theme->options('ts_skype_name', ''),
            'hot_line' => $theme->options('ts_hot_line', ''),
            'email' => $theme->options('ts_email', ''),
        ]);
    }

    public function postSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|max:255',
            'skype_id' => 'max:255',
            'phone_code' => 'required|in:' . implode(',', allCountryCodes()),
            'phone_number' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
            'g-recaptcha-response'=>'reCaptcha'
        ]);

        $errorRdr = redirect(homeUrl('teacher/sign-up'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        try {
            $user = $this->teacherRepository->create(
                $request->input('display_name'),
                $request->input('email'),
                $request->input('password'),
                $request->input('skype_id'),
                $request->input('phone_code'),
                $request->input('phone_number')
            );

            if ($user) {
                Auth::guard()->login($user);
            } else {
                return $errorRdr->withErrors([trans('auth.register_failed_system_error')]);
            }
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(homeUrl('teacher/sign-up/step/{step}', ['step' => 1]));
    }

    public function getSignUpStep(Request $request, $step)
    {
        if ($step == 1) {
            return $this->getSignUpStep1($request);
        } elseif ($step == 2) {
            return $this->getSignUpStep2($request);
        }

        abort(404);
        return false;
    }

    public function getSignUpStep1(Request $request)
    {
        $this->_title([trans('pages.home_teacher_sign_up_title'), trans('label._step', ['step' => 1])]);
        $this->_description(trans('pages.home_teacher_sign_up_desc'));

        return $this->_any('sign_up_step_1', [
            'teacher' => $request->authUser()->teacherProfile,
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    public function getSignUpStep2(Request $request)
    {
        $topicRepository = new TopicRepository();
        $teacher = $request->authUser()->teacherProfile;
        $teacherCertificates = $teacher->certificates;

        $this->_title([trans('pages.home_teacher_sign_up_title'), trans('label._step', ['step' => 2])]);
        $this->_description(trans('pages.home_teacher_sign_up_desc'));

        return $this->_any('sign_up_step_2', [
            'teacher' => $teacher,
            'topics' => $topicRepository->getAll(),
            'teacher_topic_ids' => $teacher->topics->pluck('id')->all(),
            'teacher_certificates' => $teacherCertificates,
            'teacher_other_certificates' => array_key_exists('others', $teacherCertificates) ? $teacherCertificates['others'] : '',
            'certificates' => _k('certificates'),
        ]);
    }

    public function postSignUpStep(Request $request, $step)
    {
        if ($step == 1) {
            return $this->postSignUpStep1($request);
        } elseif ($step == 2) {
            return $this->postSignUpStep2($request);
        }

        abort(404);
        return false;
    }

    public function postSignUpStep1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'sometimes|nullable|date_format:' . DateTimeHelper::shortDateFormat(),
            'gender' => 'required|in:' . implode(',', allGenders()),
            'city' => 'required|max:255',
            'country' => 'required|in:' . implode(',', allCountryCodes()),
            'nationality' => 'required|in:' . implode(',', allCountryCodes()),
            'facebook' => 'required|max:255|url',
        ]);;

        $errorRdr = redirect(homeUrl('teacher/sign-up/step/{step}', ['step' => 1]))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $userRepository = new UserRepository($request->authUser());
        $settings = settings();

        try {
            $userRepository->updateAttributes([
                'date_of_birth' => DateTimeHelper::getInstance()
                    ->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $request->input('date_of_birth'), true),
                'gender' => $request->input('gender'),
                'city' => $request->input('city'),
                'nationality' => $request->input('nationality'),
                'facebook' => $request->input('facebook'),
            ]);

            $settings->setCountry($request->input('country'));
            $settings->storeUser();
            $settings->storeSession();

        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return $settings->storeCookie(redirect(homeUrl('teacher/sign-up/step/{step}', ['step' => 2])));
    }

    public function postSignUpStep2(Request $request)
    {
        $certificates = _k('certificates');
        $validator = Validator::make($request->all(), [
            'topics' => 'required|array|exists:topics,id',
            'about_me' => 'required',
            'experience' => 'required',
            'methodology' => 'required',
            'certificates' => 'required|array|in:' . implode(',', $certificates),
            'video_introduce_url' => 'sometimes|nullable|max:255|url',
        ]);

        $errorRdr = redirect(homeUrl('teacher/sign-up/step/{step}', ['step' => 2]))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        if (in_array('Others', $request->input('certificates'))) {
            $validator = Validator::make($request->all(), [
                'other_certificates' => 'required|max:200',
            ]);
            if ($validator->fails()) {
                return $errorRdr->withErrors($validator);
            }
        }

        $this->teacherRepository->model($request->authUser()->teacherProfile);

        try {
            $this->teacherRepository->updateWhenSigningUp(
                $request->input('topics'),
                $request->input('about_me'),
                $request->input('experience'),
                $request->input('methodology'),
                $request->input('certificates'),
                $request->input('other_certificates'),
                $request->input('video_introduce_url', '')
            );
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(homeUrl());
    }

    #endregion

    #region Profile
    public function getTeacherInformation(Request $request)
    {
        $topicRepository = new TopicRepository();
        $teacher = $request->authUser()->teacherProfile;

        $this->_title(trans('label.teacher_information'));
        $this->_description(trans('label.teacher_information'));

        return $this->_any('teacher_information', [
            'teacher' => $teacher,
            'topics' => $topicRepository->getAll(),
            'teacher_topic_ids' => $teacher->topics->pluck('id')->all(),
        ]);
    }

    public function updateTeacherInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topics' => 'required|array|exists:topics,id',
            'about_me' => 'required',
            'experience' => 'required',
            'methodology' => 'required',
            'video_introduce_url' => 'sometimes|nullable|max:255|url',
            'video_teaching_url' => 'sometimes|nullable|max:255|url',
        ]);

        $errorRdr = redirect(homeUrl('profile/teacher-information'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $this->teacherRepository->model($request->authUser()->teacherProfile);

        try {
            $this->teacherRepository->updateInformation(
                $request->input('topics'),
                $request->input('about_me'),
                $request->input('experience'),
                $request->input('methodology'),
                $request->input('video_introduce_url', ''),
                $request->input('video_teaching_url', '')
            );
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(homeUrl('profile/teacher-information'))
            ->with('successes', [trans('error.success')]);
    }

    public function getTeachingTime(Request $request)
    {
        $teacher = $request->authUser()->teacherProfile;

        $availableTimes = $this->formatAvailableTimes($teacher->available_times);

        $this->_title(trans('label.teaching_time'));
        $this->_description(trans('label.teaching_time'));

        return $this->_any('teaching_time', [
            'teacher' => $teacher,
            'available_times' => $availableTimes['times'],
            'available_range_from' => $availableTimes['range_from'],
            'available_range_to' => $availableTimes['range_to'],
        ]);
    }

    protected function formatAvailableTimes($availableTimes)
    {
        $sourceTimes = $availableTimes['times'];
        $sourceRangesFrom = $availableTimes['range_from'];
        $sourceRangesTo = $availableTimes['range_to'];

        $times = [];
        $rangesFrom = [];
        $rangesTo = [];
        foreach ($sourceTimes as $time) {
            $diffDay = 0;
            if (!empty($sourceRangesFrom[$time])) {
                $rangeFrom = dateFormatFromDatabase($sourceRangesFrom[$time], 'H:i', $diffDay);
            }
            if (!empty($sourceRangesTo[$time])) {
                if (!empty($rangeFrom)) {
                    $rangeTo = dateFormatFromDatabase($sourceRangesTo[$time], 'H:i');
                } else {
                    $rangeTo = dateFormatFromDatabase($sourceRangesTo[$time], 'H:i', $diffDay);
                }
            }
            $time += $diffDay;
            $times[] = $time;
            if (!empty($rangeFrom)) {
                $rangesFrom[$time] = $rangeFrom;
            }
            if (!empty($rangeTo)) {
                $rangesTo[$time] = $rangeTo;
            }
        }
        return [
            'times' => $times,
            'range_from' => $rangesFrom,
            'range_to' => $rangesTo,
        ];
    }

    public function updateTeachingTime(Request $request)
    {
        $errorRdr = redirect(homeUrl('profile/teaching-time'))->withInput();

        $validator = Validator::make($request->all(), [
            'timezone' => 'required',
            'times' => 'required|array|in:0,1,2,3,4,5,6',
            'range_from.*' => 'sometimes|nullable|date_format:H:i',
            'range_to.*' => 'sometimes|nullable|date_format:H:i',
        ]);
        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $this->teacherRepository->model($request->authUser()->teacherProfile);

        try {
            $settings = settings();
            $settings->setTimezone($request->input('timezone'));
            $settings->storeUser();
            $settings->storeSession();

            $times = [];
            $rangesFrom = [];
            $rangesTo = [];

            $inputRangesFrom = $request->input('range_from', []);
            $inputRangesTo = $request->input('range_to', []);
            foreach ($request->input('times') as $time) {
                $hasRangeFrom = !empty($inputRangesFrom[$time]);
                $hasRangeTo = !empty($inputRangesTo[$time]);
                if ($hasRangeFrom) {
                    $rangeFrom = $inputRangesFrom[$time];
                }
                if ($hasRangeTo) {
                    $rangeTo = $inputRangesTo[$time];
                }

                $diffDay = 0;
                if ($hasRangeFrom && $hasRangeTo && strcmp($rangeFrom, $rangeTo) > 0) {
                    $tmp = $rangeTo;
                    $rangeTo = $rangeFrom;
                    $rangeFrom = $tmp;
                }

                if ($hasRangeFrom) {
                    $rangeFrom = DateTimeHelper::getInstance()
                        ->convertToCustomDatabaseFormat('H:i', $rangeFrom, null, false, $diffDay);
                }
                if ($hasRangeTo) {
                    if (!empty($rangeFrom)) {
                        $rangeTo = DateTimeHelper::getInstance()
                            ->convertToCustomDatabaseFormat('H:i', $rangeTo, null, false, $tDiffDay);
                    } else {
                        $rangeTo = DateTimeHelper::getInstance()
                            ->convertToCustomDatabaseFormat('H:i', $rangeTo);
                    }
                }
                $time += $diffDay;
                if (!empty($rangeFrom)) $rangesFrom[$time] = $rangeFrom;
                if (!empty($rangeTo)) $rangesTo[$time] = $rangeTo;
                $times[] = $time;
            }

            $this->teacherRepository->updateAvailableTimes(
                $times,
                $rangesFrom,
                $rangesTo
            );
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return $settings->storeCookie(redirect(homeUrl('profile/teaching-time')))
            ->with('successes', [trans('error.success')]);
    }

    public function getPaymentInformation(Request $request)
    {
        $teacher = $request->authUser()->teacherProfile;
        $paymentInfo = $teacher->payment_info;

        $this->buildPaymentInfo(
            $paymentInfo,
            $paymentVn, $hasPaymentVn,
            $paymentBankAccount, $hasPaymentBankAccount,
            $paymentPaypal, $hasPaymentPaypal,
            $paymentSkrill, $hasPaymentSkrill,
            $paymentPayoneer, $hasPaymentPayoneer,
            $paymentOthers, $hasPaymentOthers
        );

        $this->_title(trans('label.payment_information'));
        $this->_description(trans('label.payment_information'));

        return $this->_any('payment_information', [
            'payment_info' => $paymentInfo,
            'payment_vn' => $paymentVn,
            'has_payment_vn' => $hasPaymentVn,
            'payment_bank_account' => $paymentBankAccount,
            'has_payment_bank_account' => $hasPaymentBankAccount,
            'payment_paypal' => $paymentPaypal,
            'has_payment_paypal' => $hasPaymentPaypal,
            'payment_skrill' => $paymentSkrill,
            'has_payment_skrill' => $hasPaymentSkrill,
            'payment_payoneer' => $paymentPayoneer,
            'has_payment_payoneer' => $hasPaymentPayoneer,
            'payment_others' => $paymentOthers,
            'has_payment_others' => $hasPaymentOthers,
        ]);
    }

    protected function buildPaymentInfo($paymentInfo,
                                        &$paymentVn, &$hasPaymentVn,
                                        &$paymentBankAccount, &$hasPaymentBankAccount,
                                        &$paymentPaypal, &$hasPaymentPaypal,
                                        &$paymentSkrill, &$hasPaymentSkrill,
                                        &$paymentPayoneer, &$hasPaymentPayoneer,
                                        &$paymentOthers, &$hasPaymentOthers
    )
    {
        $hasPaymentVn = false;
        $paymentVn = [
            'vn_account_number' => '',
            'vn_bank_name' => '',
            'vn_account_name' => '',
            'vn_city' => '',
            'vn_branch' => '',
            'vn_account_own_name' => '',
        ];
        $hasPaymentBankAccount = false;
        $paymentBankAccount = [
            'bank_account_full_name' => '',
            'bank_account_address' => '',
            'bank_account_city' => '',
            'bank_account_country' => '',
            'bank_account_recipient_phone_number' => '',
            'bank_account_bank_name' => '',
            'bank_account_swift_code' => '',
            'bank_account_clearing_code' => '',
            'bank_account_number' => '',
            'bank_account_other_info' => '',
            'bank_account_currency' => '',
            'bank_account_own_name' => '',
        ];
        $hasPaymentPaypal = false;
        $paymentPaypal = [
            'paypal_email' => '',
            'paypal_full_name' => '',
            'paypal_country' => '',
        ];
        $hasPaymentSkrill = false;
        $paymentSkrill = [
            'skrill_email' => '',
            'skrill_full_name' => '',
            'skrill_country' => '',
        ];
        $hasPaymentPayoneer = false;
        $paymentPayoneer = [
            'payoneer_benificiary_name' => '',
            'payoneer_address' => '',
            'payoneer_bank_name' => '',
            'payoneer_country' => '',
            'payoneer_clearing_code' => '',
            'payoneer_account_number' => '',
            'payoneer_currency' => '',
            'payoneer_other_info' => '',
        ];
        $hasPaymentOthers = false;
        $paymentOthers = [
            'others_content' => '',
        ];
        if (!empty($paymentInfo)) {
            if ($paymentInfo['country'] == 'VN') {
                $hasPaymentVn = true;
                $paymentVn = array_merge($paymentVn, $paymentInfo['data']['vn']);
            } else {
                if (!empty($paymentInfo['data']['bank_account'])) {
                    $hasPaymentBankAccount = true;
                    $paymentBankAccount = array_merge($paymentBankAccount, $paymentInfo['data']['bank_account']);
                }
                if (!empty($paymentInfo['data']['paypal'])) {
                    $hasPaymentPaypal = true;
                    $paymentPaypal = array_merge($paymentPaypal, $paymentInfo['data']['paypal']);
                }
                if (!empty($paymentInfo['data']['skrill'])) {
                    $hasPaymentSkrill = true;
                    $paymentSkrill = array_merge($paymentSkrill, $paymentInfo['data']['skrill']);
                }
                if (!empty($paymentInfo['data']['payoneer'])) {
                    $hasPaymentPayoneer = true;
                    $paymentPayoneer = array_merge($paymentPayoneer, $paymentInfo['data']['payoneer']);
                }
                if (!empty($paymentInfo['data']['others'])) {
                    $hasPaymentOthers = true;
                    $paymentOthers = array_merge($paymentOthers, $paymentInfo['data']['others']);
                }
            }
        }
    }

    public function updatePaymentInformation(Request $request)
    {
        $errorRdr = redirect(homeUrl('profile/payment-information'))->withInput();

        $validator = Validator::make($request->all(), [
            'country' => 'required|in:' . implode(',', allCountryCodes()),
        ]);
        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $paymentData = [];
        if ($request->input('country') == 'VN') {
            $validator = Validator::make($request->all(), [
                'vn_account_number' => 'required',
                'vn_bank_name' => 'required',
                'vn_account_name' => 'required',
                'vn_city' => 'required',
                'vn_branch' => 'required',
            ], [
                'vn_account_number' => trans('error.payment_info'),
                'vn_bank_name' => trans('error.payment_info'),
                'vn_account_name' => trans('error.payment_info'),
                'vn_city' => trans('error.payment_info_vn'),
                'vn_branch' => trans('error.payment_info'),
            ]);
            if ($validator->fails()) {
                return $errorRdr->withErrors($validator);
            }
            $paymentData['vn'] = $request->only([
                'vn_account_number',
                'vn_bank_name',
                'vn_account_name',
                'vn_city',
                'vn_branch',
                'vn_account_own_name',
            ]);
        } else {
            if ($request->has('bank_account')) {
                $validator = Validator::make($request->all(), [
                    'bank_account_full_name' => 'required',
                    'bank_account_city' => 'required',
                    'bank_account_country' => 'required',
                    'bank_account_recipient_phone_number' => 'required',
                    'bank_account_bank_name' => 'required',
                    'bank_account_number' => 'required',
                    'bank_account_currency' => 'required',
                ], [
                    'bank_account_full_name' => trans('error.payment_info'),
                    'bank_account_city' => trans('error.payment_info'),
                    'bank_account_country' => trans('error.payment_info'),
                    'bank_account_recipient_phone_number' => trans('error.payment_info'),
                    'bank_account_bank_name' => trans('error.payment_info'),
                    'bank_account_number' => trans('error.payment_info'),
                    'bank_account_currency' => trans('error.payment_info'),
                ]);
                if ($validator->fails()) {
                    return $errorRdr->withErrors($validator);
                }
                $paymentData['bank_account'] = $request->only([
                    'bank_account_full_name',
                    'bank_account_address',
                    'bank_account_city',
                    'bank_account_country',
                    'bank_account_recipient_phone_number',
                    'bank_account_bank_name',
                    'bank_account_swift_code',
                    'bank_account_clearing_code',
                    'bank_account_number',
                    'bank_account_other_info',
                    'bank_account_currency',
                    'bank_account_own_name',
                ]);
            }
            if ($request->has('paypal')) {
                $validator = Validator::make($request->all(), [
                    'paypal_email' => 'required',
                    'paypal_full_name' => 'required',
                    'paypal_country' => 'required',
                ], [
                    'paypal_email' => trans('error.payment_info'),
                    'paypal_full_name' => trans('error.payment_info'),
                    'paypal_country' => trans('error.payment_info'),
                ]);
                if ($validator->fails()) {
                    return $errorRdr->withErrors($validator);
                }
                $paymentData['paypal'] = $request->only([
                    'paypal_email',
                    'paypal_full_name',
                    'paypal_country',
                ]);
            }
            if ($request->has('skrill')) {
                $validator = Validator::make($request->all(), [
                    'skrill_email' => 'required',
                    'skrill_full_name' => 'required',
                    'skrill_country' => 'required',
                ], [
                    'skrill_email' => trans('error.payment_info'),
                    'skrill_full_name' => trans('error.payment_info'),
                    'skrill_country' => trans('error.payment_info'),
                ]);
                if ($validator->fails()) {
                    return $errorRdr->withErrors($validator);
                }
                $paymentData['skrill'] = $request->only([
                    'skrill_email',
                    'skrill_full_name',
                    'skrill_country',
                ]);
            }
            if ($request->has('payoneer')) {
                $validator = Validator::make($request->all(), [
                    'payoneer_benificiary_name' => 'required',
                    'payoneer_bank_name' => 'required',
                    'payoneer_country' => 'required',
                    'payoneer_account_number' => 'required',
                    'payoneer_currency' => 'required',
                ], [
                    'payoneer_benificiary_name' => trans('error.payment_info'),
                    'payoneer_bank_name' => trans('error.payment_info'),
                    'payoneer_country' => trans('error.payment_info'),
                    'payoneer_account_number' => trans('error.payment_info'),
                    'payoneer_currency' => trans('error.payment_info'),
                ]);
                if ($validator->fails()) {
                    return $errorRdr->withErrors($validator);
                }
                $paymentData['payoneer'] = $request->only([
                    'payoneer_benificiary_name',
                    'payoneer_address',
                    'payoneer_bank_name',
                    'payoneer_country',
                    'payoneer_clearing_code',
                    'payoneer_account_number',
                    'payoneer_currency',
                    'payoneer_other_info',
                ]);
            }
            if ($request->has('others')) {
                $validator = Validator::make($request->all(), [
                    'others_content' => 'required',
                ], [
                    'others_content' => trans('error.payment_info'),
                ]);
                if ($validator->fails()) {
                    return $errorRdr->withErrors($validator);
                }
                $paymentData['others'] = $request->only([
                    'others_content',
                ]);
            }
        }

        $this->teacherRepository->model($request->authUser()->teacherProfile);

        try {
            $this->teacherRepository->updatePaymentInfo($request->input('country'), $paymentData);
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(homeUrl('profile/payment-information'))
            ->with('successes', [trans('error.success')]);
    }

    #endregion

    protected function getRatesForTeacher($reviewsForTeacher, &$ratesForTeacher, &$averageRate)
    {
        $ratesForTeacher = [];
        $averageRate = 0;
        $count = 0;
        foreach (_k('rates_for_teacher') as $rate) {
            ++$count;
            $ratesForTeacher[$rate] = $reviewsForTeacher->avg($rate);
            $averageRate += $ratesForTeacher[$rate];
        }
        $averageRate = $averageRate / $count;
    }

    public function index(Request $request)
    {
        $topicRepository = new TopicRepository();

        $searchTopics = $request->input('topics', []);
        $searchNationality = $request->input('nationality', null);
        $searchGender = $request->input('gender', null);
        $teachers = $this->teacherRepository->getHomeSearchPaged(
            $searchTopics,
            $searchNationality,
            $searchGender
        );

        $reviewsReport = new TeacherPeriodicReviewsReport($teachers->pluck('user_id')->all());
        $reviewsForTeachers = $reviewsReport->getData();
        $averageRateForTeachers = [];
        $countRatingStudentsForTeachers = [];
        foreach ($reviewsForTeachers as $teacherId => $reviewsForTeacher) {
            $this->getRatesForTeacher($reviewsForTeacher, $ratesForTeacher, $averageRate);
            $averageRateForTeachers[$teacherId] = $averageRate;
            $countRatingStudentsForTeachers[$teacherId] = $reviewsForTeacher->groupBy('user_id')->count();
        }

        $this->_title(trans('pages.home_teachers_title'));
        $this->_description(trans('pages.home_teachers_desc'));

        $this->ogTeacherList($teachers);

        return $this->_index([
            'topics' => $topicRepository->getAll(),
            'teachers' => $teachers,
            'average_rate_for_teachers' => $averageRateForTeachers,
            'count_rating_students_for_teachers' => $countRatingStudentsForTeachers,
            'pagination' => $this->paginationRender->renderByPagedModels($teachers),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'max_rate' => count(_k('rates')),

            'search_topics' => $searchTopics,
            'search_nationality' => $searchNationality,
            'search_gender' => $searchGender,
        ]);
    }

    public function show(Request $request, $id)
    {
        $teacher = $this->teacherRepository->model($id);

        $reviewsReport = new TeacherPeriodicReviewsReport($teacher->user_id);
        $reviewsForTeacher = $reviewsReport->getData()[$teacher->user_id];
        $this->getRatesForTeacher($reviewsForTeacher, $ratesForTeacher, $averageRate);

        $paymentInfoView = '';
        if ($request->isAuth() && $request->authUser()->hasRole(['admin', 'manager'])) {
            $this->buildPaymentInfo(
                $teacher->paymentInfo,
                $paymentVn, $hasPaymentVn,
                $paymentBankAccount, $hasPaymentBankAccount,
                $paymentPaypal, $hasPaymentPaypal,
                $paymentSkrill, $hasPaymentSkrill,
                $paymentPayoneer, $hasPaymentPayoneer,
                $paymentOthers, $hasPaymentOthers
            );

            if ($hasPaymentVn) {
                $paymentInfoView = view($this->_viewPath('_pi_vn'), [
                    'payment_vn' => $paymentVn,
                ])->render();
            } else {
                if ($hasPaymentBankAccount) {
                    $paymentInfoView .= view($this->_viewPath('_pi_bank_account'), [
                        'payment_bank_account' => $paymentBankAccount,
                    ])->render();
                }
                if ($hasPaymentPaypal) {
                    $paymentInfoView .= view($this->_viewPath('_pi_paypal'), [
                        'payment_paypal' => $paymentPaypal,
                    ])->render();
                }
                if ($hasPaymentSkrill) {
                    $paymentInfoView .= view($this->_viewPath('_pi_skrill'), [
                        'payment_skrill' => $paymentSkrill,
                    ])->render();
                }
                if ($hasPaymentPayoneer) {
                    $paymentInfoView .= view($this->_viewPath('_pi_payoneer'), [
                        'payment_payoneer' => $paymentPayoneer,
                    ])->render();
                }
                if ($hasPaymentOthers) {
                    $paymentInfoView .= view($this->_viewPath('_pi_others'), [
                        'payment_others' => $paymentOthers,
                    ])->render();
                }
            }
        }

        $this->teacherRepository->view();
        $theme = $request->getTheme();

        $this->_title([trans('pages.home_teacher_title'), $teacher->userProfile->display_name]);
        $this->_description(shorten($teacher->about_me));

        $this->ogTeacherSingle($teacher);

        return $this->_show([
            'teacher' => $teacher,
            'has_rates' => $reviewsForTeacher->count() > 0,
            'count_rating_students' => $reviewsForTeacher->groupBy('user_id')->count(),
            'rates_for_teacher' => $ratesForTeacher,
            'average_rate' => $averageRate,
            'max_rate' => count(_k('rates')),
            'available_times' => $this->formatAvailableTimes($teacher->available_times),

            'skype_id' => $theme->options('ts_skype_id', ''),
            'skype_name' => $theme->options('ts_skype_name', ''),
            'hot_line' => $theme->options('ts_hot_line', ''),
            'email' => $theme->options('ts_email', ''),

            'payment_info_view' => new HtmlString($paymentInfoView),
        ]);
    }

    protected function ogTeacherSingle($teacher)
    {
        $avatar = $teacher->userProfile->url_avatar;
        addFilter('open_graph_tags_before_render', new CallableObject(function ($data) use ($avatar) {
            $data['og:image'] = $avatar;
            return $data;
        }), 'teachers_view_single');
    }

    protected function ogTeacherList($teachers)
    {
        $imageUrls = [appLogo()];
        foreach ($teachers as $teacher) {
            $imageUrls[] = $teacher->userProfile->url_avatar;
        }
        addFilter('open_graph_tags_before_render', new CallableObject(function ($data) use ($imageUrls) {
            $data['og:image'] = $imageUrls;
            return $data;
        }), 'teachers_view_list');
    }
}