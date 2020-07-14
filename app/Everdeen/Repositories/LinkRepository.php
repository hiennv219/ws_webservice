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
use Katniss\Everdeen\Models\Link;
use Katniss\Everdeen\Utils\AppConfig;

class LinkRepository extends ModelRepository
{
    public function getById($id)
    {
        return Link::with(['translations', 'categories', 'categories.translations'])
            ->findOrFail($id);
    }

    public function getPaged()
    {
        return Link::with(['translations', 'categories', 'categories.translations'])
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Link::with(['translations', 'categories', 'categories.translations'])->get();
    }

    public function create($image, array $categories = [], array $localizedData = [])
    {
        DB::beginTransaction();
        try {
            $link = new Link();
            $link->image = $image;
            foreach ($localizedData as $locale => $transData) {
                $trans = $link->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->description = $transData['description'];
                $trans->url = $transData['url'];
            }
            $link->save();
            if (count($categories) > 0) {
                $link->categories()->attach($categories);
            }

            DB::commit();

            return $link;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($image, array $categories = [], array $localizedData = [])
    {
        $link = $this->model();

        DB::beginTransaction();
        try {
            $link->image = $image;

            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $link->translateOrNew($locale);
                    $trans->name = $transData['name'];
                    $trans->description = $transData['description'];
                    $trans->url = $transData['url'];
                } elseif ($link->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $link->save();

            if (count($categories) > 0) {
                $link->categories()->sync($categories);
            } else {
                $link->categories()->detach();
            }

            if (!empty($deletedLocales)) {
                $link->deleteTranslations($deletedLocales);
            }

            DB::commit();

            return $link;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $link = $this->model();

        try {
            $link->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}