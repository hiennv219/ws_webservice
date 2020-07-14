<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 18:35
 */

namespace Katniss\Everdeen\Repositories;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Post;
use Katniss\Everdeen\Themes\Extension;
use Katniss\Everdeen\Themes\Plugins\AppSettings\Extension as AppSettingsExtension;
use Katniss\Everdeen\Utils\AppConfig;

class ArticleRepository extends PostRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Post::TYPE_ARTICLE, $id);
    }

    public function getCountPublished()
    {
        return Post::where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->count();
    }

    public function getCountTeacher()
    {
        return Post::where('type', $this->type)
            ->where('status', Post::STATUS_TEACHER_EDITING)
            ->count();
    }

    public function getCountAfterDate($date)
    {
        return Post::where('type', $this->type)
            ->whereDate('created_at', '>=', $date)
            ->count();
    }

    public function hasSlug($slug)
    {
        return Post::whereTranslation('slug', $slug)->count() > 0;
    }

    public function getPublishedByIds($ids)
    {
        return Post::with('translations')
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->whereIn('id', $ids)
            ->get();
    }

    public function getByIdWithPossibleLoads($id)
    {
        return Post::with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type)
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getBySlugWithPossibleLoads($slug)
    {
        return Post::with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type)
            ->whereTranslation('slug', $slug)
            ->firstOrFail();
    }

    public function getLast($count = 1)
    {
        $posts = Post::with('translations')
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->take($count);
        return $count == 1 ? $posts->first() : $posts->get();
    }

    public function getLastMostViewed($count = 1)
    {
        $posts = Post::with('translations')
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('viewed', 'desc')
            ->orderBy('created_at', 'desc')
            ->take($count);
        return $count == 1 ? $posts->first() : $posts->get();
    }

    public function getSearchPublishedPaged($title = null, $author = null, $categories = null)
    {
        $posts = Post::with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED);

        if (!empty($title)) {
            $posts->whereTranslationLike('title', '%' . $title . '%');
        }
        if (!empty($author)) {
            $posts->where('user_id', $author);
        }
        if (!empty($categories)) {
            $posts->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('id', $categories);
            });
        }

        return $posts->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchTeacherPaged($title = null, $author = null, $categories = null)
    {
        $posts = Post::with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type)
            ->where('status', Post::STATUS_TEACHER_EDITING);

        if (!empty($title)) {
            $posts->whereTranslationLike('title', '%' . $title . '%');
        }
        if (!empty($author)) {
            $posts->where('user_id', $author);
        }
        if (!empty($categories)) {
            $posts->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('id', $categories);
            });
        }

        return $posts->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getLastPublishedByCategory($count, &$categoryId, &$category)
    {
        $categoryRepository = new ArticleCategoryRepository();
        if (empty($category)) {
            $category = $categoryRepository->getByIdWithTranslated($categoryId);
        } else {
            $categoryId = $category->id;
        }
        $articles = $category->posts()
            ->with('translations')
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->take($count);
        return $count == 1 ? $articles->first() : $articles->get();
    }

    public function getPublishedPagedByCategorySlug(&$categorySlug, &$category)
    {
        $categoryRepository = new ArticleCategoryRepository();
        if (empty($category)) {
            $category = $categoryRepository->getBySlugWithTranslated($categorySlug);
        } else {
            $categorySlug = $category->slug;
        }
        return $category->posts()
            ->with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getPublishedPagedByAuthor($authorId, &$author)
    {
        $userRepository = new UserRepository($authorId);
        $author = $userRepository->model();
        return Post::with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type)
            ->where('user_id', $author->id)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getLastPublishedByAuthorIds($count, $authorIds)
    {
        $articles = Post::with('translations')
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->whereIn('user_id', $authorIds)
            ->orderBy('created_at', 'desc')
            ->take($count);
        return $count == 1 ? $articles->first() : $articles->get();
    }

    public function getLastPublishedByTeachers($count)
    {
        $articles = Post::with('translations')
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->whereHas('author', function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'teacher');
                });
            })
            ->orderBy('created_at', 'desc')
            ->take($count);
        return $count == 1 ? $articles->first() : $articles->get();
    }

    public function getPagedByAuthor($authorId, &$author)
    {
        $userRepository = new UserRepository($authorId);
        $author = $userRepository->model();
        return Post::with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type)
            ->where('user_id', $author->id)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchCommonPaged($term = null)
    {
        $articles = Post::with(['translations', 'author'])
            ->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED);
        if (!empty($term)) {
            $articles->where(function ($query) use ($term) {
                $query->where('id', $term);
                $query->orWhereTranslationLike('title', '%' . $term . '%');
                $query->orWhereTranslationLike('description', '%' . $term . '%');
                $query->orWhereHas('author', function ($query) use ($term) {
                    $query->where('display_name', 'like', '%' . $term . '%');
                });
            });
        }
        return $articles->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function create($userId, $template = null, $featuredImage = null,
                           array $localizedData = [], array $categories = [],
                           $status = Post::STATUS_PUBLISHED)
    {
        DB::beginTransaction();
        try {
            $article = new Post();
            $article->type = $this->type;
            $article->user_id = $userId;
            $article->template = $template;
            $article->featured_image = $featuredImage;
            $article->status = $status;
            foreach ($localizedData as $locale => $transData) {
                $trans = $article->translateOrNew($locale);
                $trans->title = $transData['title'];
                $trans->slug = $transData['slug'];
                $trans->description = $transData['description'];
                $trans->content = clean($transData['content'], 'blog');
                $trans->raw_content = $transData['content'];
            }

            $article->save();

            if (count($categories) > 0) {
                $article->categories()->attach($categories);
            } else {
                $appSettings = Extension::getSharedData(AppSettingsExtension::NAME);
                $article->categories()->attach([$appSettings->defaultArticleCategory]);
            }

            DB::commit();

            return $article;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($userId, $template = null, $featuredImage = null, array $localizedData = [], array $categories = [])
    {
        $article = $this->model();
//        $article->user_id = $userId;
        $article->template = $template;
        $article->featured_image = $featuredImage;

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $article->translateOrNew($locale);
                    $trans->title = $transData['title'];
                    $trans->slug = $transData['slug'];
                    $trans->description = $transData['description'];
                    $trans->content = clean($transData['content'], 'blog');
                    $trans->raw_content = $transData['content'];
                } elseif ($article->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            if (count($categories) > 0) {
                $article->categories()->sync($categories);
            } else {
                $appSettings = Extension::getSharedData(AppSettingsExtension::NAME);
                $article->categories()->sync([$appSettings->defaultArticleCategory]);
            }

            $article->save();

            if (!empty($deletedLocales)) {
                $article->deleteTranslations($deletedLocales);
            }
            DB::commit();

            return $article;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function publish($publishedBy)
    {
        $article = $this->model();
        try {
            $article->update([
                'status' => Post::STATUS_PUBLISHED
            ]);
            return true;
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_update') . ' (' . $exception->getMessage() . ')');
        }
    }

    public function view()
    {
        $article = $this->model();
        try {
            $article->increment('viewed');
            return true;
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_update') . ' (' . $exception->getMessage() . ')');
        }
    }
}