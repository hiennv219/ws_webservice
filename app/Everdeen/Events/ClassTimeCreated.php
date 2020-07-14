<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 08:36
 */

namespace Katniss\Everdeen\Events;


use Illuminate\Queue\SerializesModels;
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Models\User;

class ClassTimeCreated extends Event
{
    use SerializesModels;

    public $user;
    public $classTime;

    /**
     * Create a new event instance.
     *
     * @param  User $user
     * @return void
     */
    public function __construct(User $user, ClassTime $classTime, array $params = [], $locale = null)
    {
        parent::__construct($params, $locale);
        $this->user = $user;
        $this->classTime = $classTime;
    }

    public function getParamsForMailing()
    {
        return array_merge([
            'display_name' => $this->user->display_name,
            'subject' => $this->classTime->subject,
            'duration' => $this->classTime->duration . ' ' . trans_choice('label.hour_lc', $this->classTime->hours),
            'start_at' => $this->classTime->inverseFullFormattedStartAt,
            'html_content' => $this->classTime->htmlContent,
            'url_classroom' => homeUrl('classrooms/{id}', ['id' => $this->classTime->classroom_id]),
            'url_confirmation' => homeUrl('class-times/{id}/confirm', ['id' => $this->classTime->id]),
        ], $this->params);
    }
}