<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 22:36
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\RegisterLearningRequestRepository;

class LearningRequestController extends WebApiController
{
    protected $learningRequestRepository;

    public function __construct()
    {
        parent::__construct();

        $this->learningRequestRepository = new RegisterLearningRequestRepository();
    }

    public function show(Request $request, $id)
    {
        $learningRequest = $this->learningRequestRepository->model($id);

        $forChildren = $learningRequest->for_children == 1;

        $learningTargets = $learningRequest->learningTargets;
        $learningTargetsLabel = [];
        foreach ($learningTargets as $key => $value) {
            if ($key == 101 || $key == 100) {
                $learningTargetsLabel[] = trans('label.learning_target_' . $key) . ' - ' . $value;
            } else {
                $learningTargetsLabel[] = trans('label.learning_target_' . $key);
            }
        }
        $learningForms = $learningRequest->learningForms;
        $learningFormsLabel = [];
        foreach ($learningForms as $key => $value) {
            if ($key == 101 || $key == 100) {
                $learningFormsLabel[] = trans('label.learning_form_' . $key) . ' - ' . $value;
            } else {
                $learningFormsLabel[] = trans('label.learning_form_' . $key);
            }
        }

        return $this->responseSuccess([
            'learning_request' => [
                'student' => [
                    'display_name' => $learningRequest->studentUserProfile->display_name,
                    'phone' => $learningRequest->studentUserProfile->phone,
                    'email' => $learningRequest->studentUserProfile->email,
                    'skype_id' => $learningRequest->studentUserProfile->skype_id,
                    'professional_skill_names' => $learningRequest->studentUserProfile->professionalSkills->implode('name', ', '),
                    'admin_edit_url' => adminUrl('students/{id}/edit', ['id' => $learningRequest->student_id]),
                ],
                'teacher' => empty($learningRequest->teacher_id) ? null : [
                    'display_name' => $learningRequest->teacherUserProfile->display_name,
                    'admin_edit_url' => adminUrl('teachers/{id}/edit', ['id' => $learningRequest->teacher_id]),
                ],
                'study_level' => empty($learningRequest->study_level_id) ? null : [
                    'name' => $learningRequest->studyLevel->name,
                ],
                'study_problem' => empty($learningRequest->study_problem_id) ? null : [
                    'name' => $learningRequest->studyProblem->name,
                ],
                'study_course' => empty($learningRequest->study_course_id) ? null : [
                    'name' => $learningRequest->studyCourse->name,
                ],
                'for_children' => $forChildren,
                'children_full_name' => $learningRequest->children_full_name,
                'age_range_label' => trans('label.age_range_' . $learningRequest->age_range),
                'learning_targets_label' => implode(', ', $learningTargetsLabel),
                'learning_forms_label' => implode(', ', $learningFormsLabel),
            ]
        ]);
    }
}