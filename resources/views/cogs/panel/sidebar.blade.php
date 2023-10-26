<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <ul class="nav side-menu">
            @foreach ($data['datamenu'] as $i => $rows)
            @if ($rows['main']!='')
            <li>
                <a><i class="{{$rows['icon']}}"></i> {{ $rows['main'] }}<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    @foreach ($data['datamenu'][$i]['menu'] as $row)
                    <li><a href="{{ url($row['menu_link']) }}">{{ $row['app_menu'] }}</a></li>
                    @endforeach
                </ul>
            </li>
            @else
            @foreach ($data['datamenu'][$i]['menu'] as $row)
            <li><a href="{{ url($row->menu_link) }}"><i class="{{$row->icon}}"></i> {{ $row->app_menu }}</a>
                @endforeach
                @endif
                @endforeach
        </ul>
    </div>
</div>
