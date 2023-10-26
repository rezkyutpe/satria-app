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

					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::Row-->
						<div class="row g-5 g-xl-8">
							<!--begin::Col-->
							<div class="col-xxl-6">
								<!--begin::Statistics Widget 1-->
								<div class="card card-xxl-stretch-50 mb-5 mb-xl-8">
									<!--begin::Body-->
									<div class="card-body d-flex flex-column justify-content-between p-0">
										<!--begin::Hidden-->
										<div class="d-flex flex-column px-9 pt-5">
											<!--begin::Number-->
											<div class="text-success fw-boldest fs-2hx">{{ count($data['dialyticket']) }} </div>
											<!--end::Number-->
											<!--begin::Description-->
											<span class="text-gray-400 fw-bold fs-6">Ticket Requested Today</span>
											<!--end::Description-->
										</div>
										<!--end::Hidden-->
										<!--begin::Chart-->
										<div  class="statistics-widget-1-chart card-rounded-bottom" data-kt-ticket="{{ $data['dialyrange'] }}" data-kt-chart-color="success" style="height: 150px"></div>
										<!--end::Chart-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Statistics Widget 1-->
								<!--begin::Mixed Widget 1-->
								<div class="card card-xxl-stretch-50 mb-xxl-8">
									<!--begin::Body-->
									<div class="card-body pt-5">
										<!--begin::Chart-->
										<div id="kt_mixed_widget_1_chart"  data-kt-percent="{{ $data['ticketpercentage'] }}" class="mb-n15"></div>
										<!--end::Chart-->
										<!--begin::Label-->
										<span class="badge badge-lg badge-light-warning w-100 text-gray-800 text-start d-flex align-items-center">
										Ticket Percentage Today </span>
										<!--end::Label-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Mixed Widget 1-->
							</div>
							<!--end::Col-->
							<!--begin::Col-->
							<div class="col-xxl-6">
								<!--begin::Mixed Widget 2-->
								<div class="card card-xxl-stretch-50 mb-5 mb-xl-8">
									<!--begin::Body-->
									<div class="card-body d-flex flex-column justify-content-between p-0">
										<!--begin::Hidden-->
										<div class="d-flex flex-column px-9 pt-5">
											<!--begin::Number-->
											<span class="text-primary fw-boldest fs-2hx">{{ count($data['dialypr']) }}</span>
											<!--end::Number-->
											<!--begin::Description-->
											<span class="text-gray-400 fw-bold fs-6">PR Requested This Month</span>
											<!--end::Description-->
										</div>
										<!--end::Hidden-->
										<!--begin::Chart-->
										<div id="kt_mixed_widget_2_chart" class="mx-3" data-kt-pr="{{ $data['rangepr'] }}" data-kt-color="primary" style="height: 175px"></div>
										<!--end::Chart-->
									</div>
								</div>
								<!--end::Mixed Widget 2-->
								<!--begin::Engage widget 1-->
								<div class="card card-xxl-stretch-50" style="background-color: #1C53E1">
														<!--begin::Card body-->
									<div class="card-body d-flex align-items-end p-0 pt-10">
										<!--begin::Wrapper-->
										<div class="flex-grow-1 ps-9 pb-9">
											<!--begin::Items-->
											<div class="pt-8">
												<!--begin::Item-->
												@foreach($data['openpr'] as $openpr)
												<div class="d-flex align-items-center mb-3">
													<span class="svg-icon svg-icon-3 svg-icon-white me-2">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
															<path d="M6.8 15.8C7.3 15.7 7.9 16 8 16.5C8.2 17.4 8.99999 18 9.89999 18H17.9C19 18 19.9 17.1 19.9 16V8C19.9 6.9 19 6 17.9 6H9.89999C8.79999 6 7.89999 6.9 7.89999 8V9.4H5.89999V8C5.89999 5.8 7.69999 4 9.89999 4H17.9C20.1 4 21.9 5.8 21.9 8V16C21.9 18.2 20.1 20 17.9 20H9.89999C8.09999 20 6.5 18.8 6 17.1C6 16.5 6.3 16 6.8 15.8Z" fill="black" />
															<path opacity="0.3" d="M12 9.39999H2L6.3 13.7C6.7 14.1 7.3 14.1 7.7 13.7L12 9.39999Z" fill="black" />
														</svg>
													</span>
													<!--end::Svg Icon-->
													<span class="fw-bolder fs-7 text-white">{{ $openpr->pr_number }}</span>
												</div>
												@endforeach
												<!--end::Item-->
											</div>
											<!--end::Items-->
											<!--begin::Link-->
											<a href="{{ url('pr-list') }}" class="btn btn-sm btn-success" >Go to PR List</a>
											<!--end::Link-->
										</div>
										<!--end::Wrapper-->
										<img class="mh-200px" alt="" src="{{ asset('public/assets/theme/dist/assets/media/svg/illustrations/engage.svg') }}" />
									</div>
									<!--end::Card body-->
								</div>
													<!--end::Engage widget 1-->
								<!--end::Engage widget 1-->
							</div>
							<div class="col-xxl-12">
								<div class="tab-content"  style="background-color: #fff;" >
									<form action="{{url('export-ticket')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<div class="tab-pane fade active show" id="kt_forms_widget_1_tab_content_1" style="margin: 10px;">
										<br>
									<h2>Export Ticket</h2>
										<div class="form-floating border border-gray-300 rounded mb-7">
											<select style="width: 100%;" id="passanger_chosen" name="subject[]" multiple class="form-control chzn-select" >
											@foreach ($data['subject'] as $subject)
												<option value="{{ $subject->subject }}" {{ (old('subject') == $subject->subject)? 'selected' : '' }}>
													{{ $subject->subject }}
												</option>
											@endforeach
											</select>
											<label for="floatingInputValue">Filter Subject</label>
										</div>
										<div class="row mb-7">
											<div class="col-6">
												<div class="form-floating">
													<input name="start" class="form-control text-gray-800 fw-bold" type="date" id="from_date"  onchange="handlerFromDate(event);"/>
													<label for="floatingInputValue">Start Date</label>
												</div>
											</div>
											<div class="col-6">
												<div class="form-floating">
													<input name="end" class="form-control text-gray-800 fw-bold" type="date" id="to_date"  onchange="handlerToDate(event);" />
													<label for="floatingInputValue">End Date</label>
												</div>
											</div>
										</div>
										<div class="d-flex align-items-end">
											<button type="submit" class="btn btn-primary fs-3 w-100">Export</a>
										</div>
										<br>
									</div>
									</form>
								</div>
								<br>
								<br>
							</div>
							<!--end::Col-->
						</div>
						<!--end::Row-->
					</div>
					<!--end::Col-->
					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::Table Widget 1-->
						<div class="card card-xxl-stretch">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5 pb-3">
								<!--begin::Card title-->
								<h3 class="card-title fw-bolder text-gray-800 fs-2">New Open Ticket</h3>
								
									<span class="text-gray-400 mt-2 fw-bold fs-6">{{ count($data['openticket']) }} Open Ticket</span>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-0">
								<!--begin::Table-->
								<div class="table-responsive" style="max-height: 600px; overflow-y:auto; padding: 0px;">
									<table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
										<!--begin::Table body-->
										<tbody>
											<!--begin::Table row-->
											<tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
												<th class="min-w-200px px-0">Ticket</th>
												<th class="min-w-125px">From</th>
												<th class="text-end pe-2 min-w-70px">Action</th>
											</tr>
											<!--end::Table row-->
											<!--begin::Table row-->
											@foreach($data['openticket'] as $openticket)
											<tr>
												<!--begin::Author=-->
												<td class="p-0">
													<div class="d-flex align-items-center">
														<!--begin::Logo-->
														<div class="symbol symbol-50px me-2">

															<span class="symbol-label bg-light-danger">
										                        	<a data-fslightbox="lightbox-hot-sales" href="{{ asset('public/ticket/'.$openticket->media) }}"><span class="badge badge-light-danger"><img alt="" class="w-25px" src="{{ asset('public/ticket/'.$openticket->media) }}" /></span></a>
										                    </span>
														</div>
														<!--end::Logo-->
														<div class="ps-3">
															<a class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">#{{ $openticket->ticket_id }}</a>
															<span class="text-gray-400 fw-bold d-block">{{ $openticket->subject }}</span>
														</div>
													</div>
												</td>
												<!--end::Author=-->
												<!--begin::Progress=-->
												<td>
													<div class="d-flex flex-column w-100 me-2 mt-2">
														<span class="text-gray-600 me-2 fw-boldest mb-2">{{ $openticket->reporter_name }}</span>
															<span class="text-gray-400 fw-bold d-block">{{ $openticket->dept_reporter }} <br>
                                    <span style="color: black;font-size:11px">Created At {{ date('d-m-y H:i',strtotime($openticket->created_at))}}</span></span>
													</div>
												</td>
												<!--end::Company=-->
												<!--begin::Action=-->
												<td class="pe-0 text-end">
													<a  class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="bottom-start">
														<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
														<span class="svg-icon svg-icon-2x">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="black" />
																<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="black" />
																<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="black" />
																<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="black" />
															</svg>
														</span>
														<!--end::Svg Icon-->
													</a>
													<!--begin::Menu 3-->
													<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
														<!--begin::Heading-->
														<div class="menu-item px-3">
					                                        <a onclick="getHistory({{ $openticket->id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-ticket" class="menu-link px-3">Detail</a>
					                                    </div>
					                                    <div class="menu-item px-3">
					                                        <a onclick="setAssign({{ $openticket->id }},'Assign Ticket')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Assign to</a>
					                                    </div>
					                                    @if($openticket->flag==1)
					                                        <div class="menu-item px-3">
					                                            <a onclick="setProccess({{ $openticket->id }},'Proccess')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Proccess</a>
					                                        </div>
					                                        <div class="menu-item px-3">
					                                            <a onclick="setCancel({{ $openticket->id }},'Cancel')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Cancel</a>
					                                        </div>
					                                    @endif
														<!--end::Menu item-->
													</div>
													<!--end::Menu 3-->
												</td>
												<!--end::Action=-->
											</tr>
											<!--end::Table row-->
											@endforeach
										</tbody>
										<!--end::Table body-->
									</table>

									<div class="py-3 text-center border-top">
										<a href="{{ url('open-ticket') }}" class="btn btn-color-gray-600 btn-active-color-primary">View All
										<span class="svg-icon svg-icon-5"><i class="fa fa-angle-right fs-2"></i></span></a>
										</span>
									</div>
								</div>
								<!--end::Table-->
							</div>
							<!--end::Body-->
						</div>
						<!--end::Table Widget 1-->
					</div>
					<!--end::Col-->
				</div>
				<!--end::Row-->
				<!--begin::Row-->
				<div class="row g-5 g-xl-8">
					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::List Widget 4-->
						<div class="card card-xl-stretch mb-5 mb-xl-8">
							<!--begin::Header-->
							<div class="card-header align-items-center border-0 mt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="fw-bolder text-dark fs-2">Your On Progress Ticket </span>
									<span class="text-gray-400 mt-2 fw-bold fs-6">{{ count($data['prosesticketbyassist']) }} Active Ticket</span>
								</h3>
								
							</div>

							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-1">
								<div class="table-responsive" style="max-height: 600px; overflow-y:auto; padding: 0px;">

									<table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
										<!--begin::Table body-->
										<tbody>
											<!--begin::Table row-->
											<tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
												<th class="min-w-200px px-0">Ticket</th>
												<th class="min-w-125px">From</th>
												<th class="text-end pe-2 min-w-70px">Action</th>
											</tr>
											<!--end::Table row-->
											<!--begin::Table row-->
											@foreach($data['prosesticketbyassist'] as $prosesticketbyassist)
											@php($datediff = (Helper::TimeInterval($prosesticketbyassist->respond_time,$prosesticketbyassist->resolve_time=='' ? Date('Y-m-d H:i:s') : $prosesticketbyassist->resolve_time)/60))
											@php($interval = (Helper::TimeInterval($prosesticketbyassist->respond_time,$prosesticketbyassist->resolve_time=='' ? Date('Y-m-d H:i:s') : $prosesticketbyassist->resolve_time)/60))
											@php($resolution = $prosesticketbyassist->resolution_time==0 ? 0 : $prosesticketbyassist->resolution_time*60)
											@php($intervalpercent =  $resolution==0 ? 0 : round($interval/$resolution*100))
											@php($duration = '('.floor($datediff/60).':'.gmdate('i:s', $interval*60).')')
											<tr>
												<td class="p-0" data-order="{{ $prosesticketbyassist->flow_name}}">
													<div class="d-flex align-items-center">
														<div class="symbol symbol-40px symbol-circle me-4">
															<span class="badge badge-light fw-bolder my-2">{{ $prosesticketbyassist->flow_name }}</span>
														</div>
														<div class="ps-3">
															<a class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">#{{ $prosesticketbyassist->ticket_id }}</a>
															<span class="text-gray-400 fw-bold d-block">{{ $prosesticketbyassist->subject }}<br><span style="color: black;font-size:11px">Created At {{ date('d-m-y H:i',strtotime($prosesticketbyassist->created_at))}}</span></span>
														</div>
													</div>
												</td>
												<td>
													<div class="d-flex flex-column w-100 me-2 mt-2">
														<span class="text-gray-400 me-2 fw-boldest mb-2">{{ $intervalpercent<=100 ? $intervalpercent : '100+' }}%  {{ isset($prosesticketbyassist->respond_time) ? $duration : '-' }}</span>
														<div class="progress bg-light-danger w-100 h-5px">
															<div @if($intervalpercent<=100) class="progress-bar bg-success" @else class="progress-bar bg-danger" @endif role="progressbar" style="width: {{ $resolution==0 ? 0 : round($interval/$resolution*100) }}%"></div>
														</div>
															<span class="text-gray-400 fw-bold d-block">SLA: {{ $prosesticketbyassist->sla_name }}</span>
													</div>
												</td>
												<td class="pe-0 text-end">
													<a  class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="bottom-start">
														<span class="svg-icon svg-icon-2x">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="black" />
																<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="black" />
																<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="black" />
																<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="black" />
															</svg>
														</span>
													</a>
													<!--begin::Menu 3-->
													<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
														<!--begin::Heading-->
														<div class="menu-item px-3">
					                                        <a onclick="getHistory({{ $prosesticketbyassist->id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-ticket" class="menu-link px-3">Detail  </a>
					                                    </div>
					                                   	@if($prosesticketbyassist->flag==1)
				                                            <div class="menu-item px-3">
				                                                <a onclick="setProccess({{ $prosesticketbyassist->id }},'Proccess')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Proccess</a>
				                                            </div>
				                                            <div class="menu-item px-3">
				                                                <a onclick="setCancel({{ $prosesticketbyassist->id }},'Cancel')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Cancel</a>
				                                            </div>
				                                        @elseif($prosesticketbyassist->flag==2 || $prosesticketbyassist->flag==3 ||$prosesticketbyassist->flag==9)
				                                            @if($prosesticketbyassist->flag!=3)
				                                            <div class="menu-item px-3">
																<a onclick="setEscalate({{ $prosesticketbyassist->id }},'Escalate')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Escalate</a>
															</div>
				                                            <div class="menu-item px-3">
				                                                <a onclick="setResolve({{ $prosesticketbyassist->id }},'Resolve')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Resolve</a>
				                                            </div>
				                                            @else
																@if(Auth::user()->dept!='8884')
																<div class="menu-item px-3">
																	<a onclick="setEscalate({{ $prosesticketbyassist->id }},'Escalate')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Escalate</a>
																</div>
																@endif
																@php($interval = Helper::TimeInterval(date('Y-m-d H:i:s'),$prosesticketbyassist->resolve_time))
																@if($interval>='32400')
																<div class="menu-item px-3">
																	<a onclick="setClose({{ $prosesticketbyassist->id }},'Close')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Close</a>
																</div>
																@endif
				                                            @endif
				                                        @elseif($prosesticketbyassist->flag!=5 && $prosesticketbyassist->flag!=6)
				                                            <div class="menu-item px-3">
				                                                <a onclick="setCancel({{ $prosesticketbyassist->id }},'Cancel')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Cancel</a>
				                                                </div>
				                                        @endif
													</div>
												</td>
												<!--end::Action=-->
											</tr>
											<!--end::Table row-->
											@endforeach
										</tbody>
										<!--end::Table body-->
									</table>
									
									<div class="py-3 text-center border-top">
										<a href="{{ url('assist-ticket') }}" class="btn btn-color-gray-600 btn-active-color-primary">View All
										<span class="svg-icon svg-icon-5"><i class="fa fa-angle-right fs-2"></i></span></a>
										</span>
									</div>
								</div>
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 4-->
					</div>
					<!--end::Col-->
					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::List Widget 5-->
						<div class="card card-xl-stretch mb-5 mb-xl-8">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bolder text-dark">Trends</span>
									<span class="text-muted mt-1 fw-bold fs-7">This Month</span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-5">
								<!--begin::Item-->
								@foreach($data['topassist'] as $topassist)
								<div class="d-flex align-items-sm-center mb-7">
									<!--begin::Symbol-->
									<div class="symbol symbol-circle symbol-50px me-5">
										<span class="symbol-label">
											<img src="@if($topassist->assist_photo!='') {{ asset('public/profile/'.$topassist->assist_photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Section-->
									<div class="d-flex align-items-center flex-row-fluid flex-wrap">
										<div class="flex-grow-1 me-2">
											<a class="text-gray-800 text-hover-primary fs-6 fw-bolder">{{ $topassist->assist_name }}</a>
											<span class="text-muted fw-bold d-block fs-7">Top Assist</span>
										</div>
										<span class="badge badge-light fw-bolder my-2">{{ $topassist->count }} Ticket</span>
									</div>
									<!--end::Section-->
								</div>
								@endforeach
								@foreach($data['topreporter'] as $topreporter)
								<div class="d-flex align-items-sm-center mb-7">
									<!--begin::Symbol-->
									<div class="symbol symbol-circle symbol-50px me-5">
										<span class="symbol-label">
											<img src="@if($topreporter->reporter_photo!='') {{ asset('public/profile/'.$topreporter->reporter_photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Section-->
									<div class="d-flex align-items-center flex-row-fluid flex-wrap">
										<div class="flex-grow-1 me-2">
											<a class="text-gray-800 text-hover-primary fs-6 fw-bolder">{{ $topreporter->reporter_name }}</a>
											<span class="text-muted fw-bold d-block fs-7">Top Reporter</span>
										</div>
										<span class="badge badge-light fw-bolder my-2">{{ $topreporter->count }} Ticket</span>
									</div>
									<!--end::Section-->
								</div>
								@endforeach
								@foreach($data['topsla'] as $topsla)
								<div class="d-flex align-items-sm-center mb-7">
									<!--begin::Symbol-->
									<div class="symbol symbol-circle symbol-50px me-5">
										<span class="symbol-label">
											<img src="/ceres-html-free/assets/media/svg/brand-logos/plurk.svg" class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Section-->
									<div class="d-flex align-items-center flex-row-fluid flex-wrap">
										<div class="flex-grow-1 me-2">
											<a class="text-gray-800 text-hover-primary fs-6 fw-bolder">{{ $topsla->sla_name}}</a>
											<span class="text-muted fw-bold d-block fs-7">Top Issue</span>
										</div>
										<span class="badge badge-light fw-bolder my-2">{{ $topsla->count}} Ticket</span>
									</div>
									<!--end::Section-->
								</div>
								@endforeach
								<!--end::Item-->
								
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 5-->
					</div>
					<!--end::Col-->
				</div>
			@if(Auth::user()->dept=='8915')	
    <!--begin::Col-->
    <div class="col-xl-12 mb-5 mb-xl-10">
        
	<!--begin::Chart widget 32-->
	<div class="card">
	    <!--begin::Header-->
	    <div class="card-header pt-7 mb-3">
	        <!--begin::Title-->
	        <h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bold text-gray-800">Support After Golive SAP S/4 HANA</span>
				<!-- <span class="text-gray-400 mt-1 fw-semibold fs-6">Total 424,567 deliveries</span> -->
			</h3>
	        <!--end::Title-->
	    </div>
	    <!--end::Header-->

	    <!--begin::Body-->
	    <div class="card-body d-flex flex-column justify-content-between pb-5 px-0">   

	        <!--begin::Tab Content-->
	        <div class="tab-content ps-4 pe-6">
	                            <!--begin::Tap pane-->
	                <div class="tab-pane fade active show" id="kt_charts_widget_32_tab_content_1">
	                    <!--begin::Chart-->
	                     <div id="kt_charts_widget_32_chart_1" data-kt-ticket="{{ implode(',',$data['sapticketvalue']) }}" data-kt-date="{{ implode(',',$data['sapticketdate']) }}"  class="min-h-auto" style="height: 375px"></div>
	                    <!--end::Chart-->
	                </div>
	                <!--end::Tap pane-->
	                            <!--begin::Tap pane-->
	                <div class="tab-pane fade " id="kt_charts_widget_32_tab_content_2">
	                    <!--begin::Chart-->
	                    <div id="kt_charts_widget_32_chart_2" class="min-h-auto" style="height: 375px"></div>
	                    <!--end::Chart-->
	                </div>
	                <!--end::Tap pane-->
	                            <!--begin::Tap pane-->
	                <div class="tab-pane fade " id="kt_charts_widget_32_tab_content_3">
	                    <!--begin::Chart-->
	                    <div id="kt_charts_widget_32_chart_3" class="min-h-auto" style="height: 375px"></div>
	                    <!--end::Chart-->
	                </div>
	                <!--end::Tap pane-->
	             
	        </div>
	        <!--end::Tab Content-->        
	    </div>
	    <!--end: Card Body-->
	</div>
	<!--end::Chart widget 32-->    
	</div>
	@endif
    <!--end::Col-->          
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
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
				<form action="{{url('export-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
						<label class="required fs-5 fw-bold mb-2">Subject</label> <br>
						<select style="width: 50%;" id="passanger_chosen" name="subject[]" multiple class="form-control form-control-lg form-control-solid chzn-select" >
						@foreach ($data['subject'] as $subject)
							<option value="{{ $subject->subject }}" {{ (old('subject') == $subject->subject)? 'selected' : '' }}>
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
						<input type="date" id="from_date"  onchange="handlerFromDate(event);" class="form-control form-control-lg form-control-solid" name="start" placeholder="" />
						<!--end::Input-->
					</div>
					<div class="col fv-row mb-10">
						<!--begin::Label-->
						<label class="required fs-5 fw-bold mb-2">End Date</label>
						<!--end::Label-->
						<!--begin::Input-->
						<input type="date"  id="to_date"  onchange="handlerToDate(event);"  class="form-control form-control-lg form-control-solid" name="end" placeholder="" />
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
                <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <div id="ticketAction"></div>              
               
	        </div>
	    </div>
