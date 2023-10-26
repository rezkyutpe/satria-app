@extends('fe-layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <!--begin::Container-->
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Index-->
            <div class="card card-page">
                <!--begin::Card body-->
                <div class="card-body">

                    <!--begin::Row-->
                    <div class="row gy-5 g-xl-8">
                        {{-- Begin Col --}}
                        <div class="col-xl-6 mb-5 mb-xl-10">
                            <!--begin::Chart widget 15-->
                            <div class="card card-flush h-xl-100">
                                <!--begin::Header-->
                                <div class="card-header pt-7">
                                    <!--begin::Title-->
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark">Ticket Requested</span>

                                        <span class="text-gray-400 pt-2 fw-semibold fs-6">Statistics by Days</span>
                                    </h3>
                                    <!--end::Title-->

                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar">
                                        <div class="col me-1">
                                            <div class="row">
                                                <label for="">Start date:</label>
                                            </div>
                                            <input type="date" class="form-control-sm" name="startdate_tr"
                                                id="startdate_tr" onchange="handlerFromDateTR(event);">
                                        </div>
                                        <div class="col me-1">
                                            <div class="row">
                                                <label for="">End date:</label>
                                            </div>
                                            <input type="date" class="form-control-sm" name="enddate_tr" id="enddate_tr"
                                                onchange="handlerToDateTR(event);">
                                        </div>
                                        <div class="card-toolbar">
                                            <a class="btn btn-sm btn-light mt-6" id="filter_tr"
                                                onclick="filterTR()">Filter</a>
                                        </div>
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div class="card-body pt-5">
                                    <!--begin::Chart container-->
                                    <div id="chartticketreq" style="width:100%; height:400px; ">
                                    </div>
                                    <!--end::Chart container-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Chart widget 15-->
                        </div>
                        {{-- End Col --}}

                        <div class="col-xl-6 mb-5 mb-xl-10">
                            <!--begin::Chart widget 15-->
                            <div class="card card-flush h-xl-100">
                                <!--begin::Header-->
                                <div class="card-header pt-7">
                                    <!--begin::Title-->
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark">Trends</span>

                                        <span class="text-gray-400 pt-2 fw-semibold fs-6">This Month</span>
                                    </h3>
                                    <!--end::Title-->

                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar">
                                        <div class="col me-1">
                                            <div class="row">
                                                <label for="">Start date:</label>
                                            </div>
                                            <input type="date" class="form-control-sm" name="startdate_trends"
                                                id="startdate_trends" onchange="handlerFromDateTRENDS(event);">
                                        </div>
                                        <div class="col me-1">
                                            <div class="row">
                                                <label for="">End date:</label>
                                            </div>
                                            <input type="date" class="form-control-sm" name="enddate_trends"
                                                id="enddate_trends" onchange="handlerToDateTRENDS(event);">
                                        </div>
                                        <div class="card-toolbar">
                                            <a class="btn btn-sm btn-light mt-6" id="filter_trends"
                                                onclick="filterTRENDS()">Filter</a>
                                        </div>
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div class="card-body pt-5 h-150px body_trends">
                                    <!--begin::Item-->
                                    @foreach ($topassist as $topassist)
                                        <div class="d-flex align-items-sm-center mb-7">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-circle symbol-50px me-5">
                                                <span class="symbol-label">
                                                    <img src="@if ($topassist->assist_photo != '') {{ asset('public/profile/' . $topassist->assist_photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }} @endif"
                                                        class="h-50 align-self-center" alt="" />
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                                <div class="flex-grow-1 me-2">
                                                    <a
                                                        class="text-gray-800 text-hover-primary fs-6 fw-bolder">{{ $topassist->assist_name }}</a>
                                                    <span class="text-muted fw-bold d-block fs-7">Top Assist</span>
                                                </div>
                                                <span class="badge badge-light fw-bolder my-2">{{ $topassist->count }}
                                                    Ticket</span>
                                            </div>
                                            <!--end::Section-->
                                        </div>
                                    @endforeach
                                    @foreach ($topreporter as $topreporter)
                                        <div class="d-flex align-items-sm-center mb-7">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-circle symbol-50px me-5">
                                                <span class="symbol-label">
                                                    <img src="@if ($topreporter->reporter_photo != '') {{ asset('public/profile/' . $topreporter->reporter_photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }} @endif"
                                                        class="h-50 align-self-center" alt="" />
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                                <div class="flex-grow-1 me-2">
                                                    <a
                                                        class="text-gray-800 text-hover-primary fs-6 fw-bolder">{{ $topreporter->reporter_name }}</a>
                                                    <span class="text-muted fw-bold d-block fs-7">Top Reporter</span>
                                                </div>
                                                <span class="badge badge-light fw-bolder my-2">{{ $topreporter->count }}
                                                    Ticket</span>
                                            </div>
                                            <!--end::Section-->
                                        </div>
                                    @endforeach
                                    @foreach ($topsla as $topsla)
                                        <div class="d-flex align-items-sm-center mb-7">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-circle symbol-50px me-5">
                                                <span class="symbol-label">
                                                    <img src="/ceres-html-free/assets/media/svg/brand-logos/plurk.svg"
                                                        class="h-50 align-self-center" alt="" />
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                                <div class="flex-grow-1 me-2">
                                                    <a
                                                        class="text-gray-800 text-hover-primary fs-6 fw-bolder">{{ $topsla->sla_name }}</a>
                                                    <span class="text-muted fw-bold d-block fs-7">Top Issue</span>
                                                </div>
                                                <span class="badge badge-light fw-bolder my-2">{{ $topsla->count }}
                                                    Ticket</span>
                                            </div>
                                            <!--end::Section-->
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end row --}}

                    {{-- Begin Row 2 --}}
                    {{-- <div class="row mx-0 gy-5 g-xl-8">
                        <!--begin::List Widget 2-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-gray-800">Your Ticket Assigned</span>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">Start date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="startdate_yta"
                                            id="startdate_yta" onchange="handlerFromDateYTA(event);">
                                    </div>
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">End date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="enddate_yta"
                                            id="enddate_yta" onchange="handlerToDateYTA(event);">
                                    </div>
                                    <div class="card-toolbar">
                                        <a class="btn btn-sm btn-light mt-6" id="filter_yta"
                                            onclick="filterYTA()">Filter</a>
                                    </div>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive" style="max-height: 350px; overflow-y:auto;">
                                    <!--begin::Table-->
                                    <table class="table table-row-dashed align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead class="">
                                            <tr class="fs-7 fw-bold border-0 text-gray-400">
                                                <th class="min-w-25px">NO</th>
                                                <th class="min-w-150px">ID TICKET</th>
                                                <th class="min-w-150px">DATE</th>
                                                <th class="min-w-100px">MESIN</th>
                                                <th class="min-w-100px">LOKASI</th>
                                                <th class="min-w-100px">PROBLEM</th>
                                                <th class="min-w-100px">REPORTER</th>
                                                <th class="min-w-75px pe-0">STATUS</th>
                                                <th class="text-end min-w-125px">ACTIONS</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->

                                        <!--begin::Table body-->
                                        <tbody id="tbody_yta" class="tbody_yta">
                                            @php($nota = 1)
                                            @if (count($ticketassign) > 0)
                                                @foreach ($ticketassign as $ta)
                                                    <tr>
                                                        <td class="">
                                                            <a
                                                                class="text-gray-800 fw-bold mb-1 fs-6">{{ $nota++ }}</a>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-boldest text-hover-primary">#{{ $ta->ticket_id }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ date_format($ta->created_at, 'H:i:s, d M Y') }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">No data.</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">No data.</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">{{ $ta->message }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $ta->reporter_name }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-light-primary"
                                                                style="background-color: {{ $ta->bg_color }}; color: {{ $ta->fg_color }};">
                                                                {{ $ta->flow_name }}</div>
                                                        </td>
                                                        <td class="pe-0 text-end">
                                                            <a class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary"
                                                                data-kt-menu-trigger="click" data-kt-menu-overflow="true"
                                                                data-kt-menu-placement="bottom-start">
                                                                <!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
                                                                <span class="svg-icon svg-icon-2x">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        fill="none">
                                                                        <rect opacity="0.3" x="2"
                                                                            y="2" width="20" height="20"
                                                                            rx="4" fill="black" />
                                                                        <rect x="11" y="11"
                                                                            width="2.6" height="2.6" rx="1.3"
                                                                            fill="black" />
                                                                        <rect x="15" y="11"
                                                                            width="2.6" height="2.6" rx="1.3"
                                                                            fill="black" />
                                                                        <rect x="7" y="11"
                                                                            width="2.6" height="2.6" rx="1.3"
                                                                            fill="black" />
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->
                                                            </a>
                                                            <!--begin::Menu 3-->
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3"
                                                                data-kt-menu="true">
                                                                <!--begin::Heading-->
                                                                <div class="menu-item px-3">
                                                                    <a onclick="getHistory({{ $ta->id }})"class="menu-link px-3"
                                                                        tooltip="New App" data-bs-toggle="modal"
                                                                        data-bs-target="#detail-ticket"
                                                                        class="menu-link px-3">Detail</a>
                                                                </div>
                                                                @if ($ta->flag == 1)
                                                                    <div class="menu-item px-3">
                                                                        <a href="#"
                                                                            onclick="setProccess({{ $ta->id }},'Proccess')"
                                                                            class="menu-link px-3" tooltip="New App"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#action-ticket"
                                                                            class="menu-link px-3">Proccess</a>
                                                                    </div>
                                                                    <div class="menu-item px-3">
                                                                        <a href="#"
                                                                            onclick="setCancel({{ $ta->id }},'Cancel')"
                                                                            class="menu-link px-3" tooltip="New App"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#action-ticket"
                                                                            class="menu-link px-3">Cancel</a>
                                                                    </div>
                                                                @elseif($ta->flag == 2 || $ta->flag == 3 || $ta->flag == 9)
                                                                    @if ($ta->flag != 3)
                                                                        <div class="menu-item px-3">
                                                                            <a href="#"
                                                                                onclick="setEscalate({{ $ta->id }},'Escalate')"
                                                                                class="menu-link px-3" tooltip="New App"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#action-ticket"
                                                                                class="menu-link px-3">Escalate</a>
                                                                        </div>
                                                                        <div class="menu-item px-3">
                                                                            <a href="#"
                                                                                onclick="setResolve({{ $ta->id }},'Resolve')"
                                                                                class="menu-link px-3" tooltip="New App"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#action-ticket"
                                                                                class="menu-link px-3">Resolve</a>
                                                                        </div>
                                                                    @else
                                                                        @if (Auth::user()->dept != '8884')
                                                                            <div class="menu-item px-3">
                                                                                <a href="#"
                                                                                    onclick="setEscalate({{ $ta->id }},'Escalate')"
                                                                                    class="menu-link px-3"
                                                                                    tooltip="New App"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#action-ticket"
                                                                                    class="menu-link px-3">Escalate</a>
                                                                            </div>
                                                                        @endif
                                                                        @php($interval = Helper::TimeInterval(date('Y-m-d H:i:s'), $ta->resolve_time))
                                                                        @if ($interval >= '32400')
                                                                            <div class="menu-item px-3">
                                                                                <a href="#"
                                                                                    onclick="setClose({{ $ta->id }},'Close')"
                                                                                    class="menu-link px-3"
                                                                                    tooltip="New App"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#action-ticket"
                                                                                    class="menu-link px-3">Close</a>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @elseif($ta->flag != 5 && $ta->flag != 6)
                                                                    <div class="menu-item px-3">
                                                                        <a href="#"
                                                                            onclick="setCancel({{ $ta->id }},'Cancel')"
                                                                            class="menu-link px-3" tooltip="New App"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#action-ticket"
                                                                            class="menu-link px-3">Cancel</a>
                                                                    </div>
                                                                @endif
                                                                <!--end::Menu item-->
                                                            </div>
                                                            <!--end::Menu 3-->
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2">No data available</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->

                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Body-->
                        </div>
                        {{-- End List Widget 2 --}}
                    {{-- </div> --}}
                    {{-- End Row --}}

                    {{-- Begin Row 3 --}}
                    <div class="row mx-0  gy-5 g-xl-8">
                        <!--begin::List Widget 2-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-gray-800">List Ticket Opened</span>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">Start date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="startdate_lto"
                                            id="startdate_lto" onchange="handlerFromDateLTO(event);">
                                    </div>
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">End date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="enddate_lto"
                                            id="enddate_lto" onchange="handlerToDateLTO(event);">
                                    </div>
                                    <div class="card-toolbar">
                                        <a class="btn btn-sm btn-light mt-6" id="filter_lto"
                                            onclick="filterLTO()">Filter</a>
                                    </div>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive" style="max-height: 350px; overflow-y:auto;">
                                    <!--begin::Table-->
                                    <table class="table table-row-dashed align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead class="">
                                            <tr class="fs-7 fw-bold border-0 text-gray-400">
                                                <th class="min-w-25px">NO</th>
                                                <th class="min-w-150px">ID TICKET</th>
                                                <th class="min-w-175px">DATE</th>
                                                <th class="min-w-100px">MESIN</th>
                                                <th class="min-w-100px">LOKASI</th>
                                                <th class="min-w-100px">PROBLEM</th>
                                                <th class="min-w-100px">REPORTER</th>
                                                <th class="min-w-75px pe-0">STATUS</th>
                                                <!-- <th class="text-end min-w-125px">ACTIONS</th> -->
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->

                                        <!--begin::Table body-->
                                        <tbody id="tbody_lto">
                                            @php($no = 1)
                                            @if (count($ticketopened) > 0)
                                                @foreach ($ticketopened as $to)
                                                    <tr>
                                                        <td class="">
                                                            <a href="#"
                                                                class="text-gray-800 fw-bold mb-1 fs-6">{{ $no++ }}</a>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-boldest text-hover-primary">#{{ $to->ticket_id }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ date_format($to->created_at, 'H:i:s, d M Y') }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $to->desc_mesin }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">{{ $to->location }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">{{ $to->message }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $to->reporter_name }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-light-primary"
                                                                style="background-color: {{ $to->bg_color }}; color: {{ $to->fg_color }};">
                                                                {{ $to->flow_name }}</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2">No data available</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                                <div class="pb-3 pt-1 text-center border-top">
                                    <a href="{{ url('open-user-ticket') }}"
                                        class="btn btn-color-gray-600 btn-active-color-primary">View All
                                        <span class="svg-icon svg-icon-5"><i
                                                class="fa fa-angle-right fs-2"></i></span></a>
                                    </span>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        {{-- End List Widget 2 --}}
                    </div>
                    {{-- End Row --}}

                    {{-- Begin Row 4 --}}
                    <div class="row mx-0 mt-3 gy-5 g-xl-8">
                        <!--begin::List Widget 2-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-gray-800">All Ticket Assigned</span>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">Start date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="startdate_ata"
                                            id="startdate_ata" onchange="handlerFromDateATA(event);">
                                    </div>
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">End date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="enddate_ata"
                                            id="enddate_ata" onchange="handlerToDateATA(event);">
                                    </div>
                                    <div class="card-toolbar">
                                        <a class="btn btn-sm btn-light mt-6" id="filter_ata"
                                            onclick="filterATA()">Filter</a>
                                    </div>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive" style="max-height: 350px; overflow-y:auto;">
                                    <!--begin::Table-->
                                    <table class="table table-row-dashed align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead class="">
                                            <tr class="fs-7 fw-bold border-0 text-gray-400">
                                                <th class="min-w-25px">NO</th>
                                                <th class="min-w-150px">ID TICKET</th>
                                                <th class="min-w-175px">DATE</th>
                                                <th class="min-w-100px">MESIN</th>
                                                <th class="min-w-100px">LOKASI</th>
                                                <th class="min-w-100px">PROBLEM</th>
                                                <th class="min-w-100px">REPORTER</th>
                                                <th class="min-w-100px">STATUS</th>
                                                <th class="min-w-100px">MEKANIK</th>
                                                {{-- <th class="text-end min-w-75px">ACTIONS</th> --}}
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->

                                        <!--begin::Table body-->
                                        <tbody class="tbody_ata">
                                            @php($no = 1)
                                            @if (count($allticketassign) > 0)
                                                @foreach ($allticketassign as $ata)
                                                    <tr>
                                                        <td class="">
                                                            <a href="#"
                                                                class="text-gray-800 fw-bold mb-1 fs-6">{{ $no++ }}</a>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-boldest text-hover-primary">#{{ $ata->ticket_id }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ date_format($ata->created_at, 'H:i:s, d M Y') }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $ata->desc_mesin }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $ata->location }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">{{ $ata->message }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $ata->reporter_name }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-light-primary"
                                                                style="background-color: {{ $ata->bg_color }}; color: {{ $ata->fg_color }};">
                                                                {{ $ata->flow_name }}</div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $ata->assist_name }}</span>
                                                        </td>
                                                        {{-- <td class="pe-0 text-end">
                                                            <a class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary"
                                                                data-kt-menu-trigger="click" data-kt-menu-overflow="true"
                                                                data-kt-menu-placement="bottom-start">
                                                                <!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
                                                                <span class="svg-icon svg-icon-2x">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        fill="none">
                                                                        <rect opacity="0.3" x="2"
                                                                            y="2" width="20" height="20"
                                                                            rx="4" fill="black" />
                                                                        <rect x="11" y="11"
                                                                            width="2.6" height="2.6" rx="1.3"
                                                                            fill="black" />
                                                                        <rect x="15" y="11"
                                                                            width="2.6" height="2.6" rx="1.3"
                                                                            fill="black" />
                                                                        <rect x="7" y="11"
                                                                            width="2.6" height="2.6" rx="1.3"
                                                                            fill="black" />
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->
                                                            </a>
                                                            <!--begin::Menu 3-->
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3"
                                                                data-kt-menu="true">
                                                                <!--begin::Heading-->
                                                                <div class="menu-item px-3">
                                                                    <a onclick="getHistory({{ $ata->id }})"class="menu-link px-3"
                                                                        tooltip="New App" data-bs-toggle="modal"
                                                                        data-bs-target="#detail-ticket"
                                                                        class="menu-link px-3">Detail</a>
                                                                </div>
                                                                @if ($ata->flag == 1)
                                                                    <div class="menu-item px-3">
                                                                        <a href="#"
                                                                            onclick="setProccess({{ $ata->id }},'Proccess')"
                                                                            class="menu-link px-3" tooltip="New App"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#action-ticket"
                                                                            class="menu-link px-3">Proccess</a>
                                                                    </div>
                                                                    <div class="menu-item px-3">
                                                                        <a href="#"
                                                                            onclick="setCancel({{ $ata->id }},'Cancel')"
                                                                            class="menu-link px-3" tooltip="New App"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#action-ticket"
                                                                            class="menu-link px-3">Cancel</a>
                                                                    </div>
                                                                @elseif($ata->flag == 2 || $ata->flag == 3 || $ata->flag == 9)
                                                                    @if ($ata->flag != 3)
                                                                        <div class="menu-item px-3">
                                                                            <a href="#"
                                                                                onclick="setEscalate({{ $ata->id }},'Escalate')"
                                                                                class="menu-link px-3" tooltip="New App"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#action-ticket"
                                                                                class="menu-link px-3">Escalate</a>
                                                                        </div>
                                                                        <div class="menu-item px-3">
                                                                            <a href="#"
                                                                                onclick="setResolve({{ $ata->id }},'Resolve')"
                                                                                class="menu-link px-3" tooltip="New App"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#action-ticket"
                                                                                class="menu-link px-3">Resolve</a>
                                                                        </div>
                                                                    @else
                                                                        @if (Auth::user()->dept != '8884')
                                                                            <div class="menu-item px-3">
                                                                                <a href="#"
                                                                                    onclick="setEscalate({{ $ata->id }},'Escalate')"
                                                                                    class="menu-link px-3"
                                                                                    tooltip="New App"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#action-ticket"
                                                                                    class="menu-link px-3">Escalate</a>
                                                                            </div>
                                                                        @endif
                                                                        @php($interval = Helper::TimeInterval(date('Y-m-d H:i:s'), $ata->resolve_time))
                                                                        @if ($interval >= '32400')
                                                                            <div class="menu-item px-3">
                                                                                <a href="#"
                                                                                    onclick="setClose({{ $ata->id }},'Close')"
                                                                                    class="menu-link px-3"
                                                                                    tooltip="New App"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#action-ticket"
                                                                                    class="menu-link px-3">Close</a>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @elseif($ata->flag != 5 && $ata->flag != 6)
                                                                    <div class="menu-item px-3">
                                                                        <a href="#"
                                                                            onclick="setCancel({{ $ata->id }},'Cancel')"
                                                                            class="menu-link px-3" tooltip="New App"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#action-ticket"
                                                                            class="menu-link px-3">Cancel</a>
                                                                    </div>
                                                                @endif
                                                                <!--end::Menu item-->
                                                            </div>
                                                            <!--end::Menu 3-->
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2">No data available</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                                <div class="pb-3 pt-1 text-center border-top">
                                    <a href="{{ url('all-assist-user-ticket') }}"
                                        class="btn btn-color-gray-600 btn-active-color-primary">View All
                                        <span class="svg-icon svg-icon-5"><i
                                                class="fa fa-angle-right fs-2"></i></span></a>
                                    </span>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        {{-- End List Widget 2 --}}
                    </div>
                    {{-- End Row --}}

                    <!--begin::Row 5-->
                    <div class="row mx-0 mt-3 gy-5 g-xl-8">
                        <!--begin::Chart widget 15-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Ticket Total</span>
                                </h3>
                                <!--end::Title-->

                                <form action="{{ url('export-ticket-dashboard') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar">
                                        <div class="col me-1">
                                            <div class="row">
                                                <label for="">Start date:</label>
                                            </div>
                                            <input type="date" class="form-control-sm" name="startdate_tickettotal"
                                                id="startdate_tickettotal" onchange="handlerFromDate(event);">
                                        </div>
                                        <div class="col me-1">
                                            <div class="row">
                                                <label for="">End date:</label>
                                            </div>
                                            <input type="date" class="form-control-sm" name="enddate_tickettotal"
                                                id="enddate_tickettotal" onchange="handlerToDate(event);">
                                        </div>
                                        <div class="card-toolbar">
                                            <a class="btn btn-sm btn-light mt-6" id="filter_tickettotal"
                                                onclick="filterTICKETTOTAL()">Filter</a>
                                        </div>
                                    </div>
                                    <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-5">
                                @if (count($newtcd2) > 0)
                                    <input type="hidden" name="def_startdate_export" value="{{ $newtcd2[0] }}">
                                    <input type="hidden" name="def_enddate_export" value="{{ $newtcd2[14] }}">
                                @endif
                                <div class="row">
                                    <div class="col-md-8">

                                    </div>
                                    <div class="col-md-4" id="alert-not-found">

                                    </div>
                                </div>
                                <div id="container2"
                                    style="width:100%; height:100%; margin-vertical:25px; background-color:white;">

                                    <div class="row">
                                        <div class="col-7">
                                            <div id="container" style="width:100%; height:400px; ">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            {{-- <div class="row">
                                                <div class="col-4 ms-auto mb-5">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary fs-7 w-100">Export Excel</a>
                                                </div>
                                            </div> --}}
                                            <div id="container3" style="width:100%; height:300px; ">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <!--end::Body-->
                        </div>
                        <!--end::Chart widget 15-->
                    </div>
                    {{-- End Row --}}
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Index-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Container-->
@endsection

@section('myscript')
    <div class="modal fade" tabindex="-1" id="kt_modal_export">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form action="{{ url('export-ticket') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="fv-row">
                            <div class="fv-row mb-15">
                                <label class="required fs-5 fw-bold mb-2">Subject</label> <br>
                                <select style="width: 50%;" id="passanger_chosen" name="subject[]" multiple
                                    class="form-control form-control-lg form-control-solid chzn-select">
                                    @foreach ($subject as $subject)
                                        <option value="{{ $subject->subject }}"
                                            {{ old('subject') == $subject->subject ? 'selected' : '' }}>
                                            {{ $subject->subject }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col fv-row mb-10">
                                <!--begin::Label-->
                                <label class="required fs-5 fw-bold mb-2">Start Date</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="date" id="from_date" onchange="handlerFromDate(event);"
                                    class="form-control form-control-lg form-control-solid" name="start"
                                    placeholder="" />
                                <!--end::Input-->
                            </div>
                            <div class="col fv-row mb-10">
                                <!--begin::Label-->
                                <label class="required fs-5 fw-bold mb-2">End Date</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="date" id="to_date" onchange="handlerToDate(event);"
                                    class="form-control form-control-lg form-control-solid" name="end"
                                    placeholder="" />
                                <!--end::Input-->
                            </div>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="action-ticket" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mw-650px">
            <div class="modal-content">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-danger fs-2"></i></span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                    <div id="ticketAction"></div>

                </div>
            </div>
        </div>
        <script>
            function filterTR() {
                var start_tr = $('#startdate_tr').val();
                var end_tr = $('#enddate_tr').val();
                let _token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ url('filter-ticket-req-user') }}?Start_tr=" + start_tr + "&End_tr=" + end_tr,
                    type: "GET",
                    success: function(response) {
                        var data = JSON.parse(response);
                        console.log(data['newtr2']);

                        const barchart = Highcharts.chart('chartticketreq', {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'Ticket Requested'
                            },
                            legend: {
                                layout: 'vertical'
                            },
                            xAxis: {
                                categories: data['newtrd2']

                            },
                            yAxis: {
                                title: {
                                    text: 'Total ticket'
                                }
                            },
                            series: [{
                                name: 'Ticket Requested',
                                data: data['newtr2']
                            }]
                        });
                    },

                });
            }

            function filterYTA() {
                var start_yta = $('#startdate_yta').val();
                var end_yta = $('#enddate_yta').val();

                $.ajax({
                    url: "{{ url('filter-your-ticket-req-user') }}?Start_yta=" + start_yta + "&End_yta=" + end_yta,
                    type: "GET",
                    success: function(response) {
                        var data = JSON.parse(response);
                        // var newdate = new Date(data.created_at);
                        // var fix_date = newdate.toLocalDateString('id-ID');
                        console.log(data);
                        $('.tbody_yta').empty();
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                if (data[i].flag == 1) {
                                    var html = `<div class="menu-item px-3">
                                                    <a onclick="setProccess(` + data[i].id + `,'Proccess')"
                                                        class="menu-link px-3" tooltip="New App"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#action-ticket"
                                                        class="menu-link px-3">Proccess</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a onclick="setCancel(` + data[i].id + `,'Cancel')"
                                                        class="menu-link px-3" tooltip="New App"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#action-ticket"
                                                        class="menu-link px-3">Cancel</a>
                                                </div>`
                                } else if (data[i].flag == 2 || data[i].flag == 3 || data[i].flag == 9) {
                                    if (data[i].flag != 3) {
                                        var html = `<div class="menu-item px-3">
                                                    <a href="#"
                                                        onclick="setEscalate(` + data[i].id + `,'Escalate')"
                                                        class="menu-link px-3" tooltip="New App"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#action-ticket"
                                                        class="menu-link px-3">Escalate</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#"
                                                        onclick="setResolve(` + data[i].id + `,'Resolve')"
                                                        class="menu-link px-3" tooltip="New App"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#action-ticket"
                                                        class="menu-link px-3">Resolve</a>
                                                </div>`
                                    } else {
                                        @if (Auth::user()->dept != '8884')
                                            var html = `<div class="menu-item px-3">
                                                            <a href="#"
                                                                onclick="setEscalate(` + data[i].id + `,'Escalate')"
                                                                class="menu-link px-3"
                                                                tooltip="New App"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#action-ticket"
                                                                class="menu-link px-3">Escalate</a>
                                                        </div>`
                                        @endif
                                        var interval = '32400'
                                        if (interval >= '32400') {
                                            var html = `<div class="menu-item px-3">
                                                            <a href="#"
                                                                onclick="setClose(` + data[i].id + `,'Close')"
                                                                class="menu-link px-3"
                                                                tooltip="New App"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#action-ticket"
                                                                class="menu-link px-3">Close</a>
                                                        </div>`
                                        }

                                    }
                                } else if (data[i].flag != 5 && data[i].flag != 6) {
                                    var html = `<div class="menu-item px-3">
                                                    <a href="#"
                                                        onclick="setCancel(` + data[i].id + `,'Cancel')"
                                                        class="menu-link px-3" tooltip="New App"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#action-ticket"
                                                        class="menu-link px-3">Cancel</a>
                                                </div>`
                                } else {
                                    var html = ``
                                }
                                // console.log(item.created_at);
                                var new_tbody =
                                    `<tr>
                                        <td class="">
                                            <a href="#"
                                                class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + [i +
                                        1
                                    ] + `</a>
                                        </td>
                                        <td>
                                            <span
                                                class="text-gray-800 fw-boldest">#` + data[i].ticket_id + `</span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-gray-800 fw-bold">` + data[i].date + `</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold">` + data[i].desc_mesin + `</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold">` + data[i].location + `</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold">` + data[i].message + `</span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-gray-800 fw-bold">` + data[i].reporter_name + `</span>
                                        </td>
                                        <td>
                                            <div class="badge badge-light-primary"
                                                style="background-color: ` + data[i].bg_color + `; color: ` + data[i]
                                    .fg_color + `;">
                                                ` + data[i].flow_name + `</div>
                                        </td>
                                        <td class="pe-0 text-end">
                                            <a class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-overflow="true"
                                                data-kt-menu-placement="bottom-start">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
                                                <span class="svg-icon svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <rect opacity="0.3" x="2"
                                                            y="2" width="20" height="20"
                                                            rx="4" fill="black" />
                                                        <rect x="11" y="11"
                                                            width="2.6" height="2.6" rx="1.3"
                                                            fill="black" />
                                                        <rect x="15" y="11"
                                                            width="2.6" height="2.6" rx="1.3"
                                                            fill="black" />
                                                        <rect x="7" y="11"
                                                            width="2.6" height="2.6" rx="1.3"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </a>
                                            <!--begin::Menu 3-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3"
                                                data-kt-menu="true">
                                                <!--begin::Heading-->
                                                <div class="menu-item px-3">
                                                    <a onclick="getHistory(` + data[i].id + `)"class="menu-link px-3"
                                                        tooltip="New App" data-bs-toggle="modal"
                                                        data-bs-target="#detail-ticket"
                                                        class="menu-link px-3">Detail</a>
                                                </div>` + html + `
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu 3-->
                                        </td>
                                    </tr>`;
                                $('.tbody_yta').append(new_tbody);
                            };

                        } else {
                            var new_tbody = `<tr>
                                <td colspan="2">No data available</td>
                                </tr>`
                            $('.tbody_yta').append(new_tbody);
                        }
                        // console.log(myJSON2);
                        KTMenu.createInstances();
                    },

                });
            }

            function filterLTO() {
                var start_lto = $('#startdate_lto').val();
                var end_lto = $('#enddate_lto').val();

                $.ajax({
                    url: "{{ url('filter-list-ticket-open-user') }}?Start_lto=" + start_lto + "&End_lto=" + end_lto,
                    type: "GET",
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#tbody_lto').empty();
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                if(data[i].desc_mesin != null){
                                    var mesin = data[i].desc_mesin;
                                }else{
                                    var mesin = ``;
                                }
                                if(data[i].location != null){
                                    var lokasi = data[i].location;
                                }else{
                                    var lokasi = ``;
                                }
                                if (data[i].flag == 1) {
                                    var html = `<div class="menu-item px-3">
                                                    <a onclick="setProccess(` + data[i].id + `,'Proccess')"
                                                        class="menu-link px-3" tooltip="New App"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#action-ticket"
                                                        class="menu-link px-3">Proccess</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a onclick="setCancel(` + data[i].id + `,'Cancel')"
                                                        class="menu-link px-3" tooltip="New App"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#action-ticket"
                                                        class="menu-link px-3">Cancel</a>
                                                </div>`
                                } else {
                                    var html = ``
                                }
                                var new_tbodyLTO =
                                    `<tr>
                                                        <td class="">
                                                            <a href="#"
                                                                class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` +
                                    [i + 1] + `</a>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-boldest">#` + data[i]
                                    .ticket_id + `</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">` + data[i].date + `</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">` + mesin + `</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">` + lokasi + `</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">` + data[i].message + `</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">` + data[i]
                                    .reporter_name + `</span>
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-light-primary"
                                                                style="background-color: ` + data[i].bg_color +
                                    `; color: ` + data[i].fg_color + `;">
                                                                ` + data[i].flow_name + `</div>
                                                        </td>
                                                    </tr>`
                                $('#tbody_lto').append(new_tbodyLTO);
                            }
                        } else {
                            var new_tbodyLTO = `<tr>
                                <td colspan="2">No data available</td>
                                </tr>`
                            $('#tbody_lto').append(new_tbodyLTO);
                        }
                        KTMenu.createInstances();
                    },
                });
            }

            function filterATA() {
                var start_ata = $('#startdate_ata').val();
                var end_ata = $('#enddate_ata').val();

                $.ajax({
                    url: "{{ url('filter-all-ticket-assign-user') }}?Start_ata=" + start_ata + "&End_ata=" +
                        end_ata,
                    type: "GET",
                    success: function(response) {
                        var data = JSON.parse(response);
                        console.log(data);
                        $('.tbody_ata').empty();
                        
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                if(data[i].desc_mesin != null){
                                    var mesin = data[i].desc_mesin;
                                }else{
                                    var mesin = ``;
                                }
                                if(data[i].location != null){
                                    var lokasi = data[i].location;
                                }else{
                                    var lokasi = ``;
                                }
                                var new_tbodyATA =
                                    `<tr>
                                                        <td class="">
                                                            <a href="#"
                                                                class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` +
                                    [i +
                                        1
                                    ] + `</a>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-boldest">#` + data[i]
                                    .ticket_id + `</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">` + data[i].date + `</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">` + mesin + `</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">` + lokasi + `</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bold">` + data[i].message + `</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">` + data[i]
                                    .reporter_name + `</span>
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-light-primary"
                                                                style="background-color: ` + data[i].bg_color +
                                    `; color: ` + data[i].fg_color + `;">
                                                                ` + data[i].flow_name + `</div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-gray-800 fw-bold">` + data[i].assist_name + `</span>
                                                        </td>
                                                    </tr>`
                                $('.tbody_ata').append(new_tbodyATA);
                            }
                        } else {
                            var new_tbodyATA = `<tr>
                                <td colspan="2">No data available</td>
                                </tr>`
                            $('.tbody_ata').append(new_tbodyATA);
                        }
                        KTMenu.createInstances();
                    }
                });
            }

            function filterTRENDS() {
                var start_trends = $('#startdate_trends').val();
                var end_trends = $('#enddate_trends').val();

                $.ajax({
                    url: "{{ url('filter-trends-user') }}?Start_trends=" + start_trends + "&End_trends=" + end_trends,
                    type: "GET",
                    success: function(response) {
                        var data = JSON.parse(response);
                        console.log(data);
                        console.log(data.topassist);
                        console.log(data.topassist[0].assist_name);

                        $('.body_trends').empty();

                        if (data.topassist[0].assist_photo != null) {
                            var html =
                                `<img src="{{ asset('public/profile/` + data.topassist[0].assist_photo + `') }} " class="h-50 align-self-center" alt="" />`
                        } else {
                            var html =
                                `<img src="{{ asset('public/assets/global/img/no-profile.jpg') }}" class="h-50 align-self-center" alt="" />`
                        }
                        if (data.topreporter[0].reporter_photo != null) {
                            var html2 =
                                `<img src="{{ asset('public/profile/` + data.topreporter[0].reporter_photo + `') }}" class="h-50 align-self-center" alt="" />`
                        } else {
                            var html2 =
                                `<img src="{{ asset('public/assets/global/img/no-profile.jpg') }}" class="h-50 align-self-center" alt="" />`
                        }
                        var new_trends = `<div class="d-flex align-items-sm-center mb-7">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-circle symbol-50px me-5">
                                        <span class="symbol-label">
                                            ` + html + `
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <div class="flex-grow-1 me-2">
                                            <a
                                                class="text-gray-800 text-hover-primary fs-6 fw-bolder">` + data
                            .topassist[0].assist_name + `</a>
                                            <span class="text-muted fw-bold d-block fs-7">Top Assist</span>
                                        </div>
                                        <span class="badge badge-light fw-bolder my-2">` + data.topassist[0]
                            .count + `
                                            Ticket</span>
                                    </div>
                                    <!--end::Section-->
                                </div>`
                        $('.body_trends').append(new_trends);

                        var new_trends2 = `<div class="d-flex align-items-sm-center mb-7">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-circle symbol-50px me-5">
                                                <span class="symbol-label">
                                                    ` + html2 + `
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                                <div class="flex-grow-1 me-2">
                                                    <a
                                                        class="text-gray-800 text-hover-primary fs-6 fw-bolder">` +
                            data.topreporter[0].reporter_name + `</a>
                                                    <span class="text-muted fw-bold d-block fs-7">Top Reporter</span>
                                                </div>
                                                <span class="badge badge-light fw-bolder my-2">` + data.topreporter[0]
                            .count + `
                                                    Ticket</span>
                                            </div>
                                            <!--end::Section-->
                                        </div>`
                        $('.body_trends').append(new_trends2);

                        var new_trends3 = `<div class="d-flex align-items-sm-center mb-7">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-circle symbol-50px me-5">
                                                <span class="symbol-label">
                                                    <img src="/ceres-html-free/assets/media/svg/brand-logos/plurk.svg"
                                                        class="h-50 align-self-center" alt="" />
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                                <div class="flex-grow-1 me-2">
                                                    <a
                                                        class="text-gray-800 text-hover-primary fs-6 fw-bolder">` +
                            data.topsla[0].sla_name + `</a>
                                                    <span class="text-muted fw-bold d-block fs-7">Top Issue</span>
                                                </div>
                                                <span class="badge badge-light fw-bolder my-2">` + data.topsla[0]
                            .count + `
                                                    Ticket</span>
                                            </div>
                                            <!--end::Section-->
                                        </div>`
                        $('.body_trends').append(new_trends3);
                    }
                });
            }

            function filterTICKETTOTAL() {
                var Start_totalticket = $("#startdate_tickettotal").val();
                var End_totalticket = $("#enddate_tickettotal").val();

                $.ajax({
                    url: "{{ url('filter-ticket-total-user') }}?Start_totalticket=" + Start_totalticket +
                        "&End_totalticket=" + End_totalticket,
                    type: "GET",
                    success: function(data) {
                        var chartdata = JSON.parse(data);
                        console.log(chartdata);
                        console.log(chartdata['newtcbar2']);

                        if (chartdata['newtcbar2'].length > 0) {
                            $('#alert-not-found').empty();
                        } else {
                            $('#alert-not-found').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>No ticket found at this date range.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`);
                        }
                        const barcharttotal = Highcharts.chart('container', {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'Ticket Total'
                            },
                            legend: {
                                layout: 'vertical'
                            },
                            xAxis: {
                                categories: chartdata['newtcd2']
                            },
                            yAxis: {
                                title: {
                                    text: 'Total ticket'
                                }
                            },
                            series: [{
                                name: 'Created Ticket',
                                data: chartdata['newtcbar2']
                            }, {
                                name: 'Resolved and Closed Ticket',
                                data: chartdata['newtrbar2'],
                                color: '#BEC3CC'
                            }]
                        });

                        const piechart = Highcharts.chart('container3', {
                            chart: {
                                type: 'pie'
                            },
                            title: {
                                text: 'Ticket Total'
                            },
                            legend: {
                                layout: 'vertical'
                            },
                            xAxis: {
                                categories: ['Ticket Created', 'Ticket Opened', 'Ticket In Progress',
                                    'Ticket Resolved', 'Ticket Closed', 'Ticket Canceled',
                                    'Ticket Escalated']
                            },
                            yAxis: {
                                title: {
                                    text: 'Total ticket'
                                }
                            },
                            series: [{
                                name: 'Total',
                                data: [{
                                    name: 'Ticket Created',
                                    y: chartdata['newttc2']
                                }, {
                                    name: 'Ticket Opened',
                                    y: chartdata['newtto2'],
                                    color: '#00a65a'
                                }, {
                                    name: 'Ticket In Progress',
                                    y: chartdata['newtti2'],
                                    color: '#001f3f'
                                }, {
                                    name: 'Ticket Resolved',
                                    y: chartdata['newttr2'],
                                    color: '#39cccc'
                                }, {
                                    name: 'Ticket Closed',
                                    y: chartdata['newttcl2'],
                                    color: '#d2d6de'
                                }, {
                                    name: 'Ticket Canceled',
                                    y: chartdata['newttca2'],
                                    color: '#d1f3e3'
                                }, {
                                    name: 'Ticket Escalated',
                                    y: chartdata['newtte2'],
                                    color: '#000000'
                                }]
                            }]
                        });
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const barchart = Highcharts.chart('chartticketreq', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Ticket Requested'
                    },
                    legend: {
                        layout: 'vertical'
                    },
                    xAxis: {
                        categories: [
                            @foreach ($newtrd2 as $trd)
                                '{{ $trd }}',
                            @endforeach
                        ]
                    },
                    yAxis: {
                        title: {
                            text: 'Total ticket'
                        }
                    },
                    series: [{
                        name: 'Ticket Requested',
                        data: {{ $newtr2 }}
                    }]
                });

                const barcharttotal = Highcharts.chart('container', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Ticket Total'
                    },
                    legend: {
                        layout: 'vertical'
                    },
                    xAxis: {
                        categories: [
                            @foreach ($newtcd2 as $tcd)
                                '{{ $tcd }}',
                            @endforeach
                        ]
                    },
                    yAxis: {
                        title: {
                            text: 'Total ticket'
                        }
                    },
                    series: [{
                        name: 'Created Ticket',
                        data: {{ $newtcbar2 }}
                    }, {
                        name: 'Resolved and Closed Ticket',
                        data: {{ $newtrclbar2 }},
                        color: '#BEC3CC'
                    }]
                });

                const piechart = Highcharts.chart('container3', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Ticket Total'
                    },
                    legend: {
                        layout: 'vertical'
                    },
                    xAxis: {
                        categories: ['Ticket Created', 'Ticket Opened', 'Ticket In Progress',
                                    'Ticket Resolved', 'Ticket Closed', 'Ticket Canceled',
                                    'Ticket Escalated']
                    },
                    yAxis: {
                        title: {
                            text: 'Total ticket'
                        }
                    },
                    series: [{
                        name: 'Total',
                        data: [{
                            name: 'Ticket Created',
                            y: {{ $newttc2 }}
                        }, {
                            name: 'Ticket Opened',
                            y: {{ $newtto2 }},
                            color: '#00a65a'
                        }, {
                            name: 'Ticket In Progress',
                            y: {{ $newtti2 }},
                            color: '#001f3f'
                        }, {
                            name: 'Ticket Resolved',
                            y: {{ $newttr2 }},
                            color: '#39cccc'
                        }, {
                            name: 'Ticket Closed',
                            y: {{ $newttcl2 }},
                            color: '#d2d6de'
                        }, {
                            name: 'Ticket Canceled',
                            y: {{ $newttca2 }},
                            color: '#d1f3e3'
                        }, {
                            name: 'Ticket Escalated',
                            y: {{ $newtte2 }},
                            color: '#000000'
                        }]
                    }]
                });
            });
        </script>

        <script type="text/javascript">
            function review(x) {
                // indextr = x.rowIndex;
                console.log(x);
                document.getElementById('ticket_id').value = x;
            }

            function handlerFromDate(e) {
                document.getElementById('to_date').min = e.target.value;
                document.getElementById('enddate_tickettotal').min = e.target.value;
            }

            function handlerToDate(e) {
                document.getElementById('from_date').max = e.target.value;
                document.getElementById('startdate_tickettotal').max = e.target.value;
            }

            function handlerFromDateTR(e) {
                document.getElementById('enddate_tr').min = e.target.value;
            }

            function handlerToDateTR(e) {
                document.getElementById('startdate_tr').max = e.target.value;
            }

            function handlerFromDateTRENDS(e) {
                document.getElementById('enddate_trends').min = e.target.value;
            }

            function handlerToDateTRENDS(e) {
                document.getElementById('startdate_trends').max = e.target.value;
            }

            function handlerFromDateYTA(e) {
                document.getElementById('enddate_yta').min = e.target.value;
            }

            function handlerToDateYTA(e) {
                document.getElementById('startdate_yta').max = e.target.value;
            }

            function handlerFromDateLTO(e) {
                document.getElementById('enddate_lto').min = e.target.value;
            }

            function handlerToDateLTO(e) {
                document.getElementById('startdate_lto').max = e.target.value;
            }

            function handlerFromDateATA(e) {
                document.getElementById('enddate_ata').min = e.target.value;
            }

            function handlerToDateATA(e) {
                document.getElementById('startdate_ata').max = e.target.value;
            }

            function setEscalate(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('escalate-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Assist : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="assist" class="form-control" required>
                                @foreach ($assist as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function setResolve(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('resolve-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span >Reduce Inventory : </span>
                        </label>
                        <select style="width: 100%;"  name="inventory" onchange="getinventory(this)"  class="form-control js-example-basic-single" data-live-search="true">
                                <option value="">- Select Inventory -</option>
                                @foreach ($inventory as $inventory)
                                    <option value="{{ $inventory->inventory_id }}" >{{ $inventory->inventory_nama }}</option>
                                @endforeach
                            </select>
                    </div>
                    
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Qty</span>
                        </label>
                        <input type="number" name="reduce"  id="reduce" class="form-control"  autocomplete="off">  
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">ANALYSIS ROOT CAUSE BREAKDOWN ?</span>
						</label>
						<input type="text"  name="analisis" placeholder="Penyebab Kerusakan Mesin" class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">CORRECTIVE ACTION ?</span>
						</label>
						<input type="text"  name="corrective" placeholder="Langkah Perbaikan"  class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">PREVENTIVE ACTION ?</span>
						</label>
						<input type="text"  name="preventive" placeholder="Langkah Pencegahan" class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">CONSUMABLE USED ?</span>
						</label>
						<input type="text"  name="consumable" placeholder="Barang Habis Pakai"  class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">SERVICES AND COSTS ?</span>
						</label>
						<input type="text"  name="costs" placeholder="Jasa dan Biaya" class="form-control form-control-lg form-control-solid" />
					</div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function setClose(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('close-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function setProccess(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('proccess-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Sla : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="sla" class="form-control" required>
                            @foreach ($sla as $sla)
                                <option value="{{ $sla->id }}">{{ $sla->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function setAssign(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('asign-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Assist : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="assist" class="form-control" required>
                                @foreach ($assist2 as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function setCancel(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('cancel-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function getinventory(sel) {
                var id = sel.value;

                APP_URL = '{{ url('/') }}';
                $.ajax({
                    url: APP_URL + '/get-inventory/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response['data']);
                        $("input[name=reduce]").val(response['data'].inventory_qty);
                        $("input[name=reduce]").attr({
                            "max": response['data'].inventory_qty,
                            "min": 1
                        });
                    }
                });
            }
        </script>

        <script src="assets/js/custom/widgets.js"></script>
    @endsection
