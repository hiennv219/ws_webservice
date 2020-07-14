<?php

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Models\Post;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\DataStructure\Hierarchy\Hierarchy;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;

class ArticleController extends ViewController
{
    protected $categoryRepository;
    protected $articleRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'article';
        $this->categoryRepository = new ArticleCategoryRepository();
        $this->articleRepository = new ArticleRepository();
    }

    public function index(Request $request)
    {
        $articles = $this->articleRepository->getSearchPublishedPaged();

        $this->_title(trans('pages.home_knowledge_title'));
        $this->_description(trans('pages.home_knowledge_desc'));

        $this->ogArticleList($articles);

        return $this->_index([
            'articles' => $articles,
            'pagination' => $this->paginationRender->renderByPagedModels($articles),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'categories_menu' => $this->getCategoriesMenuRender(),
        ]);
    }

    public function indexAuthor(Request $request, $id)
    {
        if ($request->isAuth() && $request->authUser()->id == $id) {
            $articles = $this->articleRepository->getPagedByAuthor($id, $author);
            $isAuthor = true;
        } else {
            $articles = $this->articleRepository->getPublishedPagedByAuthor($id, $author);
            $isAuthor = false;
        }

        $this->_title([trans('label.author'), $author->display_name]);
        $this->_description(trans('pages.home_knowledge_desc'));

        $this->ogArticleList($articles);

        return $this->_index([
            'articles' => $articles,
            'pagination' => $this->paginationRender->renderByPagedModels($articles),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'categories_menu' => $this->getCategoriesMenuRender(),

            'author' => $author,
            'is_author' => $isAuthor,
        ]);
    }

    public function indexCategory(Request $request, $slug)
    {
        $articles = $this->articleRepository->getPublishedPagedByCategorySlug($slug, $category);

        $this->_title([trans_choice('label.category', 1), $category->name]);
        $this->_description($category->description);

        $this->ogArticleList($articles);

        return $this->_index([
            'articles' => $articles,
            'pagination' => $this->paginationRender->renderByPagedModels($articles),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            'categories_menu' => $this->getCategoriesMenuRender(),

            'category' => $category,
        ]);
    }

    protected function getCategoriesMenuRender()
    {
        $menuRender = new MenuRender();
        $menuRender->wrapClass = 'list-group list-group-root margin-bottom-none';
        $menuRender->linkClass = 'list-group-item border-master';
        $menuRender->childrenWrapClass = 'list-group';
        return new HtmlString($menuRender->render($this->buildCategoriesMenu()));
    }

    protected function buildCategoriesMenu()
    {
        $hierarchy = new Hierarchy();
        $hierarchy->buildFromList($this->categoryRepository->getAll(), 'id', 'parent_id');
        $menu = $this->buildCategoriesMenuFromData($hierarchy->toArray());
        if ($menu->has()) {
            $data = &$menu->get();
            $data[0]['item']['link_class'] = 'first';
        }
        return $menu;
    }

    protected function buildCategoriesMenuFromData($data)
    {
        $menu = new Menu(currentFullUrl());
        foreach ($data as $item) {
            $menu->add(
                homeUrl('knowledge/categories/{slug}', ['slug' => $item['object']['slug']]),
                $item['object']['name'], '<i class="glyphicon glyphicon-chevron-right"></i> '
            );
            if (count($item['children']) > 0) {
                $menu->addSubMenu($this->buildCategoriesMenuFromData($item['children']));
            }
        }
        return $menu;
    }

    public function show(Request $request, $slug)
    {
        $article = $this->articleRepository->getBySlugWithPossibleLoads($slug);
        $isAuthor = $request->isAuth() && ($request->authUser()->id == $article->user_id || $request->authUser()->hasRole(['admin', 'editor']));
        if (!$isAuthor && !$article->isPublished) {
            abort(404);
        }

        $this->articleRepository->model($article);
        $this->articleRepository->view();

        $this->_title($article->title);
        $this->_description(htmlShorten($article->content));

        $this->ogArticleSingle($article->featured_image, $article->content);

        return $this->_show([
            'article' => $article,
            'categories_menu' => $this->getCategoriesMenuRender(),

            'is_author' => $isAuthor,
        ]);
    }

    public function showById(Request $request, $id)
    {
        $article = $this->articleRepository->getByIdWithPossibleLoads($id);
        $isAuthor = $request->isAuth() && ($request->authUser()->id == $article->user_id || $request->authUser()->hasRole(['admin', 'editor']));
        if (!$isAuthor && !$article->isPublished) {
            abort(404);
        }

        $this->articleRepository->model($article);
        $this->articleRepository->view();

        $this->_title($article->title);
        $this->_description(htmlShorten($article->content));

        $this->ogArticleSingle($article->featured_image, $article->content);

        return $this->_show([
            'article' => $article,
            'categories_menu' => $this->getCategoriesMenuRender(),

            'is_author' => $isAuthor,
        ]);
    }

    public function create(Request $request)
    {
        $articleCategoryRepository = new ArticleCategoryRepository();

        $this->_title([trans('pages.admin_articles_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_articles_desc'));

        return $this->_create([
            'categories' => $articleCategoryRepository->getExceptDefault(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'categories' => 'sometimes|nullable|exists:categories,id,type,' . Category::TYPE_ARTICLE,
            'featured_image' => 'sometimes|nullable|url',
        ]);

        $errorRedirect = redirect(homeUrl('knowledge/articles/create'))
            ->withInput();

        if ($validator->fails()) {
            return $errorRedirect->withErrors($validator);
        }

        try {
            $authUser = $request->authUser();
            $this->articleRepository->create(
                $authUser->id,
                null,
                $request->input('featured_image', ''),
                [
                    AppConfig::INTERNATIONAL_LOCALE_CODE => [
                        'title' => $request->input('title'),
                        'content' => $request->input('content'),
                        'slug' => $this->generateSlug($request->input('title')),
                        'description' => '',
                    ]
                ],
                $request->input('categories', []),
                $authUser->hasRole('teacher') && !$authUser->can('publish-articles') ?
                    Post::STATUS_TEACHER_EDITING : Post::STATUS_PUBLISHED
            );
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(homeUrl('knowledge/authors/{id}', ['id' => $authUser->id]));
    }

    protected function generateSlug($title)
    {
        $slug = Str::slug($title);
        $genSlug = $slug;
        $i = 0;
        while ($this->articleRepository->hasSlug($genSlug)) {
            $genSlug = $slug . '-' . (++$i);
        }
        return $genSlug;
    }

    protected function ogArticleSingle($featuredImage, $content)
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
            }), 'articles_view_single');
        }
    }

    protected function ogArticleList($articles)
    {
        $imageUrls = [appLogo()];
        foreach ($articles as $article) {
            if (!empty($article->featured_image)) {
                $imageUrls[] = $article->featured_image;
            }
        }
        addFilter('open_graph_tags_before_render', new CallableObject(function ($data) use ($imageUrls) {
            $data['og:image'] = $imageUrls;
            return $data;
        }), 'articles_view_list');
    }
}
