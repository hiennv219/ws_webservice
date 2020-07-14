const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.combine([
    'resources/assets/js/js.cookie.js',
    'resources/assets/js/helpers.js',
    'resources/assets/js/class.number_format_helper.js',
    'resources/assets/js/class.katniss_api.js',
    'resources/assets/js/refresh_session.js',
    'resources/assets/js/gui.jquery.js'
], 'public/assets/libraries/katniss.home.js');

mix.combine([
    'resources/assets/js/js.cookie.js',
    'resources/assets/js/helpers.js',
    'resources/assets/js/slug.js',
    'resources/assets/js/slug.jquery.js',
    'resources/assets/js/class.number_format_helper.js',
    'resources/assets/js/class.katniss_api.js',
    'resources/assets/js/refresh_session.js',
    'resources/assets/js/gui.jquery.js'
], 'public/assets/libraries/katniss.admin.js');

mix.combine([
    'resources/assets/js/realtime.pusher.js',
    'resources/assets/js/sounds.js',
    'resources/assets/js/conversation.js'
], 'public/assets/libraries/katniss.conversation.js');
mix.styles(['resources/assets/css/conversation.css'], 'public/assets/libraries/katniss.conversation.css');

mix.combine(['resources/assets/css/modal_cropper_image.css'], 'public/assets/libraries/modal_cropper_image.css');
mix.scripts(['resources/assets/js/modal_cropper_image.js'], 'public/assets/libraries/modal_cropper_image.js');

mix.combine([
    'resources/assets/js/google_maps_markers.js'
], 'public/assets/libraries/google_maps_markers.js');

mix.combine([
    'resources/assets/css/wow_skype/common.css',
    'resources/assets/css/wow_skype/nav_wizard.css',
    'resources/assets/css/wow_skype/header.css',
    'resources/assets/css/wow_skype/main.css',
    'resources/assets/css/wow_skype/footer.css',
    'resources/assets/css/wow_skype/page.home.css',
    'resources/assets/css/wow_skype/page.sign_up.css',
    'resources/assets/css/wow_skype/page.teacher_sign_up.css',
    'resources/assets/css/wow_skype/page.profile_account_information.css',
    'resources/assets/css/wow_skype/page.teachers.css',
    'resources/assets/css/wow_skype/page.student_sign_up.css',
    'resources/assets/css/wow_skype/page.classrooms.css',
    'resources/assets/css/wow_skype/page.helps.css',
    'resources/assets/css/wow_skype/page.articles.css',
    'resources/assets/css/wow_skype/page.announcements.css'
], 'public/assets/home_themes/wow_skype/css/style.css');