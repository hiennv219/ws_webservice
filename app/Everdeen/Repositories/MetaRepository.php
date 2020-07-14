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
use Katniss\Everdeen\Models\Meta;
use Katniss\Everdeen\Utils\AppConfig;

abstract class MetaRepository extends ByTypeRepository
{
    public function getById($id)
    {
        return Meta::with('translations')
            ->where('id', $id)
            ->where('type', $this->type)
            ->firstOrFail();
    }

    public function getPaged()
    {
        return Meta::with('translations')
            ->where('type', $this->type)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Meta::with('translations')
            ->where('type', $this->type)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function create(array $localizedData = [], $order = 0)
    {
        DB::beginTransaction();
        try {
            $meta = new Meta();
            $meta->order = $order;
            $meta->type = $this->type;
            foreach ($localizedData as $locale => $transData) {
                $trans = $meta->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->description = $transData['description'];
            }
            $meta->save();

            DB::commit();

            return $meta;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update(array $localizedData = [], $order = 0)
    {
        $meta = $this->model();
        $meta->order = $order;

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $meta->translateOrNew($locale);
                    $trans->name = $transData['name'];
                    $trans->description = $transData['description'];
                } elseif ($meta->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $meta->save();

            if (!empty($deletedLocales)) {
                $meta->deleteTranslations($deletedLocales);
            }
            DB::commit();

            return $meta;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $meta = $this->model();

        try {
            $meta->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}