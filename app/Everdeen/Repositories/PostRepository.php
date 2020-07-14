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
use Katniss\Everdeen\Utils\AppConfig;

class PostRepository extends ByTypeRepository
{
    public function getById($id)
    {
        return Post::with('translations')
            ->where('type', $this->type)
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getPaged()
    {
        return Post::with('translations')
            ->where('type', $this->type)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Post::where('type', $this->type)->get();
    }

    public function create($userId, $template = null, $featuredImage = null, array $localizedData = [])
    {
        DB::beginTransaction();
        try {
            $page = new Post();
            $page->type = $this->type;
            $page->user_id = $userId;
            $page->template = $template;
            $page->featured_image = $featuredImage;
            foreach ($localizedData as $locale => $transData) {
                $trans = $page->translateOrNew($locale);
                $trans->title = $transData['title'];
                $trans->slug = $transData['slug'];
                $trans->description = $transData['description'];
                $trans->content = clean($transData['content'], 'page');
                $trans->raw_content = $transData['content'];
            }

            $page->save();

            DB::commit();

            return $page;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($userId, $template = null, $featuredImage = null, array $localizedData = [])
    {
        $page = $this->model();
        $page->user_id = $userId;
        $page->template = $template;
        $page->featured_image = $featuredImage;

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $page->translateOrNew($locale);
                    $trans->title = $transData['title'];
                    $trans->slug = $transData['slug'];
                    $trans->description = $transData['description'];
                    $trans->content = clean($transData['content'], 'page');
                    $trans->raw_content = $transData['content'];
                } elseif ($page->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $page->save();

            if (!empty($deletedLocales)) {
                $page->deleteTranslations($deletedLocales);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $page = $this->model();

        try {
            $page->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}