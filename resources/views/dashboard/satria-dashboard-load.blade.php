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
				<!--begin::Row-->
				
									<!--begin::Row-->
									<div class="row gy-5 g-xl-8">
										<!--begin::Col-->
										<div class="col-xxl-6">
											<!--begin::Table Widget 1-->
											<div class="card card-xxl-stretch">
												<!--begin::Header-->
												<div class="card-header border-0 pt-5 pb-3">
													<!--begin::Card title-->
													<h3 class="card-title fw-bolder text-gray-800 fs-2">Leading Partners</h3>
													<!--end::Card title-->
													<!--begin::Card toolbar-->
													<div class="card-toolbar">
														<div class="my-1">
															<!--begin::Select-->
															<select class="form-select fw-bold w-125px" data-control="select2" data-placeholder="Status" data-hide-search="true">
																<option value="1" selected="selected">Status</option>
																<option value="2">Pending</option>
																<option value="3">In Progress</option>
																<option value="3">Complete</option>
															</select>
															<!--end::Select-->
														</div>
													</div>
													<!--end::Card toolbar-->
												</div>
												<!--end::Header-->
												<!--begin::Body-->
												<div class="card-body py-0">
													<!--begin::Table-->
													<div class="table-responsive">
														<table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
															<!--begin::Table body-->
															<tbody>
																<!--begin::Table row-->
																<tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
																	<th class="min-w-200px px-0">Authors</th>
																	<th class="min-w-125px">Progress</th>
																	<th class="text-end pe-2 min-w-70px">Action</th>
																</tr>
																<!--end::Table row-->
																<!--begin::Table row-->
																<tr>
																	<!--begin::Author=-->
																	<td class="p-0">
																		<div class="d-flex align-items-center">
																			<!--begin::Logo-->
																			<div class="symbol symbol-50px me-2">
																				<span class="symbol-label">
																					<img alt="" class="w-25px" src="/ceres-html-pro/assets/media/svg/brand-logos/aven.svg" />
																				</span>
																			</div>
																			<!--end::Logo-->
																			<div class="ps-3">
																				<a href="/ceres-html-pro/?page=account/overview" class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">Brad Simmons</a>
																				<span class="text-gray-400 fw-bold d-block">HTML, JS, ReactJS</span>
																			</div>
																		</div>
																	</td>
																	<!--end::Author=-->
																	<!--begin::Progress=-->
																	<td>
																		<div class="d-flex flex-column w-100 me-2 mt-2">
																			<span class="text-gray-400 me-2 fw-boldest mb-2">65%</span>
																			<div class="progress bg-light-danger w-100 h-5px">
																				<div class="progress-bar bg-danger" role="progressbar" style="width: 65%"></div>
																			</div>
																		</div>
																	</td>
																	<!--end::Company=-->
																	<!--begin::Action=-->
																	<td class="pe-0 text-end">
																		<a href="#" class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="bottom-start">
																			<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
																			<span class="svg-icon svg-icon-2x">
																				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																					<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
																					<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																				</svg>
																			</span>
																			<!--end::Svg Icon-->
																		</a>
																		<!--begin::Menu 3-->
																		<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
																			<!--begin::Heading-->
																			<div class="menu-item px-3">
																				<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
																			</div>
																			<!--end::Heading-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Create Invoice</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link flex-stack px-3">Create Payment 
																				<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Generate Bill</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
																				<a href="#" class="menu-link px-3">
																					<span class="menu-title">Subscription</span>
																					<span class="menu-arrow"></span>
																				</a>
																				<!--begin::Menu sub-->
																				<div class="menu-sub menu-sub-dropdown w-175px py-4">
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Plans</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Billing</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Statements</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu separator-->
																					<div class="separator my-2"></div>
																					<!--end::Menu separator-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<div class="menu-content px-3">
																							<!--begin::Switch-->
																							<label class="form-check form-switch form-check-custom form-check-solid">
																								<!--begin::Input-->
																								<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																								<!--end::Input-->
																								<!--end::Label-->
																								<span class="form-check-label text-muted fs-6">Recuring</span>
																								<!--end::Label-->
																							</label>
																							<!--end::Switch-->
																						</div>
																					</div>
																					<!--end::Menu item-->
																				</div>
																				<!--end::Menu sub-->
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3 my-1">
																				<a href="#" class="menu-link px-3">Settings</a>
																			</div>
																			<!--end::Menu item-->
																		</div>
																		<!--end::Menu 3-->
																	</td>
																	<!--end::Action=-->
																</tr>
																<!--end::Table row-->
																<!--begin::Table row-->
																<tr>
																	<!--begin::Author=-->
																	<td class="p-0">
																		<div class="d-flex align-items-center">
																			<!--begin::Logo-->
																			<div class="symbol symbol-50px me-2">
																				<span class="symbol-label">
																					<img alt="" class="w-25px" src="/ceres-html-pro/assets/media/svg/brand-logos/leaf.svg" />
																				</span>
																			</div>
																			<!--end::Logo-->
																			<div class="ps-3">
																				<a href="/ceres-html-pro/?page=account/overview" class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">Jessie Clarcson</a>
																				<span class="text-gray-400 fw-bold d-block">C#, ASP.NET, MS SQL</span>
																			</div>
																		</div>
																	</td>
																	<!--end::Author=-->
																	<!--begin::Progress=-->
																	<td>
																		<div class="d-flex flex-column w-100 me-2 mt-2">
																			<span class="text-gray-400 me-2 fw-boldest mb-2">85%</span>
																			<div class="progress bg-light-danger w-100 h-5px">
																				<div class="progress-bar bg-primary" role="progressbar" style="width: 85%"></div>
																			</div>
																		</div>
																	</td>
																	<!--end::Company=-->
																	<!--begin::Action=-->
																	<td class="pe-0 text-end">
																		<a href="#" class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="bottom-start">
																			<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
																			<span class="svg-icon svg-icon-2x">
																				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																					<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
																					<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																				</svg>
																			</span>
																			<!--end::Svg Icon-->
																		</a>
																		<!--begin::Menu 3-->
																		<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
																			<!--begin::Heading-->
																			<div class="menu-item px-3">
																				<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
																			</div>
																			<!--end::Heading-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Create Invoice</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link flex-stack px-3">Create Payment 
																				<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Generate Bill</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
																				<a href="#" class="menu-link px-3">
																					<span class="menu-title">Subscription</span>
																					<span class="menu-arrow"></span>
																				</a>
																				<!--begin::Menu sub-->
																				<div class="menu-sub menu-sub-dropdown w-175px py-4">
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Plans</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Billing</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Statements</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu separator-->
																					<div class="separator my-2"></div>
																					<!--end::Menu separator-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<div class="menu-content px-3">
																							<!--begin::Switch-->
																							<label class="form-check form-switch form-check-custom form-check-solid">
																								<!--begin::Input-->
																								<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																								<!--end::Input-->
																								<!--end::Label-->
																								<span class="form-check-label text-muted fs-6">Recuring</span>
																								<!--end::Label-->
																							</label>
																							<!--end::Switch-->
																						</div>
																					</div>
																					<!--end::Menu item-->
																				</div>
																				<!--end::Menu sub-->
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3 my-1">
																				<a href="#" class="menu-link px-3">Settings</a>
																			</div>
																			<!--end::Menu item-->
																		</div>
																		<!--end::Menu 3-->
																	</td>
																	<!--end::Action=-->
																</tr>
																<!--end::Table row-->
																<!--begin::Table row-->
																<tr>
																	<!--begin::Author=-->
																	<td class="p-0">
																		<div class="d-flex align-items-center">
																			<!--begin::Logo-->
																			<div class="symbol symbol-50px me-2">
																				<span class="symbol-label">
																					<img alt="" class="w-25px" src="/ceres-html-pro/assets/media/svg/brand-logos/atica.svg" />
																				</span>
																			</div>
																			<!--end::Logo-->
																			<div class="ps-3">
																				<a href="/ceres-html-pro/?page=account/overview" class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">Lebron Wayde</a>
																				<span class="text-gray-400 fw-bold d-block">PHP, Laravel, VueJS</span>
																			</div>
																		</div>
																	</td>
																	<!--end::Author=-->
																	<!--begin::Progress=-->
																	<td>
																		<div class="d-flex flex-column w-100 me-2 mt-2">
																			<span class="text-gray-400 me-2 fw-boldest mb-2">40%</span>
																			<div class="progress bg-light-danger w-100 h-5px">
																				<div class="progress-bar bg-success" role="progressbar" style="width: 40%"></div>
																			</div>
																		</div>
																	</td>
																	<!--end::Company=-->
																	<!--begin::Action=-->
																	<td class="pe-0 text-end">
																		<a href="#" class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="bottom-start">
																			<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
																			<span class="svg-icon svg-icon-2x">
																				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																					<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
																					<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																				</svg>
																			</span>
																			<!--end::Svg Icon-->
																		</a>
																		<!--begin::Menu 3-->
																		<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
																			<!--begin::Heading-->
																			<div class="menu-item px-3">
																				<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
																			</div>
																			<!--end::Heading-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Create Invoice</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link flex-stack px-3">Create Payment 
																				<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Generate Bill</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
																				<a href="#" class="menu-link px-3">
																					<span class="menu-title">Subscription</span>
																					<span class="menu-arrow"></span>
																				</a>
																				<!--begin::Menu sub-->
																				<div class="menu-sub menu-sub-dropdown w-175px py-4">
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Plans</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Billing</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Statements</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu separator-->
																					<div class="separator my-2"></div>
																					<!--end::Menu separator-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<div class="menu-content px-3">
																							<!--begin::Switch-->
																							<label class="form-check form-switch form-check-custom form-check-solid">
																								<!--begin::Input-->
																								<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																								<!--end::Input-->
																								<!--end::Label-->
																								<span class="form-check-label text-muted fs-6">Recuring</span>
																								<!--end::Label-->
																							</label>
																							<!--end::Switch-->
																						</div>
																					</div>
																					<!--end::Menu item-->
																				</div>
																				<!--end::Menu sub-->
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3 my-1">
																				<a href="#" class="menu-link px-3">Settings</a>
																			</div>
																			<!--end::Menu item-->
																		</div>
																		<!--end::Menu 3-->
																	</td>
																	<!--end::Action=-->
																</tr>
																<!--end::Table row-->
																<!--begin::Table row-->
																<tr>
																	<!--begin::Author=-->
																	<td class="p-0">
																		<div class="d-flex align-items-center">
																			<!--begin::Logo-->
																			<div class="symbol symbol-50px me-2">
																				<span class="symbol-label">
																					<img alt="" class="w-25px" src="/ceres-html-pro/assets/media/svg/brand-logos/volicity-9.svg" />
																				</span>
																			</div>
																			<!--end::Logo-->
																			<div class="ps-3">
																				<a href="/ceres-html-pro/?page=account/overview" class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">Natali Trump</a>
																				<span class="text-gray-400 fw-bold d-block">Python, ReactJS</span>
																			</div>
																		</div>
																	</td>
																	<!--end::Author=-->
																	<!--begin::Progress=-->
																	<td>
																		<div class="d-flex flex-column w-100 me-2 mt-2">
																			<span class="text-gray-400 me-2 fw-boldest mb-2">71%</span>
																			<div class="progress bg-light-danger w-100 h-5px">
																				<div class="progress-bar bg-info" role="progressbar" style="width: 71%"></div>
																			</div>
																		</div>
																	</td>
																	<!--end::Company=-->
																	<!--begin::Action=-->
																	<td class="pe-0 text-end">
																		<a href="#" class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="bottom-start">
																			<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
																			<span class="svg-icon svg-icon-2x">
																				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																					<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
																					<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																				</svg>
																			</span>
																			<!--end::Svg Icon-->
																		</a>
																		<!--begin::Menu 3-->
																		<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
																			<!--begin::Heading-->
																			<div class="menu-item px-3">
																				<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
																			</div>
																			<!--end::Heading-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Create Invoice</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link flex-stack px-3">Create Payment 
																				<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Generate Bill</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
																				<a href="#" class="menu-link px-3">
																					<span class="menu-title">Subscription</span>
																					<span class="menu-arrow"></span>
																				</a>
																				<!--begin::Menu sub-->
																				<div class="menu-sub menu-sub-dropdown w-175px py-4">
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Plans</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Billing</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Statements</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu separator-->
																					<div class="separator my-2"></div>
																					<!--end::Menu separator-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<div class="menu-content px-3">
																							<!--begin::Switch-->
																							<label class="form-check form-switch form-check-custom form-check-solid">
																								<!--begin::Input-->
																								<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																								<!--end::Input-->
																								<!--end::Label-->
																								<span class="form-check-label text-muted fs-6">Recuring</span>
																								<!--end::Label-->
																							</label>
																							<!--end::Switch-->
																						</div>
																					</div>
																					<!--end::Menu item-->
																				</div>
																				<!--end::Menu sub-->
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3 my-1">
																				<a href="#" class="menu-link px-3">Settings</a>
																			</div>
																			<!--end::Menu item-->
																		</div>
																		<!--end::Menu 3-->
																	</td>
																	<!--end::Action=-->
																</tr>
																<!--end::Table row-->
																<!--begin::Table row-->
																<tr>
																	<!--begin::Author=-->
																	<td class="p-0">
																		<div class="d-flex align-items-center">
																			<!--begin::Logo-->
																			<div class="symbol symbol-50px me-2">
																				<span class="symbol-label">
																					<img alt="" class="w-25px" src="/ceres-html-pro/assets/media/svg/brand-logos/bebo.svg" />
																				</span>
																			</div>
																			<!--end::Logo-->
																			<div class="ps-3">
																				<a href="/ceres-html-pro/?page=account/overview" class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">Carles Puyol</a>
																				<span class="text-gray-400 fw-bold d-block">PHP, SQLite, Artisan CLI</span>
																			</div>
																		</div>
																	</td>
																	<!--end::Author=-->
																	<!--begin::Progress=-->
																	<td>
																		<div class="d-flex flex-column w-100 me-2 mt-2">
																			<span class="text-gray-400 me-2 fw-boldest mb-2">45%</span>
																			<div class="progress bg-light-danger w-100 h-5px">
																				<div class="progress-bar bg-warning" role="progressbar" style="width: 45%"></div>
																			</div>
																		</div>
																	</td>
																	<!--end::Company=-->
																	<!--begin::Action=-->
																	<td class="pe-0 text-end">
																		<a href="#" class="btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="bottom-start">
																			<!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
																			<span class="svg-icon svg-icon-2x">
																				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																					<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
																					<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																					<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
																				</svg>
																			</span>
																			<!--end::Svg Icon-->
																		</a>
																		<!--begin::Menu 3-->
																		<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
																			<!--begin::Heading-->
																			<div class="menu-item px-3">
																				<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
																			</div>
																			<!--end::Heading-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Create Invoice</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link flex-stack px-3">Create Payment 
																				<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3">
																				<a href="#" class="menu-link px-3">Generate Bill</a>
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
																				<a href="#" class="menu-link px-3">
																					<span class="menu-title">Subscription</span>
																					<span class="menu-arrow"></span>
																				</a>
																				<!--begin::Menu sub-->
																				<div class="menu-sub menu-sub-dropdown w-175px py-4">
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Plans</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Billing</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<a href="#" class="menu-link px-3">Statements</a>
																					</div>
																					<!--end::Menu item-->
																					<!--begin::Menu separator-->
																					<div class="separator my-2"></div>
																					<!--end::Menu separator-->
																					<!--begin::Menu item-->
																					<div class="menu-item px-3">
																						<div class="menu-content px-3">
																							<!--begin::Switch-->
																							<label class="form-check form-switch form-check-custom form-check-solid">
																								<!--begin::Input-->
																								<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																								<!--end::Input-->
																								<!--end::Label-->
																								<span class="form-check-label text-muted fs-6">Recuring</span>
																								<!--end::Label-->
																							</label>
																							<!--end::Switch-->
																						</div>
																					</div>
																					<!--end::Menu item-->
																				</div>
																				<!--end::Menu sub-->
																			</div>
																			<!--end::Menu item-->
																			<!--begin::Menu item-->
																			<div class="menu-item px-3 my-1">
																				<a href="#" class="menu-link px-3">Settings</a>
																			</div>
																			<!--end::Menu item-->
																		</div>
																		<!--end::Menu 3-->
																	</td>
																	<!--end::Action=-->
																</tr>
																<!--end::Table row-->
															</tbody>
															<!--end::Table body-->
														</table>
													</div>
													<!--end::Table-->
												</div>
												<!--end::Body-->
											</div>
											<!--end::Table Widget 1-->
										</div>
										<!--end::Col-->
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
																<div class="text-success fw-boldest fs-2hx">6.2k</div>
																<!--end::Number-->
																<!--begin::Description-->
																<span class="text-gray-400 fw-bold fs-6">Weekly New Followers</span>
																<!--end::Description-->
															</div>
															<!--end::Hidden-->
															<!--begin::Chart-->
															<div class="statistics-widget-1-chart card-rounded-bottom" data-kt-chart-color="success" style="height: 150px"></div>
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
															<div id="kt_multipurpose_mixed_widget_1_chart" data-kt-height="250" class="mb-n15"></div>
															<!--end::Chart-->
															<!--begin::Label-->
															<span class="badge badge-lg badge-light-warning w-100 text-gray-800 text-start d-flex align-items-center">
															<i class="fas fa-exclamation-circle text-warning me-3 fs-3"></i>Only New Users</span>
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
																<span class="text-primary fw-boldest fs-2hx">730</span>
																<!--end::Number-->
																<!--begin::Description-->
																<span class="text-gray-400 fw-bold fs-6">My Weekly Targets</span>
																<!--end::Description-->
															</div>
															<!--end::Hidden-->
															<!--begin::Chart-->
															<div id="kt_multipurpose_mixed_widget_2_chart" class="mx-3" data-kt-color="primary" style="height: 175px"></div>
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
																	<div class="d-flex align-items-center mb-3">
																		<!--begin::Svg Icon | path: icons/duotune/arrows/arr059.svg-->
																		<span class="svg-icon svg-icon-3 svg-icon-white me-2">
																			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																				<path d="M6.8 15.8C7.3 15.7 7.9 16 8 16.5C8.2 17.4 8.99999 18 9.89999 18H17.9C19 18 19.9 17.1 19.9 16V8C19.9 6.9 19 6 17.9 6H9.89999C8.79999 6 7.89999 6.9 7.89999 8V9.4H5.89999V8C5.89999 5.8 7.69999 4 9.89999 4H17.9C20.1 4 21.9 5.8 21.9 8V16C21.9 18.2 20.1 20 17.9 20H9.89999C8.09999 20 6.5 18.8 6 17.1C6 16.5 6.3 16 6.8 15.8Z" fill="currentColor" />
																				<path opacity="0.3" d="M12 9.39999H2L6.3 13.7C6.7 14.1 7.3 14.1 7.7 13.7L12 9.39999Z" fill="currentColor" />
																			</svg>
																		</span>
																		<!--end::Svg Icon-->
																		<span class="fw-bolder fs-7 text-white">Cereate</span>
																	</div>
																	<!--end::Item-->
																	<!--begin::Item-->
																	<div class="d-flex align-items-center mb-3">
																		<!--begin::Svg Icon | path: icons/duotune/arrows/arr059.svg-->
																		<span class="svg-icon svg-icon-3 svg-icon-white me-2">
																			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																				<path d="M6.8 15.8C7.3 15.7 7.9 16 8 16.5C8.2 17.4 8.99999 18 9.89999 18H17.9C19 18 19.9 17.1 19.9 16V8C19.9 6.9 19 6 17.9 6H9.89999C8.79999 6 7.89999 6.9 7.89999 8V9.4H5.89999V8C5.89999 5.8 7.69999 4 9.89999 4H17.9C20.1 4 21.9 5.8 21.9 8V16C21.9 18.2 20.1 20 17.9 20H9.89999C8.09999 20 6.5 18.8 6 17.1C6 16.5 6.3 16 6.8 15.8Z" fill="currentColor" />
																				<path opacity="0.3" d="M12 9.39999H2L6.3 13.7C6.7 14.1 7.3 14.1 7.7 13.7L12 9.39999Z" fill="currentColor" />
																			</svg>
																		</span>
																		<!--end::Svg Icon-->
																		<span class="fw-bolder fs-7 text-white">Observe</span>
																	</div>
																	<!--end::Item-->
																	<!--begin::Item-->
																	<div class="d-flex align-items-center mb-5">
																		<!--begin::Svg Icon | path: icons/duotune/arrows/arr059.svg-->
																		<span class="svg-icon svg-icon-3 svg-icon-white me-2">
																			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																				<path d="M6.8 15.8C7.3 15.7 7.9 16 8 16.5C8.2 17.4 8.99999 18 9.89999 18H17.9C19 18 19.9 17.1 19.9 16V8C19.9 6.9 19 6 17.9 6H9.89999C8.79999 6 7.89999 6.9 7.89999 8V9.4H5.89999V8C5.89999 5.8 7.69999 4 9.89999 4H17.9C20.1 4 21.9 5.8 21.9 8V16C21.9 18.2 20.1 20 17.9 20H9.89999C8.09999 20 6.5 18.8 6 17.1C6 16.5 6.3 16 6.8 15.8Z" fill="currentColor" />
																				<path opacity="0.3" d="M12 9.39999H2L6.3 13.7C6.7 14.1 7.3 14.1 7.7 13.7L12 9.39999Z" fill="currentColor" />
																			</svg>
																		</span>
																		<!--end::Svg Icon-->
																		<span class="fw-bolder fs-7 text-white">Export PDF</span>
																	</div>
																	<!--end::Item-->
																</div>
																<!--end::Items-->
																<!--begin::Link-->
																<a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">New Target</a>
																<!--end::Link-->
															</div>
															<!--end::Wrapper-->
															<img class="mh-200px" alt="" src="/ceres-html-pro/assets/media/svg/illustrations/engage.svg" />
														</div>
														<!--end::Card body-->
													</div>
													<!--end::Engage widget 1-->
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
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
									<span class="text-gray-400 mt-2 fw-bold fs-6">5 Active Ticket</span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-1">
								<div class="table-responsive">
									
									
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
								
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 5-->
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
@endsection
@section('myscript')
<script>

$(document).ready(function(){
	GetComputerName();
});

function GetComputerName() {
    try {
        var network = new ActiveXObject('WScript.Network');
        // Show a pop up if it works
        alert(network.computerName);
    }
    catch (e) { 
    	alert(e);
    }
}
// setInterval(loadajax, 5000);
 function loadchart(){}
        var options = {
          series: [],
          chart: {
          height: 350,
          type: 'area',
        },
        dataLabels: {
          enabled: false
        },
        title: {
          text: 'Ajax Example',
        },
        noData: {
          text: 'Loading...'
        },
        xaxis: {
          type: 'category',
          tickPlacement: 'on',
          labels: {
            rotate: -45,
            rotateAlways: true
          }
        }
        };

        var chart = new ApexCharts(document.querySelector(".statistics-widget-1-chart"), options);
        chart.render();
      
      
        $.getJSON('https://my-json-server.typicode.com/apexcharts/apexcharts.js/yearly', function(response) {
        chart.updateSeries([{
          name: 'Sales',
          data: response
        }])
      });
      
    </script>
@endsection