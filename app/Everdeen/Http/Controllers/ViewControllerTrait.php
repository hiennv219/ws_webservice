<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-27
 * Time: 00:23
 */

namespace Katniss\Everdeen\Http\Controllers;


trait ViewControllerTrait
{
    /**
     * @var string
     */
    protected $viewPath;

    /**
     * Get global view params
     *
     * @return array
     */
    protected function _params()
    {
        return $this->currentRequest->getTheme()->viewParams();
    }

    protected function _title($title, $use_root = true)
    {
        return $this->currentRequest->getTheme()->title($title, $use_root);
    }

    protected function _description($description = '')
    {
        return $this->currentRequest->getTheme()->description($description);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function _page($name)
    {
        return $this->currentRequest->getTheme()->page($name);
    }

    protected function _pageExists($view)
    {
        return view()->exists($this->_page($this->viewPath . '.' . $view));
    }

    protected function _viewPath($relativePath)
    {
        return $this->_page($this->viewPath . '.' . $relativePath);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _view($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath), $data, $mergeData);
    }

    /**
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _any($view, $data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.' . $view), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _index($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.index'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _create($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.create'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _edit($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.edit'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _show($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.show'), $data, $mergeData);
    }
}