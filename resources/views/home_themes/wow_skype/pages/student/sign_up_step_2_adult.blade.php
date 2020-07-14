<form id="adult-form" class="{{ old('for_children') == 1 ? 'hide' : '' }}" method="post" action="{{ homeUrl('student/sign-up/step/{step}', ['step' => 2]) }}">
    {{ csrf_field() }}
    <input type="hidden" name="{{ \Katniss\Everdeen\Utils\AppConfig::KEY_WIZARD_NAME }}" value="{{ $wizard_name }}">
    <input type="hidden" name="{{ \Katniss\Everdeen\Utils\AppConfig::KEY_WIZARD_KEY }}" value="{{ $wizard_key }}">
    <input type="hidden" name="student_id" value="{{ $student_id }}">
    <input type="hidden" name="teacher_id" value="{{ $teacher_id }}">
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
        <label for="inputAgeRange">{{ trans('label.your_age_range') }}</label>
        <select id="inputAgeRange" class="form-control" name="age_range" required>
            <option value="">- {{ trans('form.action_select') }} -</option>
            @foreach($age_ranges as $age_range)
                <option value="{{ $age_range }}"{{ old('age_range') == $age_range ? ' selected' : '' }}>
                    {{ trans('label.age_range_' . $age_range) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="inputProfessionalSkills">{{ trans_choice('label.professional_skill', 2) }}</label>
        <select class="form-control select2" id="inputProfessionalSkills" name="professional_skills[]" required multiple="multiple"
                data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.professional_skill_lc', 2) }} -" style="width: 100%;">
            @foreach($professional_skills as $professional_skill)
                <option value="{{ $professional_skill->id }}"{{ in_array($professional_skill->id, old('professional_skills', [])) ? ' selected' : '' }}>
                    {{ $professional_skill->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="inputSkypeId">Skype ID</label>
        <span><em>({{ trans('label.optional') }})</em></span>
        <input class="form-control" id="inputSkypeId" type="text" placeholder="Skype ID" name="skype_id" value="{{ old('skype_id') }}">
    </div>
    <div class="form-group">
        <label class="control-label">{{ trans('label.your_learning_targets') }}</label>
        @foreach($learning_targets as $learning_target)
            <div class="checkbox">
                <label>
                    <input {{ in_array($learning_target, old('learning_targets', [])) ? ' checked' : '' }}
                           {!! $learning_target == '100' ? 'id="inputOtherLearningTarget"' : '' !!}
                           type="checkbox" name="learning_targets[]"
                           value="{{ $learning_target }}">
                    {{ trans('label.learning_target_' . $learning_target) }}
                </label>
            </div>
        @endforeach
        <label for="inputOtherLearningTargets" class="sr-only">Others</label>
        <input id="inputOtherLearningTargets" type="text" class="form-control hide" value="{{ old('learning_target_other') }}" name="learning_target_other">
    </div>
    <div class="form-group">
        <label class="control-label">{{ trans('label.your_learning_forms') }}</label>
        @foreach($learning_forms as $learning_form)
            <div class="checkbox">
                <label>
                    <input {{ in_array($learning_form, old('learning_forms', [])) ? ' checked' : '' }}
                           {!! $learning_form == '100' ? 'id="inputOtherLearningForm"' : '' !!}
                           type="checkbox" name="learning_forms[]"
                           value="{{ $learning_form }}">
                    {{ trans('label.learning_form_' . $learning_form) }}
                </label>
            </div>
        @endforeach
        <label for="inputOtherLearningForms" class="sr-only">Others</label>
        <input id="inputOtherLearningForms" type="text" class="form-control hide" value="{{ old('learning_form_other') }}" name="learning_form_other">
    </div>
    <div class="form-group margin-bottom-none">
        <button type="submit" class="btn btn-success uppercase btn-block"><strong>{{ trans('form.action_complete') }}</strong></button>
    </div>
</form>