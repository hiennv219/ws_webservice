<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserCertificate;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\Storage\StorePhoto;

class UserCertificateRepository extends ModelRepository
{
    public function getById($id)
    {
        return UserCertificate::findOrFail($id);
    }

    public function getPaged()
    {
        return UserCertificate::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return UserCertificate::all();
    }

    protected function getImageUrl($userId, $imageFilePath = null)
    {
        if (empty($imageFilePath)) return null;
        $storePhoto = new StorePhoto($imageFilePath);
        $user = User::findOrFail($userId);
        $storePhoto->move(userPublicPath($user->certificateDirectory), randomizeFilename());
        return publicUrl($storePhoto->getTargetFileRealPath());
    }

    public function create($userId, $type, $provided_by = '', $provided_at = null,
                           $imageFilePath = null, $meta = null,
                           $description = '')
    {
        try {
            $certificate = UserCertificate::create([
                'user_id' => $userId,
                'type' => $type,
                'provided_by' => $provided_by,
                'provided_at' => empty($provided_at) ?
                    null : DateTimeHelper::getInstance()->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $provided_at),
                'image' => $this->getImageUrl($userId, $imageFilePath),
                'meta' => empty($meta) ? null : serialize($meta),
                'description' => $description,
            ]);

            return $certificate;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($userId, $type, $provided_by = '', $provided_at = null,
                           $imageFilePath = null, $meta = null,
                           $description = '')
    {
        $certificate = $this->model();
        try {
            $data = [
                'user_id' => $userId,
                'type' => $type,
                'provided_by' => $provided_by,
                'provided_at' => empty($provided_at) ?
                    null : DateTimeHelper::getInstance()->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $provided_at),
                'meta' => empty($meta) ? null : serialize($meta),
                'description' => $description,
            ];
            if (!empty($imageFilePath)) {
                $data['image'] = $this->getImageUrl($userId, $imageFilePath);
            }
            $certificate->update($data);

            return $certificate;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $certificate = $this->model();

        try {
            $certificate->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}