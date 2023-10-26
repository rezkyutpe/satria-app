<!-- menu profile quick info -->
<div class="profile clearfix">
    <div class="profile_pic mt-2">
        <img src="@if(Auth::user()->photo!='') {{ asset('public/profile/'.Auth::user()->photo) }} @else{{ asset('public/assetss/images/user.png') }}@endif" alt="..."
            class="img-circle profile_img" style="max-height: 60px; max-width: 60px;margin-top:22px;">
    </div>
    <div class="profile_info">
        <span>Welcome,</span>
        <h2>{{ Auth::user()->name }}</h2>
    </div>
</div>
<!-- /menu profile quick info -->

<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <ul class="nav side-menu">
            @foreach ($data['datamenu'] as $i => $rows)
                @if ($rows['main'] != '')
                    @if (count($rows['menu']) == 1)
                        @if (isset($rows['menu'][count($rows['menu'])-1]['r']))
                            <li>
                                <a>{{ $rows['main'] }}<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @foreach ($data['datamenu'][$i]['menu'] as $row)
                                        <li><a href="{{ url($row['menu_link']) }}">{{ $row['app_menu'] }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @else
                        <li>
                            <a>{{ $rows['main'] }}<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                @foreach ($data['datamenu'][$i]['menu'] as $row)
                                    <li><a href="{{ url($row['menu_link']) }}">{{ $row['app_menu'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @else
                    @foreach ($data['datamenu'][$i]['menu'] as $row)
                        <li><a href="{{ url($row->menu_link) }}">{{ $row->app_menu }}</a>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
</div>
