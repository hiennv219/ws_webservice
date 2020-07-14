<?php

namespace Katniss\Everdeen\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ProfessionalSkill extends Model
{
    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'professional_skills';
    protected $fillable = ['name', 'description'];

    protected $translationForeignKey = 'skill_id';
    public $translatedAttributes = ['name', 'description'];

    public function userProfiles()
    {
        return $this->belongsToMany(User::class, 'professional_skills_teachers', 'skill_id', 'user_id');
    }

    public function userTeacherProfiles()
    {
        return $this->belongsToMany(Teacher::class, 'professional_skills_teachers', 'skill_id', 'user_id');
    }
}