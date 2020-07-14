@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_extensions_title'))
@section('page_description', trans('pages.admin_extensions_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('topics') }}">{{ trans('pages.admin_extensions_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_put($('a.activate'), '{{ trans('form.action_activate') }}', '{{ trans('label.wanna_activate', ['name' => '']) }}');
            x_modal_put($('a.deactivate'), '{{ trans('form.action_deactivate') }}', '{{ trans('label.wanna_deactivate', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.extension_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
                @if(count($extensions)>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans('label.description') }}</th>
                                    <th>{{ trans('label.status') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans('label.description') }}</th>
                                    <th>{{ trans('label.status') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            $order = 0;
                            ?>
                            @foreach($extensions as $extension)
                                <tr>
                                    <td class="order-col-2">{{ ++$order }}</td>
                                    <td>
                                        {{ $extension['display_name'] }}
                                    </td>
                                    <td>
                                        {{ $extension['description'] }}
                                    </td>
                                    <td>
                                        @if($extension['static'])
                                            <label class="label label-info">{{ trans('label.status_activated_always') }}</label>
                                        @elseif($extension['activated'])
                                            <label class="label label-success">{{ trans('label.status_activated') }}</label>
                                        @else
                                            <label class="label label-default">{{ trans('label.status_deactivated') }}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if($extension['activated'])
                                            @if($extension['editable'])
                                                <a href="{{ adminUrl('extensions/{name}/edit', ['name' => $extension['name']]) }}">{{ trans('form.action_edit') }}</a>
                                            @endif
                                            @if(!$extension['static'])
                                                <a class="deactivate" href="{{ addRdrUrl(adminUrl('extensions/{name}', ['name'=> $extension['name']]) . '?deactivate=1') }}">{{ trans('form.action_deactivate') }}</a>
                                            @endif
                                        @else
                                            <a class="activate" href="{{ addRdrUrl(adminUrl('extensions/{name}', ['name' => $extension['name']]) . '?activate=1') }}">{{ trans('form.action_activate') }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="box-body">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection