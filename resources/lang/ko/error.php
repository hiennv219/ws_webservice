<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-05
 * Time: 13:52
 */
return [
    'unknown' => 'Unknown',
    'token_mismatch' => 'Your request is not recognized by the system. Try again.',
    'database_insert' => 'Fails to insert into database',
    'database_update' => 'Fails to update database',
    'database_delete' => 'Fails to delete from database',
    'application' => 'Fails to process',
    'fail_ajax' => 'Fails to request AJAX',
    'success' => 'Success!',
    'fail' => 'Fail!',
    'fail_account_activate' => 'Your account activation gets failed.',
    '_success_account_activate' => 'Thank you. Your account is now activated. Go to <a href=":url">your starting page</a>.',
    'fail_social_get_info' => 'Fails to get information from social network.',
    'is_current_user' => 'You are this current user',
    'is_role_owner' => 'This user is owner of the system',
    'is_not_author' => 'You are not the author',
    'is_not_role_supporter' => 'The user is not a supporter',
    '_is_not_author' => 'You are not the author of :object',
    '_cannot_delete' => 'Cannot delete due to: :reason',
    'category_not_empty' => 'Category is not empty',
    'category_delete_default' => 'Cannot delete default category',
    'default_locale_inputs_must_be_set' => 'If you don\'t fill multiple locale form for all languages, please fill in default tab',
    'elfinder_rm_not_allowed' => 'You are not allowed to delete it',
    'payment_info' => 'Please fill all required fields',
    'cannot_remove_current_student_role' => 'Cannot remove student role. Please delete the student profile first.',
    'cannot_remove_current_teacher_role' => 'Cannot remove teacher role. Please delete the teacher profile first.',
    'change_password_failed' => 'Failed to change password. Try again.',
    'add_class_time_failed' => 'Failed to add class time. Try again.',
    'new_class_time_must_before_last_class_time' => 'Current adding class time must start after the last class time.',
    'classroom_has_enough_time' => 'Current classroom has been spent all of its time.',
    'teacher_add_review_failed' => 'Cannot add teacher feedback',
    'student_add_review_failed' => 'Cannot add student rating',
    'teacher_add_rich_review_failed' => 'Cannot add review for student',
    'student_add_rich_review_failed' => 'Cannot add review for teacher',
    'new_class_time_must_be_in_classroom_time' => 'Duration of class times must be not over classroom\'s duration. (Currently :overtime overtime)',
    'not_last_class_time' => 'Not current last class time',
];