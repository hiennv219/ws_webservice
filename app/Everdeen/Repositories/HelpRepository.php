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

class HelpRepository extends PostRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Post::TYPE_HELP, $id);
    }

    public function getPaged()
    {
        return Post::with(['translations', 'categories', 'categories.translations'])
            ->where('type', $this->type)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function create($userId, $template = null, $featuredImage = null, array $localizedData = [], array $categories = [])
    {
        DB::beginTransaction();
        try {
            $help = new Post();
            $help->type = $this->type;
            $help->user_id = $userId;
            foreach ($localizedData as $locale => $transData) {
                $trans = $help->translateOrNew($locale);
                $trans->title = $transData['title'];
                $trans->slug = $transData['slug'];
                $trans->description = $transData['description'];
                $trans->content = clean($transData['content'], 'blog');
                $trans->raw_content = $transData['content'];
            }

            $help->save();

            $help->categories()->attach($categories);

            DB::commit();

            return $help;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($userId, $template = null, $featuredImage = null, array $localizedData = [], array $categories = [])
    {
        $help = $this->model();
        $help->user_id = $userId;

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $help->translateOrNew($locale);
                    $trans->title = $transData['title'];
                    $trans->slug = $transData['slug'];
                    $trans->description = $transData['description'];
                    $trans->content = clean($transData['content'], 'blog');
                    $trans->raw_content = $transData['content'];
                } elseif ($help->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $help->categories()->sync($categories);

            $help->save();

            if (!empty($deletedLocales)) {
                $help->deleteTranslations($deletedLocales);
            }
            DB::commit();

            return $help;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }
}