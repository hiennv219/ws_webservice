<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Katniss\Everdeen\Models\Meta;

class StudyCourseRepository extends MetaRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Meta::TYPE_STUDY_COURSE, $id);
    }
}