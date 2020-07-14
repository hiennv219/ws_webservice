<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:44
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls\Repositories;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Repositories\ModelRepository;
use Katniss\Everdeen\Themes\Plugins\Polls\Models\Choice;
use Katniss\Everdeen\Utils\AppConfig;

class ChoiceRepository extends ModelRepository
{
    public function getById($id)
    {
        return Choice::findOrFail($id);
    }

    public function getPaged()
    {
        return Choice::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Choice::all();
    }

    public function create($pollId, $votes = 0, array $localizedData = [])
    {
        DB::beginTransaction();
        try {
            $pollRepository = new PollRepository($pollId);

            $choice = new Choice();
            $choice->poll_id = $pollId;
            $choice->votes = $votes;
            $choice->order = $pollRepository->model()->choices()->count() + 1;
            foreach ($localizedData as $locale => $transData) {
                $trans = $choice->translateOrNew($locale);
                $trans->name = $transData['name'];
            }
            $choice->save();

            DB::commit();

            return $choice;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($pollId, $votes = 0, array $localizedData = [])
    {
        $choice = $this->model();

        DB::beginTransaction();
        try {
            $choice->poll_id = $pollId;
            $choice->votes = $votes;

            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $choice->translateOrNew($locale);
                    $trans->name = $transData['name'];
                } elseif ($choice->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }
            $choice->save();

            if (!empty($deletedLocales)) {
                $choice->deleteTranslations($deletedLocales);
            }

            DB::commit();

            return $choice;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $choice = $this->model();

        try {
            $choice->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}