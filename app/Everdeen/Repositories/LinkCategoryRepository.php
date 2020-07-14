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

class LinkCategoryRepository extends CategoryRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Category::TYPE_LINK, $id);
    }

    public function getByIdWithTranslatedLinks($id)
    {
        return Category::with(['links', 'links.translations'])
            ->where('id', $id)
            ->where('type', $this->type)
            ->firstOrFail();
    }

    public function updateSort($linkIds)
    {
        $category = $this->model();

        DB::beginTransaction();
        try {
            $order = 0;
            $category_links = $category->links();
            foreach ($linkIds as $linkId) {
                ++$order;
                $category_links->updateExistingPivot($linkId, ['order' => $order]);
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
        if ($category->links()->count() > 0) {
            throw new KatnissException(trans('error.category_not_empty'));
        }

        return parent::delete();
    }
}