<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <ul class="nav side-menu">
            @foreach ($data['datamenu'] as $i => $rows)
                @if ($rows['main']!='')
                	@if ($rows['main']=='PO Local')
	                    @foreach ($data['datamenu'][$i]['menu'] as $row)
		                    @if($row->app_menu=='New PO') 
		                        <li><a href="{{ url($row->menu_link) }}">{{ $rows['main'] }}</a>
		                    @endif
	                    @endforeach
                    @elseif ($rows['main']=='PO Subcont')
	                    @foreach ($data['datamenu'][$i]['menu'] as $row)
		                    @if($row->app_menu=='New PO') 
		                        <li><a href="{{ url($row->menu_link) }}">{{ $rows['main'] }}</a>
		                    @endif
	                    @endforeach
                    @elseif ($rows['main'] == 'Parking Invoice' && (
                        Auth::user()->email == 'PROC-S-11' || 
                        Auth::user()->email == 'adm.proc2@patria.co.id' ||
                        Auth::user()->email == 'agamad.patria@gmail.com' ||
                        Auth::user()->email == '9912022' ||
                        Auth::user()->email == 'ridwanmayfernandos@gmail.com' ||
                        Auth::user()->role_id == 30))
                        <li>
                            <a>{{ $rows['main'] }}<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                @foreach ($data['datamenu'][$i]['menu'] as $row)
                                    <li><a href="{{ url($row['menu_link']) }}">{{ substr($row['app_menu'], 3) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @elseif ($rows['main'] == 'Parking Invoice')

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
                        @if ($row->app_menu == 'Index')
                        @else
                            <li><a href="{{ url($row->menu_link) }}">{{ $row->app_menu }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
</div>
