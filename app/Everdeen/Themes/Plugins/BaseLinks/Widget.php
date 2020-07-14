<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 16:16
 */

namespace Katniss\Everdeen\Themes\Plugins\BaseLinks;

use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\LinkCategoryRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'base_links';
    const DISPLAY_NAME = 'Base Links';

    protected $categoryId = '';

    protected function __init()
    {
        parent::__init();

        $this->categoryId = $this->getProperty('category_id');
    }

    public function viewAdminParams()
    {
        $linkCategoryRepository = new LinkCategoryRepository();

        return array_merge(parent::viewAdminParams(), [
            'category_id' => $this->categoryId,
            'categories' => $linkCategoryRepository->getAll(),
        ]);
    }

    public function viewHomeParams()
    {
        $links = collect([]);
        if (!empty($this->categoryId)) {
            $linkCategoryRepository = new LinkCategoryRepository();
            $links = $linkCategoryRepository->getByIdWithTranslatedLinks($this->categoryId)->links->sortBy('pivot.order');
        }
        return array_merge(parent::viewHomeParams(), [
            'links' => $links,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'category_id'
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'category_id' => 'required|exists:categories,id,type,' . Category::TYPE_LINK,
        ]);
    }
}