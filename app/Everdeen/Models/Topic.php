<?php

namespace Katniss\Everdeen\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'topics';
    protected $fillable = ['name', 'description'];

    protected $translationForeignKey = 'topic_id';
    public $translatedAttributes = ['name', 'description'];

    public function teacherProfiles()
    {
        return $this->belongsToMany(Teacher::class, 'topics_teachers', 'topic_id', 'teacher_id');
    }

    public function teacherUserProfiles()
    {
        return $this->belongsToMany(User::class, 'topics_teachers', 'topic_id', 'teacher_id');
    }
}