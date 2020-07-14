@section('lib_scripts')
    <script src="{{ _kExternalLink('handlebars') }}"></script>
@endsection
@section('extended_scripts')
    <script id="detail-template" type="text/x-handlebars-template">
        <p><strong>{{ trans('label.full_name') }}:</strong> @{{ full_name }}</p>
        @{{#if address}}
        <p><strong>{{ trans('label.address') }}:</strong> @{{ address }}</p>
        @{{/if}}
        <p><strong>{{ trans('label.phone') }}:</strong> @{{ phone }}</p>
        <p><strong>{{ trans('label.email') }}:</strong> @{{ email }}</p>
        @{{#if website}}
        <p><strong>{{ trans('label.website') }}:</strong> @{{ website }}</p>
        @{{/if}}
        <p><strong>{{ trans('label.content') }}:</strong><br>@{{{ message }}}</p>
    </script>
    <script>
        $(function () {
            var _detailTemplate = Handlebars.compile(jQuery("#detail-template").html());
            var _$detailModal = $('#detail-modal');

            function showDetailModal() {
                showDetailModalContent('<img src="{{ libraryAsset('ajax-loader.gif') }}">');
                _$detailModal.modal('show');
            }

            function showDetailModalContent(content) {
                _$detailModal.find('.modal-body').html(content);
            }

            $('a.popup-detail').on('click', function (e) {
                e.preventDefault();

                showDetailModal();

                var api = new KatnissApi(true);
                var params = {id: $(this).attr('data-id')};
                params[KATNISS_EXTRA_ROUTE_PARAM] = 'web-api/contact-forms/id';
                api.get('extra', params, function (isFail, data, messages) {
                    if (!isFail) {
                        data.message = nl2br(htmlspecialchars(data.message));
                        showDetailModalContent(_detailTemplate(data));
                    }
                });
            });
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="request-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="request-modal-label">{{ trans('contact_form.page_contact_forms_title') }}</h4>
                </div>
                <div class="modal-body">
                    <img src="{{ libraryAsset('ajax-loader.gif') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('contact_form.contact_form', 2)]) }}</h3>
            </div><!-- /.box-header -->
            @if($contact_forms->count()>0)
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.full_name') }}</th>
                            <th>{{ trans('label.phone') }}</th>
                            <th>{{ trans('label.email') }}</th>
                            <th>{{ trans('label.content') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.full_name') }}</th>
                            <th>{{ trans('label.phone') }}</th>
                            <th>{{ trans('label.email') }}</th>
                            <th>{{ trans('label.content') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($contact_forms as $contact_form)
                            <tr>
                                <td class="order-col-1">{{ ++$start_order }}</td>
                                <td>{{ $contact_form->full_name }}</td>
                                <td>{{ $contact_form->phone }}</td>
                                <td>{{ $contact_form->email }}</td>
                                <td>{{ shorten($contact_form->message, $message_length) }}</td>
                                <td>
                                    <a class="popup-detail" data-id="{{ $contact_form->id }}"
                                       href="{{ addExtraUrl('admin/contact-forms/id', adminUrl('extra')) . '&id=' . $contact_form->id }}">{{ trans('form.action_view') }}</a>
                                    <a class="delete"
                                       href="{{ addRdrUrl(addExtraUrl('admin/contact-forms/id', adminUrl('extra')) . '&id=' . $contact_form->id) }}">
                                        {{ trans('form.action_delete') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    {{ $pagination }}
                </div>
            @else
                <div class="box-body">
                    {{ trans('label.list_empty') }}
                </div>
            @endif
        </div><!-- /.box -->
    </div>
</div>