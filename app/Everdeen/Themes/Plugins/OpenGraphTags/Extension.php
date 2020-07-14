<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-19
 * Time: 15:32
 */

namespace Katniss\Everdeen\Themes\Plugins\OpenGraphTags;

use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Utils\HtmlTag\Html5;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Illuminate\Support\Facades\Request;

/**
 * See more at http://ogp.me/
 *
 * Class Extension
 * @package Katniss\Everdeen\Themes\Plugins\OpenGraphTags
 */
class Extension extends BaseExtension
{
    const NAME = 'open_graph_tags';
    const DISPLAY_NAME = 'Open Graph Tags';
    const DESCRIPTION = 'Set up Open Graph Tags';
    const EDITABLE = false;

    protected $ogTitle;
    protected $ogType;
    protected $ogUrl;
    protected $ogImage;
    protected $ogAudio;
    protected $ogVideo;
    protected $ogSiteName;
    protected $ogDescription;
    protected $ogDeterminer;
    protected $ogLocale;
    protected $ogLocaleAlternate;

    public function __construct()
    {
        parent::__construct();

        $this->ogType = 'website';
        $this->ogUrl = currentUrl();
        $this->ogTitle = themeTitle();
        $this->ogDescription = themeDescription();
        $this->ogImage = appLogo();
        $this->ogSiteName = appName();
        $this->ogLocale = currentFullLocaleCode();
        $this->ogLocaleAlternate = allSupportedFullLocaleCodes();
    }

    public function register()
    {
        enqueueThemeHeader(new CallableObject([$this, 'render']));
    }

    public function render()
    {
        $data = [
            'og:type' => $this->ogType,
            'og:title' => $this->ogTitle,
            'og:description' => $this->ogDescription,
            'og:url' => $this->ogUrl,
            'og:site_name' => $this->ogSiteName,
            'og:image' => $this->ogImage,
            'og:locale' => $this->ogLocale,
            'og:locale:alternate' => $this->ogLocaleAlternate,
        ];

        $data = contentFilter('open_graph_tags_before_render', $data);

        return $this->parseCollection($data);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function parseCollection(array $data)
    {
        $output = '<!-- ' . $this::DISPLAY_NAME . ' -->';
        foreach ($data as $property => $item) {
            $output .= $this->parseItem($property, $item);
        }
        return $output;
    }

    /**
     * @param string $property
     * @param string|array $item
     * @return string
     */
    protected function parseItem($property, $item)
    {
        $is_array = is_array($item);
        switch ($property) {
            case 'og:image':
                if ($is_array) {
                    return $this->parseOgImages($item);
                }
                break;
            case 'og:locale:alternate':
                if ($is_array) {
                    return $this->parseContents($property, $item);
                }
                break;
            default:
                break;
        }
        return PHP_EOL . Html5::metaProperty($property, $item);
    }

    #region Parse Image
    protected function parseOgImages(array $images)
    {
        $output = '';
        foreach ($images as $image) {
            $output .= $this->parseOgImage($image);
        }
        return $output;
    }

    /**
     * @param string|OgImage $image
     * @return string
     */
    protected function parseOgImage($image)
    {
        return is_a($image, OgImage::class) ? $image->render() : PHP_EOL . Html5::metaProperty('og:image', $image);
    }

    #endregion

    /**
     * @param string $property
     * @param array $contents
     * @return string
     */
    protected function parseContents($property, array $contents)
    {
        $output = '';
        foreach ($contents as $content) {
            $output .= PHP_EOL . Html5::metaProperty($property, $content);
        }
        return $output;
    }
}