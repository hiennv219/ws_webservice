<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 22:36
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Post;
use Katniss\Everdeen\Models\Teacher;
use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Repositories\TeacherRepository;
use Katniss\Everdeen\Utils\DataStructure\Pagination\Pagination;

class ArticleController extends WebApiController
{
    protected $articleRepository;

    public function __construct()
    {
        parent::__construct();

        $this->articleRepository = new ArticleRepository();
    }

    public function index(Request $request)
    {
        if ($request->has('q')) {
            return $this->indexCommon($request);
        }

        return $this->responseFail();
    }

    public function indexCommon(Request $request)
    {
        try {
            $articles = $this->articleRepository->getSearchCommonPaged($request->input('q'));
            $pagination = new Pagination($articles);
            $articles = $articles->map(function (Post $article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'featured_image' => !empty($article->featured_image) ?
                        $article->featured_image : null,
                    'short_content' => htmlShorten($article->content),
                    'author' => [
                        'display_name' => $article->author->display_name
                    ],
                ];
            });
            return $this->responseSuccess([
                'articles' => $articles,
                'pagination' => $pagination->toArray(),
            ]);
        } catch (\Exception $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }
}