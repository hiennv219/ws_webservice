@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_announcements_title'))
@section('page_description', trans('pages.admin_announcements_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('announcements') }}">{{ trans('pages.admin_announcements_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="search-modal" tabindex="false" role="dialog" aria-labelledby="search-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="search-modal-title">{{ trans('form.action_search') }}</h4>
                </div>
                <form>
                    <div id="search-modal-content" class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="inputTitle" class="control-label">{{ trans('label.title') }}</label>
                                    <input id="inputTitle" type="text" class="form-control" value="{{ $search_title }}"
                                           name="title" placeholder="{{ trans('label.title') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="inputContent" class="control-label">{{ trans('label.content') }}</label>
                                    <input id="inputContent" type="text" class="form-control" value="{{ $search_content }}"
                                           name="content" placeholder="{{ trans('label.content') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                        <a role="button" class="btn btn-warning {{ $on_searching ? '' : 'hide' }}" href="{{ $clear_search_url }}">
                            {{ trans('form.action_clear_search') }}
                        </a>
                        <button type="submit" class="btn btn-primary">{{ trans('form.action_search') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ adminUrl('announcements/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.announcement_lc', 1) }}
                </a>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.announcement_lc', 2)]) }}</h3>
                    <div class="box-tools">
                        <button type="button" class="btn {{ $on_searching ? 'btn-warning' : 'btn-primary' }} btn-sm" data-toggle="modal" data-target="#search-modal">
                            <i class="fa fa-search"></i> {{ trans('form.action_search') }}
                        </button>
                    </div>
                </div><!-- /.box-header -->
                @if($announcements->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.content') }}</th>
                                    <th>{{ trans('label.title') }}</th>
                                    <th>{{ trans('label.author') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.content') }}</th>
                                    <th>{{ trans('label.title') }}</th>
                                    <th>{{ trans('label.author') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($announcements as $announcement)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td>{{ shorten($announcement->content, \Katniss\Everdeen\Utils\AppConfig::TITLE_SHORTEN_TEXT_LENGTH) }}</td>
                                        <td>{{ $announcement->title }}</td>
                                        <td>{{ $announcement->author->display_name }}</td>
                                        <td>
                                            <a href="{{ adminUrl('announcements/{id}/edit', ['id'=> $announcement->id]) }}">
                                                {{ trans('form.action_edit') }}
                                            </a>
                                            <a class="delete" href="{{ addRdrUrl(adminUrl('announcements/{id}', ['id'=> $announcement->id])) }}">
                                                {{ trans('form.action_delete') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                             </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {{ $pagination }}
                    </div>
                @else
                    <div class="box-body">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection