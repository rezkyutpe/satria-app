@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    
    <div class="content flex-row-fluid" id="kt_content">
		<div class="card mb-5 mb-xl-10">
			<div class="card-body pt-9 pb-0">
				<!--begin::Details-->
				<div class="d-flex flex-wrap flex-sm-nowrap mb-3">
					<!--begin: Pic-->
					<div class="me-7 mb-4">
						<div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
							<img src="@if(Auth::user()->photo!='') {{ asset('public/profile/'.Auth::user()->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" alt="image" />
							<div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
						</div>
					</div>
					<!--end::Pic-->
					<!--begin::Info-->
					<div class="flex-grow-1">
						<!--begin::Title-->
						<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
							<!--begin::User-->
							<div class="d-flex flex-column">
								<!--begin::Name-->
								<div class="d-flex align-items-center mb-2">
									<a class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ Auth::user()->name}}</a>
									<a>
										<!--begin::Svg Icon | path: icons/duotune/general/gen026.svg-->
										<span class="svg-icon svg-icon-1 svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
												<path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="currentColor" />
												<path d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</a>
									<a class="btn btn-sm btn-light-info fw-bold ms-2 fs-8 py-1 px-3">{{ Auth::user()->title }}</a>
									<a class="btn btn-sm btn-light-success fw-bold ms-2 fs-8 py-1 px-3">{{ isset($data['userdetail']->vendor_name) ? $data['userdetail']->vendor_name : '-'  }}</a>
									
								</div>
								<!--end::Name-->
								<!--begin::Info-->
								<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
									
									<a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
									<!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
									<span class="svg-icon svg-icon-4 me-1">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor" />
											<path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor" />
										</svg>
									</span>
									{{ Auth::user()->email }}</a>
									<a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
									<!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
									<span class="svg-icon svg-icon-4 me-1">
									<i class="fa fa-duotone fa-briefcase"></i>
									</span>
									{{ Auth::user()->department }}</a>
									<a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
									<!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
									<span class="svg-icon svg-icon-4 me-1">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
											<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
										</svg>
									</span>
									{{ Auth::user()->company_name }}</a>
									
								</div>
								<!--end::Info-->
							</div>
							<!--end::User-->
						</div>
						<!--end::Title-->
						<!--begin::Stats-->
						<div class="d-flex flex-wrap flex-stack">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column flex-grow-1 pe-8">
								<!--begin::Stats-->
								<div class="d-flex flex-wrap">
									<!--begin::Stat-->
									<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
										<!--begin::Number-->
										<div class="d-flex align-items-center">
											<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
											<span class="svg-icon svg-icon-3 svg-icon-success me-2">
											<i class="fa fa-user-clock text-primary"></i>
											</span>
											<!--end::Svg Icon-->
											<div class="fs-2 fw-bold">{{ isset($data['attendancetoday']->in_time) ? date('H:i',strtotime($data['attendancetoday']->in_time)) : '-' }}</div>  <span>In</span>
										</div>
										<!--end::Number-->
										<!--begin::Label-->
										<div class="fw-semibold fs-6 text-gray-400" style="float: right;">
											@if(!isset($data['attendancetoday']->in_time))<a class="btn btn-sm btn-danger fw-bold ms-2 fs-8 py-1 px-3" onClick="getLocation()" data-bs-toggle="modal" data-bs-target="#kt_modal_in_user">Check In</a>
											@elseif(isset($data['attendancetoday']->in_time) && !isset($data['attendancetoday']->out_time))<a class="btn btn-sm btn-success fw-bold ms-2 fs-8 py-1 px-3" onClick="getLocation()" data-bs-toggle="modal" data-bs-target="#kt_modal_out_user">Check Out</a>
											@else
											<div class="fw-semibold fs-6 text-gray-400"> <strong>{{ isset($data['attendancetoday']->out_time) ? date('H:i',strtotime($data['attendancetoday']->out_time)) : '-' }}</strong> Out</div>
											@endif
										</div>
										<!--end::Label-->
									</div>
									<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
										<!--begin::Number-->
										<div class="d-flex align-items-center">
											<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
											<span class="svg-icon svg-icon-3 svg-icon-success me-2">
												<i class="fa fa-stopwatch text-warning"></i>
											</span>
											<!--end::Svg Icon-->
											<div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ isset($data['attendancetoday']->in_time) ? round(Helper::TimeInterval($data['attendancetoday']->in_time,isset($data['attendancetoday']->out_time) ? $data['attendancetoday']->out_time :date('d-m-Y H:i:s'))/60) : '0' }}" >0</div>
										</div>
										<!--end::Number-->
										<!--begin::Label-->
										<div class="fw-semibold fs-6 text-gray-400">Minutes</div>
										<!--end::Label-->
									</div>
									<!--end::Stat-->
									<!--begin::Stat-->
									<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
										<!--begin::Number-->
										<div class="d-flex align-items-center">
											<!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
											<span class="svg-icon svg-icon-3 svg-icon-danger me-2">
											<i class="fa fa-briefcase text-success"></i>
											</span>
											<!--end::Svg Icon-->
											<div class="fs-2 fw-bold">{{ isset($data['attendancetoday']->work_metode)? $data['attendancetoday']->work_metode : '-' }}</div>
										</div>
										<!--end::Number-->
										<!--begin::Label-->
										<div class="fw-semibold fs-6 text-gray-400">Work Methode</div>
										<!--end::Label-->
									</div>
									<!--end::Stat-->
									<!--begin::Stat-->
									<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
										<!--begin::Number-->
										<div class="d-flex align-items-center">
											<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
											<span class="svg-icon svg-icon-3 svg-icon-success me-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
													<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
												</svg>
											</span>
											<!--end::Svg Icon-->
											<div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ count($data['attendance']) }}">0</div> <span>Attendance</span>
										</div>
										<!--end::Number-->
										<!--begin::Label-->
										<div class="fw-semibold fs-6 text-gray-400">At {{ isset($_GET['q']) ? date('M Y',strtotime($_GET['q'])) : date('M Y') }}</div>
										<!--end::Label-->
									</div>
									<!--end::Stat-->
								</div>
								<!--end::Stats-->
							</div>
							<!--end::Wrapper-->
							<!--begin::Progress-->
							
							<!--end::Progress-->
						</div>
						<!--end::Stats-->
					</div>
					<!--end::Info-->
				</div>
				<!--end::Details-->
				<!--begin::Navs-->
				<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
					<!--begin::Nav item-->
					<li class="nav-item mt-2">
						<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="{{ url('user-attendance') }}">Overview</a>
					</li>
				</ul>
				<!--begin::Navs-->
			</div>
		</div>
		<div class="card card-flush">
			<div class="card-header align-items-center py-5 gap-2 gap-md-5">
				<h2 class="text-end pe-0">Your Attendance Record</h2>
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
						<form action="{{ url('user-attendance') }}" method="get">
							<input type="month" name="q" id="q" class="form-control form-control-solid" value="{{ isset($_GET['q']) ? $_GET['q'] : date('Y-m') }}" onchange="this.form.submit()">
						</form>
					</div>
					<div class="w-200 mw-250px">
						<form action="{{ url('user-attendance-export') }}" method="get">
						<input type="hidden" name="q" id="q" class="form-control form-control-solid" value="{{ isset($_GET['q']) ? $_GET['q'] : date('Y-m') }}">
						<button type="submit" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
						<span class="svg-icon svg-icon-2">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor" />
								<path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor" />
								<path opacity="0.3" d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z" fill="currentColor" />
							</svg>
						</span>
						<!--end::Svg Icon-->Export Report</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card-body pt-0">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                                        <!--begin::Table head-->
                                        <thead>
											<tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
												<th>#</th>
												<th class="text-end min-w-70px">Methode</th>
												<th class="min-w-150px">Picture In</th>
												<th class="text-end min-w-100px">Clock In</th>
												<th class="min-w-150px">Picture Out</th>
												<th class="text-end min-w-100px">Clock Out</th>
												<th class="text-end w-200px">Note</th>
												<th class="text-end min-w-100px">Actions</th>
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
												<td class="text-center pe-0" data-order="{{ $attendance->work_metode }}">
													<div class="@if($attendance->work_metode=='WFO') {{'badge badge-light-danger'}} @else {{'badge badge-light-primary'}} @endif">{{ $attendance->work_metode }}</div>
												</td>
												<td class="text-center pe-0">
														<div class="symbol symbol-50px me-2">
                                                            <span class="symbol-label bg-light-danger">
                                                                <a data-fslightbox="lightbox-hot-sales" href="@if($attendance->foto_in!='') {{ Storage::disk('public')->url('/Attendance/In/'.$attendance->foto_in) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendance->foto_in!='') {{ Storage::disk('public')->url('/Attendance/In/'.$attendance->foto_in) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
                                                            </span>
                                                        </div>
												</td>
												<td class="text-end pe-0">
                                                    <div class="d-flex flex-column">
                                                        <strong><div class="badge badge-light-danger">{{ isset($attendance->revice_in_time) ? date('d-m-Y H:i',strtotime($attendance->revice_in_time)) : (isset($attendance->in_time) ? date('d-m-Y H:i',strtotime($attendance->in_time)) : '-')}}</div></strong>
                                                        <span><div class="badge badge-light-warning">{{ $attendance->longitude_in }}, {{ $attendance->latitude_in }}</div></span>
                                                    </div>
												</td>
												<td class="text-center pe-0">
														<div class="symbol symbol-50px me-2">
                                                        <span class="symbol-label bg-light-danger">
                                                            <a data-fslightbox="lightbox-hot-sales" href="@if($attendance->foto_out!='') {{ Storage::disk('public')->url('/Attendance/Out/'.$attendance->foto_out) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendance->foto_out!='') {{ Storage::disk('public')->url('/Attendance/Out/'.$attendance->foto_out) }}  @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
                                                        </span>
                                                        </div>
												</td>
												<td class="text-end pe-0">
                                                    <div class="d-flex flex-column">
                                                        <strong><div class="badge badge-light-primary">{{ isset($attendance->revice_out_time) ? date('d-m-Y H:i',strtotime($attendance->revice_out_time)) : (isset($attendance->out_time) ? date('d-m-Y H:i',strtotime($attendance->out_time)) : '-')}}</div></strong>
                                                        <span><div class="badge badge-light-warning">{{ $attendance->longitude_out }}, {{ $attendance->latitude_out }}</div></span>
                                                    </div>
												</td>
												
                                                <td class="text-end">
                                                    <div class="d-flex flex-column">
                                                        <span style="font-size: 12px;">{{ $attendance->note   }}</span>
                                                    </div>
                                                </td>
												<td class="text-end">
													<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions 
													</a>
													<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
														<div class="menu-item px-3">
															
														<a href="#" onClick="editattendance({{ $attendance->id }})" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-attendance" class="menu-link px-3">Revice</a>
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

