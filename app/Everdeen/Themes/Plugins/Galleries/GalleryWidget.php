<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 16:16
 */

namespace Katniss\Everdeen\Themes\Plugins\Galleries;

use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Models\Media;
use Katniss\Everdeen\Repositories\MediaCategoryRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class GalleryWidget extends DefaultWidget
{
    const NAME = 'galleries.widget';
    const DISPLAY_NAME = 'Gallery';

    protected $categoryId = '';

    protected function __init()
    {
        parent::__init();

        $this->categoryId = $this->getProperty('category_id');
    }

    public function register()
    {
        enqueueThemeFooter('<script>
    $(function() {
        $(\'#' . $this->getHtmlId() . ' .thumbnail a\').fancybox({
            helpers: {
                overlay: {
                  locked: false
                }
            }
        });
    });
</script>', $this->getHtmlId());
    }

    public function viewAdminParams()
    {
        $mediaCategoryRepository = new MediaCategoryRepository();

        return array_merge(parent::viewAdminParams(), [
            'category_id' => $this->categoryId,
            'categories' => $mediaCategoryRepository->getAll(),
        ]);
    }

    public function viewHomeParams()
    {
        $photos = collect([]);
        if (!empty($this->categoryId)) {
            $mediaCategoryRepository = new MediaCategoryRepository();
            $photos = $mediaCategoryRepository->model($this->categoryId)->orderedMedia->where('type', Media::TYPE_PHOTO);
        }

        return array_merge(parent::viewHomeParams(), [
            'photos' => $photos,
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
            'category_id' => 'required|exists:categories,id,type,' . Category::TYPE_MEDIA,
        ]);
    }
}