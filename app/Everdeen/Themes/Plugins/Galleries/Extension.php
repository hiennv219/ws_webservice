<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\Galleries;

use Katniss\Everdeen\Models\Media;
use Katniss\Everdeen\Repositories\MediaCategoryRepository;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Thunder\Shortcode\ShortcodeFacade;

class Extension extends BaseExtension
{
    const NAME = 'galleries';
    const DISPLAY_NAME = 'Galleries';
    const DESCRIPTION = 'Enable to embed galleries to layout';
    const EDITABLE = false;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        _kWidgets([GalleryWidget::NAME => GalleryWidget::class]);
    }

    public function register()
    {
        addFilter('short_code', new CallableObject(function (ShortcodeFacade $facade) {
            $facade->addHandler('gallery', function (ShortcodeInterface $s) {
                static $galleryCount = 0;
                ++$galleryCount;
                $id = $s->getParameter('id');
                if (!empty($id)) {
                    try {
                        $mediaCategoryRepository = new MediaCategoryRepository();
                        $photos = $mediaCategoryRepository->model($id)->orderedMedia->where('type', Media::TYPE_PHOTO);
                        return view()->make($this->view('html'), [
                            'photos' => $photos,
                            'gallery_name' => 'post_content_gallery_' . $galleryCount,
                        ])->render();
                    } catch (\Exception $exception) {
                    }
                }
                return Str::format('[gallery id="{0}"]', $id);
            });
            return $facade;
        }), 'ext:galleries');

        enqueueThemeHeader('<link rel="stylesheet" href="' . libraryAsset('fancybox/jquery.fancybox.css') . '">', 'galleries_widget_css');
        enqueueThemeFooter('<script src="' . libraryAsset('fancybox/jquery.fancybox.pack.js') . '"></script>', 'galleries_widget_js');
        enqueueThemeFooter('<script>
    $(function() {
        $(\'.embed-galleries .thumbnail a\').fancybox({
            helpers: {
                overlay: {
                  locked: false
                }
            }
        });
    });
</script>', 'embed_galleries');
    }
}