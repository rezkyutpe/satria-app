@extends('fe-layouts.master')

@section('content')
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
	<!--begin::Post-->
	<div class="content flex-row-fluid" id="kt_content">
		<!--begin::Index-->
		
		<div class="card card-page">
			<!--begin::Card body-->
			<div class="card-body">
				<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
					<!--begin::Col-->
					<div class="col-xxl-8">
						<div class="card-header align-items-center py-5 gap-2 gap-md-5">
							<div class="card-title">
								<h3>Attendance {{ isset($_GET['q']) ? 'At '.date('d F Y',strtotime($_GET['q'])) : 'Today, '.date('d F Y') }}</h3>
							</div>
							<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
								<div class="w-150 mw-250px">
								<form action="{{ url('user-dashboard') }}" method="get">
									<input type="date" name="q" id="q" class="form-control form-control" value="{{ isset($_GET['q']) ? $_GET['q'] : date('Y-m-d') }}" onchange="this.form.submit()">
								</form>
								</div>
							</div>
						</div>
						<!--begin::Row-->
						<div class="row g-5 g-xl-10">
							<!--begin::Col-->
							<div class="col-md-4">
								<!--begin::Card widget 11-->
								<div class="card card-flush h-xl-100" style="background-color: #F6E5CA">
									<!--begin::Header-->
									<div class="card-header flex-nowrap pt-6">
										<!--begin::Title-->
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label fw-bold fs-4 text-gray-800">All User </span>
											<span class="mt-1 fw-semibold fs-7" style="color:">Attendance</span>
										</h3>
										<!--end::Title-->
										<!--begin::Toolbar-->
										<div class="card-toolbar">
											<a href="{{ url('user-attendance-list') }} " data-bs-toggle='tooltip' data-bs-dismiss='click' data-bs-custom-class="tooltip-dark" title="All Attendance Record">View All</a>
										</div>
										<!--end::Toolbar-->
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body text-center pt-5">
										<!--begin::Image-->
										<img src="https://preview.keenthemes.com/ceres-html-pro/assets/media/svg/shapes/bitcoin.svg" class="h-125px mb-5" alt="" />
										<!--end::Image-->
										<!--begin::Section-->
										<div class="text-start">
											<br>
											<span class="d-block fw-bold fs-1 text-gray-800">{{ count($data['attendance'])}} Person</span>
											<span class="mt-1 fw-semibold fs-8" style="color:">Of {{ $data['dept'] }} Attendance's</span>
										</div>
										<!--end::Section-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Card widget 11-->
							</div>
							<!--end::Col-->
							<!--begin::Col-->
							<div class="col-md-4">
								<!--begin::Card widget 11-->
								<div class="card card-flush h-xl-100" style="background-color: #F3D6EF">
									<!--begin::Header-->
									<div class="card-header flex-nowrap pt-5">
										<!--begin::Title-->
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label fw-bold fs-4 text-gray-800">WFO User</span>
											<span class="mt-1 fw-semibold fs-7" style="color:">Attendance</span>
										</h3>
										<!--end::Title-->
										<!--begin::Toolbar-->
										<div class="card-toolbar">
											<!--begin::Menu-->
											<button class="btn btn-icon justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true" style="color:">
												<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
												<span class="svg-icon svg-icon-1">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
														<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
														<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
														<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
													</svg>
												</span>
												<!--end::Svg Icon-->
											</button>
											<!--begin::Menu 2-->
											<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Attendence List</div>
												</div>
												<div class="separator mb-3 opacity-75"></div>
												@foreach($data['attendancewfo'] as $attendancewfo)
												<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
													<a href="#" class="menu-link px-3">
														<span class="menu-title">{{ $attendancewfo->name}}</span>
														<span class="menu-arrow"></span>
													</a>
													<div class="menu-sub menu-sub-dropdown w-175px py-4">
														<div class="menu-item px-3">
															<a class="menu-link px-3 badge badge-light-danger">
																<span class="svg-icon svg-icon-3 svg-icon-success me-2">
																	<i class="fa fa-user-clock text-primary"></i>
																</span>{{ date('d-m-Y H:i:s',strtotime($attendancewfo->in_time))}}
															</a>
														</div>
														<div class="menu-item px-3">
															<a class="menu-link px-3"><span class="symbol-label bg-light-danger">
															<a data-fslightbox="lightbox-hot-sales" href="@if($attendancewfo->foto_in!='') {{ Storage::disk('public')->url('/Attendance/In/'.$attendancewfo->foto_in) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendancewfo->foto_in!='')  {{ Storage::disk('public')->url('/Attendance/In/'.$attendancewfo->foto_in) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
																			</span> <strong>#{{ $attendancewfo->work_metode}}</strong>
															</a><span class="badge badge-light-danger" style="font-size: 10px;margin-top: 5px;"> <i class="fa fa-map-marker text-warning"> </i> {{ $attendancewfo->longitude_in }}, {{ $attendancewfo->latitude_in }}
																</span></a>
														</div>
													</div>
												</div>
												@endforeach
												
												
												<!--end::Menu item-->
											</div>
											<!--end::Menu 2-->
											<!--end::Menu-->
										</div>
										<!--end::Toolbar-->
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body text-center pt-5">
										<!--begin::Image-->
										<img src="https://preview.keenthemes.com/ceres-html-pro/assets/media/svg/shapes/ethereum.svg" class="h-125px mb-5" alt="" />
										<!--end::Image-->
										<!--begin::Section-->
										<div class="text-start">
										<br>
											<span class="d-block fw-bold fs-1 text-gray-800">{{ count($data['attendancewfo'])}} Person</span>
											<span class="mt-1 fw-semibold fs-8" style="color:">Of {{ $data['dept'] }} Attendance's</span>
										</div>
										<!--end::Section-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Card widget 11-->
							</div>
							<!--end::Col-->
							<!--begin::Col-->
							<div class="col-md-4">
								<!--begin::Card widget 11-->
								<div class="card card-flush h-xl-100" style="background-color: #BFDDE3">
									<!--begin::Header-->
									<div class="card-header flex-nowrap pt-5">
										<!--begin::Title-->
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label fw-bold fs-4 text-gray-800">WFH User</span>
											<span class="mt-1 fw-semibold fs-7" style="color:">Attendance</span>
										</h3>
										<!--end::Title-->
										<!--begin::Toolbar-->
										<div class="card-toolbar">
											<!--begin::Menu-->
											<button class="btn btn-icon justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true" style="color:">
												<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
												<span class="svg-icon svg-icon-1">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
														<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
														<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
														<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
													</svg>
												</span>
												<!--end::Svg Icon-->
											</button>
											<!--begin::Menu 2-->
											<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Attendence List</div>
												</div>
												<div class="separator mb-3 opacity-75"></div>
												@foreach($data['attendancewfh'] as $attendancewfh)
												<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
													<a href="#" class="menu-link px-3">
														<span class="menu-title">{{ $attendancewfh->name}}</span>
														<span class="menu-arrow"></span>
													</a>
													<div class="menu-sub menu-sub-dropdown w-175px py-4">
														<div class="menu-item px-3">
															<a class="menu-link px-3 badge badge-light-danger">
																<span class="svg-icon svg-icon-3 svg-icon-success me-2">
																	<i class="fa fa-user-clock text-primary"></i>
																</span>{{ date('d-m-Y H:i:s',strtotime($attendancewfh->in_time))}}
															</a>
														</div>
														<div class="menu-item px-3">
															<a class="menu-link px-3"><span class="symbol-label bg-light-danger">
															<a data-fslightbox="lightbox-hot-sales" href="@if($attendancewfh->foto_in!='') {{ Storage::disk('public')->url('/Attendance/In/'.$attendancewfh->foto_in) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendancewfh->foto_in!='')  {{ Storage::disk('public')->url('/Attendance/In/'.$attendancewfh->foto_in) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
																			</span> <strong>#{{ $attendancewfh->work_metode}}</strong>
															</a><span class="badge badge-light-danger" style="font-size: 10px;margin-top: 5px;"> <i class="fa fa-map-marker text-warning"> </i> {{ $attendancewfh->longitude_in }}, {{ $attendancewfh->latitude_in }}
																</span></a>
														</div>
													</div>
												</div>
												@endforeach
												
												
											</div>
											<!--end::Menu 2-->
											<!--end::Menu-->
										</div>
										<!--end::Toolbar-->
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body text-center pt-5">
										<!--begin::Image-->
										<img src="https://preview.keenthemes.com/ceres-html-pro/assets/media/svg/shapes/dogecoin.svg" class="h-125px mb-5" alt="" />
										<!--end::Image-->
										<!--begin::Section-->
										<div class="text-start">
										<br>
											<span class="d-block fw-bold fs-1 text-gray-800">{{ count($data['attendancewfh'])}} Person</span>
											<span class="mt-1 fw-semibold fs-8" style="color:">Of {{ $data['dept'] }} Attendance's</span>
										</div>
										<!--end::Section-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Card widget 11-->
							</div>
							<!--end::Col-->
						</div>
						<!--end::Row-->
					</div>
					<!--end::Col-->
					<!--begin::Col-->
					<div class="col-xxl-4">
					<div class="card card-flush h-lg-100">
						<!--begin::Header-->
						<div class="card-header pt-5">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark">Contract Statistics</span>
								<span class="text-gray-400 mt-1 fw-semibold fs-6">Contract List Overdue</span>
							</h3>
							<!--end::Title-->
							<div class="card-toolbar">
									<a href="#" class="btn btn-sm btn-light" data-bs-toggle='tooltip' data-bs-dismiss='click' data-bs-custom-class="tooltip-dark" title="Delivery App is coming soon">View All</a>
								</div>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-9 pb-5">
							<!--begin::Slider-->
							<div class="tns">
								<div data-tns="true" data-tns-nav-position="bottom" data-tns-controls="false">
										<!--begin::Slide-->
										@php($no=0)
										@foreach($data['userdetail'] as $userdetail)
										@php($no=$no+1)
										@php($overdue=Helper::Datediff(date('Y-m-d'),$userdetail->end_date))
										@if($no==1 || $no%5==1)
										<div class="mb-3">
											<div class="d-flex flex-stack">
												<!--begin::Section-->
												<div class="m-0">
													<span class="text-gray-800 fw-bold d-block fs-2hx lh-1 ls-n2">{{ $overdue }}d</span>
													<span class="text-gray-400 fw-semibold fs-6">{{ $userdetail->name}} </span>
												</div>
												<!--end::Section-->
												<!--begin::Statistics-->
												<div class="d-flex flex-column align-items-end w-100 mw-200px overflow-hidden">
													<!--begin::Select-->
													<div class="mb-2"><div class="badge badge-light-{{ ($overdue>=60) ? 'info' : 'danger' }}">{{ date('d-M-y',strtotime($userdetail->join_date)).' s/d '.date('d-M-y',strtotime($userdetail->end_date)) }}</div></div>
													<!--end::Select-->
													<!--begin::Progress-->
													<div class="progress h-6px w-100 bg-light">
														<div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<!--end::Progress-->
												</div>
											
											</div>
											<div class="separator separator-dashed my-5"></div>
										@else
											<div class="d-flex flex-stack">
												<!--begin::Section-->
												<div class="m-0">
													<span class="text-gray-800 fw-bold d-block fs-2hx lh-1 ls-n2">{{ $overdue }}d</span>
													<span class="text-gray-400 fw-semibold fs-6">{{ $userdetail->name}}</span>
												</div>
												<!--end::Section-->
												<!--begin::Statistics-->
												<div class="d-flex flex-column align-items-end w-100 mw-200px overflow-hidden">
													<!--begin::Select-->
													<div class="mb-2"><div class="badge badge-light-{{ ($overdue>=60) ? 'info' : 'danger' }}">{{ date('d-M-y',strtotime($userdetail->join_date)).' s/d '.date('d-M-y',strtotime($userdetail->end_date)) }}</div></div>
													<!--end::Select-->
													<!--begin::Progress-->
													<div class="progress h-6px w-100 bg-light">
														<div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<!--end::Progress-->
												</div>
											
											</div>
											<div class="separator separator-dashed my-5"></div>
										@endif
										
										@if($no%5==0)
										</div>
										@endif
										@endforeach
									</div>
								</div>
							</div>
							<!--end::Slider-->
						</div>
						<!--end::Body-->
					</div>
					
					<!--end::Col-->
				</div>
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Index-->
	</div>
	<!--end::Post-->
