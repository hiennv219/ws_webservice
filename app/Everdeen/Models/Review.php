<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class Review extends Model
{
    const TYPE_SINGLE = 0;
    const TYPE_MULTIPLE = 1;

    protected $table = 'reviews';

    protected $fillable = [
        'user_id', 'rate', 'rates', 'review', 'multi_rate'
    ];

    public function getIsMultiRateAttribute()
    {
        return $this->attributes['multi_rate'] == self::TYPE_MULTIPLE;
    }

    public function getRatesAttribute()
    {
        return !empty($this->attributes['rates']) ?
            unserialize($this->attributes['rates']) : [];
    }

    public function getHtmlReviewAttribute()
    {
        if (empty($this->attributes['review'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', preg_split('/\r*\n/', htmlspecialchars($this->attributes['review']))) . '</p>';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function classTimes()
    {
        return $this->belongsToMany(ClassTime::class, 'class_times_reviews', 'review_id', 'class_time_id');
    }
}
