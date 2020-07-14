<?php

use Illuminate\Database\Seeder;
use Katniss\Everdeen\Models\AppOption;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Models\Permission;
use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Models\Student;
use Katniss\Everdeen\Models\Teacher;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserApp;
use Katniss\Everdeen\Models\UserSetting;

class DefaultSeeder extends Seeder
{
    public function run()
    {
        $admin_access_permission = Permission::create([
            'name' => 'access-admin',
            'display_name' => 'Access admin',
            'description' => 'Access admin pages'
        ]);
        $publish_articles_permission = Permission::create([
            'name' => 'publish-articles',
            'display_name' => 'Publish articles',
            'description' => 'Publish articles'
        ]);
        $create_articles_permission = Permission::create([
            'name' => 'create-articles',
            'display_name' => 'Create articles',
            'description' => 'Create articles'
        ]);

        $owner_role = Role::create(array(
            'name' => 'owner',
            'display_name' => 'Owner',
            'description' => 'Owner of the system',
            'status' => Role::STATUS_HIDDEN,
        ));
        $owner_role->attachPermission($admin_access_permission);

        $admin_role = Role::create(array(
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Manage operation of the system and important modules'
        ));
        $admin_role->attachPermission($admin_access_permission);
        $admin_role->attachPermission($publish_articles_permission);
        $admin_role->attachPermission($create_articles_permission);

        $tester_role = Role::create(array(
            'name' => 'tester',
            'display_name' => 'Tester',
            'description' => 'Tester of the system',
            'status' => Role::STATUS_HIDDEN,
        ));
        $tester_role->attachPermission($admin_access_permission);

        $user_role = Role::create(array(
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Normal user'
        ));

        $manager_role = Role::create(array(
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Manager'
        ));
        $manager_role->attachPermission($admin_access_permission);

        $student_visor_role = Role::create(array(
            'name' => 'student_visor',
            'display_name' => 'Student-visor',
            'description' => 'Student-visor'
        ));
        $student_visor_role->attachPermission($admin_access_permission);

        $editor_role = Role::create(array(
            'name' => 'editor',
            'display_name' => 'Editor',
            'description' => 'Editor'
        ));
        $editor_role->attachPermission($admin_access_permission);
        $editor_role->attachPermission($publish_articles_permission);
        $editor_role->attachPermission($create_articles_permission);

        $teacher_role = Role::create(array(
            'name' => 'teacher',
            'display_name' => 'Teacher',
            'description' => 'Teacher'
        ));
        $teacher_role->attachPermission($create_articles_permission);

        $student_role = Role::create(array(
            'name' => 'student',
            'display_name' => 'Student',
            'description' => 'Student'
        ));

        $supporter_role = Role::create(array(
            'name' => 'supporter',
            'display_name' => 'Supporter',
            'description' => 'Supporter'
        ));
        $supporter_role->attachPermission($admin_access_permission);

        $student_agent_role = Role::create(array(
            'name' => 'student_agent',
            'display_name' => 'Student Agent',
            'description' => 'Student Agent'
        ));
        $student_agent_role->attachPermission($admin_access_permission);

        // TODO: Add 1 administrator
        $setting = UserSetting::create();
        $owner = User::create(array(
            'display_name' => 'Owner',
            'name' => 'owner',
            'email' => 'owner@katniss.linhntaim.com',
            'password' => bcrypt(')^KM$bB-W7:Z@8eG'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $owner->attachRole($owner_role);

        $setting = UserSetting::create();
        $admin = User::create(array(
            'display_name' => 'Administrator',
            'name' => 'admin',
            'email' => 'admin@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $admin->attachRoles([$admin_role, $owner_role]);

        UserApp::create([
            'user_id' => $admin->id,
            'secret' => str_random(32),
            'name' => 'Katniss Web',
            'version' => 'v1',
        ]);

        $setting = UserSetting::create();
        $tester = User::create(array(
            'display_name' => 'Tester',
            'name' => 'tester',
            'email' => 'tester@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $tester->attachRole($tester_role);

        $setting = UserSetting::create();
        $editor = User::create(array(
            'display_name' => 'Editor',
            'name' => 'editor',
            'email' => 'editor@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $editor->attachRole($editor_role);

        $setting = UserSetting::create();
        $manager = User::create(array(
            'display_name' => 'Manager',
            'name' => 'manager',
            'email' => 'manager@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $manager->attachRole($manager_role);

        $setting = UserSetting::create();
        $supporter = User::create(array(
            'display_name' => 'Supporter',
            'name' => 'supporter',
            'email' => 'supporter@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $supporter->attachRole($supporter_role);

        $setting = UserSetting::create();
        $teacher = User::create(array(
            'display_name' => 'Teacher',
            'name' => 'teacher',
            'email' => 'teacher@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $teacher->attachRole($teacher_role);
        Teacher::create([
            'user_id' => $teacher->id,
        ]);

        $setting = UserSetting::create();
        $student = User::create(array(
            'display_name' => 'Student',
            'name' => 'student',
            'email' => 'student@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $student->attachRole($student_role);
        Student::create([
            'user_id' => $student->id,
        ]);

        AppOption::create([
            'key' => 'admin_theme',
            'rawValue' => Katniss\Everdeen\Themes\AdminThemes\AdminLte\Theme::NAME,
            'data_type' => 'string',
            'registered_by' => 'theme:admin',
        ]);
        AppOption::create([
            'key' => 'home_theme',
            'rawValue' => Katniss\Everdeen\Themes\HomeThemes\WowSkype\Theme::NAME,
            'data_type' => 'string',
            'registered_by' => 'theme:home',
        ]);

        $locales = ['en', 'vi'];
        $category = new Category();
        $category->type = Category::TYPE_ARTICLE;
        $data = [
            'en' => [
                'name' => 'Uncategorized',
                'slug' => 'uncategorized',
                'description' => 'For uncategorized articles',
            ],
            'vi' => [
                'name' => 'Chuyên mục chung',
                'slug' => 'chuyen-muc-chung',
                'description' => 'Chứa các chuyên đề không có chuyên mục',
            ]
        ];
        foreach ($locales as $locale) {
            $transData = $data[$locale];
            $trans = $category->translateOrNew($locale);
            $trans->name = $transData['name'];
            $trans->slug = $transData['slug'];
            $trans->description = $transData['description'];
        }
        $category->save();
        AppOption::create([
            'key' => 'extension_app_settings',
            'rawValue' => '{"register_enable":"0","default_article_category":"' . $category->id . '"}',
            'data_type' => 'array',
            'registered_by' => 'ext:app_settings',
        ]);
    }
}
