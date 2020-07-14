<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-14
 * Time: 00:28
 */

namespace Katniss\Everdeen\Themes\HomeThemes\ExampleTheme\Composers;


use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Extension as ContactFormExtension;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Extension as GoogleMapsMarkersExtension;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories\MapMarkerRepository;

class ContactTemplateComposer
{
    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $postTemplate = new \stdClass();
        $postTemplate->showContactForm = isActivatedExtension(ContactFormExtension::NAME);
        $postTemplate->contactForm = $postTemplate->showContactForm ?
            ContactFormExtension::htmlContactForm() : null;
        $postTemplate->showMapMarker = isActivatedExtension(GoogleMapsMarkersExtension::NAME);
        $postTemplate->mapMarker = null;
        if ($postTemplate->showMapMarker) {
            $postTemplate->mapMarker = $this->getMapMarker();
        }
        $view->with('post_template', $postTemplate);
    }

    protected function getMapMarker()
    {
        $themeOptions = getOption('theme_example', []);
        if (empty($themeOptions['default_map_marker_id'])) return null;

        return new HtmlString(
            GoogleMapsMarkersExtension::enqueueMapMarkerLayout(
                $themeOptions['default_map_marker_id'],
                'default-map-marker',
                '#default-map-marker .map'
            )
        );

    }
}