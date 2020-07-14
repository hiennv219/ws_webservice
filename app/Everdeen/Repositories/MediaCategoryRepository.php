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

class MediaCategoryRepository extends CategoryRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Category::TYPE_MEDIA, $id);
    }

    public function updateSort(array $mediaIds)
    {
        $category = $this->model();

        DB::beginTransaction();
        try {
            $order = 0;
            $categoryMedia = $category->media();
            foreach ($mediaIds as $mediaId) {
                ++$order;
                $categoryMedia->updateExistingPivot($mediaId, ['order' => $order]);
            }
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $category = $this->model();
        if ($category->media()->count() > 0) {
            throw new KatnissException(trans('error.category_not_empty'));
        }

        return parent::delete();
    }
}