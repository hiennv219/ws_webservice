<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Closure;
use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;
use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Themes\WidgetsFacade;

class WidgetController extends AdminController
{
    protected $widgetRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'widget';
        $this->widgetRepository = new ThemeWidgetRepository();

        $this->middleware(function ($request, Closure $next) {
            WidgetsFacade::init();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $widgetClasses = WidgetsFacade::all();
        asort($widgetClasses);
        $widgets = [];
        foreach ($widgetClasses as $widgetClass) {
            $widget = new $widgetClass();
            $widgets[$widget->getName()] = [
                'display_name' => $widget->getDisplayName(),
                'editable' => $widget->isEditable(),
            ];
        }

        $placeholders = homeThemePlaceholders();
        asort($placeholders);
        $placeholderNames = array_keys($placeholders);
        $themeWidgets = $this->widgetRepository->getAll($placeholderNames, array_keys($widgets));
        $themePlaceholders = [];
        foreach ($placeholderNames as $placeholderName) {
            $themePlaceholders[$placeholderName] = [];
        }
        foreach ($themeWidgets as $themeWidget) {
            $placeholderName = $themeWidget->placeholder;
            $themePlaceholders[$placeholderName][] = $themeWidget;
        }

        $this->_title(trans('pages.admin_widgets_title'));
        $this->_description(trans('pages.admin_widgets_desc'));

        return $this->_index([
            'widgets' => $widgets,
            'placeholders' => $placeholders,
            'placeholderNames' => $placeholderNames,
            'themePlaceholders' => $themePlaceholders,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->has('duplicate')) {
            return $this->duplicate($request);
        }

        $validator = Validator::make($request->all(), [
            'widget' => 'required|in:' . implode(',', array_keys(WidgetsFacade::all())),
            'placeholder' => 'required|in:' . implode(',', array_keys(homeThemePlaceholders())),
        ]);

        $redirect = redirect(adminUrl('widgets'));
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator);
        }

        $widget = WidgetsFacade::resolveClass($request->input('widget'));
        if (is_null($widget)) {
            abort(404);
        }
        $widget->create($request->input('placeholder'));

        return $redirect;
    }

    protected function duplicate(Request $request)
    {
        $this->widgetRepository->model($request->input('id'));

        $validator = Validator::make($request->all(), [
            'placeholder' => 'required|in:' . implode(',', array_keys(homeThemePlaceholders())),
        ]);

        $redirect = redirect(adminUrl('widgets'));
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        $this->widgetRepository->duplicate($request->input('placeholder'));

        return $redirect;
    }

    public function edit(Request $request, $id)
    {
        $themeWidget = $this->widgetRepository->model($id);
        if (!$themeWidget->checkWidget()) {
            abort(404);
        }
        $widget = $themeWidget->widget();

        $this->_title([trans('pages.admin_widgets_title'), $widget->getDisplayName(), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_widgets_desc'));

        return $this->_edit(array_merge([
            'widget' => $widget,
            'themeWidget' => $themeWidget,
            'widget_view' => $widget->viewAdmin(),
        ], $widget->viewAdminParams()));
    }

    public function update(Request $request, $id)
    {
        if ($request->has('activate')) {
            return $this->activate($request, $id);
        }
        if ($request->has('deactivate')) {
            return $this->deactivate($request, $id);
        }

        $themeWidget = $this->widgetRepository->model($id);
        if (!$themeWidget->checkWidget()) {
            abort(404);
        }
        $widget = $themeWidget->widget();

        $redirect = redirect(adminUrl('widgets/{id}/edit', ['id' => $themeWidget->id]));

        $data = [];
        foreach ($widget->fields() as $field) {
            $data[$field] = $request->input($field, '');
        }
        $validator = Validator::make($data, $widget->validationRules());
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator);
        }

        $translatable = $widget->isTranslatable();
        $localizedData = [];
        if ($translatable) {
            $validateResult = $this->validateMultipleLocaleInputs($request, $widget->localizedValidationRules());

            if ($validateResult->isFailed()) {
                return $redirect->withInput()->withErrors($validateResult->getFailed());
            }

            $localizedData = $validateResult->getLocalizedInputs();
        }

        $save = $widget->update($data, $localizedData);
        if ($save !== true) {
            return $redirect->withInput()->withErrors($save);
        }

        return $redirect;
    }

    protected function activate(Request $request, $id)
    {
        $this->widgetRepository->model($id);

        $this->_rdrUrl($request, adminUrl('widgets'), $rdrUrl, $errorRdrUrl);

        try {
            $this->widgetRepository->updateActive();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function deactivate(Request $request, $id)
    {
        $this->widgetRepository->model($id);

        $this->_rdrUrl($request, adminUrl('widgets'), $rdrUrl, $errorRdrUrl);

        try {
            $this->widgetRepository->updateActive(false);
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function destroy(Request $request, $id)
    {
        $this->widgetRepository->model($id);

        $this->_rdrUrl($request, adminUrl('widgets'), $rdrUrl, $errorRdrUrl);

        try {
            $this->widgetRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
