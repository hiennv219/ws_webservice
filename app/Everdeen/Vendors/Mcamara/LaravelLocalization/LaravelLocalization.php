<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-07-02
 * Time: 13:09
 */

namespace Katniss\Everdeen\Vendors\Mcamara\LaravelLocalization;

use Mcamara\LaravelLocalization\Exceptions\UnsupportedLocaleException;
use Mcamara\LaravelLocalization\LaravelLocalization as BaseLaravelLocalization;

class LaravelLocalization extends BaseLaravelLocalization
{
    public function setLocale($locale = null)
    {
        if (empty($locale) || !is_string($locale)) {
            // If the locale has not been passed through the function
            // it tries to get it from the first segment of the url
            $locale = $this->request->segment(1);
        }

        if (!empty($this->supportedLocales[$locale])) {
            $this->currentLocale = $locale;
        } else {
            // if the first segment/locale passed is not valid
            // the system would ask which locale have to take
            // it could be taken by the browser
            // depending on your configuration

            $locale = null;

            // if we reached this point and hideDefaultLocaleInURL is true
            // we have to assume we are routing to a defaultLocale route.
            if ($this->hideDefaultLocaleInURL()) {
                $this->currentLocale = $this->defaultLocale;
            }
            // but if hideDefaultLocaleInURL is false, we have
            // to retrieve it from the browser...
            else {
                $this->currentLocale = $this->getCurrentLocale();

                // linhnt.aim@outlook.com
                if (!$this->matchLocaleRoute()) {
                    $this->currentLocale = $this->defaultLocale;
                    if (!$this->matchLocaleRoute()) {
                        // don't know what to do here
                    }
                }
                // end fixed // if no fixed, delete
            }
        }

        $this->app->setLocale($this->currentLocale);

        // Regional locale such as de_DE, so formatLocalized works in Carbon
        $regional = $this->getCurrentLocaleRegional();
        if ($regional) {
            setlocale(LC_TIME, $regional . '.UTF-8');
            setlocale(LC_MONETARY, $regional . '.UTF-8');
        }

        return $locale;
    }

    protected function matchLocaleRoute()
    {
        $path = $this->request->path();
        $matchedLocale = false;
        foreach (trans()->get('routes', [], $this->currentLocale) as $name => $trans) { // lang of default locale
            $nameRegex = '/^' . str_replace('/', '\\/', preg_replace('/\{[^\}]+\}/', '(.*)', $trans)) . '$/';
            if (preg_match($nameRegex, $path, $matches)) {
                $matchedLocale = true;
                break;
            }
        }
        return $matchedLocale;
    }

    public function getLocalizedURL($locale = null, $url = null, $attributes = array(), $forceDefaultLocation = false)
    {
        if ($locale === null) {
            $locale = $this->getCurrentLocale();
        }

        if (!$this->checkLocaleInSupportedLocales($locale)) {
            throw new UnsupportedLocaleException('Locale \'' . $locale . '\' is not in the list of supported locales.');
        }

        if (empty($attributes)) {
            $attributes = $this->extractAttributes($url, $locale);
        }

        if (empty($url)) {
            if (!empty($this->routeName)) {
                return $this->getURLFromRouteNameTranslated($locale, $this->routeName, $attributes, $forceDefaultLocation);
            }

            $url = $this->request->fullUrl();
        } else {
            $url = $this->url->to($url);
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) { // if no fixed, delete
            // linhnt.aim@outlook.com
            $parsed_url = parse_url($url);
            if (!empty($parsed_url['query'])) {
                if ($parsed_url['query'] == '}' && mb_strpos($parsed_url['path'], '{') !== false) {
                    $parsed_url['path'] .= '?}';
                    $parsed_url['query'] = '';
                } else {
                    $tmp = explode('=', $parsed_url['query'])[0];
                    $i = mb_strpos($tmp, '?');
                    while ($i !== false) {
                        if ($tmp{$i + 1} != '}') {
                            $parsed_url['path'] .= '?' . mb_substr($tmp, 0, $i);
                            $parsed_url['query'] = mb_substr($parsed_url['query'], $i + 1);
                            break;
                        }
                        $i = mb_strpos($tmp, '?', $i + 1);
                    }
                }
            }
            $tmpUrl = $parsed_url['scheme'] . '://' . $parsed_url['host'];
            if (!empty($parsed_url['path'])) {
                $tmpUrl .= $parsed_url['path'];
            }
            // end fixed // if no fixed, delete

//            if ($locale && $translatedRoute = $this->findTranslatedRouteByUrl($url, $attributes, $this->currentLocale)) { // if no fixed, uncomment
            if ($locale && $translatedRoute = $this->findTranslatedRouteByUrl($tmpUrl, $attributes, $this->currentLocale)) { // if no fixed, delete
                // linhnt.aim@outlook.com
                $translatedUrl = $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes, $forceDefaultLocation);
                if (!empty($parsed_url['query'])) {
                    $translatedUrl .= '?' . $parsed_url['query'];
                }
                if (!empty($parsed_url['fragment'])) {
                    $translatedUrl .= '#' . $parsed_url['fragment'];
                }
                return $translatedUrl;
                // end fixed // if no fixed, delete
//            return $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes); // if no fixed, uncomment
            }
        } // if no fixed, delete

        $base_path = $this->request->getBaseUrl();
        $parsed_url = parse_url($url);
        $url_locale = $this->getDefaultLocale();

        if (!$parsed_url || empty($parsed_url['path'])) {
            $path = $parsed_url['path'] = '';
        } else {
            $parsed_url['path'] = str_replace($base_path, '', '/' . ltrim($parsed_url['path'], '/'));
            $path = $parsed_url['path'];
            foreach ($this->getSupportedLocales() as $localeCode => $lang) {
                $parsed_url['path'] = preg_replace('%^/?' . $localeCode . '/%', '$1', $parsed_url['path']);
                if ($parsed_url['path'] !== $path) {
                    $url_locale = $localeCode;
                    break;
                }

                $parsed_url['path'] = preg_replace('%^/?' . $localeCode . '$%', '$1', $parsed_url['path']);
                if ($parsed_url['path'] !== $path) {
                    $url_locale = $localeCode;
                    break;
                }
            }
        }

        $parsed_url['path'] = ltrim($parsed_url['path'], '/');

        if ($translatedRoute = $this->findTranslatedRouteByPath($parsed_url['path'], $url_locale)) {
            return $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes, $forceDefaultLocation);
        }

        if (!empty($locale)) {
            if ($locale != $this->getDefaultLocale() || !$this->hideDefaultLocaleInURL() || $forceDefaultLocation) {
                $parsed_url['path'] = $locale . '/' . ltrim($parsed_url['path'], '/');
            }
        }
        $parsed_url['path'] = ltrim(ltrim($base_path, '/') . '/' . $parsed_url['path'], '/');

        //Make sure that the pass path is returned with a leading slash only if it come in with one.
        if (starts_with($path, '/') === true) {
            $parsed_url['path'] = '/' . $parsed_url['path'];
        }
        $parsed_url['path'] = rtrim($parsed_url['path'], '/');

        $url = $this->unparseUrl($parsed_url);

        if ($this->checkUrl($url)) {
            return $url;
        }

        return $this->createUrlFromUri($url);
    }
}