@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
						<!--begin::Post-->
						<div class="content flex-row-fluid" id="kt_content">
							<!--begin::Layout-->
							<div class="d-flex flex-column flex-lg-row">
								<!--begin::Sidebar-->
								<div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
									<!--begin::Card-->
									<div class="card mb-5 mb-xl-8">
										<!--begin::Card body-->
										<div class="card-body">
											<!--begin::Summary-->
											<!--begin::User Info-->
											<div class="d-flex flex-center flex-column py-5">
												<!--begin::Avatar-->
												<div class="symbol symbol-100px symbol-circle mb-7">
													<img src="@if($data['usersatria']->photo!='') {{ asset('public/profile/'.$data['usersatria']->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" alt="image" />
												</div>
												<a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ $data['usersatria']->name }}</a>
												<div class="fs-7 fw-semibold text-center text-gray-400 mb-3">{{ $data['usersatria']->department }}</div>
												<div class="mb-9">
													<div class="badge badge-lg badge-light-primary d-inline">{{ $data['usersatria']->title }}</div>
												</div>
											</div>
											<!--end::User Info-->
											<!--end::Summary-->
											<!--begin::Details toggle-->
											<div class="d-flex flex-stack fs-4 py-3">
												<div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">Details 
												<span class="ms-2 rotate-180">
													<!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
													<span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span>
													<!--end::Svg Icon-->
												</span></div>
												<span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit User details">
													<a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_user{{ $data['user']->user_id }}">Edit</a>
												</span>
											</div>
											<!--end::Details toggle-->
											<div class="separator"></div>
											<!--begin::Details content-->
											<div id="kt_user_view_details" class="collapse show">
												<div class="pb-5 fs-6">
													<div class="fw-bold mt-5">NRP</div>
													<div class="text-gray-600">{{ $data['user']->nrp }}</div>
													<div class="fw-bold mt-5">Email</div>
													<div class="text-gray-600">
														<a href="#" class="text-gray-600 text-hover-primary">{{ $data['user']->email }}</a>
													</div>
													<div class="fw-bold mt-5">Address</div>
													<div class="text-gray-600">{{ $data['user']->address }}</div>
													<div class="fw-bold mt-5">Birth Date </div>
													<div class="text-gray-600">{{ date('d-m-Y',strtotime($data['user']->birth_date)) }}</div>
													<div class="fw-bold mt-5">Plant </div>
													<div class="text-gray-600">{{ $data['user']->plant .' - '.$data['user']->company_name }}</div>
													<div class="fw-bold mt-5">Status | Marital | Klasifikasi</div>
													<div class="text-gray-600">{{ $data['user']->emp_status .' | '.$data['user']->marital_code.' | '.$data['user']->klasifikasi_name  }} <br><a class="btn btn-sm btn-light-success fw-bold ms-2 fs-8 py-1 px-3"> {{ $data['user']->vendor_name }}</a></div>
													<div class="fw-bold mt-5">Join & End Date</div>
													<div class="text-gray-600">{{ $data['user']->join_date .' s/d '.$data['user']->end_date }}</div>
												</div>
											</div>
											<!--end::Details content-->
										</div>
										<!--end::Card body-->
									</div>
								</div>
								<!--end::Sidebar-->
								<!--begin::Content-->
								<div class="flex-lg-row-fluid ms-lg-15">
									<!--begin:::Tabs-->
									<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
										<li class="nav-item">
											<a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">Overview</a>
										</li>
										<!-- <li class="nav-item">
											<a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#kt_user_view_overview_security">Security</a>
										</li>
										<li class="nav-item">
											<a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_user_view_overview_events_and_logs_tab">Events & Logs</a>
										</li> -->
									</ul>
									<!--end:::Tabs-->
									<!--begin:::Tab content-->
									<div class="tab-content" id="myTabContent">
										<!--begin:::Tab pane-->
										<div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
											<!--begin::Card-->
											<div class="card card-flush mb-6 mb-xl-9">
												<!--begin::Card header-->
												<div class="card-header mt-6">
													<!--begin::Card title-->
													<div class="card-title flex-column">
														<h2 class="mb-1">User Attendances</h2>
														<!-- <div class="fs-6 fw-semibold text-muted">2 upcoming meetings</div> -->
													</div>
													<!--end::Card title-->
													<!--begin::Card toolbar-->
													<!-- <div class="card-toolbar">
														<button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_schedule">
														Add Schedule</button>
													</div> -->
													<!--end::Card toolbar-->
												</div>
												<!--end::Card header-->
												<!--begin::Card body-->
												<div class="card-body p-9 pt-4">
													<!--begin::Dates-->
													<ul class="nav nav-pills d-flex flex-nowrap hover-scroll-x py-2">
														@for($r=0;$r<=11;$r++)
														<li class="nav-item me-1">
															<a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary @if(date('m')==$r+1) active @endif" data-bs-toggle="tab" href="#kt_schedule_day_{{ $r }}">
																<span class="opacity-50 fs-7 fw-semibold">{{ date('M',strtotime("2022-".($r+1)))}}</span>
																<span class="fs-6 fw-bolder">{{ date('y') }}</span>
															</a>
														</li>
														@endfor
													</ul>
													<!--end::Dates-->
													<!--begin::Tab Content-->
													<div class="tab-content">
													@for($r=date('m')-1;$r>=0;$r--)
														<div id="kt_schedule_day_{{ $r }}" class="tab-pane fade show active">
															@foreach($data['attendance'] as $attendance)
															
															@if(date('m',strtotime($attendance->created_at))==$r+1)
																<div class="d-flex flex-stack position-relative mt-6">
																	<div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
																	<div class="fw-semibold ms-5">
																		<div class="fs-7 mb-1"> <strong>#</strong> <div class="badge badge-light-danger">In at {{ date('d-m-Y H:i:s',strtotime($attendance->in_time))}}</div> | 
																		<div class="badge badge-light-primary">{{ isset($attendance->out_time) ? date('d-m-Y H:i:s',strtotime($attendance->out_time)) : '-'}}</div>
																		<span class="fs-7 text-muted text-uppercase">{{ $attendance->work_metode }}</span></div>
																		<a  class="fs-5 fw-bold text-dark text-hover-primary mb-2">{{ $attendance->name }} |<a class="btn btn-sm btn-light-success fw-bold ms-2 fs-8 py-1 px-3"> {{ $attendance->vendor_name }}</a></a>
																		<div class="fs-7 text-muted">
																			<div class="symbol symbol-50px me-2">
																			<span class="symbol-label bg-light-danger">
																				<a data-fslightbox="lightbox-hot-sales" href="@if($attendance->foto_in!='') {{ Storage::disk('public')->url('/Attendance/In/'.$attendance->foto_in) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendance->foto_in!='')  {{ Storage::disk('public')->url('/Attendance/In/'.$attendance->foto_in) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
																			</span>
																			</div>
																			Location In 
																			<a >{{ $attendance->longitude_in }}, {{ $attendance->latitude_in }}</a>
																		</div>
																		<br>
																		<div class="fs-7 text-muted">
																			<div class="symbol symbol-50px me-2">
																			<span class="symbol-label bg-light-danger">
																				<a data-fslightbox="lightbox-hot-sales" href="@if($attendance->foto_out!='')  {{ Storage::disk('public')->url('/Attendance/Out/'.$attendance->foto_out) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif"><span class="badge badge-light-danger"><img alt="" class="w-15px" src="@if($attendance->foto_out!='')  {{ Storage::disk('public')->url('/Attendance/Out/'.$attendance->foto_out) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" /></span></a>
																			</span>
																			</div>
																			Location Out 
																			<a >{{ $attendance->longitude_out }}, {{ $attendance->latitude_out }}</a></div>
																	</div>
																	<a class="btn btn-light bnt-active-light-primary btn-sm">View</a>
																</div>
															@endif
															@endforeach
														</div>
														@endfor
													</div>
													<!--end::Tab Content-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Card-->
										</div>
										<!--end:::Tab pane-->
										
									</div>
									<!--end:::Tab content-->
								</div>
								<!--end::Content-->
							</div>
							<!--end::Layout-->
						</div>
						<!--end::Post-->
					</div>
