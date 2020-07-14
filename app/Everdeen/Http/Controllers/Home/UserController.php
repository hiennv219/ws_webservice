<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:05
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ProfessionalSkillRepository;
use Katniss\Everdeen\Repositories\UserCertificateRepository;
use Katniss\Everdeen\Repositories\UserEducationRepository;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Repositories\UserWorkRepository;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\Extension as SocialIntegrationExtension;
use Katniss\Everdeen\Utils\DateTimeHelper;

class UserController extends ViewController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'user';
        $this->userRepository = new UserRepository();
    }

    public function signUp(Request $request)
    {
        if ($request->isAuth() && $request->authUser()->hasRole(['teacher', 'student'])) {
            return redirect(homeUrl());
        }

        $this->_title(trans('pages.home_user_sign_up_title'));
        $this->_description(trans('pages.home_user_sign_up_desc'));

        return $this->_any('sign_up');
    }

    public function getAccountInformation(Request $request)
    {
        $this->userRepository->model($request->authUser());

        $this->_title(trans('label.account_information'));
        $this->_description(trans('label.account_information'));

        return $this->_any('account_information', [
            'social_integration' => SocialIntegrationExtension::getSharedViewData(),
            'has_facebook_connected' => $this->userRepository->hasFacebookConnected(),
        ]);
    }

    public function getUserInformation(Request $request)
    {
        $this->_title(trans('label.user_information'));
        $this->_description(trans('label.user_information'));

        return $this->_any('user_information', [
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    public function updateUserInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|max:255',
            'date_of_birth' => 'sometimes|nullable|date_format:' . DateTimeHelper::shortDateFormat(),
            'gender' => 'required|in:' . implode(',', allGenders()),
            'phone_code' => 'required|in:' . implode(',', allCountryCodes()),
            'phone_number' => 'required|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'country' => 'required|in:' . implode(',', allCountryCodes()),
            'nationality' => 'required|in:' . implode(',', allCountryCodes()),
        ]);;

        $errorRdr = redirect(homeUrl('profile/user-information'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $userRepository = new UserRepository($request->authUser());
        $settings = settings();

        try {
            $userRepository->updateAttributes([
                'display_name' => $request->input('display_name'),
                'date_of_birth' => DateTimeHelper::getInstance()
                    ->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $request->input('date_of_birth'), true),
                'gender' => $request->input('gender'),
                'phone_code' => $request->input('phone_code'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'nationality' => $request->input('nationality'),
            ]);

            $settings->setCountry($request->input('country'));
            $settings->storeUser();
            $settings->storeSession();

        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return $settings->storeCookie(redirect(homeUrl('profile/user-information')))
            ->with('successes', [trans('error.success')]);
    }

    public function getEducationsAndWorks(Request $request)
    {
        $user = $request->authUser();
        $professionalSkillRepository = new ProfessionalSkillRepository();

        $this->_title(trans('label.educations_and_works'));
        $this->_description(trans('label.educations_and_works'));

        return $this->_any('educations_and_works', [
            'professional_skills' => $professionalSkillRepository->getAll(),
            'user_professional_skill_ids' => $user->professionalSkills->pluck('id')->all(),
            'user_educations' => $user->educations,
            'user_certificates' => $user->certificates,
            'user_works' => $user->works,
            'certificate_types' => _k('certificate_types'),
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    public function postProfessionalSkills(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'professional_skills' => 'required|array|exists:professional_skills,id',
        ]);

        $redirect = redirect(homeUrl('profile/educations-and-works'));
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator, 'professional_skills');
        }

        try {
            $user = $request->authUser();
            if ($user->professionalSkills()->count() > 0) {
                $user->professionalSkills()->sync($request->input('professional_skills'));
            } else {
                $user->professionalSkills()->attach($request->input('professional_skills'));
            }

            return redirect(homeUrl('profile/educations-and-works'));
        } catch (\Exception $exception) {
            return $redirect->withInput()->withErrors([$exception->getMessage()], 'professional_skills');
        }
    }

    public function storeWork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required|max:255',
            'position' => 'required|max:255',
            'start_month' => 'sometimes|nullable|integer|min:0|max:12',
            'start_year' => 'required_with:start_month|integer|min:0|max:' . date('Y'),
            'end_month' => 'sometimes|nullable|integer|min:0|max:12',
            'end_year' => 'required_with:end_month|integer|min:0|max:' . date('Y'),
        ]);

        $redirect = redirect(homeUrl('profile/educations-and-works') . '#fresh-works');
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator, 'work');
        }

        try {
            $workRepository = new UserWorkRepository();
            $work = $workRepository->create(
                $request->authUser()->id,
                $request->input('company'),
                $request->input('position'),
                $request->input('start_month', 0),
                $request->input('start_year', 0),
                $request->input('end_month', 0),
                $request->input('end_year', 0),
                $request->input('description', '')
            );

            return redirect(homeUrl('profile/educations-and-works') . '#work-' . $work->id);
        } catch (KatnissException $exception) {
            return $redirect->withInput()->withErrors([$exception->getMessage()], 'work');
        }
    }

    public function updateWork(Request $request, $id)
    {
        $workRepository = new UserWorkRepository($id);

        $validator = Validator::make($request->all(), [
            'company' => 'required|max:255',
            'position' => 'required|max:255',
            'start_month' => 'sometimes|nullable|integer|min:0|max:12',
            'start_year' => 'required_with:start_month|integer|min:0|max:' . date('Y'),
            'end_month' => 'sometimes|nullable|integer|min:0|max:12',
            'end_year' => 'required_with:end_month|integer|min:0|max:' . date('Y'),
        ]);

        $redirect = redirect(homeUrl('profile/educations-and-works') . '#work-' . $id);
        if ($validator->fails()) {
            return $redirect->withErrors($validator, 'work_' . $id);
        }

        try {
            $workRepository->update(
                $request->authUser()->id,
                $request->input('company'),
                $request->input('position'),
                $request->input('start_month', 0),
                $request->input('start_year', 0),
                $request->input('end_month', 0),
                $request->input('end_year', 0),
                $request->input('description', '')
            );
        } catch (KatnissException $exception) {
            return $redirect->withErrors([$exception->getMessage()], 'work_' . $id);
        }

        return $redirect;
    }

    public function destroyWork(Request $request, $id)
    {
        $workRepository = new UserWorkRepository($id);

        $this->_rdrUrl($request, null, $rdrUrl, $errorRdrUrl);

        try {
            $workRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function storeEducation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school' => 'required|max:255',
            'field' => 'required|max:255',
            'start_month' => 'sometimes|nullable|integer|min:0|max:12',
            'start_year' => 'required_with:start_month|integer|min:0|max:' . date('Y'),
            'end_month' => 'sometimes|nullable|integer|min:0|max:12',
            'end_year' => 'required_with:end_month|integer|min:0|max:' . date('Y'),
        ]);

        $redirect = redirect(homeUrl('profile/educations-and-works') . '#fresh-educations');
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator, 'education');
        }

        try {
            $educationRepository = new UserEducationRepository();
            $education = $educationRepository->create(
                $request->authUser()->id,
                $request->input('school'),
                $request->input('field'),
                $request->input('start_month', 0),
                $request->input('start_year', 0),
                $request->input('end_month', 0),
                $request->input('end_year', 0),
                $request->input('description', '')
            );

            return redirect(homeUrl('profile/educations-and-works') . '#education-' . $education->id);
        } catch (KatnissException $exception) {
            return $redirect->withInput()->withErrors([$exception->getMessage()], 'education');
        }
    }

    public function updateEducation(Request $request, $id)
    {
        $educationRepository = new UserEducationRepository($id);

        $validator = Validator::make($request->all(), [
            'school' => 'required|max:255',
            'field' => 'required|max:255',
            'start_month' => 'sometimes|nullable|integer|min:0|max:12',
            'start_year' => 'required_with:start_month|integer|min:0|max:' . date('Y'),
            'end_month' => 'sometimes|nullable|integer|min:0|max:12',
            'end_year' => 'required_with:end_month|integer|min:0|max:' . date('Y'),
        ]);

        $redirect = redirect(homeUrl('profile/educations-and-works') . '#education-' . $id);
        if ($validator->fails()) {
            return $redirect->withErrors($validator, 'education_' . $id);
        }

        try {
            $educationRepository->update(
                $request->authUser()->id,
                $request->input('school'),
                $request->input('field'),
                $request->input('start_month', 0),
                $request->input('start_year', 0),
                $request->input('end_month', 0),
                $request->input('end_year', 0),
                $request->input('description', '')
            );
        } catch (KatnissException $exception) {
            return $redirect->withErrors([$exception->getMessage()], 'education_' . $id);
        }

        return $redirect;
    }

    public function destroyEducation(Request $request, $id)
    {
        $educationRepository = new UserEducationRepository($id);

        $this->_rdrUrl($request, null, $rdrUrl, $errorRdrUrl);

        try {
            $educationRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function storeCertificate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:' . implode(',', _k('certificate_types')),
            'meta' => 'sometimes|nullable|array',
            'provided_by' => 'required|max:255',
            'provided_at' => 'sometimes|nullable|date_format:' . DateTimeHelper::shortDateFormat(),
            'image' => 'sometimes|nullable|image',
        ]);

        $redirect = redirect(homeUrl('profile/educations-and-works') . '#fresh-certificates');
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator, 'certificate');
        }

        try {
            $certificateRepository = new UserCertificateRepository();
            $certificate = $certificateRepository->create(
                $request->authUser()->id,
                $request->input('type'),
                $request->input('provided_by'),
                $request->input('provided_at', null),
                $request->hasFile('image') ? $request->file('image')->getRealPath() : null,
                $request->input('meta', []),
                $request->input('description', '')
            );

            return redirect(homeUrl('profile/educations-and-works') . '#certificate-' . $certificate->id);
        } catch (KatnissException $exception) {
            return $redirect->withInput()->withErrors([$exception->getMessage()], 'certificate');
        }
    }

    public function updateCertificate(Request $request, $id)
    {
        $certificateRepository = new UserCertificateRepository($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:' . implode(',', _k('certificate_types')),
            'meta' => 'sometimes|nullable|array',
            'provided_by' => 'required|max:255',
            'provided_at' => 'sometimes|nullable|date_format:' . DateTimeHelper::shortDateFormat(),
            'image' => 'sometimes|nullable|image',
        ]);

        $redirect = redirect(homeUrl('profile/educations-and-works') . '#certificate-' . $id);
        if ($validator->fails()) {
            return $redirect->withErrors($validator, 'certificate_' . $id);
        }

        try {
            $certificateRepository->update(
                $request->authUser()->id,
                $request->input('type'),
                $request->input('provided_by'),
                $request->input('provided_at', null),
                $request->hasFile('image') ? $request->file('image')->getRealPath() : null,
                $request->input('meta', []),
                $request->input('description', '')
            );
        } catch (KatnissException $exception) {
            return $redirect->withErrors([$exception->getMessage()], 'certificate_' . $id);
        }

        return $redirect;
    }

    public function destroyCertificate(Request $request, $id)
    {
        $certificationRepository = new UserCertificateRepository($id);

        $this->_rdrUrl($request, null, $rdrUrl, $errorRdrUrl);

        try {
            $certificationRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}