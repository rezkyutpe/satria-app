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
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="{{ url('user-attendance-list') }}">Attendance</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-performance') }}">User Performance</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-competence') }}">User Competence</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-safety-hour') }}">Safety Hour</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-attendance-ratio') }}">Attendance Ratio</a>
                    </li>
                </ul>
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <h2 class="pe-0">Attendance List <br><div class="badge badge-light-primary">- {{ isset($_GET['q']) ? 'At '.date('d F Y',strtotime($_GET['q'])) : 'Today '.date('d F Y') }} </div> </h2>
                    </div>
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        <div class="w-150 mw-250px">
                                        <form action="{{ url('user-attendance-list') }}" method="get">
                                            <input type="date" name="q" id="q" class="form-control form-control-solid" value="{{ isset($_GET['q']) ? $_GET['q'] : date('Y-m-d') }}" onchange="this.form.submit()">
                                        </form>
                                        </div>
                                        <div class="w-100 mw-150px">
                                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Work" data-kt-ecommerce-product-filter="status">
                                                <option></option>
                                                <option value="all">All</option>
                                                <option value="WFO">WFO</option>
                                                <option value="WFH">WFH</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                            <!--begin::Table head-->
                            <thead>
											<tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
												<th>#</th>
												<th>User</th>
												<th class="min-w-100px">Picture In</th>
												<th class="text-end min-w-50px">User In</th>
												<th>Picture Out</th>
												<th class="text-end min-w-50px">User Out</th>
												<th class="text-end min-w-50px">Methode</th>
												<th class="text-end min-w-70px">Actions</th>
											</tr>
										</thead>
										<tbody class="fw-bold text-gray-600">
											
										@php($no=0)
										@foreach($data['attendance'] as $attendance)
										@php($no=$no+1)
											<tr>
												<td>
													<div class="form-check form-check-sm form-check-custom form-check-solid">{{$no}}
													</div>
												</td>
                                                <td class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                        <a>
                                                            <div class="symbol-label">
                                                                <img src="@if($attendance->photo!='') {{ asset('public/profile/'.$attendance->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" alt="Emma Smith" class="w-100" />
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <strong>{{ $attendance->name }}</strong>
                                                        <span>{{ $attendance->nrp   }}</span>
                                                    </div>
                                                </td>
												<td>
													<div class="d-flex">
														<div class="symbol symbol-50px me-2">
                                                            <span class="symbol-label bg-light-danger">
                                                                <a data-fslightbox="lightbox-hot-sales" href="@if($attendance->foto_in!='') {{ Storage::disk('public')->url('/Attendance/In/'.$attendance->foto_in) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendance->foto_in!='') {{ Storage::disk('public')->url('/Attendance/In/'.$attendance->foto_in) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
                                                            </span>
                                                        </div>
													</div>
												</td>
												<td class="text-end pe-0">
                                                    <div class="d-flex flex-column">
                                                        <strong><div class="badge badge-light-danger">{{ date('d-m-Y H:i:s',strtotime($attendance->in_time))}}</div></strong>
                                                        <span><div class="badge badge-light-warning">{{ $attendance->longitude_in }}, {{ $attendance->latitude_in }}</div></span>
                                                    </div>
												</td>
												<td>
													<div class="d-flex">
														<div class="symbol symbol-50px me-2">
                                                        <span class="symbol-label bg-light-danger">
                                                            <a data-fslightbox="lightbox-hot-sales" href="@if($attendance->foto_out!='') {{ Storage::disk('public')->url('/Attendance/Out/'.$attendance->foto_out) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendance->foto_out!='') {{ Storage::disk('public')->url('/Attendance/Out/'.$attendance->foto_out) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
                                                        </span>
                                                        </div>
													</div>
												</td>
												<td class="text-end pe-0">
                                                    <div class="d-flex flex-column">
                                                        <strong><div class="badge badge-light-primary">{{ isset($attendance->out_time) ? date('d-m-Y H:i:s',strtotime($attendance->out_time)) : '-'}}</div></strong>
                                                        <span><div class="badge badge-light-warning">{{ $attendance->longitude_out }}, {{ $attendance->latitude_out }}</div></span>
                                                    </div>
												</td>
												
												<td class="text-end pe-0" data-order="{{ $attendance->work_metode }}">
													<div class="@if($attendance->work_metode=='WFO') {{'badge badge-light-danger'}} @else {{'badge badge-light-primary'}} @endif">{{ $attendance->work_metode }}</div>
												</td>
												<td class="text-end">
													<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions 
													<span class="svg-icon svg-icon-5 m-0">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span></a>
													<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
														<div class="menu-item px-3">
															
                                                        <a href="{{ url('user-detail/'.$attendance->created_by) }}" class="menu-link px-3">Detail</a>
													</div>
												</td>
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
@endsection