</div>
					<!--end::Container-->
<div class="modal fade" id="detail-logs" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered mw-900px">
				<div class="modal-content">
					<div class="modal-header">
						<h2>Detail Logs</h2>
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
						</div>
					</div>
					<div class="modal-body py-lg-10 px-lg-10">
						<div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
							
							<div class="flex-row-fluid py-lg-5 px-lg-15">
								<form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
									<div class="current" data-kt-stepper-element="content">
										<div class="w-100">
											<div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten" id="detailLogsData">
											
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
@endsection
@section('myscript')

<script src="assets/js/custom/widgets.js"></script>
<script type="text/javascript">
	
function detail(x) {
    // indextr = x.rowIndex;
    console.log(x);
    document.getElementById('id_logs').value=x;
}
 
function getDetailLogs(val){
APP_URL = '{{url('/')}}' ;
ASSET_URL = '{{asset('/public')}}' ;
$.ajax({
            url: APP_URL+'/detail-logs/' + val,
    type: 'get',
    dataType: 'json',
    success: function(response){
    var len = 0;
    $('#historyTable').empty(); // Empty >
    $('#detailLogsData').empty(); // Empty >
    if(response['data'] != null){
        len = response['data'].length;
    }
    var data_str = `<div class="mb-8">
                       
                      <span class="badge badge-light-warning">Test <i class="bi bi-star-fill text-warning"></i></span>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Code Error:</div>
                        <div class="fw-bolder text-gray-800 fs-6">`+response['logs']['code']+`</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Apps:</div>
                        <div class="fw-bolder text-gray-800 fs-6">`+response['logs']['app_name']+`</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Action:</div>
                        <div class="fw-bolder text-gray-800 fs-6">`+response['logs']['action']+`</div>
                    </div>
                    <div class="mb-15">
                        <div class="fw-bold text-gray-600 fs-7">Error By</div>
                        <div class="fw-bolder fs-6 text-gray-800">`+response['logs']['name']+`<br>
                        <a class="link-primary ps-1">`+response['logs']['department']+`</a></div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Remote Address</div>
                        <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">`+response['logs']['remote_addr']+` 
                        <span class="fs-7 text-danger d-flex align-items-center">
                         | At : `+response['logs']['created_at']+`</div>
                    </div>
                     <div class="mb-15">
                        <div class="fw-bold text-gray-600 fs-7">Error Exception</div>
                        <div class="fw-bolder fs-6 text-gray-800">`+response['logs']['message']+`<br>
                        <p class="fw-bolder text-gray-600 fs-9" >`+response['logs']['ex_string']+`</p></div>
                    </div>`;
    $("#detailLogsData").append(data_str);


    }
});
}
</script>
@endsection