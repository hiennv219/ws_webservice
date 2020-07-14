<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:43
 */

namespace Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Repositories\ModelRepository;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Models\MapMarker;
use Katniss\Everdeen\Utils\AppConfig;

class MapMarkerRepository extends ModelRepository
{
    public function getById($id)
    {
        return MapMarker::findOrFail($id);
    }

    public function getPaged()
    {
        return MapMarker::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return MapMarker::all();
    }

    public function create(array $data, array $localizedData = [])
    {
        DB::beginTransaction();
        try {
            $mapMarker = new MapMarker();
            $mapMarker->data = json_encode($data);
            foreach ($localizedData as $locale => $transData) {
                $trans = $mapMarker->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->description = $transData['description'];
            }
            $mapMarker->save();

            DB::commit();

            return $mapMarker;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update(array $data, array $localizedData = [])
    {
        $mapMarker = $this->model();

        DB::beginTransaction();
        try {
            $mapMarker->data = json_encode($data);

            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $mapMarker->translateOrNew($locale);
                    $trans->name = $transData['name'];
                    $trans->description = $transData['description'];
                } elseif ($mapMarker->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }
            $mapMarker->save();

            if (!empty($deletedLocales)) {
                $mapMarker->deleteTranslations($deletedLocales);
            }

            DB::commit();

            return $mapMarker;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $mapMarker = $this->model();

        try {
            $mapMarker->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}