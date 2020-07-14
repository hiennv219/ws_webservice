<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 22:50
 */

namespace Katniss\Everdeen\Utils\Storage;


class StorePhotoByCropperJs extends StorePhoto
{
    protected $offsetX;
    protected $offsetY;
    protected $width;
    protected $height;
    protected $angle;

    public function __construct($sourceFile, $cropperJson = null)
    {
        parent::__construct($sourceFile);

        if (!empty($cropperJson)) {
            $data = json_decode($cropperJson);
            if (!empty($data)) {
                $this->offsetX = isset($data->x) ? intval($data->x) : 0;
                $this->offsetY = isset($data->y) ? intval($data->y) : 0;
                $this->width = isset($data->width) ? intval($data->width) : 0;
                $this->height = isset($data->height) ? intval($data->height) : 0;
                $this->angle = isset($data->rotate) ? floatval($data->rotate) : 0;

                $needSave = false;
                if ($this->angle != 0) {
                    $this->rotate($this->angle);
                    $needSave = true;
                }
                if ($this->width > 0 && $this->height > 0) {
                    $this->crop($this->width, $this->height, $this->offsetX, $this->offsetY);
                    $needSave = true;
                }
                if ($needSave) {
                    $this->save();
                }
            }
        }
    }
}