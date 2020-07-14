<form id="children-form" method="post" class="{{ old('for_children') == 1 ? '' : 'hide' }}" action="{{ homeUrl('student/sign-up/step/{step}', ['step' => 2]) }}">
    {{ csrf_field() }}
    <input type="hidden" name="{{ \Katniss\Everdeen\Utils\AppConfig::KEY_WIZARD_NAME }}" value="{{ $wizard_name }}">
    <input type="hidden" name="{{ \Katniss\Everdeen\Utils\AppConfig::KEY_WIZARD_KEY }}" value="{{ $wizard_key }}">
    <input type="hidden" name="student_id" value="{{ $student_id }}">
    <input type="hidden" name="teacher_id" value="{{ $teacher_id }}">
    <input type="hidden" name="for_children" value="1">
    <input type="hidden" name="study_level" value="{{ $study_level }}">
    <input type="hidden" name="study_problem" value="{{ $study_problem }}">
    <input type="hidden" name="study_course" value="{{ $study_course }}">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="form-group">
        <label for="inputChildrenFullName_children">{{ trans('label.your_children_full_name') }}</label>
        <input class="form-control" id="inputChildrenFullName_children" placeholder="{{ trans('label.your_children_full_name') }}"
               type="text" required name="children_full_name" value="{{ old('children_full_name') }}">
    </div>
    <div class="form-group">
        <label for="inputAgeRange_children">{{ trans('label.your_children_age_range') }}</label>
        <select id="inputAgeRange_children" class="form-control" name="age_range" required>
            <option value="">- {{ trans('form.action_select') }} -</option>
            @foreach($age_ranges_children as $age_range)
                <option value="{{ $age_range }}"{{ old('age_range') == $age_range ? ' selected' : '' }}>
                    {{ trans('label.age_range_' . $age_range) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="inputSkypeId_children">Skype ID</label>
        <span><em>({{ trans('label.optional') }})</em></span>
        <input class="form-control" id="inputSkypeId_children" type="text" placeholder="Skype ID" name="skype_id" value="{{ old('skype_id') }}">
    </div>
    <div class="form-group">
        <label class="control-label">{{ trans('label.your_children_learning_targets') }}</label>
        @foreach($learning_targets_children as $learning_target)
            <div class="checkbox">
                <label>
                    <input {{ in_array($learning_target, old('learning_targets', [])) ? ' checked' : '' }}
                           {!! $learning_target == '101' ? 'id="inputOtherLearningTarget_children"' : '' !!}
                           type="checkbox" name="learning_targets[]"
                           value="{{ $learning_target }}">
                    {{ trans('label.learning_target_' . $learning_target) }}
                </label>
            </div>
        @endforeach
        <label for="inputOtherLearningTarget_childrens" class="sr-only">Others</label>
        <input id="inputOtherLearningTargets_children" type="text" class="form-control hide" value="{{ old('learning_target_other') }}" name="learning_target_other">
    </div>
    <div class="form-group">
        <label class="control-label">{{ trans('label.your_children_learning_forms') }}</label>
        @foreach($learning_forms_children as $learning_form)
            <div class="checkbox">
                <label>
                    <input {{ in_array($learning_form, old('learning_forms', [])) ? ' checked' : '' }}
                           {!! $learning_form == '101' ? 'id="inputOtherLearningForm_children"' : '' !!}
                           type="checkbox" name="learning_forms[]"
                           value="{{ $learning_form }}">
                    {{ trans('label.learning_form_' . $learning_form) }}
                </label>
            </div>
        @endforeach
        <label for="inputOtherLearningForms_children" class="sr-only">Others</label>
        <input id="inputOtherLearningForms_children" type="text" class="form-control hide" value="{{ old('learning_form_other') }}" name="learning_form_other">
    </div>
    <div class="form-group margin-bottom-none">
        <button type="submit" class="btn btn-success uppercase btn-block"><strong>{{ trans('form.action_complete') }}</strong></button>
    </div>
</form>