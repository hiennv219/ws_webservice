<div class="wrapper">
    @if(!$header_nav_simple)
        @include('home_themes.wow_skype.master.header_nav_simple')
    @else
        @include('home_themes.wow_skype.master.header_nav_full')
    @endif
</div>
<div class="bars clearfix">
    <div class="bar bg-master bar-50 pull-left"></div>
    <div class="bar bg-slave bar-50 pull-right"></div>
</div>
<div class="bars">
    <div class="wrapper">
        <div class="bar bg-master bar-75 pull-left"></div>
        <div class="bar bg-slave bar-25 pull-right"></div>
    </div>
</div>