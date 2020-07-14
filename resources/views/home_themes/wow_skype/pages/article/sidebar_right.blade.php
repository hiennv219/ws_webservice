<div class="margin-bottom-20">
    <a id="categories-menu-toggle" class="btn btn-primary btn-block">
        {{ trans_choice('label.category', 2) }}
    </a>
    <div id="categories-menu" class="well padding-none border-master no-border-radius-top margin-bottom-none" style="display: none">
        {{ $categories_menu }}
    </div>
</div>
<div class="widgets">
    {!! placeholder('article_sidebar_right') !!}
</div>

@section('extended_scripts')
    @parent
    <script>
        $(function () {
            var _$menuToggle = $('#categories-menu-toggle');
            var _$menu = $("#categories-menu");
            _$menuToggle.on('click', function (e) {
                e.preventDefault();
                if(_$menu.is(':visible')) {
                    _$menu.slideUp(0);
                    _$menuToggle.removeClass('no-border-radius-bottom');
                }
                else {
                    _$menu.slideDown(0);
                    _$menuToggle.addClass('no-border-radius-bottom');
                }
            });
        })
    </script>
@endsection