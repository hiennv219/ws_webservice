<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-02-06
 * Time: 16:12
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Repositories\PageRepository;
use Katniss\Everdeen\Themes\ThemeFacade;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class ExampleController extends ViewController
{
    public function index()
    {
        return $this->_any('home');
    }

    public function getSocialSharing()
    {
        $this->_title(trans('example_theme.social_sharing'));
        $this->_description(trans('example_theme.social_sharing'));

        return $this->_any('social_sharing');
    }

    public function getFacebookComments()
    {
        $this->_title(trans('example_theme.facebook_comment'));
        $this->_description(trans('example_theme.facebook_comment'));

        return $this->_any('facebook_comments');
    }

    public function getWidgets()
    {
        $this->_title(trans('example_theme.example_widget'));
        $this->_description(trans('example_theme.example_widget'));

        return $this->_any('widgets');
    }

    public function getMySettings()
    {
        $settings = settings();
        $datetimeHelper = DateTimeHelper::getInstance();
        $localeCode = $settings->getLocale();
        $locale = allLocale($localeCode);
        $countryCode = $settings->getCountry();
        $country = allCountry($countryCode);

        $this->_title(trans('pages.page_my_settings_title'));
        $this->_description(trans('pages.page_my_settings_desc'));

        return $this->_any('my_settings', [
            'country' => $countryCode . ' - ' . $country['name'] . ' (+' . $country['calling_code'] . ')',
            'locale' => $localeCode . '_' . $locale['country_code'] . ' - ' . $locale['name'] . ' (' . $locale['native'] . ')',
            'timezone' => $settings->getTimezone() . ' (' . $datetimeHelper->getCurrentTimeZone() . ')',
            'price' => toFormattedNumber(22270) . ' VND = ' . toFormattedCurrency(22270, 'VND'),
            'long_datetime' => $datetimeHelper->compound(DateTimeHelper::LONG_DATE_FUNCTION, ' ', DateTimeHelper::LONG_TIME_FUNCTION),
            'short_datetime' => $datetimeHelper->compound(),
        ]);
    }

    public function getPages()
    {
        $pageRepository = new PageRepository();
        $pages = $pageRepository->getPaged();

        $this->ogPostList($pages);

        $this->_title(trans_choice('label.page', 2));
        $this->_description(trans('form.list_of', ['name' => trans_choice('label.page_lc', 2)]));

        return $this->_any('pages', [
            'pages' => $pages,
        ]);
    }

    public function getPage($id)
    {
        $pageRepository = new PageRepository();
        $page = $pageRepository->model($id);

        $this->ogPostSingle($page->featured_image, $page->content);

        $this->_title([trans_choice('label.page', 1), $page->title]);
        $this->_description(htmlShorten($page->content));

        return $this->_any(ThemeFacade::pageTemplateView($page->template, 'page'), [
            'page' => $page,
        ]);
    }

    public function getArticles()
    {
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getPaged();

        $this->ogPostList($articles);

        $this->_title(trans_choice('label.article', 2));
        $this->_description(trans('form.list_of', ['name' => trans_choice('label.article_lc', 2)]));

        return $this->_any('articles', [
            'articles' => $articles,
        ]);
    }

    public function getArticle($id)
    {
        $articleRepository = new ArticleRepository();
        $article = $articleRepository->model($id);

        $this->ogPostSingle($article->featured_image, $article->content);

        $this->_title([trans_choice('label.article', 1), $article->title]);
        $this->_description(htmlShorten($article->content));

        return $this->_any(ThemeFacade::articleTemplateView($article->template, 'article'), [
            'article' => $article,
            'article_categories' => $article->categories,
        ]);
    }

    protected function ogPostSingle($featuredImage, $content)
    {
        $imageUrls = extractImageUrls($content);
        if (!empty($featuredImage)) {
            array_unshift($imageUrls, $featuredImage);
        }
        if (count($imageUrls) > 0) {
            array_unshift($imageUrls, appLogo());
            addFilter('open_graph_tags_before_render', new CallableObject(function ($data) use ($imageUrls) {
                $data['og:image'] = $imageUrls;
                return $data;
            }), 'posts_view_single');
        }
    }

    protected function ogPostList($posts)
    {
        $imageUrls = [appLogo()];
        foreach ($posts as $post) {
            if (!empty($post->featured_image)) {
                $imageUrls[] = $post->featured_image;
            }
        }
        addFilter('open_graph_tags_before_render', new CallableObject(function ($data) use ($imageUrls) {
            $data['og:image'] = $imageUrls;
            return $data;
        }), 'posts_view_list');
    }

    public function getCategoryArticles($id)
    {
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getPagedByCategory($id, $category);

        $this->ogPostList($articles);

        $this->_title([trans_choice('label.category', 1) . ': ' . $category->name, trans_choice('label.article', 2)]);
        $this->_description(trans_choice('label.category', 1) . ': ' . $category->name . ' - ' . trans('form.list_of', ['name' => trans_choice('label.article_lc', 2)]));

        return $this->_any('articles', [
            'articles' => $articles,
            'category' => $category,
        ]);
    }

    public function getPublicConversation()
    {
        $this->_title(trans('example_theme.public_conversation'));
        $this->_description(trans('example_theme.public_conversation'));

        return $this->_any('public_conversation');
    }
}