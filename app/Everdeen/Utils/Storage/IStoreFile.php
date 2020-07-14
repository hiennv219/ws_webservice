<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-25
 * Time: 23:00
 */

namespace Katniss\Everdeen\Utils\Storage;


interface IStoreFile
{
    public function move($targetDirectory, $name = null, $autoExtension = true);
    public function copy($targetDirectory, $name = null, $autoExtension = true);
    public function duplicate($targetDirectory, $name = null, $autoExtension = true);
}