<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localize']
], function () {
    Route::any(homeRoute('extra'), 'ViewController@extra');

    Route::get(homeRoute('errors/{code}'), 'ViewController@error');
    Route::get(adminRoute('errors/{code}'), 'ViewController@error');

    Route::group([
        'namespace' => 'Home',
    ], function () {
        Route::group([
            'middleware' => 'auth'
        ], function () {
            Route::get(homeRoute('profile/account-information'), 'UserController@getAccountInformation');
            Route::get(homeRoute('profile/user-information'), 'UserController@getUserInformation');
            Route::put(homeRoute('profile/user-information'), 'UserController@updateUserInformation');
            Route::get(homeRoute('profile/educations-and-works'), 'UserController@getEducationsAndWorks');
            Route::post(homeRoute('profile/professional-skills'), 'UserController@postProfessionalSkills');
            Route::post(homeRoute('profile/user-works'), 'UserController@storeWork');
            Route::put(homeRoute('profile/user-works/{id}'), 'UserController@updateWork');
            Route::delete(homeRoute('profile/user-works/{id}'), 'UserController@destroyWork');
            Route::post(homeRoute('profile/user-educations'), 'UserController@storeEducation');
            Route::put(homeRoute('profile/user-educations/{id}'), 'UserController@updateEducation');
            Route::delete(homeRoute('profile/user-educations/{id}'), 'UserController@destroyEducation');
            Route::post(homeRoute('profile/user-certificates'), 'UserController@storeCertificate');
            Route::put(homeRoute('profile/user-certificates/{id}'), 'UserController@updateCertificate');
            Route::delete(homeRoute('profile/user-certificates/{id}'), 'UserController@destroyCertificate');

            Route::get(homeRoute('announcements'), 'AnnouncementController@index');
            Route::put(homeRoute('announcements/{id}'), 'AnnouncementController@update');

            Route::group([
                'middleware' => 'entrust:,create-articles'
            ], function () {
                Route::get(homeRoute('knowledge/articles/create'), 'ArticleController@create');
                Route::post(homeRoute('knowledge/articles'), 'ArticleController@store');
            });

            Route::group([
                'middleware' => 'entrust:teacher'
            ], function () {
                Route::get(homeRoute('teacher/sign-up/step/{step}'), 'TeacherController@getSignUpStep');
                Route::post(homeRoute('teacher/sign-up/step/{step}'), 'TeacherController@postSignUpStep');

                Route::get(homeRoute('profile/teacher-information'), 'TeacherController@getTeacherInformation');
                Route::put(homeRoute('profile/teacher-information'), 'TeacherController@updateTeacherInformation');
                Route::get(homeRoute('profile/teaching-time'), 'TeacherController@getTeachingTime');
                Route::put(homeRoute('profile/teaching-time'), 'TeacherController@updateTeachingTime');
                Route::get(homeRoute('profile/payment-information'), 'TeacherController@getPaymentInformation');
                Route::put(homeRoute('profile/payment-information'), 'TeacherController@updatePaymentInformation');
            });

            Route::group([
                'middleware' => 'entrust:teacher|student|supporter'
            ], function () {
                Route::get(homeRoute('opening-classrooms'), 'ClassroomController@indexOpening');
                Route::get(homeRoute('closed-classrooms'), 'ClassroomController@indexClosed');
            });

            Route::group([
                'middleware' => 'entrust:teacher|student|supporter|student_visor|manager|admin'
            ], function () {
                Route::get(homeRoute('classrooms/{id}'), 'ClassroomController@show');
            });

            Route::group([
                'middleware' => 'entrust:teacher|manager|admin'
            ], function () {
                Route::put(homeRoute('classrooms/{id}'), 'ClassroomController@update');
                Route::delete(homeRoute('class-times/{id}'), 'ClassTimeController@destroy');
            });

            Route::group([
                'middleware' => 'entrust:student'
            ], function () {
                Route::get(homeRoute('class-times/{id}/confirm'), 'ClassTimeController@confirm');
            });
        });

        Route::get('/', 'HomepageController@index');
        Route::get(homeRoute('user/sign-up'), 'UserController@signUp');
        Route::get(homeRoute('teacher/sign-up'), 'TeacherController@getSignUp');
        Route::post(homeRoute('teacher/sign-up'), 'TeacherController@postSignUp');
        Route::get(homeRoute('student/sign-up'), 'StudentController@getSignUp');
        Route::post(homeRoute('student/sign-up'), 'StudentController@postSignUp');
        Route::get(homeRoute('student/sign-up/step/{step}'), 'StudentController@getSignUpStep');
        Route::post(homeRoute('student/sign-up/step/{step}'), 'StudentController@postSignUpStep');

        Route::get(homeRoute('teachers'), 'TeacherController@index');
        Route::get(homeRoute('teachers/{id}'), 'TeacherController@show');

        Route::get(homeRoute('helps'), 'HelpController@index');
        Route::get(homeRoute('helps/{slug}'), 'HelpController@show');

        Route::get(homeRoute('knowledge'), 'KnowledgeController@index');
        Route::get(homeRoute('knowledge/articles'), 'ArticleController@index');
        Route::get(homeRoute('knowledge/articles/{slug}'), 'ArticleController@show');
        Route::get(homeRoute('knowledge/categories/{slug}'), 'ArticleController@indexCategory');
        Route::get(homeRoute('knowledge/authors/{id}'), 'ArticleController@indexAuthor');
    });

    Route::get(homeRoute('me/settings'), 'Admin\SettingsController@index');
    Route::put(homeRoute('me/settings'), 'Admin\SettingsController@update');
    Route::put(homeRoute('me/timezone'), 'Admin\SettingsController@updateTimezone');

    Route::group([
        'namespace' => 'Auth',
    ], function () {
        Route::get(homeRoute('auth/login'), 'LoginController@showLoginForm')->name('login');
        Route::post(homeRoute('auth/login'), 'LoginController@login');
        Route::get(homeRoute('auth/logout'), 'LoginController@logout')->name('logout');

        Route::get(homeRoute('auth/social/{provider}'), 'LoginController@redirectToSocialAuthProvider');
        Route::get(homeRoute('auth/social/callback/{provider}'), 'LoginController@handleSocialAuthProviderCallback');

        Route::get(homeRoute('auth/register'), 'RegisterController@showRegistrationForm')->name('register');
        Route::post(homeRoute('auth/register'), 'RegisterController@register');

        Route::get(homeRoute('auth/register/social'), 'RegisterController@showSocialRegistrationForm');
        Route::post(homeRoute('auth/register/social'), 'RegisterController@socialRegister');

        Route::get(homeRoute('auth/activate/{id}/{activation_code}'), 'ActivateController@getActivation')->where('id', '[0-9]+');
        Route::get(homeRoute('auth/inactive'), 'ActivateController@getInactive');
        Route::post(homeRoute('auth/inactive'), 'ActivateController@postInactive');

        Route::get(homeRoute('password/email'), 'ForgotPasswordController@getEmail')->name('password.request');
        Route::post(homeRoute('password/email'), 'ForgotPasswordController@postEmail')->name('password.email');
        Route::get(homeRoute('password/reset/{token}'), 'ResetPasswordController@getReset')->name('password.reset');
        Route::post(homeRoute('password/reset'), 'ResetPasswordController@postReset');
    });

    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::any(adminRoute('extra'), 'ViewController@extra');
        // my account
        Route::get(homeRoute('me/account'), 'Admin\AccountController@index');
        Route::put(homeRoute('me/account'), 'Admin\AccountController@update');
        // document
        Route::any(homeRoute('me/documents/connector'), 'DocumentController@getConnector');
        Route::any(homeRoute('me/documents/for/ckeditor'), 'DocumentController@forCkeditor');
        Route::any(homeRoute('me/documents/for/popup/{input_id}'), 'DocumentController@forPopup');

        #region Admin Role
        Route::group([
            'namespace' => 'Admin',
            'middleware' => 'entrust:,access-admin'
        ], function () {
            Route::get(homeRoute('admin'), 'DashboardController@index');
            Route::get(adminRoute('my-documents'), 'DocumentController@index');

            Route::group([
                'middleware' => 'entrust:admin'
            ], function () {
                //App Options
                Route::get(adminRoute('app-options'), 'AppOptionController@index');
                Route::get(adminRoute('app-options/{id}/edit'), 'AppOptionController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('app-options/{id}'), 'AppOptionController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('app-options/{id}'), 'AppOptionController@destroy')->where('id', '[0-9]+');
                //Extensions
                Route::get(adminRoute('extensions'), 'ExtensionController@index');
                Route::get(adminRoute('extensions/{name}/edit'), 'ExtensionController@edit');
                Route::put(adminRoute('extensions/{name}'), 'ExtensionController@update');
                //Widgets
                Route::get(adminRoute('widgets'), 'WidgetController@index');
                Route::post(adminRoute('widgets'), 'WidgetController@store');
                Route::get(adminRoute('widgets/{id}/edit'), 'WidgetController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('widgets/{id}'), 'WidgetController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('widgets/{id}'), 'WidgetController@destroy')->where('id', '[0-9]+');
                //Langs
                Route::get(adminRoute('ui-lang/php'), 'UiLangController@editPhp');
                Route::put(adminRoute('ui-lang/php'), 'UiLangController@updatePhp');
                Route::get(adminRoute('ui-lang/email'), 'UiLangController@editEmail');
                Route::put(adminRoute('ui-lang/email'), 'UiLangController@updateEmail');
                //Roles
                Route::get(adminRoute('user-roles'), 'RoleController@index');
                //Users
                Route::get(adminRoute('users'), 'UserController@index');
                Route::get(adminRoute('users/create'), 'UserController@create');
                Route::post(adminRoute('users'), 'UserController@store');
                Route::get(adminRoute('users/{id}/edit'), 'UserController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('users/{id}'), 'UserController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('users/{id}'), 'UserController@destroy')->where('id', '[0-9]+');
                //Link Categories
                Route::get(adminRoute('link-categories'), 'LinkCategoryController@index');
                Route::get(adminRoute('link-categories/create'), 'LinkCategoryController@create');
                Route::post(adminRoute('link-categories'), 'LinkCategoryController@store');
                Route::get(adminRoute('link-categories/{id}/edit'), 'LinkCategoryController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('link-categories/{id}'), 'LinkCategoryController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('link-categories/{id}'), 'LinkCategoryController@destroy')->where('id', '[0-9]+');
                Route::get(adminRoute('link-categories/{id}/sort'), 'LinkCategoryController@sort')->where('id', '[0-9]+');
                //Links
                Route::get(adminRoute('links'), 'LinkController@index');
                Route::get(adminRoute('links/create'), 'LinkController@create');
                Route::post(adminRoute('links'), 'LinkController@store');
                Route::get(adminRoute('links/{id}/edit'), 'LinkController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('links/{id}'), 'LinkController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('links/{id}'), 'LinkController@destroy')->where('id', '[0-9]+');
                //Media Categories
//                Route::get(adminRoute('media-categories'), 'MediaCategoryController@index');
//                Route::get(adminRoute('media-categories/create'), 'MediaCategoryController@create');
//                Route::post(adminRoute('media-categories'), 'MediaCategoryController@store');
//                Route::get(adminRoute('media-categories/{id}/edit'), 'MediaCategoryController@edit')->where('id', '[0-9]+');
//                Route::put(adminRoute('media-categories/{id}'), 'MediaCategoryController@update');
//                Route::delete(adminRoute('media-categories/{id}'), 'MediaCategoryController@destroy')->where('id', '[0-9]+');
//                Route::get(adminRoute('media-categories/{id}/sort'), 'MediaCategoryController@sort')->where('id', '[0-9]+');
                //Media
//                Route::get(adminRoute('media-items'), 'MediaController@index');
//                Route::get(adminRoute('media-items/create'), 'MediaController@create');
//                Route::post(adminRoute('media-items'), 'MediaController@store');
//                Route::get(adminRoute('media-items/{id}/edit'), 'MediaController@edit')->where('id', '[0-9]+');
//                Route::put(adminRoute('media-items/{id}'), 'MediaController@update')->where('id', '[0-9]+');
//                Route::delete(adminRoute('media-items/{id}'), 'MediaController@destroy')->where('id', '[0-9]+');
                //Announcements
                Route::get(adminRoute('announcements'), 'AnnouncementController@index');
                Route::get(adminRoute('announcements/create'), 'AnnouncementController@create');
                Route::post(adminRoute('announcements'), 'AnnouncementController@store');
                Route::get(adminRoute('announcements/{id}/edit'), 'AnnouncementController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('announcements/{id}'), 'AnnouncementController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('announcements/{id}'), 'AnnouncementController@destroy')->where('id', '[0-9]+');
            });

            Route::group([
                'middleware' => 'entrust:admin|editor'
            ], function () {
//                //Pages
//                Route::get(adminRoute('pages'), 'PageController@index');
//                Route::get(adminRoute('pages/create'), 'PageController@create');
//                Route::post(adminRoute('pages'), 'PageController@store');
//                Route::get(adminRoute('pages/{id}/edit'), 'PageController@edit')->where('id', '[0-9]+');
//                Route::put(adminRoute('pages/{id}'), 'PageController@update')->where('id', '[0-9]+');
//                Route::delete(adminRoute('pages/{id}'), 'PageController@destroy')->where('id', '[0-9]+');
                //Help Categories
                Route::get(adminRoute('help-categories'), 'HelpCategoryController@index');
                Route::get(adminRoute('help-categories/create'), 'HelpCategoryController@create');
                Route::post(adminRoute('help-categories'), 'HelpCategoryController@store');
                Route::get(adminRoute('help-categories/{id}/edit'), 'HelpCategoryController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('help-categories/{id}'), 'HelpCategoryController@update');
                Route::delete(adminRoute('help-categories/{id}'), 'HelpCategoryController@destroy')->where('id', '[0-9]+');
                Route::get(adminRoute('help-categories/{id}/sort'), 'HelpCategoryController@sort')->where('id', '[0-9]+');
                //Help
                Route::get(adminRoute('helps'), 'HelpController@index');
                Route::get(adminRoute('helps/create'), 'HelpController@create');
                Route::post(adminRoute('helps'), 'HelpController@store');
                Route::get(adminRoute('helps/{id}/edit'), 'HelpController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('helps/{id}'), 'HelpController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('helps/{id}'), 'HelpController@destroy')->where('id', '[0-9]+');
                //Article Categories
                Route::get(adminRoute('article-categories'), 'ArticleCategoryController@index');
                Route::get(adminRoute('article-categories/create'), 'ArticleCategoryController@create');
                Route::post(adminRoute('article-categories'), 'ArticleCategoryController@store');
                Route::get(adminRoute('article-categories/{id}/edit'), 'ArticleCategoryController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('article-categories/{id}'), 'ArticleCategoryController@update');
                Route::delete(adminRoute('article-categories/{id}'), 'ArticleCategoryController@destroy')->where('id', '[0-9]+');
                //Articles
                Route::get(adminRoute('published-articles'), 'ArticleController@indexPublished');
                Route::get(adminRoute('teacher-articles'), 'ArticleController@indexTeacher');
                Route::get(adminRoute('articles'), 'ArticleController@index');
                Route::get(adminRoute('articles/create'), 'ArticleController@create');
                Route::post(adminRoute('articles'), 'ArticleController@store');
                Route::get(adminRoute('articles/{id}/edit'), 'ArticleController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('articles/{id}'), 'ArticleController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('articles/{id}'), 'ArticleController@destroy')->where('id', '[0-9]+');
            });

            Route::group([
                'middleware' => 'entrust:admin|manager'
            ], function () {
                //Study Level
                Route::get(adminRoute('study-levels'), 'StudyLevelController@index');
                Route::get(adminRoute('study-levels/create'), 'StudyLevelController@create');
                Route::post(adminRoute('study-levels'), 'StudyLevelController@store');
                Route::get(adminRoute('study-levels/{id}/edit'), 'StudyLevelController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('study-levels/{id}'), 'StudyLevelController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('study-levels/{id}'), 'StudyLevelController@destroy')->where('id', '[0-9]+');
                //Study Problem
                Route::get(adminRoute('study-problems'), 'StudyProblemController@index');
                Route::get(adminRoute('study-problems/create'), 'StudyProblemController@create');
                Route::post(adminRoute('study-problems'), 'StudyProblemController@store');
                Route::get(adminRoute('study-problems/{id}/edit'), 'StudyProblemController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('study-problems/{id}'), 'StudyProblemController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('study-problems/{id}'), 'StudyProblemController@destroy')->where('id', '[0-9]+');
                //Study Course
                Route::get(adminRoute('study-courses'), 'StudyCourseController@index');
                Route::get(adminRoute('study-courses/create'), 'StudyCourseController@create');
                Route::post(adminRoute('study-courses'), 'StudyCourseController@store');
                Route::get(adminRoute('study-courses/{id}/edit'), 'StudyCourseController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('study-courses/{id}'), 'StudyCourseController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('study-courses/{id}'), 'StudyCourseController@destroy')->where('id', '[0-9]+');
                //Professional Skills
                Route::get(adminRoute('professional-skills'), 'ProfessionalSkillController@index');
                Route::get(adminRoute('professional-skills/create'), 'ProfessionalSkillController@create');
                Route::post(adminRoute('professional-skills'), 'ProfessionalSkillController@store');
                Route::get(adminRoute('professional-skills/{id}/edit'), 'ProfessionalSkillController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('professional-skills/{id}'), 'ProfessionalSkillController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('professional-skills/{id}'), 'ProfessionalSkillController@destroy')->where('id', '[0-9]+');
                //Topic
                Route::get(adminRoute('topics'), 'TopicController@index');
                Route::get(adminRoute('topics/create'), 'TopicController@create');
                Route::post(adminRoute('topics'), 'TopicController@store');
                Route::get(adminRoute('topics/{id}/edit'), 'TopicController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('topics/{id}'), 'TopicController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('topics/{id}'), 'TopicController@destroy')->where('id', '[0-9]+');
                //Student Agents
                Route::get(adminRoute('student-agents'), 'StudentAgentController@index');
                Route::get(adminRoute('student-agents/create'), 'StudentAgentController@create');
                Route::post(adminRoute('student-agents'), 'StudentAgentController@store');
                Route::get(adminRoute('student-agents/{id}/edit'), 'StudentAgentController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('student-agents/{id}'), 'StudentAgentController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('student-agents/{id}'), 'StudentAgentController@destroy')->where('id', '[0-9]+');
                //Teacher
                Route::get(adminRoute('approved-teachers'), 'TeacherController@indexApproved');
                Route::get(adminRoute('registering-teachers'), 'TeacherController@indexRegistering');
                Route::get(adminRoute('teachers/create'), 'TeacherController@create');
                Route::post(adminRoute('teachers'), 'TeacherController@store');
                Route::get(adminRoute('teachers/{id}/edit'), 'TeacherController@edit')->where('id', '[0-9]+');
                Route::get(adminRoute('teachers/{id}/review'), 'TeacherController@review')->where('id', '[0-9]+');
                Route::put(adminRoute('teachers/{id}'), 'TeacherController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('teachers/{id}'), 'TeacherController@destroy')->where('id', '[0-9]+');
                //Student
                Route::get(adminRoute('students/create'), 'StudentController@create');
                Route::post(adminRoute('students'), 'StudentController@store');
                Route::get(adminRoute('students/{id}/edit'), 'StudentController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('students/{id}'), 'StudentController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('students/{id}'), 'StudentController@destroy')->where('id', '[0-9]+');
                //Classroom
                Route::get(adminRoute('classrooms/create'), 'ClassroomController@create');
                Route::post(adminRoute('classrooms'), 'ClassroomController@store');
                Route::get(adminRoute('classrooms/{id}/edit'), 'ClassroomController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('classrooms/{id}'), 'ClassroomController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('classrooms/{id}'), 'ClassroomController@destroy')->where('id', '[0-9]+');
                //Salary
                Route::get(adminRoute('salary-report'), 'SalaryReportController@index');
                Route::post(adminRoute('salary-report'), 'SalaryReportController@store');
                //Register learning request
                Route::put(adminRoute('learning-requests/{id}'), 'LearningRequestController@update');
            });

            Route::group([
                'middleware' => 'entrust:admin|manager|student_visor'
            ], function () {
                //Student
                Route::get(adminRoute('approved-students'), 'StudentController@indexApproved');
                Route::get(adminRoute('registering-students'), 'StudentController@indexRegistering');
                //Classroom
                Route::get(adminRoute('opening-classrooms'), 'ClassroomController@indexOpening');
                Route::get(adminRoute('closed-classrooms'), 'ClassroomController@indexClosed');
                Route::get(adminRoute('ready-classrooms'), 'ClassroomController@indexReady');
                //Register learning request
                Route::get(adminRoute('register-learning-requests'), 'LearningRequestController@indexRegistering');
                Route::get(adminRoute('processed-learning-requests'), 'LearningRequestController@indexProcessed');
            });

            Route::group([
                'middleware' => 'entrust:admin|manager|student_agent'
            ], function () {
                Route::get(adminRoute('student-agents/{id}/students'), 'StudentAgentController@students')->where('id', '[0-9]+');
            });
        });
        #endregion
    });
});

Route::get('*', function() {return "Invalid"; });
