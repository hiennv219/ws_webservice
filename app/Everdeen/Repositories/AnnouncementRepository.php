<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Announcement;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\AppConfig;

class AnnouncementRepository extends ModelRepository
{
    public function getById($id)
    {
        return Announcement::where('id', $id)->firstOrFail();
    }

    public function getPaged()
    {
        return Announcement::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchPaged($title = null, $content = null)
    {
        $announcements = Announcement::with('author')
            ->orderBy('created_at', 'desc');
        if (!empty($title)) {
            $announcements->where('title', 'like', '%' . $title . '%');
        }
        if (!empty($content)) {
            $announcements->where('content', 'like', '%' . $content . '%');
        }
        return $announcements->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getCountByUser(&$userId, User &$user)
    {
        if (empty($userId) && empty($user)) return 0;

        if (empty($userId)) {
            $userId = $user->id;
        } else {
            $user = User::findOrFail($userId);
        }

        $roles = $user->roles->pluck('id')->all();
        $announcements = Announcement::where('to_all', 1);
        foreach ($roles as $role) {
            $announcements->orWhere('to_roles', 'like', '%,' . $role . ',%');
        }
        $announcements->orWhere('to_ids', 'like', '%,' . $user->id . ',%');
        return $announcements->count();
    }

    public function getPagedByUser(&$userId, User &$user)
    {
        if (empty($userId) && empty($user)) return 0;

        if (empty($userId)) {
            $userId = $user->id;
        } else {
            $user = User::findOrFail($userId);
        }

        $roles = $user->roles->pluck('id')->all();
        $announcements = Announcement::where('to_all', 1);
        foreach ($roles as $role) {
            $announcements->orWhere('to_roles', 'like', '%,' . $role . ',%');
        }
        $announcements->orWhere('to_ids', 'like', '%,' . $user->id . ',%');
        return $announcements->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchPagedByUser(&$userId, User &$user, $status = null)
    {
        if (empty($userId) && empty($user)) return 0;

        if (empty($userId)) {
            $userId = $user->id;
        } else {
            $user = User::findOrFail($userId);
        }

        $roles = $user->roles->pluck('id')->all();
        $announcements = Announcement::orderBy('created_at', 'desc');
        $announcements->where(function ($query) use ($roles, $user) {
            $query->where('to_all', 1);
            foreach ($roles as $role) {
                $query->orWhere('to_roles', 'like', '%,' . $role . ',%');
            }
            $query->orWhere('to_ids', 'like', '%,' . $user->id . ',%');
        });

        if ($status == 'read') {
            $announcements->join('read_announcements', function ($join) use ($userId) {
                $join->on('read_announcements.announcement_id', '=', 'announcements.id')
                    ->on('read_announcements.user_id', '=', DB::raw($userId));
            });
        } elseif ($status == 'unread') {
            $announcements->leftJoin('read_announcements', function ($join) use ($userId) {
                $join->on('read_announcements.announcement_id', '=', 'announcements.id')
                    ->on('read_announcements.user_id', '=', DB::raw($userId));
            })->whereNull('read_announcements.announcement_id');
        }

        return $announcements->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Announcement::all();
    }

    public function create($userId, $to, $content, $title = '')
    {
        try {
            $announcement = Announcement::create([
                'user_id' => $userId,
                'content' => $content,
                'title' => $title,
                'to_all' => !empty($to['all']) && $to['all'] == 1 ? 1 : 0,
                'to_roles' => empty($to['roles']) ? null : ',' . implode(',', $to['roles']) . ',',
                'to_ids' => empty($to['users']) ? null : ',' . implode(',', $to['users']) . ',',
            ]);

            return $announcement;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($userId, $to, $content, $title = '', $existedIds = [])
    {
        $announcement = $this->model();

        try {
            $toUsers = empty($to['users']) ? $existedIds : array_merge($to['users'], $existedIds);
            $announcement->update([
                'user_id' => $userId,
                'content' => $content,
                'title' => $title,
                'to_all' => !empty($to['all']) && $to['all'] == 1 ? 1 : 0,
                'to_roles' => empty($to['roles']) ? null : ',' . implode(',', $to['roles']) . ',',
                'to_ids' => empty($toUsers) ? null : ',' . implode(',', $toUsers) . ',',
            ]);

            return $announcement;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function read($userId)
    {
        $announcement = $this->model();

        try {
            if ($announcement->readUsers()->where('id', $userId)->count() <= 0) {
                $announcement->readUsers()->attach($userId);
            }

            return $announcement;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $announcement = $this->model();

        try {
            $announcement->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}