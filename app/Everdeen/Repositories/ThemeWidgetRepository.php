<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-05
 * Time: 00:59
 */

namespace Katniss\Everdeen\Repositories;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\ThemeWidget;
use Katniss\Everdeen\Utils\AppConfig;

class ThemeWidgetRepository extends ModelRepository
{
    public function getById($id)
    {
        return ThemeWidget::findOrFail($id);
    }

    public function getPaged()
    {
        return ThemeWidget::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll(array $availablePlaceholderNames = [], array $availableWidgetNames = [])
    {
        return ThemeWidget::checkPlaceholders($availablePlaceholderNames)
            ->checkWidgets($availableWidgetNames)
            ->orderBy('order', 'asc')->orderBy('created_at', 'asc')->get();
    }

    public function getActive(array $availableWidgetNames = [])
    {
        return ThemeWidget::checkWidgets($availableWidgetNames)->forDisplay()->get();
    }

    public function create($widgetName, $themeName, $placeholder, $constructingData, $active = true)
    {
        try {
            return ThemeWidget::create([
                'widget_name' => $widgetName,
                'theme_name' => $themeName,
                'placeholder' => $placeholder,
                'constructing_data' => $constructingData,
                'active' => $active,
                'order' => ThemeWidget::where('placeholder', $placeholder)->count() + 1,
            ]);
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function duplicate($placeholder)
    {
        $widget = $this->model();
        return $this->create(
            $widget->widget_name,
            $widget->theme_name,
            $placeholder,
            $widget->constructing_data,
            $widget->active
        );
    }

    public function updateActive($active = true)
    {
        $widget = $this->model();
        try {
            $widget->active = $active;
            $widget->save();
            return $widget;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateData($constructingData)
    {
        $widget = $this->model();
        try {
            $widget->constructing_data = $constructingData;
            $widget->save();
            return $widget;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateSort($widgetIds, $placeholder)
    {
        DB::beginTransaction();
        try {
            $order = 0;
            foreach ($widgetIds as $id) {
                ThemeWidget::where('id', $id)->update([
                    'placeholder' => $placeholder,
                    'order' => ++$order,
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $widget = $this->model();
        try {
            $widget->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}