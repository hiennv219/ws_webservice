<div id="home-register" class="padding-v-20 margin-bottom-20">
    <div class="row">
        <div class="col-sm-2 col-md-3"></div>
        <div class="col-sm-8 col-md-6">
            <form id="home-register-form" class="padding-left-20 padding-right-20" method="get"
                  action="{{ homeUrl('student/sign-up') }}">
                <div class="form-group">
                    <label for="inputStudyLevel" class="sr-only">{{ trans_choice('label.study_level_1_lc', 1) }}</label>
                    <select id="inputStudyLevel" name="study_level" class="form-control form-control-master">
                        <option value="">{{ trans('form.action_select') }} {{ trans_choice('label.study_level_1_lc', 1) }}</option>
                        @foreach($study_levels as $study_level)
                            <option value="{{ $study_level->id }}">{{ $study_level->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputStudyProblem" class="sr-only">{{ trans_choice('label.study_problem_1_lc', 1) }}</label>
                    <select id="inputStudyProblem" name="study_problem" class="form-control form-control-master">
                        <option value="">{{ trans('form.action_select') }} {{ trans_choice('label.study_problem_1_lc', 1) }}</option>
                        @foreach($study_problems as $study_problem)
                            <option value="{{ $study_problem->id }}">{{ $study_problem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputStudyCourse" class="sr-only">{{ trans_choice('label.study_course_1_lc', 1) }}</label>
                    <select id="inputStudyCourse" name="study_course" class="form-control form-control-master">
                        <option value="">{{ trans('form.action_select') }} {{ trans_choice('label.study_course_1_lc', 1) }}</option>
                        @foreach($study_courses as $study_course)
                            <option value="{{ $study_course->id }}">{{ $study_course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block uppercase bold-700" type="submit">
                        {{ trans('form.action_register') }}
                    </button>
                </div>
                <div class="form-group margin-bottom-none">
                    <a role="button" class="btn btn-success btn-block bold-700"
                       href="{{ homeUrl('student/sign-up') }}">
                        {{ trans('label.or_lc') }} <span class="uppercase">{{ trans('form.action_register_class') }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>