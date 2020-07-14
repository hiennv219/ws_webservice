<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Repositories\RegisterLearningRequestRepository;
use Katniss\Everdeen\Repositories\StudentRepository;
use Katniss\Everdeen\Repositories\TeacherRepository;

class DashboardController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'dashboard';
    }

    public function index()
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P30D'));
        $date = $date->format('Y-m-d');

        $teacherRepository = new TeacherRepository();
        $countApprovedTeachers = $teacherRepository->getCountApproved();
        $countRegisteringTeachers = $teacherRepository->getCountRegistering();
        $countAfterDateTeachers = $teacherRepository->getCountAfterDate($date);
        $pcAfterDateTeachers = $countApprovedTeachers + $countRegisteringTeachers - $countAfterDateTeachers > 0 ?
            intval($countAfterDateTeachers * 100 / ($countApprovedTeachers + $countRegisteringTeachers)) : 100;

        $studentRepository = new StudentRepository();
        $countApprovedStudents = $studentRepository->getCountApproved();
        $countRegisteringStudents = $studentRepository->getCountRegistering();
        $countAfterDateStudents = $studentRepository->getCountAfterDate($date);
        $pcAfterDateStudents = $countApprovedStudents + $countRegisteringStudents - $countAfterDateStudents > 0 ?
            intval($countAfterDateStudents * 100 / ($countApprovedStudents + $countRegisteringStudents)) : 100;

        $classroomRepository = new ClassroomRepository();
        $countOpeningClassrooms = $classroomRepository->getCountOpening();
        $countClosedClassrooms = $classroomRepository->getCountClosed();
        $countAfterDateClassrooms = $classroomRepository->getCountAfterDate($date);
        $pcAfterDateClassrooms = $countOpeningClassrooms + $countClosedClassrooms - $countAfterDateClassrooms > 0 ?
            intval($countAfterDateClassrooms * 100 / ($countOpeningClassrooms + $countClosedClassrooms)) : 100;

        $learningRequestRepository = new RegisterLearningRequestRepository();
        $countNewlyLearningRequests = $learningRequestRepository->getCountNewly();
        $countProcessedLearningRequests = $learningRequestRepository->getCountProcessed();
        $countAfterDateLearningRequests = $learningRequestRepository->getCountAfterDate($date);
        $pcAfterDateLearningRequests = $countNewlyLearningRequests + $countProcessedLearningRequests - $countAfterDateLearningRequests > 0 ?
            intval($countAfterDateLearningRequests * 100 / ($countNewlyLearningRequests + $countProcessedLearningRequests)) : 100;

        $articleRepository = new ArticleRepository();
        $countPublishedArticles = $articleRepository->getCountPublished();
        $countTeacherArticles = $articleRepository->getCountTeacher();
        $countAfterDateArticles = $articleRepository->getCountAfterDate($date);
        $pcAfterDateArticles = $countPublishedArticles + $countTeacherArticles - $countAfterDateArticles > 0 ?
            intval($countAfterDateArticles * 100 / ($countPublishedArticles + $countTeacherArticles)) : 100;

        $this->_title(trans('pages.admin_dashboard_title'));
        $this->_description(trans('pages.admin_dashboard_desc'));

        return $this->_view([
            'count_approved_teachers' => $countApprovedTeachers,
            'count_registering_teachers' => $countRegisteringTeachers,
            'pc_teachers' => $pcAfterDateTeachers,
            'count_approved_students' => $countApprovedStudents,
            'count_registering_students' => $countRegisteringStudents,
            'pc_students' => $pcAfterDateStudents,
            'count_opening_classrooms' => $countOpeningClassrooms,
            'count_closed_classrooms' => $countClosedClassrooms,
            'pc_classrooms' => $pcAfterDateClassrooms,
            'count_newly_lr' => $countNewlyLearningRequests,
            'count_processed_lr' => $countProcessedLearningRequests,
            'pc_lr' => $pcAfterDateLearningRequests,
            'count_published_articles' => $countPublishedArticles,
            'count_teacher_articles' => $countTeacherArticles,
            'pc_articles' => $pcAfterDateArticles,
        ]);
    }
}
