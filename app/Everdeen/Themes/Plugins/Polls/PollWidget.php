<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 16:16
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Everdeen\Themes\Plugins\Polls\Repositories\PollRepository;

class PollWidget extends DefaultWidget
{
    const NAME = 'polls.widget';
    const DISPLAY_NAME = 'Poll';

    protected $pollId = '';

    protected function __init()
    {
        parent::__init();

        $this->pollId = $this->getProperty('poll_id');
    }

    public function register()
    {
        enqueueThemeFooter('<script>
    $(function(){
        var _isVoted = [];
        function initWidgetsOfPolls() {
            var cookie = Cookies.get(\'wdg:polls_widget:voted\');
            _isVoted = cookie ? cookie.split(\',\') : [];
            $(\'.widget-polls form\').each(function() {
                var $form = $(this);
                if(_isVoted.length <= 0 || _isVoted.indexOf($form.attr(\'data-id\')) == -1) {
                    $form.removeClass(\'hide\');
                }
                else {
                    $form.next().removeClass(\'hide\').find(\'button.show-poll-votes\').hide();
                }
            });
        }
        initWidgetsOfPolls();
        $(\'.widget-polls form button.show-poll-result\').on(\'click\', function(e){
            e.preventDefault();
            
            var $form = $(this).closest(\'form\');
            $form.next().removeClass(\'hide\');
            $form.addClass(\'hide\');
        });
        $(\'.widget-polls .poll-result button.show-poll-votes\').on(\'click\', function(e){
            e.preventDefault();
            
            var $result = $(this).closest(\'.poll-result\');
            $result.prev().removeClass(\'hide\');
            $result.addClass(\'hide\');
        });
        $(\'.widget-polls form\').on(\'submit\', function(){
            var $form = $(this);
            var $choices = $form.find(\'[name="choice_ids[]"]:checked\');
            if($choices.length <= 0) {
                x_modal_alert(\'' . trans('polls.must_select_one') . '\');
            }
            else {
                var choiceIds = [];
                $choices.each(function() {
                    choiceIds.push($(this).val());
                });
                var pollId = $form.attr(\'data-id\');
                var params = {
                    id: pollId,
                    choice_ids: choiceIds,
                    votes: 1
                };
                params[KATNISS_EXTRA_ROUTE_PARAM] = \'web-api/polls/id\';
                var api = new KatnissApi(true);
                api.put(\'extra\', params, function(isFailed, data, messages){
                    if(!isFailed) {
                        $choices.prop(\'checked\', false);
                        var $result = $form.next();
                        var $resultList = $result.children(\'ul\');
                        $resultList.empty();
                        var choices = data.choices;
                        for(var index in choices) {
                            $resultList.append(\'<li>\' + choices[index].name + \': \'+ choices[index].votes + \' \' + (choices[index].votes == 1 ? \'vote\' : \'votes\') + \'</li>\')
                        }
                        $result.removeClass(\'hide\');
                        $result.find(\'button.show-poll-votes\').hide();
                        _isVoted.push(pollId);
                        Cookies.set(\'wdg:polls_widget:voted\', _isVoted.join(\',\'), {
                            expires: 365
                        });
                        $form.addClass(\'hide\');
                    }
                });
            }
            
            return false;
        });
    });
</script>', 'polls_widget');
    }

    public function viewAdminParams()
    {
        $pollRepository = new PollRepository();

        return array_merge(parent::viewAdminParams(), [
            'poll_id' => $this->pollId,
            'polls' => $pollRepository->getAll(),
        ]);
    }

    public function viewHomeParams()
    {
        $choices = collect([]);
        $pollName = '';
        $pollDescription = '';
        $inputType = 'radio';
        if (!empty($this->pollId)) {
            $pollRepository = new PollRepository($this->pollId);
            $poll = $pollRepository->model();
            $choices = $poll->orderedChoices;
            $pollName = $poll->name;
            $pollDescription = $poll->description;
            $inputType = $poll->multi_choice ? 'checkbox' : 'radio';
        }
        return array_merge(parent::viewHomeParams(), [
            'poll_id' => $this->pollId,
            'poll_name' => $pollName,
            'poll_description' => $pollDescription,
            'input_type' => $inputType,
            'choices' => $choices,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'poll_id'
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'poll_id' => 'required|exists:polls,id',
        ]);
    }
}