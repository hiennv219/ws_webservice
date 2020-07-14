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
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Meta;
use Katniss\Everdeen\Repositories\ProfessionalSkillRepository;
use Katniss\Everdeen\Repositories\RegisterLearningRequestRepository;
use Katniss\Everdeen\Repositories\StudentRepository;
use Katniss\Everdeen\Utils\ReCaptcha;

class StudentController extends ViewController
{
    protected $studentRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'student';
        $this->studentRepository = new StudentRepository();
    }

    #region Sign up
    public function getSignUp(Request $request)
    {
        if ($request->isAuth() && $request->authUser()->hasRole('student')) {
            return redirect(homeUrl());
        }

        $this->_title(trans('pages.home_student_sign_up_title'));
        $this->_description(trans('pages.home_student_sign_up_desc'));

        return $this->_any('sign_up', [
            'agent' => $request->input('agent', null),
            'teacher_id' => $request->input('teacher_id', null),
            'study_level' => $request->input('study_level', null),
            'study_problem' => $request->input('study_problem', null),
            'study_course' => $request->input('study_course', null),
        ]);
    }

    public function postSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|max:255',
            'phone_code' => 'required|in:' . implode(',', allCountryCodes()),
            'phone_number' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
            'agent' => 'sometimes|nullable|exists:users,id',
            'g-recaptcha-response'=>'reCaptcha'
        ]);

        $studyParams = http_build_query([
            'teacher_id' => $request->input('teacher_id', null),
            'study_level' => $request->input('study_level', null),
            'study_problem' => $request->input('study_problem', null),
            'study_course' => $request->input('study_course', null),
        ]);

        $errorRdr = redirect(homeUrl('student/sign-up') . (!empty($studyParams) ? '?' . $studyParams : ''))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        try {
            $student = $this->studentRepository->create(
                $request->input('display_name'),
                $request->input('email'),
                $request->input('password'),
                $request->input('phone_code'),
                $request->input('phone_number'),
                $request->input('agent', null)
            );

            $wizard = $this->startWizard();
            return redirect(addWizardUrl(
                    homeUrl('student/sign-up/step/{step}', ['step' => 2]),
                    $wizard['name'],
                    $wizard['key']
                ) . '&student_id=' . $student->id . (!empty($studyParams) ? '&' . $studyParams : ''));
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }
    }

    public function getSignUpStep(Request $request, $step)
    {
        if ($step == 1) {
            return $this->getSignUpStep1($request);
        } elseif ($step == 2) {
            return $this->getSignUpStep2($request);
        } elseif ($step == 3) {
            return $this->getSignUpStep3($request);
        }

        abort(404);
        return false;
    }

    public function getSignUpStep1(Request $request)
    {
        return $this->getSignUp($request);
    }

    public function getSignUpStep2(Request $request)
    {
        $wizard = $this->checkWizard();

        $student = $this->studentRepository->model($request->input('student_id', -1));

        $professionalSkillRepository = new ProfessionalSkillRepository();

        $this->_title([trans('pages.home_student_sign_up_title'), trans('label._step', ['step' => 2])]);
        $this->_description(trans('pages.home_student_sign_up_desc'));

        return $this->_any('sign_up_step_2', [
            'professional_skills' => $professionalSkillRepository->getAll(),
            'age_ranges' => _k('age_ranges'),
            'age_ranges_children' => _k('age_ranges_children'),
            'learning_targets' => _k('learning_targets'),
            'learning_targets_children' => _k('learning_targets_children'),
            'learning_forms' => _k('learning_forms'),
            'learning_forms_children' => _k('learning_forms_children'),
            'teacher_id' => $request->input('teacher_id', ''),
            'student_id' => $student->user_id,
            'wizard_name' => $wizard['name'],
            'wizard_key' => $wizard['key'],

            'study_level' => $request->input('study_level', null),
            'study_problem' => $request->input('study_problem', null),
            'study_course' => $request->input('study_course', null),
        ]);
    }

    public function getSignUpStep3(Request $request)
    {
        if (!$this->checkWizard(true)) {
            return redirect(homeUrl());
        }
        $this->endWizard();

        $this->_title([trans('pages.home_student_sign_up_title'), trans('label._step', ['step' => 3])]);
        $this->_description(trans('pages.home_student_sign_up_desc'));

        return $this->_any('sign_up_step_3');
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
        return $this->postSignUp($request);
    }

    public function postSignUpStep2(Request $request)
    {
        $wizard = $this->checkWizard();

        if ($request->has('for_children')) {
            return $this->postSignUpStep2Children($request, $wizard);
        }
        return $this->postSignUpStep2Adult($request, $wizard);
    }

    protected function postSignUpStep2Adult(Request $request, $wizard)
    {
        $ageRanges = _k('age_ranges');
        $learningTargets = _k('learning_targets');
        $learningForms = _k('learning_forms');

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,user_id',
            'teacher_id' => 'sometimes|nullable|exists:teachers,user_id',
            'for_children' => 'sometimes|nullable|in:1',
            'age_range' => 'required|in:' . implode(',', $ageRanges),
            'professional_skills' => 'required|array|exists:professional_skills,id',
            'skype_id' => 'required|max:255',
            'learning_targets' => 'required|array|in:' . implode(',', $learningTargets),
            'learning_forms' => 'required|array|in:' . implode(',', $learningForms),

            'study_level' => 'sometimes|nullable|exists:meta,id,type,' . Meta::TYPE_STUDY_LEVEL,
            'study_problem' => 'sometimes|nullable|exists:meta,id,type,' . Meta::TYPE_STUDY_PROBLEM,
            'study_course' => 'sometimes|nullable|exists:meta,id,type,' . Meta::TYPE_STUDY_COURSE,
        ]);

        $studyParams = http_build_query([
            'study_level' => $request->input('study_level', null),
            'study_problem' => $request->input('study_problem', null),
            'study_course' => $request->input('study_course', null),
        ]);

        $errorRdr = redirect(addWizardUrl(
                homeUrl('student/sign-up/step/{step}', ['step' => 2]),
                $wizard['name'],
                $wizard['key']
            ) . '&student_id=' . $request->input('student_id') . (!empty($studyParams) ? '&' . $studyParams : ''))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        if (in_array('100', $request->input('learning_targets'))) {
            $validator = Validator::make($request->all(), [
                'learning_target_other' => 'required|max:200',
            ]);
            if ($validator->fails()) {
                return $errorRdr->withErrors($validator);
            }
        }

        if (in_array('100', $request->input('learning_forms'))) {
            $validator = Validator::make($request->all(), [
                'learning_form_other' => 'required|max:200',
            ]);
            if ($validator->fails()) {
                return $errorRdr->withErrors($validator);
            }
        }

        try {
            $learningRequestRepository = new RegisterLearningRequestRepository();
            $learningRequest = $learningRequestRepository->createAdult(
                $request->input('age_range'),
                $request->input('learning_targets'),
                $request->input('learning_target_other'),
                $request->input('learning_forms'),
                $request->input('learning_form_other'),
                $request->input('student_id'),
                $request->input('professional_skills'),
                $request->input('skype_id'),
                $request->input('teacher_id', null),
                $request->input('study_level', null),
                $request->input('study_problem', null),
                $request->input('study_course', null)
            );

            Auth::guard()->login($learningRequest->studentUserProfile);
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(addWizardUrl(
            homeUrl('student/sign-up/step/{step}', ['step' => 3]),
            $wizard['name'],
            $wizard['key']
        ));
    }

    protected function postSignUpStep2Children(Request $request, $wizard)
    {
        $ageRanges = _k('age_ranges_children');
        $learningTargets = _k('learning_targets_children');
        $learningForms = _k('learning_forms_children');

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,user_id',
            'teacher_id' => 'sometimes|nullable|exists:teachers,user_id',
            'age_range' => 'required|in:' . implode(',', $ageRanges),
            'children_full_name' => 'required|max:255',
            'skype_id' => 'required|max:255',
            'learning_targets' => 'required|array|in:' . implode(',', $learningTargets),
            'learning_forms' => 'required|array|in:' . implode(',', $learningForms),

            'study_level' => 'sometimes|nullable|exists:meta,id,type,' . Meta::TYPE_STUDY_LEVEL,
            'study_problem' => 'sometimes|nullable|exists:meta,id,type,' . Meta::TYPE_STUDY_PROBLEM,
            'study_course' => 'sometimes|nullable|exists:meta,id,type,' . Meta::TYPE_STUDY_COURSE,
        ]);

        $studyParams = http_build_query([
            'study_level' => $request->input('study_level', null),
            'study_problem' => $request->input('study_problem', null),
            'study_course' => $request->input('study_course', null),
        ]);

        $errorRdr = redirect(addWizardUrl(
                homeUrl('student/sign-up/step/{step}', ['step' => 2]),
                $wizard['name'],
                $wizard['key']
            ) . '&student_id=' . $request->input('student_id') . (!empty($studyParams) ? '&' . $studyParams : ''))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        if (in_array('100', $request->input('learning_targets'))) {
            $validator = Validator::make($request->all(), [
                'learning_target_other' => 'required|max:200',
            ]);
            if ($validator->fails()) {
                return $errorRdr->withErrors($validator);
            }
        }

        if (in_array('100', $request->input('learning_forms'))) {
            $validator = Validator::make($request->all(), [
                'learning_form_other' => 'required|max:200',
            ]);
            if ($validator->fails()) {
                return $errorRdr->withErrors($validator);
            }
        }

        try {
            $learningRequestRepository = new RegisterLearningRequestRepository();
            $learningRequest = $learningRequestRepository->createChildren(
                $request->input('age_range'),
                $request->input('learning_targets'),
                $request->input('learning_target_other'),
                $request->input('learning_forms'),
                $request->input('learning_form_other'),
                $request->input('student_id'),
                $request->input('children_full_name'),
                $request->input('skype_id'),
                $request->input('teacher_id', null),
                $request->input('study_level', null),
                $request->input('study_problem', null),
                $request->input('study_course', null)
            );

            Auth::guard()->login($learningRequest->studentUserProfile);
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(addWizardUrl(
            homeUrl('student/sign-up/step/{step}', ['step' => 3]),
            $wizard['name'],
            $wizard['key']
        ));
    }
    #endregion
}