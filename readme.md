# Katniss (A Laravel-based Framework)

Katniss extends the Laravel Framework by:

- Integrating with existed useful external solutions from communities
- Developing new helpful modules for customization 

The destination of Katniss is to help to build website more faster:

- Many common problems are solved and solutions are integrated.
- Lots of business flows, functions and features are ready to use and easily to modify

## Installation

### Environment file

Duplicate the file named `.env.example` at the root directory of the application and change the name of the duplicated file to `.env`.

Change some configuration as you want.

Enable write permission for some directories:

```
chmod -R 777 bootstrap/cache
chmod -R 777 storage
chmod -R 777 public/assets/cache
chmod -R 777 public/files
chmod -R 777 public/upload
```

### PHP

Require PHP >= `5.6.4`.

Current version: `7.0.9`.

### Database

MySQL is recommended.

Require MySQL >= `5.5`.

Current version: `MariaDB 10.1.16`.

Current database settings (in the `.env` file) of the framework require you to make those configuration in MySQL (`my.ini` or `my.cnf` file):

```php
innodb_file_format=BARRACUDA
innodb_large_prefix=ON
```

Those settings require to create database `katniss` with `default character set utf8mb4` and `default collate utf8mb4_unicode_ci`

After creating, you should initialize database by running these commands when in root directory of the application:

`composer install`

`php artisan migrate --seed`

**Notice:**

If you change the current database settings (in the `.env` file) to:

```php
DB_CHARSET=utf8
DB_COLLATION=utf8_unicode_ci
```

Then, you don't have to change MySQL configuration.

Therefore, you must create database `katniss` with `default character set utf8` and `default collate utf8_unicode_ci`

Furthermore, you must go through all the migrating files in directory `root\database\migrations` and delete these lines when you see them in the code:

```php
$table->engine = 'InnoDB';
$table->rowFormat = 'DYNAMIC';
```

## Components and Modules

### Vendors

#### laravel/laravel

[Laravel PHP Framework](https://github.com/laravel/laravel)

Current version: `5.4.21`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)

Base framework.

Customization:

- Database:
    - Enable schema for MySQL database to support row format when creating tables.
- Session:
    - Extend database session handler for further purpose (currently session is based on this handler).
    - Fix file session handler bugs may remove session of user when happening many concurrent AJAX requests.
- Support:
    - Extend Str class for new methods to operate string.

#### barryvdh/laravel-debugbar

[Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)

