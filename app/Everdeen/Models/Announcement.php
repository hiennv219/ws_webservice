<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 21:02
 */

namespace Katniss\Everdeen\Models;


use Illuminate\Database\Eloquent\Model;
use Katniss\Everdeen\Utils\DateTimeHelper;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = ['user_id', 'title', 'content', 'to_all', 'to_roles', 'to_ids', 'type'];

    public function getToAllAttribute()
    {
        return $this->attributes['to_all'] == 1;
    }

    public function getToRolesAttribute()
    {
        if (empty($this->attributes['to_roles'])) {
            return [];
        }
        return explode(',', trim($this->attributes['to_roles'], ','));
    }

    public function getToUsersAttribute()
    {
        if (empty($this->attributes['to_ids'])) {
            return [];
        }
        return explode(',', trim($this->attributes['to_ids'], ','));
    }

    public function getHtmlContentAttribute()
    {
        if (empty($this->attributes['content'])) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, htmlspecialchars($this->attributes['content']))) . '</p>';
    }

    public function getDiffDaysAttribute()
    {
        return DateTimeHelper::diffDay($this->attributes['created_at']);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function readUsers()
    {
        return $this->belongsToMany(User::class, 'read_announcements', 'announcement_id', 'user_id');
    }
}