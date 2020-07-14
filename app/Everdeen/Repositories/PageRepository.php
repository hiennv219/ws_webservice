<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 18:35
 */

namespace Katniss\Everdeen\Repositories;

use Katniss\Everdeen\Models\Post;

class PageRepository extends PostRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Post::TYPE_PAGE, $id);
    }
}