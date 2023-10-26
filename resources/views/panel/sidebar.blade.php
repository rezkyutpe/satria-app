<div class="page-sidebar navbar-collapse collapse">
    <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="heading">
            <h3 class="uppercase">Menu</h3>
        </li>
        
        @foreach($data['datamenu'] as $i => $rows)
        @if($rows['main']!='')
        <li  class="nav-item">
            <a href="#homeSubmenu{{$i}}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fa fa-table"></i>
                <span class="title"><strong> {{ $rows['main'] }} </strong></span>
                <i class="fa fa-caret-down"></i>
            </a>
            
        </li>
            <ul class="collapse page-sidebar-menu" id="homeSubmenu{{$i}}">
            @foreach($data['datamenu'][$i]['menu'] as $row)
                    <li class="nav-item {{ Request::is($row['menu_link']) || Request::is($row['menu_link'].'/*') ? 'active' : '' }}">
                        <a href="{{ url($row['menu_link'])}}" class="nav-link nav-toggle">
                        &emsp;<i class="{{ $row->icon }} "></i>
                            <span class="title">{{ $row['app_menu'] }} </span>
                        </a>
                    </li>
            @endforeach
            </ul>
        </li>
        @else
            @foreach($data['datamenu'][$i]['menu'] as $row)
            <li class="nav-item {{ Request::is($row->menu_link) || Request::is($row->menu_link.'/*') ? 'active' : '' }}">
                <a href="{{ url($row->menu_link)}}" class="nav-link nav-toggle">
                    <i class="{{ $row->icon }} "></i>
                    <span class="title">{{ $row->app_menu }} </span>
                </a>
            </li>
            @endforeach
        @endif
        @endforeach
        
    </ul>
</div>