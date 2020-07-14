<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Http\Request;

class UiLangController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'ui_lang';
    }

    public function editPHP(Request $request)
    {
        $file_locale = $request->input('file_locale', 'en');
        $file_name = $request->input('file_name', 'validation');

        $storage = Storage::disk('lang');
        $file_path = str_replace(['../', '..'], '', $file_locale . '/' . $file_name . '.php');
        if (!$storage->exists($file_path)) {
            abort(404);
        }
        $file_content = $storage->get($file_path);

        $php_files = [];
        foreach (allSupportedLocales() as $localeCode => $properties) {
            if (!$storage->exists($localeCode)) continue;
            $locale = $localeCode . ' (' . $properties['native'] . ')';
            $php_files[$locale] = [];
            $files = $storage->files($localeCode);
            foreach ($files as $file) {
                $php_files[$locale][] = [
                    'locale' => $localeCode,
                    'file' => preg_replace('/(^' . $localeCode . '\/|\.php$)/', '', $file)
                ];
            }
        }

        $this->_title(trans('pages.admin_ui_lang_php_title'));
        $this->_description(trans('pages.admin_ui_lang_php_desc'));

        return $this->_any('php', [
            'file_locale' => $file_locale,
            'file_name' => $file_name,
            'file_content' => $file_content,
            'files' => $php_files,
        ]);
    }

    public function updatePHP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_locale' => 'required',
            'file_name' => 'required',
            'file_content' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect(adminUrl('ui-lang/php'))
                ->withErrors($validator);
        }
        $file_path = str_replace(['../', '..'], '', $request->input('file_locale') . '/' . $request->input('file_name') . '.php');
        $storage = Storage::disk('lang');
        if (!$storage->exists($file_path)) {
            abort(404);
        } else {
            $storage->put($file_path, $request->input('file_content'));
        }

        return redirect(adminUrl('ui-lang/php') . '?file_locale=' . $request->input('file_locale') . '&file_name=' . $request->input('file_name'))
            ->with('response', trans('error.success'));
    }

    public function editEmail(Request $request)
    {
        $file_locale = $request->input('file_locale', 'en');
        $file_name = $request->input('file_name', 'welcome');

        $storage = Storage::disk('email');
        $file_path = str_replace(['../', '..'], '', $file_name . '/' . $file_locale . '.blade.php');
        if (!$storage->exists($file_path)) {
            abort(404);
        }
        $file_content = $storage->get($file_path);

        $php_files = [];
        $supportedLocaleCodes = allSupportedLocaleCodes();
        foreach ($storage->directories() as $directory) {
            $files = $storage->files($directory);
            foreach ($files as $file) {
                $localeCode = preg_replace('/(^' . $directory . '\/|\.blade\.php$)/', '', $file);
                if (in_array($localeCode, $supportedLocaleCodes)) {
                    $locale = $localeCode . ' (' . allSupportedLocale($localeCode, 'native') . ')';
                    $php_files[$locale][] = [
                        'locale' => $localeCode,
                        'file' => $directory
                    ];
                }
            }
        }

        $this->_title(trans('pages.admin_ui_lang_email_title'));
        $this->_description(trans('pages.admin_ui_lang_email_desc'));

        return $this->_any('email', [
            'file_locale' => $file_locale,
            'file_name' => $file_name,
            'file_content' => $file_content,
            'files' => $php_files,
        ]);
    }

    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_locale' => 'required',
            'file_name' => 'required',
            'file_content' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect(adminUrl('ui-lang/email'))
                ->withErrors($validator);
        }
        $storage = Storage::disk('email');
        $file_path = str_replace(['../', '..'], '', $request->input('file_name') . '/' . $request->input('file_locale') . '.blade.php');
        if (!$storage->exists($file_path)) {
            abort(404);
        } else {
            $storage->put($file_path, $request->input('file_content'));
        }

        return redirect(adminUrl('ui-lang/email') . '?file_locale=' . $request->input('file_locale') . '&file_name=' . $request->input('file_name'))
            ->with('response', trans('error.success'));
    }
}