</div>	
<script type="text/javascript">
// $(document).ready(function(){
// 	loadajax();
// });

// setInterval(loadajax, 5000);
// function loadajax() {
//  		var percent = document.getElementById("kt_mixed_widget_1_chart");
//  		document.getElementById("kt_mixed_widget_1_chart").contentWindow.location.reload(true);
//         APP_URL = '{{url('/')}}' ;
//         $.ajax({
//             url: APP_URL+'/get-dashboard',
//             type: 'get',
//             dataType: 'json',         
//             cache: true, 
//             success: function (response) {
//                 console.log(response);
// 				percent.setAttribute('data-kt-percent', response['ticketpercentage']);
//             }
//         });

//     }	
// window.setTimeout(function () {
//   window.top.location.reload(true);
// }, 5000);
function review(x) {
    // indextr = x.rowIndex;
    console.log(x);
    document.getElementById('ticket_id').value=x;
}
function handlerFromDate(e){
      document.getElementById('to_date').min = e.target.value;
    }
    function handlerToDate(e){
      document.getElementById('from_date').max = e.target.value;
    }
function setEscalate(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('escalate-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Assist : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="assist" class="form-control" required>
                                @foreach($data['assist'] as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
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

function setResolve(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('resolve-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span >Reduce Inventory : </span>
                        </label>
                        <select style="width: 100%;"  name="inventory" onchange="getinventory(this)"  class="form-control js-example-basic-single" data-live-search="true">
                                <option value="">- Select Inventory -</option>
                                @foreach($data['inventory'] as $inventory)
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
                        <input type="hidden" name="id" value="`+x+`">
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

function setClose(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('close-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
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
function setProccess(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('proccess-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Sla : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="sla" class="form-control" required>
                            @foreach($data['sla'] as $sla)
                                <option value="{{ $sla->id }}">{{ $sla->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
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
function setAssign(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('asign-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Assist : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="assist" class="form-control" required>
                                @foreach($data['assist'] as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
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

function setCancel(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('cancel-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
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
       
        APP_URL = '{{url('/')}}' ;
        $.ajax({
            url: APP_URL+'/get-inventory/' + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                console.log(response['data']);
                $("input[name=reduce]").val(response['data'].inventory_qty);
                $("input[name=reduce]").attr({
                    "max" : response['data'].inventory_qty,
                    "min" : 1
                });
            }
        });
    }
</script>

                            <script src="https://preview.keenthemes.com/ceres-html-pro/assets/plugins/custom/datatables/datatables.bundle.js"></script>
                            <script src="https://preview.keenthemes.com/ceres-html-pro/assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="assets/js/custom/widgets.bundle.js"></script>
<script src="assets/js/custom/widgets.js"></script>
@endsection