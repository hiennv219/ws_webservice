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
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Utils\AppConfig;

abstract class CategoryRepository extends ByTypeRepository
{
    public function getById($id)
    {
        return Category::with('translations')
            ->where('id', $id)
            ->where('type', $this->type)
            ->firstOrFail();
    }

    public function getByIdWithTranslated($id)
    {
        return Category::with('translations')
            ->where('id', $id)
            ->where('type', $this->type)
            ->firstOrFail();
    }

    public function getPaged()
    {
        return Category::with('translations')
            ->where('type', $this->type)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Category::with('translations')
            ->where('type', $this->type)
            ->get();
    }

    public function create($parentId, array $localizedData = [], $order = 0)
    {
        DB::beginTransaction();
        try {
            $category = new Category();
            $category->order = $order;
            $category->type = $this->type;
            if ($parentId != 0) {
                $category->parent_id = $parentId;
            }
            foreach ($localizedData as $locale => $transData) {
                $trans = $category->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->slug = $transData['slug'];
                $trans->description = $transData['description'];
            }
            $category->save();

            DB::commit();

            return $category;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($parentId, array $localizedData = [], $order = 0)
    {
        $category = $this->model();
        $category->parent_id = $parentId != 0 && $parentId !== $category->parent_id ? $parentId : null;
        $category->order = $order;

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $category->translateOrNew($locale);
                    $trans->name = $transData['name'];
                    $trans->slug = $transData['slug'];
                    $trans->description = $transData['description'];
                } elseif ($category->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $category->save();

            if (!empty($deletedLocales)) {
                $category->deleteTranslations($deletedLocales);
            }
            DB::commit();

            return $category;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $category = $this->model();

        DB::beginTransaction();
        try {
            Category::where('parent_id', $category->id)->update(['parent_id' => null]);
            $category->delete();
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}