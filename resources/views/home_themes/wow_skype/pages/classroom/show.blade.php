@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('barrating/themes/fontawesome-stars.css') }}">
@endsection
@section('extended_styles')
    <style>
        .br-theme-fontawesome-stars .br-widget .br-current-rating {
            display: inline-block;
            vertical-align: middle;
            margin-top: -6px;
            margin-left: 6px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('moment/moment.min.js') }}"></script>
    <script src="{{ libraryAsset('moment/locale/' . $site_locale . '.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/inputmask.binding.js') }}"></script>
    <script src="{{ libraryAsset('barrating/jquery.barrating.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            var _canAddTeacherReview = '{{ $can_add_teacher_review ? 1 : 0 }}';
            var _canAddStudentReview = '{{ $can_add_student_review ? 1 : 0 }}';
            var _canConfirmClassTime = '{{ $can_confirm_class_time ? 1 : 0 }}';
            var _urlConfirm = '{{ homeUrl('class-times/{id}/confirm', ['id' => '{id}']) }}';

            function renderClassTime(classTime, maxRate) {
                if(classTime.is_periodic) {
                    return renderPeriodicClassTime(classTime, maxRate);
                }
                return '<li id="class-time-' + classTime.id + '" class="media class-time-item month-' + classTime.month_year_start_at + '" ' +
                    'data-id="' + classTime.id + '" data-subject="' + classTime.subject + '" data-content="' + classTime.content + '">' +
                    '<div class="media-line"></div>' +
                    '<div class="media-left"><span>' + classTime.order + '</span></div>' +
                    '<div class="media-body">' +
                    '<div class="row">'+
                        '<div class="col-sm-6">'+
                            '<div class="media-heading">' +
                            @if($can_classroom_edit)
                                '<a class="edit-class-time big pull-right" title="{{ trans('form.action_edit') }} {{ trans_choice('label.class_time', 1) }}" href="#">' +
                                '<i class="fa fa-edit"></i>' +
                                '</a>' +
                            @endif
                            '<span class="class-time-subject">' + classTime.subject + '</span>' +
                            '</div>' +
                            (classTime.confirmed ? '' : '<div class="help-block"><label class="label label-danger">{{ trans('label.status_student_not_confirmed') }}</label>' +
                                (_canConfirmClassTime ? ' <a class="btn btn-xs btn-success" href="' + _urlConfirm.replace('{id}', classTime.id) + '">{{ trans('form.action_confirm') }}</a>' : '') + '</div>') +
                            '<div class="help-block">' + classTime.duration + ' - {{ trans('label.start_at') }} ' + classTime.start_at + '</div>' +
                            '<div class="bg-warning padding-10 class-time-content' + (classTime.content != '' ? '' : ' hide') + '">' +
                            classTime.html_content +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-3">'+
                            '<div class="h6 margin-top-none"><strong>{{ trans('label.teacher_feedback') }}</strong></div>'+
                            (classTime.teacher_review == null ?
                                (_canAddTeacherReview == 1 ?
                                    '<button type="button" class="btn btn-success btn-block margin-bottom-10 teacher-add-review">{{ trans('form.action_add_feedback') }}</button>'
                                        : '<div>{{ trans('label.no_teacher_feedback_yet') }}</div>')
                                    : renderReview(classTime.teacher_review, maxRate)) +
                        '</div>' +
                        '<div class="col-sm-3">'+
                            '<div class="h6 margin-top-none"><strong>{{ trans('label.student_rating') }}</strong></div>'+
                            (classTime.student_review == null ?
                                (_canAddStudentReview == 1 ?
                                    '<button type="button" class="btn btn-success btn-block margin-bottom-10 student-add-review">{{ trans('form.action_add_rating') }}</button>'
                                        : '<div>{{ trans('label.no_student_rating_yet') }}</div>')
                                    : renderReview(classTime.student_review, maxRate)) +
                        '</div>' +
                    '</div>' +
                    '</div>' +
                    '</li>';
            }

            function renderPeriodicClassTime(classTime, maxRate) {
                return '<li id="class-time-' + classTime.id + '" data-id="' + classTime.id + '" ' +
                    'class="media class-time-item class-time-periodic month-' + classTime.month_year_start_at + '">' +
                    '<div class="media-line"></div>' +
                    '<div class="media-left"><span class="warning"><i class="fa fa-star"></i></span></div>' +
                    '<div class="media-body">' +
                        '<div class="media-heading">' +
                            '<span class="text-warning">' + classTime.trans_after + '</span>' +
                        '</div>' +
                        '<div class="row">'+
                            '<div class="col-sm-6">'+
                                '<div class="h6 margin-top-none"><strong>{{ trans('label.teacher_review') }}</strong></div>'+
                                (classTime.teacher_review == null ?
                                    (_canAddTeacherReview == 1 ?
                                        '<button type="button" class="btn btn-warning margin-bottom-10 teacher-add-rich-review">{{ trans('form.action_review_student') }}</button>'
                                            : '<div>{{ trans('label.no_teacher_review_yet') }}</div>')
                                        : renderRichReview(classTime.teacher_review, maxRate)) +
                            '</div>' +
                            '<div class="col-sm-6">'+
                                '<div class="h6 margin-top-none"><strong>{{ trans('label.student_review') }}</strong></div>'+
                                (classTime.student_review == null ?
                                    (_canAddStudentReview == 1 ?
                                        '<button type="button" class="btn btn-warning margin-bottom-10 student-add-rich-review">{{ trans('form.action_review_teacher') }}</button>'
                                            : '<div>{{ trans('label.no_student_review_yet') }}</div>')
                                        : renderRichReview(classTime.student_review, maxRate)) +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '</li>';
            }

            function renderTotal(sumHours, monthYear, transMonthYear) {
                var formatter = new NumberFormatHelper();
                return '<li class="media class-time-month" data-hours="' + sumHours + '" ' +
                    'data-target=".month-' + monthYear + '">' +
                    '<div class="media-line"></div>' +
                    '<div class="media-left">' +
                    '<span class="info">Σ</span>' +
                    '</div>' +
                    '<div class="media-body">' +
                    '<div class="media-heading text-info">' + '{{ trans('label.statistics') }} - ' + transMonthYear + '</div>' +
                    '<div>' +
                    '{{ trans('label.total') }}: ' +
                    '<span class="sum-hours">' +
                    formatter.format(sumHours) +
                    (sumHours == 1 ? ' {{ trans_choice('label.hour_lc', 1) }}' : ' {{ trans_choice('label.hour_lc', 2) }}') +
                    '</span>' +
                    '</div>' +
                    '</div>' +
                    '</li>';
            }

            function renderReview(review, maxRate) {
                var str = '<div class="help-block color-star" title="' + review.trans_rate + '">';
                for (var i = 0; i < maxRate; ++i) {
                    str += '<i class="fa ' + (i < review.rate ? 'fa-star' : 'fa-star-o') + '"></i>';
                }
                str += '</div>';
                if (review.content != '') {
                    str += '<div>' + review.html_review + '</div>';
                }
                return str;
            }

            function renderRichReview(review, maxRate) {
                var str = '<div class="row">';
                for (var name in review.rates) {
                    str += '<div class="col-sm-4">';
                    str += '<div>' + review.trans_rate_names[name] + '</div>';
                    str += '<div class="help-block color-star" title="' + review.trans_rates[name] + '">';
                    for (var i = 0; i < maxRate; ++i) {
                        str += '<i class="fa ' + (i < review.rates[name] ? 'fa-star' : 'fa-star-o') + '"></i>';
                    }
                    str += '</div>';
                    str += '</div>';
                }
                str += '</div>';
                if (review.content != '') {
                    str += '<div>' + review.html_review + '</div>';
                }
                return str;
            }

            $('.rating').barrating({
                theme: 'fontawesome-stars'
            });
            $('.date-time-picker').datetimepicker({
                format: '{{ $date_js_format }}'
            });

            var $classroomName = $('#classroom-name');
            var $classroomSpentTime = $('#spent-time-total');
            var $updateClassroomNameForm = $('#update-classroom-name-form');
            var $updateClassroomNameFormLoading = $updateClassroomNameForm.find('.update-classroom-name-form-loading');
            var $updateClassroomNameModal = $('#update-classroom-name-modal');
            var $addClassTimeList = $('#class-time-list');
            var $addClassTimeLastItem = $addClassTimeList.children('.class-time-add');
            var $addClassTimeFirstItem = $addClassTimeList.children('.class-time-previous');
            var $addClassTimeForm = $('#add-class-time-form');
            var $addClassTimeFormLoading = $updateClassroomNameForm.find('.add-class-time-form-loading');
            var $addClassTimeModal = $('#add-class-time-modal');
            var $updateClassTimeForm = $('#update-class-time-form');
            var $updateClassTimeFormLoading = $updateClassroomNameForm.find('.update-class-time-form-loading');
            var $updateClassTimeModal = $('#update-class-time-modal');
            var $teacherAddReviewForm = $('#teacher-add-review-form');
            var $teacherAddReviewFormLoading = $teacherAddReviewForm.find('.teacher-add-review-form-loading');
            var $teacherAddReviewModal = $('#teacher-add-review-modal');
            var $studentAddReviewForm = $('#student-add-review-form');
            var $studentAddReviewFormLoading = $studentAddReviewForm.find('.student-add-review-form-loading');
            var $studentAddReviewModal = $('#student-add-review-modal');
            var $teacherAddRichReviewForm = $('#teacher-add-rich-review-form');
            var $teacherAddRichReviewFormLoading = $teacherAddRichReviewForm.find('.teacher-add-review-form-loading');
            var $teacherAddRichReviewModal = $('#teacher-add-rich-review-modal');
            var $studentAddRichReviewForm = $('#student-add-rich-review-form');
            var $studentAddRichReviewFormLoading = $studentAddRichReviewForm.find('.student-add-rich-review-form-loading');
            var $studentAddRichReviewModal = $('#student-add-rich-review-modal');

            $updateClassroomNameModal.on('show.bs.modal', function () {
                $updateClassroomNameForm.find('[name="name"]').val($classroomName.text());
            });

            $updateClassroomNameForm.on('submit', function (e) {
                e.preventDefault();

                $updateClassroomNameFormLoading.removeClass('hide');

                var $alert = $updateClassroomNameForm.find('.alert');
                var $submit = $updateClassroomNameForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.put('classrooms/{{ $classroom->id }}', {
                    only_name: 1,
                    name: $updateClassroomNameForm.find('[name="name"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $updateClassroomNameModal.modal('hide');

                        $classroomName.text(data.classroom.name);
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.add_class_time_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $updateClassroomNameFormLoading.addClass('hide');
                });
            });

            $addClassTimeModal.on('hide.bs.modal', function () {
                $addClassTimeForm.find('.form-control').val('');
            });

            $addClassTimeForm.on('submit', function (e) {
                e.preventDefault();

                $addClassTimeFormLoading.removeClass('hide');

                var $alert = $addClassTimeForm.find('.alert');
                var $submit = $addClassTimeForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.post('class-times', {
                    classroom: '{{ $classroom->id }}',
                    subject: $addClassTimeForm.find('[name="subject"]').val(),
                    content: $addClassTimeForm.find('[name="content"]').val(),
                    duration: $addClassTimeForm.find('[name="duration"]').val(),
                    start_at: $addClassTimeForm.find('[name="start_at"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $addClassTimeModal.modal('hide');

                        var formatter = new NumberFormatHelper();
                        var monthYearStartAt = data.class_time.month_year_start_at;
                        if ($('.class-time-item.month-' + monthYearStartAt).length > 0) {
                            var $prev = $addClassTimeLastItem.prev();
                            $prev.before(renderClassTime(data.class_time));
                            if(data.class_time_periodic) {
                                $prev.before(renderPeriodicClassTime(data.class_time_periodic, data.max_rate));
                            }
                            var hours = parseFloat($prev.attr('data-hours')) + data.class_time.hours;
                            $prev.attr('data-hours', hours).find('.sum-hours').text(
                                formatter.format(hours) + (hours == 1 ? ' {{ trans_choice('label.hour_lc', 1) }}' : ' {{ trans_choice('label.hour_lc', 2) }}')
                            );
                        }
                        else {
                            $addClassTimeLastItem.before(renderClassTime(data.class_time, data.max_rate));
                            if(data.class_time_periodic) {
                                $addClassTimeLastItem.before(renderPeriodicClassTime(data.class_time_periodic, data.max_rate));
                            }
                            $addClassTimeLastItem.before(renderTotal(
                                data.class_time.hours,
                                data.class_time.month_year_start_at,
                                data.class_time.trans_month_year_start_at
                            ));
                        }

                        var spentTime = parseFloat($classroomSpentTime.attr('data-hours')) + data.class_time.hours;
                        $classroomSpentTime.attr('data-hours', spentTime).text(
                            formatter.format(spentTime) + (spentTime == 1 ? ' {{ trans_choice('label.hour_lc', 1) }}' : ' {{ trans_choice('label.hour_lc', 2) }}')
                        );

                        if (data.overtime == 0) {
                            $('.classroom-close').removeClass('hide');
                            $('.class-time-add').remove();
                        }
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.add_class_time_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $addClassTimeFormLoading.addClass('hide');
                });
            });

            $('.delete-class-time').on('click', function (e) {
                e.preventDefault();

                $updateClassTimeModal.modal('hide');

                x_modal_form(
                    '{{ trans('form.action_delete') }}',
                    '{{ trans('label.wanna_delete', ['name' => '']) }}',
                    $(this).attr('data-delete'),
                    'delete'
                );
            });
            $(document).on('click', '.edit-class-time', function (e) {
                e.preventDefault();

                var $classTime = $(this).closest('.class-time-item');
                $updateClassTimeForm.find('[name="id"]').val($classTime.attr('data-id'));
                $updateClassTimeForm.find('[name="subject"]').val($classTime.attr('data-subject'));
                $updateClassTimeForm.find('[name="content"]').val($classTime.attr('data-content'));

                if($(this).closest('.class-time-item').attr('id') == $('.class-time-item:not(.class-time-periodic):last').attr('id')) {
                    $updateClassTimeModal.find('.delete-class-time')
                        .removeClass('hide')
                        .attr('data-delete', '{{ homeUrl('class-times/{id}', ['id'=> '']) }}/' + $classTime.attr('data-id'));
                }
                else {
                    $updateClassTimeModal.find('.delete-class-time')
                        .addClass('hide');
                }
                $updateClassTimeModal.modal('show');
            });
            $updateClassTimeForm.on('submit', function (e) {
                e.preventDefault();

                $updateClassTimeFormLoading.removeClass('hide');

                var $alert = $updateClassTimeForm.find('.alert');
                var $submit = $updateClassTimeForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.put('class-times/' + $updateClassTimeForm.find('[name="id"]').val(), {
                    subject: $updateClassTimeForm.find('[name="subject"]').val(),
                    content: $updateClassTimeForm.find('[name="content"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $updateClassTimeModal.modal('hide');

                        var $classTime = $('#class-time-' + data.class_time.id);
                        $classTime.attr('data-subject', data.class_time.subject);
                        $classTime.attr('data-content', data.class_time.content);
                        $classTime.find('.class-time-subject').text(data.class_time.subject);
                        $classTime.find('.class-time-content').html(data.class_time.html_content);
                        if (data.class_time.content != '') {
                            $classTime.find('.class-time-content').removeClass('hide').html(data.class_time.html_content);
                        }
                        else {
                            $classTime.find('.class-time-content').addClass('hide');
                        }
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.add_class_time_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $updateClassTimeFormLoading.addClass('hide');
                });
            });

            $(document).on('click', '.teacher-add-review', function (e) {
                e.preventDefault();

                var $classTime = $(this).closest('.class-time-item');
                $teacherAddReviewForm.find('[name="class_time_id"]').val($classTime.attr('data-id'));

                $teacherAddReviewModal.modal('show');
            });
            $teacherAddReviewForm.on('submit', function (e) {
                e.preventDefault();

                $teacherAddReviewFormLoading.removeClass('hide');

                var $alert = $teacherAddReviewForm.find('.alert');
                var $submit = $teacherAddReviewForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.post('class-times/' + $teacherAddReviewForm.find('[name="class_time_id"]').val() + '/reviews', {
                    teacher: 1,
                    rate: $teacherAddReviewForm.find('[name="rate"]').val(),
                    review: $teacherAddReviewForm.find('[name="feedback"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $teacherAddReviewModal.modal('hide');

                        var $classTime = $('#class-time-' + data.review.class_time_id);
                        var $button = $classTime.find('.teacher-add-review');
                        var $heading = $button.prev();
                        $button.remove();
                        $heading.after(renderReview(data.review, data.max_rate));
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.teacher_add_review_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $teacherAddReviewFormLoading.addClass('hide');
                });
            });

            $(document).on('click', '.student-add-review', function (e) {
                e.preventDefault();

                var $classTime = $(this).closest('.class-time-item');
                $studentAddReviewForm.find('[name="class_time_id"]').val($classTime.attr('data-id'));

                $studentAddReviewModal.modal('show');
            });
            $studentAddReviewForm.on('submit', function (e) {
                e.preventDefault();

                $studentAddReviewFormLoading.removeClass('hide');

                var $alert = $studentAddReviewForm.find('.alert');
                var $submit = $studentAddReviewForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.post('class-times/' + $studentAddReviewForm.find('[name="class_time_id"]').val() + '/reviews', {
                    student: 1,
                    rate: $studentAddReviewForm.find('[name="rate"]').val(),
                    review: $studentAddReviewForm.find('[name="review"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $studentAddReviewModal.modal('hide');

                        var $classTime = $('#class-time-' + data.review.class_time_id);
                        var $button = $classTime.find('.student-add-review');
                        var $heading = $button.prev();
                        $button.remove();
                        $heading.after(renderReview(data.review, data.max_rate));
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.student_add_review_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $studentAddReviewFormLoading.addClass('hide');
                });
            });

            $(document).on('click', '.teacher-add-rich-review', function (e) {
                e.preventDefault();

                var $classTime = $(this).closest('.class-time-item');
                $teacherAddRichReviewForm.find('[name="class_time_id"]').val($classTime.attr('data-id'));

                $teacherAddRichReviewModal.modal('show');
            });
            $teacherAddRichReviewForm.on('submit', function (e) {
                e.preventDefault();

                $teacherAddRichReviewFormLoading.removeClass('hide');

                var $alert = $teacherAddRichReviewForm.find('.alert');
                var $submit = $teacherAddRichReviewForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.post('class-times/' + $teacherAddRichReviewForm.find('[name="class_time_id"]').val() + '/rich-reviews', {
                    teacher: 1,
                    rate: {
                        attitude: $teacherAddRichReviewForm.find('[name="rate[attitude]"]').val(),
                        progress: $teacherAddRichReviewForm.find('[name="rate[progress]"]').val(),
                        quality: $teacherAddRichReviewForm.find('[name="rate[quality]"]').val()
                    },
                    review: $teacherAddRichReviewForm.find('[name="review"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $teacherAddRichReviewModal.modal('hide');

                        var $classTime = $('#class-time-' + data.review.class_time_id);
                        var $button = $classTime.find('.teacher-add-rich-review');
                        var $heading = $button.prev();
                        $button.remove();
                        $heading.after(renderRichReview(data.review, data.max_rate));
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.teacher_add_rich_review_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $teacherAddRichReviewFormLoading.addClass('hide');
                });
            });

            $(document).on('click', '.student-add-rich-review', function (e) {
                e.preventDefault();

                var $classTime = $(this).closest('.class-time-item');
                $studentAddRichReviewForm.find('[name="class_time_id"]').val($classTime.attr('data-id'));

                $studentAddRichReviewModal.modal('show');
            });
            $studentAddRichReviewForm.on('submit', function (e) {
                e.preventDefault();

                $studentAddRichReviewFormLoading.removeClass('hide');

                var $alert = $studentAddRichReviewForm.find('.alert');
                var $submit = $studentAddRichReviewForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.post('class-times/' + $studentAddRichReviewForm.find('[name="class_time_id"]').val() + '/rich-reviews', {
                    student: 1,
                    rate: {
                        document: $studentAddRichReviewForm.find('[name="rate[document]"]').val(),
                        attitude: $studentAddRichReviewForm.find('[name="rate[attitude]"]').val(),
                        quality: $studentAddRichReviewForm.find('[name="rate[quality]"]').val()
                    },
                    review: $studentAddRichReviewForm.find('[name="review"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $studentAddRichReviewModal.modal('hide');

                        var $classTime = $('#class-time-' + data.review.class_time_id);
                        var $button = $classTime.find('.student-add-rich-review');
                        var $heading = $button.prev();
                        $button.remove();
                        $heading.after(renderRichReview(data.review, data.max_rate));
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.student_add_rich_review_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $studentAddRichReviewFormLoading.addClass('hide');
                });
            });

            var $previousMonthClassTimes = $('.previous-month-class-times');
            var $previousMonthClassTimesLoading = $('.previous-month-class-times-loading');
            $previousMonthClassTimes.on('click', function (e) {
                e.preventDefault();

                $previousMonthClassTimesLoading.removeClass('hide');

                var $this = $(this);
                var api = new KatnissApi(true);
                api.get('classrooms/{{ $classroom->id }}', {
                    monthly_class_times: 1,
                    year: $this.attr('data-year'),
                    month: $this.attr('data-month')
                }, function (failed, data, messages) {
                    if (!failed) {
                        $addClassTimeFirstItem.after(renderTotal(
                            data.stats.sum_hours,
                            data.stats.month_year,
                            data.stats.trans_month_year
                        ));
                        var classTime;
                        for (var i in data.class_times) {
                            classTime = data.class_times[i];
                            if(!classTime.is_periodic) {
                                classTime.order = data.class_time_order_end--;
                            }
                            $addClassTimeFirstItem.after(renderClassTime(classTime, data.max_rate));
                        }
                        if (data.has_previous_month_class_times) {
                            $previousMonthClassTimes.attr('data-year', data.previous_year)
                                .attr('data-month', data.previous_month);
                        }
                        else {
                            $addClassTimeFirstItem.remove();
                        }
                    }
                }, null, function () {
                    $previousMonthClassTimesLoading.addClass('hide');
                });
            });

            x_modal_put($('.classroom-close'), '{{ trans('form.action_close') }}', '{{ trans('label.wanna_close', ['name' => '']) }}');
        });
    </script>
@endsection
@section('main_content')
    <div id="page-classrooms-detail">
{{--        @if($isOwner && $canAddStudentReview)--}}
            <div class="bg-warning text-warning padding-10 text-center margin-bottom-20 bold-600">
                <i class="fa fa-life-ring"></i> {!! trans('label.classroom_info_message', ['skype_id' => $ss_skype_id, 'skype_name' => $ss_skype_name]) !!}
            </div>
        {{--@endif--}}
        <ol class="breadcrumb big">
            <li>
                <a href="{{ $classrooms_url }}">{{ trans_choice('label.classroom', 2) }}</a>
            </li>
            <li><span id="classroom-name">{{$classroom->id}}::{{ $classroom->name }}</span>
                @if($can_classroom_edit)
                    &nbsp;
                    <a data-toggle="modal" data-target="#update-classroom-name-modal"
                       title="{{ trans('form.action_edit') }} {{ trans('label.name') }}" href="#">
                        <i class="fa fa-edit"></i>
                    </a>
                @endif
            </li>
        </ol>
        <div class="row">
            <div class="col-xs-12 col-md-3 margin-bottom-10">
                <div>
                    <strong>{{ trans('label.class_duration') }}:</strong>
                    {{ $classroom->duration }} {{ trans_choice('label.hour_lc', $classroom->hours) }}
                </div>
                <?php $spentTime = $classroom->spentTime; ?>
                <div>
                    <strong>{{ trans('label.class_spent_time') }}:</strong>
                    <span id="spent-time-total" data-hours="{{ $spentTime }}">
                        {{ toFormattedNumber($spentTime) }} {{ trans_choice('label.hour_lc', $spentTime) }}
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 margin-bottom-10">
                <div class="media">
                    <div class="media-left">
                        <img class="width-32 img-circle" src="{{ $teacher->userProfile->url_avatar_thumb }}">

                    </div>
                    <div class="media-body">
                        <div class="color-master"><strong>{{ $teacher->userProfile->display_name }}</strong></div>
                        <div>{{ trans_choice('label.teacher', 1) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 margin-bottom-10">
                <div class="media">
                    <div class="media-left">
                        <img class="width-32 img-circle" src="{{ $student->userProfile->url_avatar_thumb }}">
                    </div>
                    <div class="media-body">
                        <div class="color-master"><strong>{{ $student->userProfile->display_name }}</strong></div>
                        <div>{{ trans_choice('label.student', 1) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 margin-bottom-10">
                <div class="media">
                    <div class="media-left">
                        <img class="width-32 img-circle pop-hover" src="{{ $supporter->url_avatar_thumb }}"
                             data-placement="bottom"
                             data-content="<strong>Skype ID:</strong> {{ $supporter->skype_id }}<br><strong>{{ trans('label.email') }}:</strong> {{ $supporter->email }}<br><strong>{{ trans('label.phone') }}:</strong> {{ $supporter->phone }}">
                    </div>
                    <div class="media-body">
                        <div class="color-master">
                            <strong>
                                {{ $supporter->display_name }}
                            </strong>
                        </div>
                        <div>{{ trans_choice('label.supporter', 1) }}</div>
                    </div>
                </div>
            </div>
        </div>
        @if($can_classroom_export)
            <div class="margin-v-10">
                <a class="btn btn-success" target="_blank" href="?export=1">
                    <i class="fa fa-download"></i> {{ trans('form.action_export') }}
                </a>
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <ul id="class-time-list" class="media-list step-list margin-top-30">
            @if($has_previous_month_class_times)
                <li class="media class-time-previous">
                    <div class="media-line"></div>
                    <div class="media-left">
                        <span class="success role-button previous-month-class-times"
                              data-year="{{ $previous_year }}" data-month="{{ $previous_month }}">
                            <i class="fa fa-chevron-up" style="position:relative;top:-2px"></i>
                        </span>
                    </div>
                    <div class="media-body">
                        <div class="media-heading text-success">
                            <span class="role-button previous-month-class-times"
                                  data-year="{{ $previous_year }}" data-month="{{ $previous_month }}">
                                {{ trans('form.action_view') }} {{ trans('label.previous_month_lc') }}
                            </span>
                            <span class="previous-month-class-times-loading hide">
                                <i class="fa fa-refresh fa-spin fa-fw"></i>
                            </span>
                        </div>
                    </div>
                </li>
            @endif
            @if($class_times->count() > 0)
                @foreach($class_times as $class_time)
                    @if($class_time->isPeriodic)
                        <li id="class-time-{{ $class_time->id }}" data-id="{{ $class_time->id }}"
                            class="media class-time-item class-time-periodic month-{{ date('m-Y', strtotime($class_time->start_at)) }}">
                            <div class="media-line"></div>
                            <div class="media-left">
                                <span class="warning"><i class="fa fa-star"></i></span>
                            </div>
                            <div class="media-body">
                                <div class="media-heading">
                                    <span class="text-warning">{{ trans('label._periodic_class_review', ['after' => $class_time->subject]) }}</span>
                                </div>
                                <?php
                                $reviews = $class_time->reviews;
                                $teacherReview = $reviews->where('user_id', $classroom->teacher_id)->first();
                                $studentReview = $reviews->where('user_id', $classroom->student_id)->first();
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="h6 margin-top-none"><strong>{{ trans('label.teacher_review') }}</strong></div>
                                        @if(empty($teacherReview))
                                            @if($can_add_teacher_review)
                                                <button type="button" class="btn btn-warning margin-bottom-10 teacher-add-rich-review">
                                                    {{ trans('form.action_review_student') }}
                                                </button>
                                            @else
                                                <div>{{ trans('label.no_teacher_review_yet') }}</div>
                                            @endif
                                        @else
                                            <?php
                                                $rates = $teacherReview->rates;
                                                $transRates = transRate($rates);
                                            ?>
                                            <div class="row">
                                                @foreach($rates as $name => $rate)
                                                    <div class="col-sm-4">
                                                        <div>{{ trans('label.student_' . $name . '_rate') }}</div>
                                                        <div class="help-block color-star" title="{{ $transRates[$name] }}">
                                                            @for($i = 0; $i < $max_rate; ++$i)
                                                                <i class="fa {{ $i < $rate ? 'fa-star' : 'fa-star-o' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if(!empty($teacherReview->review))
                                                <div>{!! $teacherReview->htmlReview !!}</div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="h6 margin-top-none"><strong>{{ trans('label.student_review') }}</strong></div>
                                        @if(empty($studentReview))
                                            @if($can_add_student_review)
                                                <button type="button" class="btn btn-warning margin-bottom-10 student-add-rich-review">
                                                    {{ trans('form.action_review_teacher') }}
                                                </button>
                                            @else
                                                <div>{{ trans('label.no_student_review_yet') }}</div>
                                            @endif
                                        @else
                                            <?php
                                            $rates = $studentReview->rates;
                                            $transRates = transRate($rates);
                                            ?>
                                            <div class="row">
                                                @foreach($rates as $name => $rate)
                                                    <div class="col-sm-4">
                                                        <div>{{ trans('label.teacher_' . $name . '_rate') }}</div>
                                                        <div class="help-block color-star" title="{{ $transRates[$name] }}">
                                                            @for($i = 0; $i < $max_rate; ++$i)
                                                                <i class="fa {{ $i < $rate ? 'fa-star' : 'fa-star-o' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if(!empty($studentReview->review))
                                                <div>{!! $studentReview->htmlReview !!}</div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @else
                        <li id="class-time-{{ $class_time->id }}"
                            class="media class-time-item month-{{ date('m-Y', strtotime($class_time->start_at)) }}"
                            data-id="{{ $class_time->id }}" data-subject="{{ $class_time->subject }}" data-content="{{ $class_time->content }}">
                            <div class="media-line"></div>
                            <div class="media-left">
                                <span>{{  $class_time_order_start++ }}</span>
                            </div>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="media-heading">
                                            @if($can_classroom_edit)
                                                <a class="edit-class-time big pull-right"
                                                   title="{{ trans('form.action_edit') }} {{ trans_choice('label.class_time', 1) }}" href="#">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
                                            <span class="class-time-subject">{{ $class_time->subject }}</span>
                                        </div>
                                        @if(!$class_time->isConfirmed)
                                            <div class="help-block">
                                                <label class="label label-danger">{{ trans('label.status_student_not_confirmed') }}</label>
                                                @if($can_confirm_class_time)
                                                    <a class="btn btn-xs btn-success" href="{{ homeUrl('class-times/{id}/confirm', ['id' => $class_time->id]) }}">{{ trans('form.action_confirm') }}</a>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="help-block">
                                            {{ $class_time->duration }} {{ trans_choice('label.hour_lc', $class_time->hours) }}
                                            - {{ trans('label.start_at') }} {{ $class_time->inverseFullFormattedStartAt }}
                                        </div>
                                        <div class="bg-warning padding-10 class-time-content{{ !empty($class_time->content) ? '' : ' hide' }}">
                                            {!! $class_time->htmlContent !!}
                                        </div>
                                    </div>
                                    <?php
                                        $reviews = $class_time->reviews;
                                        $teacherReview = $reviews->where('user_id', $classroom->teacher_id)->first();
                                        $studentReview = $reviews->where('user_id', $classroom->student_id)->first();
                                    ?>
                                    <div class="col-sm-3">
                                        <div class="h6 margin-top-none"><strong>{{ trans('label.teacher_feedback') }}</strong></div>
                                        @if(empty($teacherReview))
                                            @if($can_add_teacher_review)
                                                <button type="button" class="btn btn-success btn-block margin-bottom-10 teacher-add-review">
                                                    {{ trans('form.action_add_feedback') }}
                                                </button>
                                            @else
                                                <div>{{ trans('label.no_teacher_feedback_yet') }}</div>
                                            @endif
                                        @else
                                            <div class="help-block color-star" title="{{ transRate($teacherReview->rate) }}">
                                                @for($i = 0; $i < $max_rate; ++$i)
                                                    <i class="fa {{ $i < $teacherReview->rate ? 'fa-star' : 'fa-star-o' }}"></i>
                                                @endfor
                                            </div>
                                            @if(!empty($teacherReview->review))
                                                <div>{!! $teacherReview->htmlReview !!}</div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="h6 margin-top-none"><strong>{{ trans('label.student_rating') }}</strong></div>
                                        @if(empty($studentReview))
                                            @if($can_add_student_review)
                                                <button type="button" class="btn btn-success btn-block margin-bottom-10 student-add-review">
                                                    {{ trans('form.action_add_rating') }}
                                                </button>
                                            @else
                                                <div>{{ trans('label.no_student_rating_yet') }}</div>
                                            @endif
                                        @else
                                            <div class="help-block color-star" title="{{ transRate($studentReview->rate) }}">
                                                @for($i = 0; $i < $max_rate; ++$i)
                                                    <i class="fa {{ $i < $studentReview->rate ? 'fa-star' : 'fa-star-o' }}"></i>
                                                @endfor
                                            </div>
                                            @if(!empty($studentReview->review))
                                                <div>{!! $studentReview->htmlReview !!}</div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
                    <?php $sum_hours = $class_times->sum('hours') ?>
                    <li class="media class-time-month" data-hours="{{ $sum_hours }}"
                        data-target=".month-{{ date('m-Y', strtotime($class_times[0]->start_at)) }}">
                        <div class="media-line"></div>
                        <div class="media-left">
                            <span class="info">Σ</span>
                        </div>
                        <div class="media-body">
                            <div class="media-heading text-info">
                                {{ trans('label.statistics') }} - {{ transMonthYear($class_times[0]->start_at) }}
                            </div>
                            <div>
                                {{ trans('label.total') }}:
                                <span class="sum-hours">
                                    {{ toFormattedNumber($sum_hours) }} {{ trans_choice('label.hour_lc', $sum_hours) }}
                                </span>
                            </div>
                        </div>
                    </li>
            @endif
            @if($can_classroom_edit && $classroom->spentTime < $classroom->hours)
                <li class="media class-time-add">
                    <div class="media-line"></div>
                    <div class="media-left">
                        <span class="success role-button" data-toggle="modal" data-target="#add-class-time-modal">
                            <i class="fa fa-plus"></i>
                        </span>
                    </div>
                    <div class="media-body">
                        <div class="media-heading text-success">
                            <span class="role-button" data-toggle="modal" data-target="#add-class-time-modal">
                                {{ trans('form.action_add') }} {{ trans_choice('label.class_time', 1) }}
                            </span>
                        </div>
                    </div>
                </li>
            @endif
        </ul>
        <div class="bg-warning text-warning padding-15 text-center margin-top-30 role-button classroom-close{{ $can_classroom_close ? '' : ' hide' }}"
             data-put="{{ addRdrUrl(homeUrl('classrooms/{id}', ['id'=> $classroom->id]) . '?close=1') }}">
            {{ trans('label.classroom_ready_to_close_message') }}
        </div>
        @if(!$classroom->isOpening)
            <div class="bg-danger text-danger padding-15 text-center margin-top-30">
                {{ trans('label.classroom_was_closed') }}
            </div>
        @endif
    </div>
@endsection
@section('modals')
    <div class="modal fade" id="update-classroom-name-modal" tabindex="-1" role="dialog" aria-labelledby="update-classroom-name-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="update-classroom-name-modal-title">
                        {{ trans('form.action_edit') }} {{ trans_choice('label.classroom', 1) }}
                    </h4>
                </div>
                <form id="update-classroom-name-form" method="post" action="{{ webApiUrl('classrooms/{id}', ['id' => $classroom->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="only_name" value="1">
                    <div id="update-classroom-name-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputName" class="control-label required">{{ trans('label.name') }}</label>
                            <input type="text" placeholder="{{ trans('label.name') }}"
                                   class="form-control" id="inputName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="update-classroom-name-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-class-time-modal" tabindex="-1" role="dialog" aria-labelledby="add-class-time-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="add-class-time-modal-title">
                        {{ trans('form.action_add') }} {{ trans_choice('label.class_time', 1) }}
                    </h4>
                </div>
                <form id="add-class-time-form" method="post" action="{{ webApiUrl('class-times') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="classroom" value="{{ $classroom->id }}">
                    <div id="add-class-time-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputSubject" class="control-label required">{{ trans('label.subject') }}</label>
                            <input type="text" placeholder="{{ trans('label.subject') }}"
                                   class="form-control" id="inputSubject" name="subject" required>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="inputDuration" class="control-label required">{{ trans('label.class_duration') }}</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="{{ trans('label.class_duration') }}" value="{{ old('duration') }}"
                                               class="form-control" id="inputDuration" name="duration" required
                                               data-inputmask="'alias':'decimal','radixPoint':'{{ $number_format_chars[0] }}','groupSeparator':'{{ $number_format_chars[1] }}','autoGroup':true,'integerDigits':6,'digits':2,'digitsOptional':false,'placeholder':'0{{ $number_format_chars[0] }}00'">
                                        <span class="input-group-addon">{{ trans_choice('label.hour_lc', 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="inputStartAt" class="control-label required">{{ trans('label.start_at') }} ({{ $date_js_format }})</label>
                                    <input type="text" placeholder="{{ trans('label.start_at') }}" value="{{ old('start_at') }}"
                                           data-inputmask="'mask':'{{ preg_replace('/[^-:\/\s]/', '9', $date_js_format) }}'"
                                           class="form-control date-time-picker" name="start_at" id="inputStartAt" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputContent" class="control-label">{{ trans('label.content') }}</label>
                            <textarea rows="6" class="form-control" id="inputContent" name="content"
                                      placeholder="{{ trans('label.content') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="add-class-time-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="update-class-time-modal" tabindex="-1" role="dialog" aria-labelledby="update-class-time-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="update-class-time-modal-title">
                        {{ trans('form.action_edit') }} {{ trans_choice('label.class_time', 1) }}
                    </h4>
                </div>
                <form id="update-class-time-form" method="post">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="id">
                    <div id="update-class-time-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputSubject" class="control-label required">{{ trans('label.subject') }}</label>
                            <input type="text" placeholder="{{ trans('label.subject') }}"
                                   class="form-control" id="inputSubject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="inputContent" class="control-label">{{ trans('label.content') }}</label>
                            <textarea rows="6" class="form-control" id="inputContent" name="content"
                                      placeholder="{{ trans('label.content') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <button type="button" class="btn btn-danger pull-left delete-class-time hide">{{ trans('form.action_delete') }}</button>
                        <span class="update-class-time-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="teacher-add-review-modal" tabindex="-1" role="dialog" aria-labelledby="teacher-add-review-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="teacher-add-review-modal-title">
                        {{ trans('form.action_add') }} {{ trans('label.teacher_feedback') }}
                    </h4>
                </div>
                <form id="teacher-add-review-form" method="post">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="class_time_id">
                    <div id="teacher-add-review-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputRate" class="control-label required">{{ trans('label.rate') }}</label>
                            {{ htmlRateSelection('rate', 'inputRate', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputFeedback" class="control-label">{{ trans('label.feedback') }}</label>
                            <textarea rows="6" class="form-control" id="inputFeedback" name="feedback"
                                      placeholder="{{ trans('label.feedback') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="teacher-add-review-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="student-add-review-modal" tabindex="-1" role="dialog" aria-labelledby="student-add-review-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="student-add-review-modal-title">
                        {{ trans('form.action_add') }} {{ trans('label.student_rating') }}
                    </h4>
                </div>
                <form id="student-add-review-form" method="post">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="class_time_id">
                    <div id="student-add-review-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputRate" class="control-label required">{{ trans('label.rate') }}</label>
                            {{ htmlRateSelection('rate', 'inputRate', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputReview" class="control-label">{{ trans('label.review') }}</label>
                            <textarea rows="6" class="form-control" id="inputReview" name="review"
                                      placeholder="{{ trans('label.review') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="student-add-review-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="teacher-add-rich-review-modal" tabindex="-1" role="dialog" aria-labelledby="teacher-add-rich-review-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="teacher-add-rich-review-modal-title">
                        {{ trans('form.action_add') }} {{ trans_choice('label.student', 1) }} {{ trans('label.review') }}
                    </h4>
                </div>
                <form id="teacher-add-rich-review-form" method="post">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="class_time_id">
                    <div id="teacher-add-rich-review-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputRateAttitude" class="control-label required">{{ trans('label.student_attitude_rate') }}</label>
                            {{ htmlRateSelection('rate[attitude]', 'inputRateAttitude', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputRateProgress" class="control-label required">{{ trans('label.student_progress_rate') }}</label>
                            {{ htmlRateSelection('rate[progress]', 'inputRateProgress', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputRateQuality" class="control-label required">{{ trans('label.student_quality_rate') }}</label>
                            {{ htmlRateSelection('rate[quality]', 'inputRateQuality', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputReview" class="control-label">{{ trans('label.review') }}</label>
                            <textarea rows="6" class="form-control" id="inputReview" name="review"
                                      placeholder="{{ trans('label.review') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="teacher-add-rich-review-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="student-add-rich-review-modal" tabindex="-1" role="dialog" aria-labelledby="student-add-rich-review-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="student-add-rich-review-modal-title">
                        {{ trans('form.action_add') }} {{ trans_choice('label.teacher', 1) }} {{ trans('label.review') }}
                    </h4>
                </div>
                <form id="student-add-rich-review-form" method="post">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="class_time_id">
                    <div id="student-add-rich-review-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputRateDocument" class="control-label required">{{ trans('label.teacher_document_rate') }}</label>
                            {{ htmlRateSelection('rate[document]', 'inputRateDocument', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputRateAttitude" class="control-label required">{{ trans('label.teacher_attitude_rate') }}</label>
                            {{ htmlRateSelection('rate[attitude]', 'inputRateAttitude', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputRateQuality" class="control-label required">{{ trans('label.teacher_quality_rate') }}</label>
                            {{ htmlRateSelection('rate[quality]', 'inputRateQuality', 'rating', true, 3) }}
                        </div>
                        <div class="form-group">
                            <label for="inputReview" class="control-label">{{ trans('label.review') }}</label>
                            <textarea rows="6" class="form-control" id="inputReview" name="review"
                                      placeholder="{{ trans('label.review') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="student-add-rich-review-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection