<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-16
 * Time: 01:46
 */

namespace Katniss\Everdeen\Utils;

class AppConfig
{
    const KEY_REDIRECT_URL = 'x_rdr';
    const KEY_REDIRECT_ON_ERROR_URL = 'x_rdr_error';
    const KEY_WIZARD = 'x_wizard';
    const KEY_WIZARD_NAME = 'x_wizard_name';
    const KEY_WIZARD_KEY = 'x_wizard_key';
    const KEY_FORCE_LOCALE = 'x_force_locale';
    const KEY_FORCE_THEME = 'x_force_theme';
    const KEY_HTML_INPUTS = 'x_html_inputs';
    const KEY_LOCALE_INPUT = 'x_locale';
    const KEY_CALLBACK_REDIRECT_URL = 'x_callback_redirect_uri';
    const KEY_EXTRA_ROUTE = 'x_route';

    const REGEX_YOUTUBE_URL = '/^(http:\/\/|https:\/\/|\/\/)(www.|m.|)(youtube.com\/watch\?v=|youtube.com\/embed\/|youtu.be\/)(.+)$/';
    const REGEX_VIMEO_URL = '/^(http:\/\/|https:\/\/|\/\/)(vimeo.com\/|player.vimeo.com\/video\/)([0-9]+).*$/';
    const REGEX_DAILYMOTION_URL = '/^(http:\/\/|https:\/\/|\/\/)(www.|m.|)(dailymotion.com\/video\/([^_]+).*|(dailymotion.com\/embed\/video\/|dai.ly\/)(.+))$/';

    const DEFAULT_HTML_CLEAN_SETTING = 'typical';
    const DEFAULT_ITEMS_PER_PAGE = 10;

    const TITLE_SHORTEN_TEXT_LENGTH = 25;
    const TINY_SHORTEN_TEXT_LENGTH = 75;
    const SMALLER_SHORTEN_TEXT_LENGTH = 120;
    const SMALL_SHORTEN_TEXT_LENGTH = 150;
    const DEFAULT_SHORTEN_TEXT_LENGTH = 200;
    const MEDIUM_SHORTEN_TEXT_LENGTH = 300;
    const LONG_SHORTEN_TEXT_LENGTH = 400;

    const INTERNATIONAL_COUNTRY_CODE = '--';
    const INTERNATIONAL_LOCALE_CODE = '--';

    const DEFAULT_PAGINATION_ITEMS = 5;
    const ON_PHONE_PAGINATION_ITEMS = 4;
}