Current version: `2.3.2`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-debugbar/version.png)](https://packagist.org/packages/barryvdh/laravel-debugbar)

For debugging.

#### barryvdh/laravel-elfinder

[elFinder Package for Laravel 5](https://github.com/barryvdh/laravel-elfinder)

Current version: `0.3.10`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-elfinder/version.png)](https://packagist.org/packages/barryvdh/laravel-elfinder)

For file managing/uploading.

#### jenssegers/agent

[Agent](https://github.com/jenssegers/agent)

Current version: `2.5.1`.

Latest version:

[![Latest Stable Version](http://img.shields.io/packagist/v/jenssegers/agent.svg)](https://packagist.org/packages/jenssegers/agent) 

For detecting client.

#### laravel/socialite

[Laravel Socialite](https://github.com/laravel/socialite)

Current version: `3.0.6`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/laravel/socialite/v/stable.svg)](https://packagist.org/packages/laravel/socialite)

For logging in & registering from social networks.

Customization:

- Change Facebook provider to get large avatar of authenticated user.

#### mews/purifier

[HTMLPurifier for Laravel 5](https://github.com/mewebstudio/Purifier)

Current version: `2.0.7`.

For filtering HTML content.

#### dimsav/laravel-translatable

[Laravel-Translatable](https://github.com/dimsav/laravel-translatable)

Current version: `7.0`.

Latest version:

[![Latest Stable Version](http://img.shields.io/packagist/v/dimsav/laravel-translatable.svg)](https://packagist.org/packages/dimsav/laravel-translatable)

For multilingual models (Database Entities & App Models).

#### zizaco/entrust

[ENTRUST (Laravel 5 Package)](https://github.com/Zizaco/entrust)

Current version: `1.8.0`.

Latest version:

[![Version](https://img.shields.io/packagist/v/Zizaco/entrust.svg)](https://packagist.org/packages/zizaco/entrust)

To authorize users with roles and permissions.

Customization:

- Middleware to authorize routes was created.
- Force not to use the method `Cache::tags` in workflow.
    - If you plan to use the cache drivers different from file and database, you should remove this customization.
    - See more at [Cache Tags](https://laravel.com/docs/5.3/cache#cache-tags).

#### mcamara/laravel-localization

[Laravel Localization](https://github.com/mcamara/laravel-localization)

Current version: `1.2.4`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/mcamara/laravel-localization/version.png)](https://packagist.org/packages/mcamara/laravel-localization) 

For localizing (esp. with URL).

Customization:

- Fix some bugs:
    - Localizing url gets wrong result when the url contains query or hash string.
    - Setting wrong locale when locale from browser is not similar to locale from path.

#### larabros/elogram

[elogram](https://github.com/larabros/elogram/)

Current version: `1.2.2`.

Latest version:

[![Latest Version on Packagist](https://img.shields.io/packagist/v/larabros/elogram.svg?style=flat-square
)](https://packagist.org/packages/larabros/elogram)

For fetching data from Instagram API.

#### thunderer/shortcode

[Shortcode](https://github.com/thunderer/Shortcode)

Current version: `0.6.5`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/thunderer/shortcode/v/stable.svg)](https://packagist.org/packages/thunderer/shortcode)

For parsing short codes.

#### almasaeed2010/AdminLTE

[AdminLTE](https://github.com/almasaeed2010/AdminLTE)

Current version: `2.3.2`.

Latest version:

![Bower version](https://img.shields.io/bower/v/adminlte.svg)

For admin template.

#### IronSummitMedia/startbootstrap-scrolling-nav

[Start Bootstrap - Scrolling Nav](https://github.com/IronSummitMedia/startbootstrap-scrolling-nav)

Current version: `1.0.4`.

For home template.

### API Definition

Applications get data in JSON format by making requests to API urls.

#### (App) API

For data requesting from external applications (external requests).

Requests have no any sessions & cookies for storing (stateless).

An application which makes requests must be registered then gets its own secret key.

Applications access data by providing their known secret keys in every sending requests.

Request flow:

- Request > API Middleware (`ApiMiddleware`) > API Controller > Repository > Model > Response (JSON format).

There's always an default application needed to register with `id = 1`:

- See `database\seeds\DefaultSeeder.php`, line 78.

#### Web API

For data requesting from internal applications like AJAX requests, .. (internal requests).

Requests share sessions & cookies between connections (stateful).

Request flow:

- Request > Web Middleware (`WebApiMiddleware`) > Web API Controller > Repository > Model > Response (JSON format).

### Action Hooking

For hooking into existing codes.

#### Content Filter

For changing value of variables.

#### Content Place

For placing value together.

#### Hook

For hooking to a context of coding.

#### Trigger

For triggering only one in an available set of actions.

### Extra Routes

Based on action hooking of Trigger.

For processing extra routes defined by theme or plugins.

See coding of theme `Example Theme` or extensions `Contact Form`, `Polls` or `Google Maps Marker` for more.

### Short Codes

Parser integrated.

- See vendor [thunder/shortcode](#thunderershortcode).
- See example coding in `Google Maps Marker` & `Galleries` extensions.
- Must be enabled in `App Settings` extension in order to work, default is disabled.

### App Options

To store/retrieve runtime application's options in database.

### Theme and Plugin Definition

#### Theme

To organize templates into themes for easily developing/extending.

Themes can be extended by plugins.

Request flow:

- Request > View Middleware (`ViewMiddleware`) > View Controller > Repository > Model > Theme View.

##### Admin Theme

Themes for administration.

Request flow:

- Request > View Middleware (`ViewMiddleware`) > Admin Controller > Repository > Model > Admin Theme View.

Sample Themes:

- Admin LTE (base on [AdminLTE](#almasaeed2010adminlte)).

##### Home Theme

Themes for business.

Can extend admin themes by Extra Routes.

Request flow:

- Request > View Middleware (`ViewMiddleware`) > Home Controller > Repository > Model > Home Theme View.
    
Sample Themes:

- Example Theme (base on [Start Bootstrap - Scrolling Nav](#ironsummitmediastartbootstrap-scrolling-nav)).
    - Admin Options Page for editing theme options:
        - Set default map marker for theme.
    - Specific template for post detail instead of default template:
        - Post contact template: display a contact form and a default map marker at bottom of page.
        - See [Template](#template) for more.

#### Plugins

Defines plugins for the site, which are extensions and widgets.

Plugins can be common or theme-based.

Widgets can be extension-based.
    
##### Extension

Define extensions for adding extra functions/features to themes (or even the system).

Can extend admin themes by Extra Routes.

Extensions can share its data to other components.

Sample Extensions:

- App Settings:
    - Be always activated.
    - Configure some app settings.
        - Enable user registration.
        - Set default category for Articles.
        - Enable short code functionality to work.
- Open Graph Tags:
    - Be always activated.
    - Add open graph tags into website.
- Analytic Services:
    - Add website analytics.
    - Current: Google, MixPanel.
- Social Integration:
    - Integrate website into social networks.
    - Current: Facebook, Twitter, Google, LinkedIn, Instagram.
    - Log-in enable: Facebook, Google.
- Currency Exchange:
    - Allow user to configure the exchange rates to automatically convert to/from any currencies.
- Contact Form:
    - Allow user to embed contact forms to website and manage contact form request data.
- Polls:
    - Allow user to and manage polls and embed polls to website.
- Google Maps Markers:
    - Enable to manage and embed maps with markers into website.
    - Support short code: `[map_marker id="{id of a map marker}"]`.
        - To see example, try to insert it into a post content.
- Galleries:
    - Enable to embed galleries to websites.
    - Support short code: `[gallery id="{id of a media category}"]`.
        - To see example, try to insert it into a post content.

##### Widget

Define widgets of content for inserting into placeholders of home themes.

Widgets in a placeholder are sortable; their orders can be changed.

To edit list of placeholders, please edit the method `placeholders` in the class of home theme.

Sample Widgets:

- Extra HTML:
    - Add HTML content to website.
- Base Links:
    - Add collection of links to website.
- Instagram Wall:
    - Only available when Social Integration extension is activated and integration with Instagram is enabled.
    - Display wall of images from Instagram user.
- Pages:
    - Show list of available pages.
- Article Categories:
    - Show list of categories of articles.
- Contact Form:
    - Show contact form.
- Poll:
    - Show poll for voting and viewing result on website.
- Google Maps Marker:
    - Show map marker by Google Maps on website.
- Gallery:
    - Show photos media on website.
    
#### Template

##### Page

Define custom templates for content of pages.

As usual, we have a common template for displaying pages. This functionality helps us to replace this common template with other custom template for some specific pages.
 
To edit list of custom templates, please edit the method `pageTemplates` in the class of home theme.

##### Article

Define custom templates for content of articles.

As usual, we have a common template for displaying articles. This functionality helps us to replace this common template with other custom template for some specific articles.

To edit list of custom templates, please edit the method `articleTemplates` in the class of home theme.

### Authentication

Functions/Features:

- Registration.
    - Must go editing App Settings extension and enable user registration.
- Activation.
- Login.
- Logout.
- Forgot/Reset Password.
- Register/Login using Accounts on Social Networks (Facebook, Google).
    - Must enable Social Integration extension and enable log-in functionality.
- Email supported (for registering, resending activation & password resetting & password changed):
    - Emails for registering & password changed are queued before sending.
    - Configure a queue worker or `php artisan queue:work` for sending queued emails (see [Running The Queue Worker](https://laravel.com/docs/5.3/queues#running-the-queue-worker)).
- Lock screen supported, when user is idle while accessing admin page.
- Update account information.

### Authorization

To authorize users with roles & permissions.

Mainly based on [zizaco/entrust](#zizacoentrust).

Database seeding:

- Access admin permission.
- Owner role (has permission of Accessing Admin).
- Administrators role (has permission of Accessing Admin).
- Tester role (has permission of Accessing Admin).
- User role.
- 3 starting users (with default settings): 
    - Name: `owner`. 
        - Password: `^KM$bB-W7:Z@8eG`.
        - Role: `Owner`.
    - Name: `admin`.
        - Password: `123456`.
        - Role: `Owner`, `Administrator`, 
    - Name: `tester`.
        - Password: `123456`.
        - Role: `Tester`.

Anonymous users will get the role of User after registering.

### User Settings

To store customized settings of each user.

For anonymous users, settings are saved in cookie & session.

For authenticated users, settings are saved in database & session & cookie.

### File Manager

For users to easily upload & manage files.

Based on [barryvdh/laravel-elfinder](#barryvdhlaravel-elfinder).

Ready for integrating with:

- CKEditor.
- Input field.

Users cannot delete folder `profile_pictures` in their own directory.

### Links

Manage links and categories of links.

Links in a category can be sorted.

### Posts

Manage pages and articles & categories of articles.

Pages and articles can have their own custom templates, depending on definition of page & post templates of home themes.

- See [Template of Home Theme](#template).

There's always a default category needs to be set for articles. 

- For seeding information, please see `database/seeds/DefaultSeeder.php`, line 97.

### Media

Manage media (photos, videos) and categories of them.

Media in a category can be sorted.

### Conversation

Users can send message to each others in a conversation.

Support real-time messaging, based on [Realtime.co Messaging Service](https://framework.realtime.co/messaging/).

Conversation is designed to easily embed into view (through HTML tag: `iframe`).

Conversation can be type of:

- 1-to-1: Messaging between 2 users.
- Group: Messaging among a group of users.
- Public: Messaging among everyone.
- Support: Messaging between 1 anonymous vs 1 user