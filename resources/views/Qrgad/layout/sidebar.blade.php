<ul class="nav nav-primary">

    <li class="nav-item {{ Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
        <a href="{{ url('/dashboard') }}" class="collapsed" aria-expanded="false">
          <i class="fas fa-home"></i>
          <p>Dashboard</p>
        </a>
    </li>
    
    @foreach($data['datamenu'] as $i => $rows)
        @if($rows['main']!='')
        <li class="nav-item">
          <a data-toggle="collapse" href="#{{ $rows['main'] }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-users"></i>
            <p>{{ $rows['main'] }}</p>
            <span class="caret"></span>
          </a>
        <!-- <li  class="nav-item">
            <a href="#homeSubmenu{{$i}}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fa fa-table"></i>
                <span class="title"><strong> {{ $rows['main'] }} </strong></span>
                <i class="fa fa-caret-down"></i>
            </a>
            
        </li -->
        
          <div class="collapse" id="{{ $rows['main'] }}">
            <ul class="nav nav-collapse">
              
            @foreach($data['datamenu'][$i]['menu'] as $row)
                <li class="{{ Request::is($row['menu_link']) || Request::is($row['menu_link'].'/*') ? 'active' : '' }}">
                  <a href="{{ url($row['menu_link'])}}">
                    <span class="sub-item ">{{ $row['app_menu'] }} </span>
                  </a>
                </li>
            @endforeach

            </ul>
          </div>
        </li>
            <!-- <ul class="collapse page-sidebar-menu" id="homeSubmenu{{$i}}">
            @foreach($data['datamenu'][$i]['menu'] as $row)
                    <li class="nav-item {{ Request::is($row['menu_link']) || Request::is($row['menu_link'].'/*') ? 'active' : '' }}">
                        <a href="{{ url($row['menu_link'])}}" class="nav-link nav-toggle">
                        &emsp;<i class="{{ $row->icon }} "></i>
                            <span class="title">{{ $row['app_menu'] }} </span>
                        </a>
                    </li>
            @endforeach
            </ul>
        </li> -->
        @else
            @foreach($data['datamenu'][$i]['menu'] as $row)
            <li class="{{ Request::is($row['menu_link']) || Request::is($row['menu_link'].'/*') ? 'active' : '' }}">
                  <a href="{{ url($row['menu_link'])}}">
                    <span class="sub-item ">{{ $row['app_menu'] }} </span>
                  </a>
                </li>
            @endforeach
        @endif
      @endforeach
        
  </ul>