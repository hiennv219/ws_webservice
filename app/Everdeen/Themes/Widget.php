<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 10:03
 */

namespace Katniss\Everdeen\Themes;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\ThemeWidget;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;
use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Utils\AppConfig;

abstract class Widget extends Plugin
{
    /**
     * @var ThemeWidget
     */
    protected $themeWidget;

    public function __construct(array $data = [])
    {
        if ($this::EDITABLE) {
            $this->fromDataConstruct($data);
        }

        parent::__construct();
    }

    public function setId($id)
    {
        $widgetRepository = new ThemeWidgetRepository($id);
        $this->themeWidget = $widgetRepository->model();
    }

    public function getId()
    {
        return empty($this->themeWidget) ? $this::NAME : $this->themeWidget->id;
    }

    public function getHtmlId()
    {
        return 'widget-' . $this->getId();
    }

    /**
     * @param ThemeWidget $themeWidget
     */
    public function setThemeWidget($themeWidget)
    {
        $this->themeWidget = $themeWidget;
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'html_id' => $this->getHtmlId(),
        ]);
    }

    public function viewHome()
    {
        return $this->view('render');
    }

    public function viewHomeParams()
    {
        return [
            'widget_id' => $this->getId(),
            'html_id' => $this->getHtmlId(),
        ];
    }

    protected function renderByTemplate()
    {
        return view()->make($this->viewHome(), $this->viewHomeParams())->render();
    }

    public function render()
    {
        return '';
    }

    public function save($placeholder, array $data = [], array $localizedData = [])
    {
        if (empty($this->themeWidget)) {
            return $this->create($placeholder, $data, $localizedData);
        } else {
            return $this->update($data, $localizedData);
        }
    }

    public function create($placeholder, array $data = [], array $localizedData = [])
    {
        if (!$this::TRANSLATABLE) {
            $localizedData = [];
        }

        $widgetRepository = new ThemeWidgetRepository();
        try {
            $this->themeWidget = $widgetRepository->create(
                $this::NAME,
                $this::THEME_ONLY ? ThemeFacade::getName() : '',
                $placeholder,
                $this->toDataConstructAsJson($data, $localizedData)
            );
            return true;
        } catch (KatnissException $ex) {
            return [$ex->getMessage()];
        }
    }

    public function update(array $data = [], array $localizedData = [])
    {
        if (!$this::EDITABLE) abort(404);

        $widgetRepository = new ThemeWidgetRepository($this->themeWidget);
        try {
            $this->themeWidget = $widgetRepository->updateData($this->toDataConstructAsJson($data, $localizedData));
            return true;
        } catch (KatnissException $ex) {
            return [$ex->getMessage()];
        }
    }
}