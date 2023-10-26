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
		
    <!--begin::Col-->
    <div class="col-xl-12 mb-5 mb-xl-10">
        
<!--begin::Chart widget 32-->
<div class="card">
    <!--begin::Header-->
    <div class="card-header pt-7 mb-3">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-800">Deliveries by Category</span>
			<span class="text-gray-400 mt-1 fw-semibold fs-6">{{ implode(",",$data['sapticketvalue'])}}</span>
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
                    <div id="kt_charts_widget_32_chart_2" class="min-h-auto" style="height: 377px"></div>
                    <!--end::Chart-->
                </div>
                <!--end::Tap pane-->
                            <!--begin::Tap pane-->
                <div class="tab-pane fade " id="kt_charts_widget_32_tab_content_3">
                    <!--begin::Chart-->
                    <div id="kt_charts_widget_32_chart_3"  data-kt-ticket="{{ implode(',',$data['sapticketvalue']) }}" data-kt-chart-color="success" class="min-h-auto" style="height: 377px"></div>
                    <!--end::Chart-->
                </div>
                <!--end::Tap pane-->
             
        </div>
        <!--end::Tab Content-->        
    </div>
    <!--end: Card Body-->
</div>
<!--end::Chart widget 32-->    </div>
    <!--end::Col-->           
				<div class="row gy-5 g-xl-10">
								<!--begin::Col-->
								<div class="col-xl-4 mb-xl-10">
									<!--begin::List widget 16-->
									<div class="card card-flush h-xl-100">
										<!--begin::Header-->
										<div class="card-header pt-7">
											<!--begin::Title-->
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder text-gray-800">Ticket Tracking</span>
												<span class="text-gray-400 mt-1 fw-bold fs-6">56 deliveries in progress</span>
											</h3>
											<!--end::Title-->
											<!--begin::Toolbar-->
											<div class="card-toolbar">
												<a href="#" class="btn btn-sm btn-light" data-bs-toggle='tooltip' data-bs-dismiss='click' data-bs-custom-class="tooltip-dark" title="Delivery App is coming soon">View All</a>
											</div>
											<!--end::Toolbar-->
										</div>
										<!--end::Header-->
										<!--begin::Body-->
										<div class="card-body pt-4 px-0">
											<!--begin::Nav-->
											<ul class="nav nav-pills nav-pills-custom item position-relative mx-9 mb-9">
												<!--begin::Item-->
												<li class="nav-item col-4 mx-0 p-0">
													<!--begin::Link-->
													<a class="nav-link active d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_list_widget_16_tab_1">
														<!--begin::Subtitle-->
														<span class="nav-text text-gray-800 fw-bolder fs-6 mb-3">New</span>
														<!--end::Subtitle-->
														<!--begin::Bullet-->
														<!-- <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span> -->
														<!--end::Bullet-->
													</a>
													<!--end::Link-->
												</li>
												<!--end::Item-->
												<!--begin::Item-->
												<li class="nav-item col-4 mx-0 px-0">
													<!--begin::Link-->
													<a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_list_widget_16_tab_2">
														<!--begin::Subtitle-->
														<span class="nav-text text-gray-800 fw-bolder fs-6 mb-3">On Going</span>
														<!--end::Subtitle-->
														<!--begin::Bullet-->
														<!-- <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span> -->
														<!--end::Bullet-->
													</a>
													<!--end::Link-->
												</li>
												<!--end::Item-->
												<!--begin::Item-->
												<li class="nav-item col-4 mx-0 px-0">
													<!--begin::Link-->
													<a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_list_widget_16_tab_3">
														<!--begin::Subtitle-->
														<span class="nav-text text-gray-800 fw-bolder fs-6 mb-3">Closed</span>
														<!--end::Subtitle-->
														<!--begin::Bullet-->
														<!-- <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span> -->
														<!--end::Bullet-->
													</a>
													<!--end::Link-->
												</li>
												<!--end::Item-->
												<!--begin::Bullet-->
												<span class="position-absolute z-index-1 bottom-0 w-100 h-4px bg-light rounded"></span>
												<!--end::Bullet-->
											</ul>
											<!--end::Nav-->
											<!--begin::Tab Content-->
											<div class="tab-content px-9 hover-scroll-overlay-y pe-7 me-3 mb-2" style="height: 454px">
												<!--begin::Tap pane-->
												<div class="tab-pane fade show active" id="kt_list_widget_16_tab_1">
													<!--begin::Item-->
													<div class="m-0">
														<!--begin::Timeline-->
														<div class="timeline ms-n1">
															<!--begin::Timeline item-->
															<div class="timeline-item align-items-center mb-4">
																<!--begin::Timeline line-->
																<div class="timeline-line w-20px mt-9 mb-n14"></div>
																<!--end::Timeline line-->
																<!--begin::Timeline icon-->
																<div class="timeline-icon pt-1" style="margin-left: 0.7px">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen015.svg-->
																	<span class="svg-icon svg-icon-2 svg-icon-success">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10ZM6.39999 9.89999C6.99999 8.19999 8.40001 6.9 10.1 6.4C10.6 6.2 10.9 5.7 10.7 5.1C10.5 4.6 9.99999 4.3 9.39999 4.5C7.09999 5.3 5.29999 7 4.39999 9.2C4.19999 9.7 4.5 10.3 5 10.5C5.1 10.5 5.19999 10.6 5.39999 10.6C5.89999 10.5 6.19999 10.2 6.39999 9.89999ZM14.8 19.5C17 18.7 18.8 16.9 19.6 14.7C19.8 14.2 19.5 13.6 19 13.4C18.5 13.2 17.9 13.5 17.7 14C17.1 15.7 15.8 17 14.1 17.6C13.6 17.8 13.3 18.4 13.5 18.9C13.6 19.3 14 19.6 14.4 19.6C14.5 19.6 14.6 19.6 14.8 19.5Z" fill="currentColor" />
																			<path d="M16 12C16 14.2 14.2 16 12 16C9.8 16 8 14.2 8 12C8 9.8 9.8 8 12 8C14.2 8 16 9.8 16 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10Z" fill="currentColor" />
																		</svg>
																	</span>
																	<!--end::Svg Icon-->
																</div>
																<!--end::Timeline icon-->
																<!--begin::Timeline content-->
																<div class="timeline-content m-0">
																	<!--begin::Label-->
																	<span class="fs-8 fw-boldest text-success text-uppercase">Sender</span>
																	<!--begin::Label-->
																	<!--begin::Title-->
																	<a href="#" class="fs-6 text-gray-800 fw-bolder d-block text-hover-primary">Brooklyn Simmons</a>
																	<!--end::Title-->
																	<!--begin::Title-->
																	<span class="fw-bold text-gray-400">6391 Elgin St. Celina, Delaware 10299</span>
																	<!--end::Title-->
																</div>
																<!--end::Timeline content-->
															</div>
															<!--end::Timeline item-->
															<!--begin::Timeline item-->
															<div class="timeline-item align-items-center">
																<!--begin::Timeline line-->
																<div class="timeline-line w-20px"></div>
																<!--end::Timeline line-->
																<!--begin::Timeline icon-->
																<div class="timeline-icon pt-1" style="margin-left: 0.5px">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
																	<span class="svg-icon svg-icon-2 svg-icon-info">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
																		</svg>
																	</span>
																	<!--end::Svg Icon-->
																</div>
																<!--end::Timeline icon-->
																<!--begin::Timeline content-->
																<div class="timeline-content m-0">
																	<!--begin::Label-->
																	<span class="fs-8 fw-boldest text-info text-uppercase">Receiver</span>
																	<!--begin::Label-->
																	<!--begin::Title-->
																	<a href="#" class="fs-6 text-gray-800 fw-bolder d-block text-hover-primary">Ralph Edwards</a>
																	<!--end::Title-->
																	<!--begin::Title-->
																	<span class="fw-bold text-gray-400">2464 Royal Ln. Mesa, New Jersey 45463</span>
																	<!--end::Title-->
																</div>
																<!--end::Timeline content-->
															</div>
															<!--end::Timeline item-->
														</div>
														<!--end::Timeline-->
													</div>
													<!--end::Item-->
													<!--begin::Separator-->
													<div class="separator separator-dashed mt-5 mb-4"></div>
													<!--end::Separator-->
												</div>
												<!--end::Tap pane-->
												<!--begin::Tap pane-->
												<div class="tab-pane fade" id="kt_list_widget_16_tab_2">
													<!--begin::Item-->
													<div class="m-0">
														<!--begin::Timeline-->
														<div class="timeline ms-n1">
															<!--begin::Timeline item-->
															<div class="timeline-item align-items-center mb-4">
																<!--begin::Timeline line-->
																<div class="timeline-line w-20px mt-9 mb-n14"></div>
																<!--end::Timeline line-->
																<!--begin::Timeline icon-->
																<div class="timeline-icon pt-1" style="margin-left: 0.7px">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen015.svg-->
																	<span class="svg-icon svg-icon-2 svg-icon-success">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10ZM6.39999 9.89999C6.99999 8.19999 8.40001 6.9 10.1 6.4C10.6 6.2 10.9 5.7 10.7 5.1C10.5 4.6 9.99999 4.3 9.39999 4.5C7.09999 5.3 5.29999 7 4.39999 9.2C4.19999 9.7 4.5 10.3 5 10.5C5.1 10.5 5.19999 10.6 5.39999 10.6C5.89999 10.5 6.19999 10.2 6.39999 9.89999ZM14.8 19.5C17 18.7 18.8 16.9 19.6 14.7C19.8 14.2 19.5 13.6 19 13.4C18.5 13.2 17.9 13.5 17.7 14C17.1 15.7 15.8 17 14.1 17.6C13.6 17.8 13.3 18.4 13.5 18.9C13.6 19.3 14 19.6 14.4 19.6C14.5 19.6 14.6 19.6 14.8 19.5Z" fill="currentColor" />
																			<path d="M16 12C16 14.2 14.2 16 12 16C9.8 16 8 14.2 8 12C8 9.8 9.8 8 12 8C14.2 8 16 9.8 16 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10Z" fill="currentColor" />
																		</svg>
																	</span>
																	<!--end::Svg Icon-->
																</div>
																<!--end::Timeline icon-->
																<!--begin::Timeline content-->
																<div class="timeline-content m-0">
																	<!--begin::Label-->
																	<span class="fs-8 fw-boldest text-success text-uppercase">Sender</span>
																	<!--begin::Label-->
																	<!--begin::Title-->
																	<a href="#" class="fs-6 text-gray-800 fw-bolder d-block text-hover-primary">Cameron Williamson</a>
																	<!--end::Title-->
																	<!--begin::Title-->
																	<span class="fw-bold text-gray-400">3891 Ranchview Dr. Richardson, California 62639</span>
																	<!--end::Title-->
																</div>
																<!--end::Timeline content-->
															</div>
															<!--end::Timeline item-->
															<!--begin::Timeline item-->
															<div class="timeline-item align-items-center">
																<!--begin::Timeline line-->
																<div class="timeline-line w-20px"></div>
																<!--end::Timeline line-->
																<!--begin::Timeline icon-->
																<div class="timeline-icon pt-1" style="margin-left: 0.5px">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
																	<span class="svg-icon svg-icon-2 svg-icon-info">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
																		</svg>
																	</span>
																	<!--end::Svg Icon-->
																</div>
																<!--end::Timeline icon-->
																<!--begin::Timeline content-->
																<div class="timeline-content m-0">
																	<!--begin::Label-->
																	<span class="fs-8 fw-boldest text-info text-uppercase">Receiver</span>
																	<!--begin::Label-->
																	<!--begin::Title-->
																	<a href="#" class="fs-6 text-gray-800 fw-bolder d-block text-hover-primary">Kristin Watson</a>
																	<!--end::Title-->
																	<!--begin::Title-->
																	<span class="fw-bold text-gray-400">8502 Preston Rd. Inglewood, Maine 98380</span>
																	<!--end::Title-->
																</div>
																<!--end::Timeline content-->
															</div>
															<!--end::Timeline item-->
														</div>
														<!--end::Timeline-->
													</div>
													<!--end::Item-->
													<!--begin::Separator-->
													<div class="separator separator-dashed mt-5 mb-4"></div>
													<!--end::Separator-->
												</div>
												<!--end::Tap pane-->
												<!--begin::Tap pane-->
												<div class="tab-pane fade" id="kt_list_widget_16_tab_3">
													<!--begin::Item-->
													<div class="m-0">
														<!--begin::Timeline-->
														<div class="timeline ms-n1">
															<!--begin::Timeline item-->
															<div class="timeline-item align-items-center mb-4">
																<!--begin::Timeline line-->
																<div class="timeline-line w-20px mt-9 mb-n14"></div>
																<!--end::Timeline line-->
																<!--begin::Timeline icon-->
																<div class="timeline-icon pt-1" style="margin-left: 0.7px">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen015.svg-->
																	<span class="svg-icon svg-icon-2 svg-icon-success">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10ZM6.39999 9.89999C6.99999 8.19999 8.40001 6.9 10.1 6.4C10.6 6.2 10.9 5.7 10.7 5.1C10.5 4.6 9.99999 4.3 9.39999 4.5C7.09999 5.3 5.29999 7 4.39999 9.2C4.19999 9.7 4.5 10.3 5 10.5C5.1 10.5 5.19999 10.6 5.39999 10.6C5.89999 10.5 6.19999 10.2 6.39999 9.89999ZM14.8 19.5C17 18.7 18.8 16.9 19.6 14.7C19.8 14.2 19.5 13.6 19 13.4C18.5 13.2 17.9 13.5 17.7 14C17.1 15.7 15.8 17 14.1 17.6C13.6 17.8 13.3 18.4 13.5 18.9C13.6 19.3 14 19.6 14.4 19.6C14.5 19.6 14.6 19.6 14.8 19.5Z" fill="currentColor" />
																			<path d="M16 12C16 14.2 14.2 16 12 16C9.8 16 8 14.2 8 12C8 9.8 9.8 8 12 8C14.2 8 16 9.8 16 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10Z" fill="currentColor" />
																		</svg>
																	</span>
																	<!--end::Svg Icon-->
																</div>
																<!--end::Timeline icon-->
																<!--begin::Timeline content-->
																<div class="timeline-content m-0">
																	<!--begin::Label-->
																	<span class="fs-8 fw-boldest text-success text-uppercase">Sender</span>
																	<!--begin::Label-->
																	<!--begin::Title-->
																	<a href="#" class="fs-6 text-gray-800 fw-bolder d-block text-hover-primary">Albert Flores</a>
																	<!--end::Title-->
																	<!--begin::Title-->
																	<span class="fw-bold text-gray-400">3517 W. Gray St. Utica, Pennsylvania 57867</span>
																	<!--end::Title-->
																</div>
																<!--end::Timeline content-->
															</div>
															<!--end::Timeline item-->
															<!--begin::Timeline item-->
															<div class="timeline-item align-items-center">
																<!--begin::Timeline line-->
																<div class="timeline-line w-20px"></div>
																<!--end::Timeline line-->
																<!--begin::Timeline icon-->
																<div class="timeline-icon pt-1" style="margin-left: 0.5px">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
																	<span class="svg-icon svg-icon-2 svg-icon-info">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
																		</svg>
																	</span>
																	<!--end::Svg Icon-->
																</div>
																<!--end::Timeline icon-->
																<!--begin::Timeline content-->
																<div class="timeline-content m-0">
																	<!--begin::Label-->
																	<span class="fs-8 fw-boldest text-info text-uppercase">Receiver</span>
																	<!--begin::Label-->
																	<!--begin::Title-->
																	<a href="#" class="fs-6 text-gray-800 fw-bolder d-block text-hover-primary">Jessie Clarcson</a>
																	<!--end::Title-->
																	<!--begin::Title-->
																	<span class="fw-bold text-gray-400">Total 2,356 Items in the Stock</span>
																	<!--end::Title-->
																</div>
																<!--end::Timeline content-->
															</div>
															<!--end::Timeline item-->
														</div>
														<!--end::Timeline-->
													</div>
													<!--end::Item-->
													<!--begin::Separator-->
													<div class="separator separator-dashed mt-5 mb-4"></div>
													<!--end::Separator-->
												</div>
												<!--end::Tap pane-->
											</div>
											<!--end::Tab Content-->
										</div>
										<!--end: Card Body-->
									</div>
									<!--end::List widget 16-->
								</div>
								<!--end::Col-->
								<!--begin::Col-->
								
								<div class="col-xl-8 mb-5 mb-xl-10">
									<!--begin::Tables widget 6-->
									<div class="card card-flush h-xl-100">
										<!--begin::Header-->
										<div class="card-header pt-7">
											<!--begin::Title-->
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder text-gray-800">Error Logs Satria</span>
												<span class="text-gray-400 mt-1 fw-bold fs-6">Total {{ count($data['errorlogs']) }} Errors</span>
											</h3>
											<!--end::Title-->
										</div>
										<!--end::Header-->
										<!--begin::Body-->
										<div class="card-body">
											<div id="kt_mixed_widget_2_chart" class="mx-3" data-kt-pr="10,20,60,10,50,40,30,50,30," data-kt-color="primary" style="height: 475px"></div>
                                            <div class="table-responsive">
                                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                                <!--begin::Table head-->
                                                <thead>
                                                    <tr class="fs-7 fw-bolder text-gray-500 border-bottom-0">
                                                        <th class="p-0 w-200px w-xxl-450px"></th>
                                                        <th class="p-0 min-w-150px"></th>
                                                        <th class="p-0 min-w-150px"></th>
                                                        <th class="p-0 min-w-190px"></th>
                                                    </tr>
                                                </thead>
                                                <!--end::Table head-->
                                                <!--begin::Table body-->
                                                <tbody>
                                                    @foreach($data['errorlogs'] as $error)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="d-flex justify-content-start flex-column">
                                                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">Code : {{ $error->code }}</a>
                                                                    <span class="text-muted fw-bold d-block fs-7">Apps : {{ $error->app_name }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 fw-bolder d-block mb-1 fs-6">{{ $error->remote_addr }}</span>
                                                            <span class="fw-bold text-gray-400 d-block">By : {{ $error->name }}</span>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ $error->action }}</a>
                                                            <span class="text-muted fw-bold d-block fs-7">At : {{ $error->created_at }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <a onclick="getDetailLogs({{ $error->id }})"  tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-logs"  class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                                                                <span class="svg-icon svg-icon-5 svg-icon-gray-700">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path d="M14.4 11H3C2.4 11 2 11.4 2 12C2 12.6 2.4 13 3 13H14.4V11Z" fill="currentColor" />
                                                                        <path opacity="0.3" d="M14.4 20V4L21.7 11.3C22.1 11.7 22.1 12.3 21.7 12.7L14.4 20Z" fill="currentColor" />
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <!--end::Table body-->
                                            </table>
										</div>
										</div>
										<!--end: Card Body-->
									</div>
									<!--end::Tables widget 6-->
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

                            <script src="https://preview.keenthemes.com/ceres-html-pro/assets/plugins/custom/datatables/datatables.bundle.js"></script>
                            <script src="https://preview.keenthemes.com/ceres-html-pro/assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
                            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="assets/js/custom/widgets.bundle2.js"></script>
<script src="assets/js/custom/widgets.js"></script>
                            <!-- <script src="https://preview.keenthemes.com/ceres-html-pro/assets/js/widgets.bundle.js"></script> -->
                            <!-- <script src="https://preview.keenthemes.com/ceres-html-pro/assets/js/custom/widgets.js"></script> -->
<script type="text/javascript">
	
function detail(x) {
    // indextr = x.rowIndex;
    console.log(x);
    document.getElementById('id_logs').value=x;
}
 
function getDetailLogs(val){

        APP_URL = '{{url('/')}}' ;
        $.ajax({
            url: APP_URL+'/get-dashboard',
            type: 'get',
            dataType: 'json',         
            cache: true, 
            success: function (response) {
                console.log(response);
            }
        });
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