@endsection

@section('myscript')
<div class="modal fade" id="kt_modal_in_user" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-650px">
		<div class="modal-content">
			<div class="modal-header" id="kt_modal_add_user_header">
				<h2 class="fw-bold">Attendance In</h2>
				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
								</svg>
							</span>
						</div>
			</div>
			<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
				<form id="kt_modal_add_user_form" class="form" method="post" action="{{ url('user-clock-in') }}" enctype="multipart/form-data">
                    @csrf
					<div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
						<div class="mb-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6">Your Attendance Picture</label>
                                <div class="col-lg-12">
                                   
									<div class="image-input image-input-outline" data-kt-image-input="true">
                                        <div class="form-group">
											<center>
                                            <div id="my_camerain"></div>
											<div class="col-md-12">
												<div id="results"></div>
											</div>
											<br/>
											<input type="button" id="take" class="btn btn-success" value="Capture" onClick="take_snapshot()">
											<input type="button" id="retake" class="btn btn-warning" style="display: none;" value="ReCapture" onClick="retake_snapshot()">
											<input type="hidden" name="photo" value="null" id="image-tag">
											</center>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<div class="mb-7">
							<label class="required fw-semibold fs-6 mb-5">Work Method:</label>
							<input type="hidden" value="0" name="latitude" id="lat" >
							<input type="hidden" value="0" name="longitude" id="long" >
							<div class="d-flex fv-row">
								<div class="form-check form-check-custom form-check-solid">
									<input class="form-check-input me-3" name="metode" type="radio" value="WFO" id="kt_modal_update_role_option_0" checked='checked' />
									<label class="form-check-label" for="kt_modal_update_role_option_0">
										<div class="fw-bold text-gray-800">WFO</div>
									</label>
								</div>
							</div>
							<div class='separator separator-dashed my-5'></div>
							<div class="d-flex fv-row">
								<div class="form-check form-check-custom form-check-solid">
									<input class="form-check-input me-3" name="metode" type="radio" value="WFH" id="kt_modal_update_role_option_1" />
									<label class="form-check-label" for="kt_modal_update_role_option_1">
										<div class="fw-bold text-gray-800">WFH</div>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="text-center pt-15">
						<center>
						<button type="submit" id="buttonin" style="display: none;" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">Submit</span>
							<span class="indicator-progress">Please wait... 
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="kt_modal_out_user" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-650px">
		<div class="modal-content">
			<div class="modal-header" id="kt_modal_add_user_header">
				<h2 class="fw-bold">Attendance Out</h2>
				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
								</svg>
							</span>
						</div>
			</div>
			<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
				<form id="kt_modal_add_user_form" class="form" method="POST" action="{{ url('user-clock-out') }}" enctype="multipart/form-data">
                    @csrf
					<div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
						<div class="mb-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6">Your Attendance Picture</label>
                                <div class="col-lg-12">
                                   
									<div class="image-input image-input-outline" data-kt-image-input="true">
                                        <div class="form-group">
                                        	<center>
                                            <div id="my_cameraout" ></div>
											<div class="col-md-12">
												<div id="resultsout"></div>
											</div>
											<br/>
											<input type="button" id="takeout" class="btn btn-success" value="Capture" onClick="take_snapshotout()">
											<input type="button" id="retakeout" class="btn btn-warning" style="display: none;" value="ReCapture" onClick="retake_snapshotout()">
											<input type="hidden" required name="photo" value="null" id="image-tagout">
											</center>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<div class="mb-7">
							<label class="required fw-semibold fs-6 mb-5">Note:</label>
							<input type="hidden" name="latitude" id="latout" >
							<input type="hidden" name="longitude" id="longout" >
							<input type="hidden" name="id" value="{{ isset($data['attendancetoday']->id)?$data['attendancetoday']->id:'0' }}" >
							<textarea name="note" class="form-control" cols="30" rows="10"></textarea>
						</div>
					</div>
					<div class="text-center pt-15">
						<center>
						<button type="submit" id="buttonout" style="display: none;" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">Submit</span>
							<span class="indicator-progress">Please wait... 
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="action-attendance" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="action-attendance_header">
                <h2 class="fw-bold">Revice Attendance</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
				<br>
                <form id="kt_modal_add_user_form" class="form" action="{{ url('revice-attendance') }}" method="POST">
                    @csrf
				<div class="fv-row mb-7 row">
					<div class="col-md-6">
						<label class="required fw-semibold fs-6 mb-2">Clock In</label>
						<input type="hidden" name="id" id="idattendance" >
						<input type="time" required name="in_time" id="in_timeattendance" required class="form-control form-control-solid mb-3 mb-lg-0" />
					</div>
					<div class="col-md-6">
						<label class="required fw-semibold fs-6 mb-2">Clock Out</label>
						<input type="time" required name="out_time" id="out_timeattendance" required class="form-control form-control-solid mb-3 mb-lg-0" />
					</div>
				</div>
				<div class="fv-row mb-7">
					<label class="required fw-semibold fs-6 mb-2">Is Overtime?</label> <br>
                        <input type="checkbox" name="ovt" value="1"> Overtime
				</div>
				<div class="fv-row mb-7">
					<label class="required fw-semibold fs-6 mb-2">Note</label>
					<textarea name="note" required cols="5" rows="5" id="noteattendance" class="form-control form-control-solid mb-3 mb-lg-0"></textarea>
				</div>
				<div class="text-center pt-15" style="float:right;">
					<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
						<span class="indicator-label">Submit</span>
						<span class="indicator-progress">Please wait... 
						<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
					</button>
				</div>
				</form>
			</div>       
               
        </div>
    </div>
