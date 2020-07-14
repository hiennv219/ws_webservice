<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\HomeTeacher;

use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'home_teacher';
    const DISPLAY_NAME = 'Home Teacher';

    protected $teachers;
    protected $tagLines;
    protected $reviews;

    public function __init()
    {
        parent::__init();

        $this->teachers = defPr($this->getProperty('teachers'), []);
        $this->tagLines = defPr($this->getProperty('tag_lines'), []);
        $this->reviews = defPr($this->getProperty('reviews'), []);
    }

    public function viewAdminParams()
    {
        $userRepository = new UserRepository();
        $teachers = [];
        foreach ($this->teachers as $teacher) {
            if (!empty($teacher)) {
                $user = $userRepository->getByIdLoosely($teacher);
                if (!empty($user)) {
                    $teachers[] = [
                        'id' => $user->id,
                        'email' => $user->email,
                        'display_name' => $user->display_name,
                    ];
                }
            }
        }

        $teacherLastOrder = count($this->teachers);
        $tagLineLastOrder = count($this->tagLines);
        $reviewLastOrder = count($this->reviews);
        return array_merge(parent::viewAdminParams(), [
            'last_order' => max($tagLineLastOrder, $teacherLastOrder, $reviewLastOrder),
            'teachers' => $teachers,
        ]);
    }

    public function register()
    {
        enqueueThemeHeader(
            '
<style>
#home-teacher .teacher-item{margin-bottom: 10px}
#home-teacher .teacher-item .media-left > a{display: block;position:relative}
#home-teacher .teacher-item .media-left > a > .teacher-avatar::after{
content: \' \';
position: absolute;
display: block;
top: 1px;
left: 1px;
width: 148px;
height: 148px;
border-radius: 4px;
background: transparent; /* For browsers that do not support gradients */
background: -webkit-linear-gradient(rgba(0,0,0,0) 50%, rgba(85,87,86,1)); /* For Safari 5.1 to 6.0 */
background: -o-linear-gradient(rgba(0,0,0,0) 50%, rgba(85,87,86,1)); /* For Opera 11.1 to 12.0 */
background: -moz-linear-gradient(rgba(0,0,0,0) 50%, rgba(85,87,86,1)); /* For Firefox 3.6 to 15 */
background: linear-gradient(rgba(0,0,0,0) 50%, rgba(85,87,86,1)); /* Standard syntax */
}
#home-teacher .teacher-item .media-left > a > .teacher-meta{position:absolute;left:0;bottom:8px}
#home-teacher .teacher-item .media-body .teacher-review{font-size: 13px;line-height: 18px}
#home-teacher .teacher-item .media-body .teacher-review img{width: 21px}
</style>',
            'widget_home_teacher'
        );
    }

    public function viewHomeParams()
    {
        $userRepository = new UserRepository();
        $teachers = [];
        $i = 0;
        foreach ($this->teachers as $teacher) {
            if (!empty($teacher)) {
                $user = $userRepository->getByIdLoosely($teacher);
                if (!empty($user)) {
                    $teachers[] = [
                        'url' => homeUrl('teachers/{id}', ['id' => $user->id]),
                        'avatar' => $user->url_avatar_thumb,
                        'display_name' => $user->display_name,
                        'nationality' => preg_replace('/\s*\(.*\)\s*/', '', allCountry($user->nationality, 'name')),
                        'tag_line' => $this->tagLines[$i],
                        'review' => $this->reviews[$i],
                    ];
                }
            }
            ++$i;
        }
        return array_merge(parent::viewHomeParams(), [
            'teachers' => $teachers,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'teachers'
        ]);
    }

    public function localizedFields()
    {
        return array_merge(parent::localizedFields(), [
            'tag_lines',
            'reviews',
        ]);
    }
}