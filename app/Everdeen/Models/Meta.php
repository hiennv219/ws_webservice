<?php

namespace Katniss\Everdeen\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use Translatable;

    const TYPE_STUDY_LEVEL = 1;
    const TYPE_STUDY_PROBLEM = 2;
    const TYPE_STUDY_COURSE = 3;

    public $useTranslationFallback = true;

    protected $table = 'meta';
    protected $fillable = ['name', 'description', 'order', 'type'];

    protected $translationForeignKey = 'meta_id';
    public $translatedAttributes = ['name', 'description'];
}