<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\ExtensionsFacade;
use Katniss\Everdeen\Utils\AppOptionHelper;

class ExtensionController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'extension';
    }

    public function index(Request $request)
    {
        $extensionClasses = ExtensionsFacade::all();
        $extensions = [];
        foreach ($extensionClasses as $extensionClass) {
            $extension = new $extensionClass();
            $extensionName = $extension->getName();
            $extensions[] = [
                'name' => $extensionName,
                'display_name' => $extension->getDisplayName(),
                'description' => $extension->getDescription(),
                'editable' => $extension->isEditable(),
                'activated' => ExtensionsFacade::isActivated($extensionName),
                'static' => ExtensionsFacade::isStatic($extensionName),
            ];
        }

        $this->_title(trans('pages.admin_extensions_title'));
        $this->_description(trans('pages.admin_extensions_desc'));

        return $this->_index([
            'extensions' => $extensions,
        ]);
    }

    public function edit(Request $request, $name)
    {
        $extension = ExtensionsFacade::resolveClass($name);
        if (is_null($extension) || !$extension->isEditable()) {
            abort(404);
        }

        $this->_title([trans('pages.admin_extensions_title'), $extension->getDisplayName(), trans('form.action_edit')]);
        $this->_description($extension->getDescription());

        return $this->_edit(array_merge([
            'extension' => $extension,
            'extension_view' => $extension->viewAdmin(),
        ], $extension->viewAdminParams()));
    }

    public function update(Request $request, $name)
    {
        if ($request->has('activate')) {
            return $this->activate($request, $name);
        }
        if ($request->has('deactivate')) {
            return $this->deactivate($request, $name);
        }

        $extension = ExtensionsFacade::resolveClass($name);
        if (is_null($extension) || !$extension->isEditable()) {
            abort(404);
        }

        $redirect = redirect(adminUrl('extensions/{name}/edit', ['name' => $extension->getName()]));

        $data = [];
        foreach ($extension->fields() as $field) {
            $data[$field] = $request->input($field, '');
        }
        $validator = Validator::make($data, $extension->validationRules());
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator);
        }

        $translatable = $extension->isTranslatable();
        $localizedData = [];
        if ($translatable) {
            $validateRequest = $this->validateMultipleLocaleInputs($request, $extension->localizedValidationRules());

            if ($validateRequest->isFailed()) {
                return $redirect->withInput()->withErrors($validateRequest->getFailed());
            }

            $localizedData = $validateRequest->getLocalizedInputs();
        }

        $save = $extension->save($data, $localizedData);
        if ($save !== true) {
            return $redirect->withInput()->withErrors($save);
        }

        return $redirect;
    }

    protected function activate(Request $request, $name)
    {
        $extensionClasses = array_keys(ExtensionsFacade::all());
        $activatedExtensions = activatedExtensions();
        if (in_array($name, $extensionClasses) && !in_array($name, $activatedExtensions)) {
            $activatedExtensions[] = $name;
            AppOptionHelper::set('activated_extensions', $activatedExtensions, 'man:extensions');
        }

        $this->_rdrUrl($request, adminUrl('extensions'), $rdrUrl, $errorRdrUrl);

        return redirect($rdrUrl);
    }

    protected function deactivate(Request $request, $name)
    {
        $extensionClasses = array_keys(ExtensionsFacade::all());
        $activatedExtensions = activatedExtensions();
        if (in_array($name, $extensionClasses) && in_array($name, $activatedExtensions)) {
            $activatedExtensions = array_diff($activatedExtensions, [$name]);
            AppOptionHelper::set('activated_extensions', $activatedExtensions, 'man:extensions');
        }

        $this->_rdrUrl($request, adminUrl('extensions'), $rdrUrl, $errorRdrUrl);

        return redirect($rdrUrl);
    }
}
