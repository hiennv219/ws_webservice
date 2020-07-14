<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Topic;
use Katniss\Everdeen\Utils\AppConfig;

class TopicRepository extends ModelRepository
{
    public function getById($id)
    {
        return Topic::with('translations')
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getPaged()
    {
        return Topic::with('translations')
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Topic::with('translations')->get();
    }

    public function create(array $localizedData = [])
    {
        DB::beginTransaction();
        try {
            $topic = new Topic();
            foreach ($localizedData as $locale => $transData) {
                $trans = $topic->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->description = $transData['description'];
            }
            $topic->save();

            DB::commit();

            return $topic;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update(array $localizedData = [])
    {
        $topic = $this->model();

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $topic->translateOrNew($locale);
                    $trans->name = $transData['name'];
                    $trans->description = $transData['description'];
                } elseif ($topic->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $topic->save();

            if (!empty($deletedLocales)) {
                $topic->deleteTranslations($deletedLocales);
            }
            DB::commit();

            return $topic;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $topic = $this->model();

        try {
            $topic->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}