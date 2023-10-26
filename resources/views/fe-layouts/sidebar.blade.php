<div class="flex-column flex-lg-row-auto w-100 w-xl-325px mb-10" style="background-color: white"> 
     <div id="kt_aside" class="aside card" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" 
     data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle" 
     data-kt-sticky="true" data-kt-sticky-name="aside-sticky" data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '265px'}" 
     data-kt-sticky-left="auto" data-kt-sticky-top="95px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95"  >
       <br>
        <div class="card-body pt-0 p-10">
            <div class="aside-menu flex-column-fluid">
                    <div class="hover-scroll-overlay-y my-5 my-lg-6" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                        data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_aside_footer, #kt_footer" data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" 
                        data-kt-scroll-offset="0px">
                        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                            
                            <div class="d-flex flex-center flex-column mb-10">
                                <div class="symbol mb-3 symbol-100px symbol-circle">
                                    <img alt="Pic" src="@if(Auth::user()->photo!='') {{ asset('public/profile/'.Auth::user()->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" />
                                </div>
                                <a href="#" class="fs-2 text-gray-800 text-hover-primary fw-bolder mb-1">{{ Auth::user()->name }}</a>
                                <div class="fs-6 fw-bold text-gray-400 mb-2">{{ Auth::user()->title }}</div>
                            <hr>
                            </div>
                            <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion mb-1">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <span class="svg-icon svg-icon-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                                <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                                <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black" />
                                                <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="menu-title">Dashboards</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Satria Dashboard</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- 
                            @foreach($data['datamenu'] as $i => $rows)
                            @if($rows['main']!='')
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fa fa-table"></i></span>
                                    <span class="menu-title">{{ $rows['main'] }} </span>
                                    <span class="menu-arrow"></span>
                                </span>
                                                    
                                @foreach($data['datamenu'][$i]['menu'] as $row)
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ url($row['menu_link'])}}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ $row['app_menu'] }} </span>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            
                            @foreach($data['datamenu'][$i]['menu'] as $row)
                            <div class="menu-item">
                                <a class="menu-link"  href="{{ url($row->menu_link)}}"  data-kt-page="pro">
                                    <span class="menu-icon">
                                        <span class="svg-icon svg-icon-2"> <i class="{{ $row->icon }} "></i></span>
                                    </span>
                                    <span class="menu-title">{{ $row->app_menu }} </span>
                                </a>
                            </div>
                            @endforeach
                            @endif
                            @endforeach -->
                        </div>
                        <!--end::Menu-->
                    </div>
                </div>
            <!--end::Account info-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
<!--end::Sidebar