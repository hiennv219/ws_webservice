<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 11:03
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Repositories\ModelRepository;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Models\ContactForm;
use Katniss\Everdeen\Utils\AppConfig;

class ContactFormRepository extends ModelRepository
{
    public function getById($id)
    {
        return ContactForm::findOrFail($id);
    }

    public function getPaged()
    {
        return ContactForm::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return ContactForm::all();
    }

    public function create($fullName, $phone, $email, $message, $address = '', $website = '')
    {
        try {
            return ContactForm::create([
                'full_name' => $fullName,
                'phone' => $phone,
                'email' => $email,
                'message' => $message,
                'address' => $address,
                'website' => $website,
            ]);
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $contactForm = $this->model();

        try {
            $contactForm->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}