<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:03
 */

namespace Katniss\Everdeen\Repositories;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Media;
use Katniss\Everdeen\Utils\AppConfig;

class MediaRepository extends ModelRepository
{
    public function getById($id)
    {
        return Media::findOrFail($id);
    }

    public function getPaged()
    {
        return Media::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Media::all();
    }

    public function create($url, $type, array $categories = [], array $localizedData = [])
    {
        DB::beginTransaction();
        try {
            $media = new Media();
            $media->url = $url;
            $media->type = $type;
            foreach ($localizedData as $locale => $transData) {
                $trans = $media->translateOrNew($locale);
                $trans->title = $transData['title'];
                $trans->description = $transData['description'];
            }
            $media->save();
            if (count($categories) > 0) {
                $media->categories()->attach($categories);
            }

            DB::commit();

            return $media;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($url, $type, array $categories = [], array $localizedData = [])
    {
        $media = $this->model();

        DB::beginTransaction();
        try {
            $media->url = $url;
            $media->type = $type;

            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $media->translateOrNew($locale);
                    $trans->title = $transData['title'];
                    $trans->description = $transData['description'];
                } elseif ($media->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $media->save();

            if (count($categories) > 0) {
                $media->categories()->sync($categories);
            } else {
                $media->categories()->detach();
            }

            if (!empty($deletedLocales)) {
                $media->deleteTranslations($deletedLocales);
            }

            DB::commit();

            return $media;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $media = $this->model();

        try {
            $media->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}