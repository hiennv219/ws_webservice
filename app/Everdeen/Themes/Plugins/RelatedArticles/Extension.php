<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\RelatedArticles;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Models\Post;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class Extension extends BaseExtension
{
    const NAME = 'related_articles';
    const DISPLAY_NAME = 'Related Articles';
    const DESCRIPTION = 'Show related articles';

    protected $numberOfItems;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        $this->numberOfItems = defPr($this->getProperty('number_of_items'), 3);
    }

    public function register()
    {
        enqueueThemeHeader(
            '<style>.related-article-item,.related-article-item .image-cover{border-radius: 8px}</style>',
            'ext:related_articles'
        );

        addPlace('article_after', new CallableObject(function ($article) {
            $articles = Post::OfArticle()->published()->where('id', '<>', $article->id);
            $articles->where(function ($query) use ($article) {
                $query->where('user_id', $article->user_id);
                $categories = $article->categories->pluck('id')->all();
                $query->orWhereHas('categories', function ($query) use ($categories) {
                    $query->whereIn('id', $categories);
                });
                $values = ['title' => []];
                foreach (explode(' ', $article->title) as $titlePiece) {
                    $titlePiece = trim($titlePiece);
                    if (!empty($titlePiece)) {
                        $values['title'][] = $titlePiece;
                    }
                }
                $query->orManyWhereTranslationLike($values);
            });
            $homeTheme = homeTheme();
            return view()->make('plugins.related_articles.render', [
                'articles' => $articles->orderBy('created_at', 'desc')->take($this->numberOfItems)->get(),
                'default_image' => $homeTheme->options('knowledge_default_article_image'),
            ]);
        }), 'ext:related_articles');
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'number_of_items' => $this->numberOfItems,
        ]);
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'number_of_items',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'number_of_items' => 'required|min:1',
        ]);
    }
}