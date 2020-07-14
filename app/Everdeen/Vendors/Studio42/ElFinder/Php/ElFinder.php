<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-16
 * Time: 19:22
 */

namespace Katniss\Everdeen\Vendors\Studio42\ElFinder\Php;


class ElFinder extends \elFinder
{
    protected function _rmCheck($path)
    {
        $ownDirectoryPath = userPublicPath(request()->authUser()->ownDirectory);
        $notDeletes = [
            concatDirectories($ownDirectoryPath, 'profile_pictures') => true,
        ];
        foreach ($notDeletes as $pathDelete => $loose) {
            if (!$loose && $pathDelete == $path) {
                return false;
            } elseif ($loose && beginsWith($pathDelete, $path)) {
                return false;
            }
        }
        return true;
    }

    protected function rm($args)
    {
        $targets = is_array($args['targets']) ? $args['targets'] : array();
        $result = array('removed' => array());


        foreach ($targets as $target) {
            elFinder::extendTimeLimit();

            if (($volume = $this->volume($target)) == false) {
                $result['warning'] = $this->error(self::ERROR_RM, '#' . $target, self::ERROR_FILE_NOT_FOUND);
                return $result;
            }
            if (!$this->_rmCheck($volume->getPath($target))) {
                $result['warning'] = trans('error.elfinder_rm_not_allowed');
                return $result;
            }
            if (!$volume->rm($target)) {
                $result['warning'] = $this->error($volume->error());
                return $result;
            }
        }

        return $result;
    }
}