@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
    <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">User Subcont</a>
                        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px" data-kt-menu="true">
                            <div class="menu-item">
                                <a class="menu-link py-3" href="{{ url('user-subcont') }}">
                                    <span class="menu-icon">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
                                        <span class="svg-icon svg-icon-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="25" viewBox="0 0 24 25"
                                                fill="none">
                                                <path opacity="0.3"
                                                    d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
                                                    fill="black" />
                                                <path
                                                    d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                    <span class="menu-title">Menu 1</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link py-3" href="{{ url('user-subcont') }}">
                                    <span class="menu-icon">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
                                        <span class="svg-icon svg-icon-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="25" viewBox="0 0 24 25"
                                                fill="none">
                                                <path opacity="0.3"
                                                    d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
                                                    fill="black" />
                                                <path
                                                    d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                    <span class="menu-title">Menu 2</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <!--end::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-attendance-list') }}">Attendance</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-performance') }}">User Performance</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-competence') }}">User Competence</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="{{ url('user-safety-hour') }}">Safety Hour</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-attendance-ratio') }}">Attendance Ratio</a>
                    </li>
                </ul>
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="text" data-kt-user-safety-hour-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search Safety Hour" />
                            </div>
                        </div>
                        <form method="POST" action="{{ url('user-safety-hour') }}">
                            @csrf
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                <div class="w-150 mw-250px ms-auto">
                                    <input type="month" name="month_search" id="month_search" value="{{$data['tanggal_dropdown']}}" class="form-control form-control-solid" required>
                                </div>
                                <div class="w-150 mw-150px">
                                    <select class="form-select form-select-solid" name="company_search" data-control="select2" data-hide-search="true" data-placeholder="Company" required>
                                        <option></option>
                                        <option value="patria" @if($data['company'] == "patria") selected @endif>Patria</option>
                                        <option value="triatra" @if($data['company'] == "triatra") selected @endif>Triatra</option>
                                    </select>
                                </div>
                                <div class="w-150 mw-150px">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div style="text-align: center;">
                        <h1 style="font-weight: bold;">Safety Hour</h1>
                        <h5>{{$data['tanggal']}}</h5>
                        <h3>{{ucfirst($data['company'])}}</h3>
                    </div>
                    <div style="text-align: right;">
                        <form action="{{ url('export-safety-hour') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="month_export" id="month_export" value="{{$data['tanggal_dropdown']}}" class="form-control form-control-solid" required>
                            <input type="hidden" name="company_export" id="company_export" value="{{$data['company']}}" class="form-control form-control-solid" required>
                            <input type="hidden" name="tanggal_export" id="tanggal_export" value="{{$data['tanggal']}}" class="form-control form-control-solid" required>
                            <button type="submit" class="btn btn-success">Export Excel</button>
                        </form>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_user_safety_hour_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-5px pe-2">#</th>
                                    <th class="min-w-100px">NRP</th>
                                    <th class="min-w-175px">Name</th>
                                    <th class="min-w-175px">Departement</th>
                                    <th class="min-w-175px">Position</th>
                                    <th class="min-w-5px">Hours</th>
                                    <th class="min-w-5px">Minutes</th>
                                    <th class="min-w-5px">PRS</th>
                                    <th class="min-w-5px">SWC</th>
                                    <th class="min-w-5px">SL</th>
                                    <th class="min-w-5px">SDC</th>
                                    <th class="min-w-5px">PRDOFF</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-semibold">
                                @php($no=0)
                                @foreach($data['safety'] as $safety)
                                @php($no=$no+1)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $safety['nrp'] }}</td>
                                    <td>{{ $safety['name'] }}</td>
                                    <td>{{ $safety['department'] }}</td>
                                    <td>{{ $safety['position'] }}</td>
                                    <td>{{ intval($safety['hours']) }}</td>
                                    <td>{{ $safety['minutes'] }}</td>
                                    <td>{{ $safety['PRS'] }}</td>
                                    <td>{{ $safety['SWC'] }}</td>
                                    <td>{{ $safety['SL'] }}</td>
                                    <td>{{ $safety['SDC'] }}</td>
                                    <td>{{ $safety['PRSOFF'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('myscript')
<script type="text/javascript">
		var DatasRow = function () {
		    // Shared variables
		    var table;
		    var datatable;

		    // Private functions
		    var initDatatable = function () {
		        // Init datatable --- more info on datatables: https://datatables.net/manual/
		        datatable = $(table).DataTable({
		            "info": false,
		            'order': [],
		            'pageLength': 10
		        });

		        // Re-init functions on datatable re-draws
		        datatable.on('draw', function () {
		            handleDeleteRows();
		        });
		    }
		    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
		    var handleSearchDatatable = () => {
		        const filterSearch = document.querySelector('[data-kt-user-safety-hour-filter="search"]');
		        filterSearch.addEventListener('keyup', function (e) {
		            datatable.search(e.target.value).draw();
		        });
		    }
		    // Public methods
		    return {
		        init: function () {
		            table = document.querySelector('#kt_user_safety_hour_table');

		            if (!table) {
		                return;
		            }

		            initDatatable();
		            handleSearchDatatable();
		        }
		    };
		}();
    </script>
@endsection