<!-- Modal Edit User -->
<div class="modal fade" id="kt_modal_update_user{{ $data['user']->user_id }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_update_user_header">
                <h2 class="fw-bold">Edit User</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="kt_modal_update_user_form" class="form" action="{{ url('update-subcont') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_user_header" data-kt-scroll-wrappers="#kt_modal_update_user_scroll" data-kt-scroll-offset="300px">
                        <div class="fv-row mb-7 row">
                            <input type="hidden" name="id" value="{{ $data['user']->id }}"/>
                            <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $data['user']->name }}" required placeholder="Full Name<" />
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">NRP</label>
                                <input type="number" name="nrp" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $data['user']->nrp }}" required placeholder="NRP" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Email</label>
                                <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $data['user']->email }}" required placeholder="example@domain.com" />
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Marital Status</label>
                                <select name="marital_status" required class="form-control">
                                    @foreach($data['marital'] as $marital)
                                        <option value="{{ $marital->id }}" @if($data['user']->marital_status == $marital->id) selected @endif>
                                            {{ $marital->code.' | '.$marital->ket }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Birth Date</label>
                                <input type="date" name="birth_date" value="{{ $data['user']->birth_date }}" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Address</label>
                            <textarea name="address" id="" cols="5" rows="5" class="form-control form-control-solid mb-3 mb-lg-0">{{ $data['user']->address }}</textarea>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Vendor</label>                                
                            <select name="vendor" required class="form-control">
                                @foreach($data['subcont'] as $subcont)
                                    <option value="{{ $subcont->id }}" @if($data['user']->vendor == $subcont->id) selected @endif> 
                                        {{ $subcont->code.' | '.$subcont->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Join Date</label>
                                <input type="date" name="join_date" value="{{ $data['user']->join_date }}" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">End Date</label>
                                <input type="date" name="end_date" value="{{ $data['user']->end_date }}" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Plant</label>
                                <select name="plant" required class="form-control">
                                    <option value="HO" @if($data['user']->plant == "HO") selected @endif>HO</option>
                                    <option value="RJKT" @if($data['user']->plant == "RJKT") selected @endif>RJKT</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Klasifikasi</label>
                                <select name="klasifikasi" required class="form-control">
                                    @foreach($data['klasifikasi'] as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}"@if($data['user']->klasifikasi == $klasifikasi->id) selected @endif>
                                            {{ $klasifikasi->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-5">Status</label>
                            @foreach($data['status'] as $status)
                            <div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input me-3" required name="status" type="radio" value="{{ $status->id }}" id="kt_modal_update_role_option_{{ $status->id }}"
                                        @if($data['user']->status == $status->id) checked @endif />
                                    <label class="form-check-label" for="kt_modal_update_role_option_{{ $status->id }}">
                                        <div class="fw-bold text-gray-800">{{ $status->name }}</div>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-center pt-15" style="float:right;">
                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
@endsection

@section('myscript')
@endsection