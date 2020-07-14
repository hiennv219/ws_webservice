<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\TeacherArticles;

use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Repositories\TeacherRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'teacher_articles';
    const DISPLAY_NAME = 'Teacher Articles';

    protected $teachers;
    protected $numberOfItems;

    public function __init()
    {
        parent::__init();

        $this->teachers = defPr($this->getProperty('teachers'), []);
        $this->numberOfItems = defPr($this->getProperty('number_of_items'), 10);
    }

    public function register()
    {
        enqueueThemeHeader('<style>
.widget-teacher-articles ul{font-size:13px}
.widget-teacher-articles ul i{font-size:20px;vertical-align:middle;margin-right:5px;margin-left:2px}
.widget-teacher-articles ul a:hover span{font-weight:600}
</style>', 'widget_teacher_articles');
    }

    public function viewAdminParams()
    {
        $teacherRepository = new TeacherRepository();
        $teachers = [];
        if (!empty($this->teachers)) {
            $teachers = $teacherRepository->getApprovedByIds($this->teachers);
        }
        return array_merge(parent::viewAdminParams(), [
            'teachers' => $teachers,
            'number_of_items' => $this->numberOfItems,
        ]);
    }

    public function viewHomeParams()
    {
        $articleRepository = new ArticleRepository();
        if (!empty($this->teachers)) {
            $articles = $articleRepository->getLastPublishedByAuthorIds($this->numberOfItems, $this->teachers);
        }
        else {
            $articles = $articleRepository->getLastPublishedByTeachers($this->numberOfItems);
        }
        return array_merge(parent::viewHomeParams(), [
            'articles' => $articles,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'teachers',
            'number_of_items',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'teachers' => 'sometimes|nullable|array',
            'number_of_items' => 'sometimes|nullable|integer|min:1',
        ]);
    }
}