</div>	

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script>

Webcam.set({
		width: 320,
		height: 380,
		image_format: 'jpeg',
		jpeg_quality: 90
	});
$('#kt_modal_out_user').on('shown.bs.modal', function () {
  console.log("open");
  

	Webcam.attach( '#my_cameraout' );
});

$('#kt_modal_out_user').on('hidden.bs.modal', function () {
  console.log("close");
  Webcam.reset('#my_cameraout');
});

$('#kt_modal_in_user').on('shown.bs.modal', function () {
  console.log("open");
  

	Webcam.attach( '#my_camerain' );
});

$('#kt_modal_in_user').on('hidden.bs.modal', function () {
  console.log("close");
  Webcam.reset('#my_cameraoin');
});
function take_snapshot() {
    Webcam.snap( function(data_uri) {
        // $("#image-tag").val(data_uri);
        document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        document.getElementById('my_camerain').style.display = 'none';
        document.getElementById('take').style.display = 'none';
        document.getElementById('retake').style.display = 'block';
        document.getElementById('buttonin').style.display = 'block';
    } );
}
function retake_snapshot() {
    document.getElementById('results').innerHTML = '';
    document.getElementById('my_camerain').style.display = 'block';
    document.getElementById('take').style.display = 'block';
        document.getElementById('retake').style.display = 'none';
        document.getElementById('buttonin').style.display = 'none';
}
function take_snapshotout() {
    Webcam.snap( function(data_uri) {
        // $("#image-tagout").val(data_uri);
        document.getElementById('resultsout').innerHTML = '<img src="'+data_uri+'"/>';
        document.getElementById('my_cameraout').style.display = 'none';
        document.getElementById('takeout').style.display = 'none';
        document.getElementById('retakeout').style.display = 'block';
        document.getElementById('buttonout').style.display = 'block';
    } );
}
function retake_snapshotout() {
    document.getElementById('resultsout').innerHTML = '';
    document.getElementById('my_cameraout').style.display = 'block';
    document.getElementById('takeout').style.display = 'block';
        document.getElementById('retakeout').style.display = 'none';
        document.getElementById('buttonout').style.display = 'none';
}
  function getLocation() {
	console.log('getloc');
    if (navigator.geolocation) {
    	console.log('showloc');
      navigator.geolocation.getCurrentPosition(showPosition,errorCallback,{timeout:10000});
    } else { 
      alert("Geolocation is not supported by this browser.");
    }
  }

  function showPosition(position) {
  	document.getElementById("long").value = position.coords.longitude;
  	document.getElementById("lat").value = position.coords.latitude;
  	document.getElementById("longout").value = position.coords.longitude;
  	document.getElementById("latout").value = position.coords.latitude;
  	console.log(position);
  }
  function errorCallback(error) {
     switch(error.code) {
    case error.PERMISSION_DENIED:
		  alert("User denied the request for Geolocation.");
		  break;
		case error.POSITION_UNAVAILABLE:
		  alert("Location information is unavailable.");
		  break;
		case error.TIMEOUT:
		  alert("The request to get user location timed out.");
		  break;
		case error.UNKNOWN_ERROR:
		  alert("An unknown error occurred.");
		  break;
		}
		window.location.reload();
  }
  
	function editattendance(val) {
		// indextr = x.rowIndex;
		APP_URL = '{{url('/')}}' ;
		$.ajax({
		url: APP_URL+'/user-attendance-detail/' + val,
		type: 'get',
		dataType: 'json',
		success: function(response){
		// console.log(response);
		if(response['revice_out_time']==null){
			var outtimeParse = new Date(response['out_time']);
			var outtime = outtimeParse.toLocaleTimeString('en-IT', { hour12: false }).replace(/(.*)\D\d+/, '$1');
		}else{
			var outtimeParse = new Date(response['revice_out_time']);
			var outtime = outtimeParse.toLocaleTimeString('en-IT', { hour12: false }).replace(/(.*)\D\d+/, '$1');
		}
		if(response['revice_in_time']==null){
			var intimeParse = new Date(response['in_time']);
			var intime = intimeParse.toLocaleTimeString('en-IT', { hour12: false }).replace(/(.*)\D\d+/, '$1');
		}else{
			var intimeParse = new Date(response['revice_in_time']);
			var intime = intimeParse.toLocaleTimeString('en-IT', { hour12: false }).replace(/(.*)\D\d+/, '$1');
		}
			document.getElementById('idattendance').value=val;
			document.getElementById('in_timeattendance').value=intime;
			document.getElementById('out_timeattendance').value=outtime;
			document.getElementById('noteattendance').value=response['note'];
		}
		});
	}
</script>
@endsection