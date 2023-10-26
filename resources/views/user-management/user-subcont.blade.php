@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    
    <div class="content flex-row-fluid" id="kt_content">
    <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">User Subcont</a>
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
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-safety-hour') }}">Safety Hour</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{ url('user-attendance-ratio') }}">Attendance Ratio</a>
                    </li>
                </ul>
                <div class="card card-flush">
                    <!-- <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <h2 class="text-end pe-0">Your Attendance Record</h2>
                    </div> -->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="text" data-kt-user-subcont-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search User" />
                            </div>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <!-- <div class="w-100 mw-150px">
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Assign" data-kt-ecommerce-product-filter="status">
                                    <option></option>
                                    <option value="all">All</option>
                                    <option value="WFO">WFO</option>
                                    <option value="WFH">WFH</option>
                                </select>
                            </div> -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
											<span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
												</svg>
											</span>
                            Add User</button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_user_subcont_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">#</th>
                                    <th class="min-w-175px">User</th>
                                    <th class="min-w-75px">Marital</th>
                                    <th class="min-w-75px">Plant</th>
                                    <th class="min-w-125px">Vendor</th>
                                    <th class="min-w-125px">Klasifikasi</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-semibold">
                                @php($no=0)
                                @foreach($data['user'] as $user)
                                @php($no=$no+1)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                            <a >
                                                <div class="symbol-label">
                                                    <img src="@if($user->photo!='') {{ asset('public/profile/'.$user->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" alt="Emma Smith" class="w-100" />
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $user->name }}</strong>
                                            <span>{{ $user->nrp }} / {{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->marital_ket }}</td>
                                    <td>
                                        <div class="badge badge-light fw-bold">{{ $user->plant }}</div>
                                    </td>
                                    <td>{{ $user->vendor_name }}</td>
                                    <td>{{ $user->klasifikasi_name }}</td>
                                    <td>{{ $user->emp_status }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions 
                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="{{ url('user-detail/'.$user->user_id) }}" class="menu-link px-3">Detail</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_update_user{{ $user->user_id }}">Edit</a>
                                            </div>
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

<!-- Modal Add User -->
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header" >
                <h2 class="fw-bold">Add User</h2>
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
                <div class="text-center mb-13">
                    <div class="text-muted fw-bold fs-5">
                        <a class="link-primary fw-bolder"></a>
                    </div>
                </div>
                <form id="kt_modal_add_user_form" class="form" action="{{ url('insert-subcont') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                        <div class="fv-row mb-7 row">
                            <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" required placeholder="Full Name" />
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">NRP</label>
                                <input type="text" name="nrp" class="form-control form-control-solid mb-3 mb-lg-0" required placeholder="NRP" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Email</label>
                                <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" required placeholder="example@domain.com" />
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Phone/WA*</label>
                                <input type="number" name="phone" class="form-control form-control-solid mb-3 mb-lg-0" required placeholder="Phone/WA*" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Birth Date</label>
                                <input type="date" name="birth_date" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <label class="required fw-semibold fs-6 mb-2">Marital Status</label>
                                <select name="marital_status" required class="form-control">
                                @foreach($data['marital'] as $marital)
                                    <option value="{{ $marital->id }}">{{ $marital->code.' | '.$marital->ket }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Address</label>
                            <textarea name="address" id="" cols="5" rows="5" class="form-control form-control-solid mb-3 mb-lg-0"></textarea>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Vendor</label>                                
                            <select name="vendor" required class="form-control">
                                @foreach($data['subcont'] as $subcont)
                                    <option value="{{ $subcont->id }}">{{ $subcont->code.' | '.$subcont->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Join Date</label>
                                <input type="date" name="join_date" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">End Date</label>
                                <input type="date" name="end_date" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Plant</label>
                                <select name="plant" required class="form-control">
                                    <option value="HO">HO</option>
                                    <option value="RJKT">RJKT</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Klasifikasi</label>
                                <select name="klasifikasi" required class="form-control">
                                    @foreach($data['klasifikasi'] as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Manager</label>
                                <select class="form-control" name="manager_search" id="manager_search" data-control="select2" data-placeholder="Manager*" required>
                                    <option></option>
                                    @foreach($data['manager'] as $manager)
                                        <option value="{{ $manager['nrp'] }}">{{ $manager['nrp'] }} - {{ $manager['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Departement</label>
                                <input type="text" name="departement" id="departement" class="form-control form-control-solid mb-3 mb-lg-0" required readonly placeholder="Departement*" />
                                <input type="hidden" name="department_code" id="department_code" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="fw-semibold fs-6 mb-2">Section</label>
                                <input type="text" name="section" id="section" class="form-control form-control-solid mb-3 mb-lg-0" required readonly placeholder="Section" />
                                <input type="hidden" name="section_code" id="section_code" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Division</label>
                                <input type="text" name="division" id="division" class="form-control form-control-solid mb-3 mb-lg-0" required readonly placeholder="Division*" />
                                <input type="hidden" name="division_code" id="division_code" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Title</label>                                
                            <input type="text" name="title" id="title" class="form-control form-control-solid mb-3 mb-lg-0" required placeholder="Title*" />
                            <input type="hidden" name="company_name" id="company_name" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                            <input type="hidden" name="company_id" id="company_id" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                            <input type="hidden" name="worklocation_code" id="worklocation_code" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                            <input type="hidden" name="worklocation_name" id="worklocation_name" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                            <input type="hidden" name="worklocation_lat_long" id="worklocation_lat_long" class="form-control form-control-solid mb-3 mb-lg-0" required readonly/>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Password: *</label>
                                <input type="password" name="new_password" class="form-control form-control-solid mb-3 mb-lg-0" required placeholder="Password" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Re-type Password: *</label>
                                <input type="password" name="re_password" class="form-control form-control-solid mb-3 mb-lg-0" required placeholder="Re-type Password" />
                            </div>
                        </div>
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-5">Status</label>
                            @foreach($data['status'] as $status)
                            <div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input me-3" required name="status" type="radio" value="{{ $status->id }}" id="kt_modal_update_role_option_0" checked='checked' />
                                    <label class="form-check-label" for="kt_modal_update_role_option_0">
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
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
@foreach($data['user'] as $user)
<div class="modal fade" id="kt_modal_update_user{{ $user->user_id }}" tabindex="-1" aria-hidden="true">
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
                            <input type="hidden" name="id" value="{{ $user->id }}"/>
                            <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $user->name }}" required placeholder="Full Name" />
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">NRP</label>
                                <input type="number" name="nrp" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $user->nrp }}" required placeholder="NRP" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Email</label>
                                <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $user->email }}" required placeholder="example@domain.com" />
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Marital Status</label>
                                <select name="marital_status" required class="form-control">
                                    @foreach($data['marital'] as $marital)
                                        <option value="{{ $marital->id }}" @if($user->marital_status == $marital->id) selected @endif>
                                            {{ $marital->code.' | '.$marital->ket }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Birth Date</label>
                                <input type="date" name="birth_date" value="{{ $user->birth_date }}" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Address</label>
                            <textarea name="address" id="" cols="5" rows="5" class="form-control form-control-solid mb-3 mb-lg-0">{{ $user->address }}</textarea>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Vendor</label>                                
                            <select name="vendor" required class="form-control">
                                @foreach($data['subcont'] as $subcont)
                                    <option value="{{ $subcont->id }}" @if($user->vendor == $subcont->id) selected @endif> 
                                        {{ $subcont->code.' | '.$subcont->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Join Date</label>
                                <input type="date" name="join_date" value="{{ $user->join_date }}" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">End Date</label>
                                <input type="date" name="end_date" value="{{ $user->end_date }}" required class="form-control form-control-solid mb-3 mb-lg-0" />
                            </div>
                        </div>
                        <div class="fv-row mb-7 row">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Plant</label>
                                <select name="plant" required class="form-control">
                                    <option value="HO" @if($user->plant == "HO") selected @endif>HO</option>
                                    <option value="RJKT" @if($user->plant == "RJKT") selected @endif>RJKT</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Klasifikasi</label>
                                <select name="klasifikasi" required class="form-control">
                                    @foreach($data['klasifikasi'] as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}"@if($user->klasifikasi == $klasifikasi->id) selected @endif>
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
                                        @if($user->status == $status->id) checked @endif />
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

@endforeach

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
		        const filterSearch = document.querySelector('[data-kt-user-subcont-filter="search"]');
		        filterSearch.addEventListener('keyup', function (e) {
		            datatable.search(e.target.value).draw();
		        });
		    }
		    // Public methods
		    return {
		        init: function () {
		            table = document.querySelector('#kt_user_subcont_table');

		            if (!table) {
		                return;
		            }

		            initDatatable();
		            handleSearchDatatable();
		        }
		    };
		}();
    </script>

    <script>
        $(document).ready(function () {
            // Tangani perubahan pada select manager_search
            $('select[name="manager_search"]').on('change', function () {
                document.getElementById('departement').value = "";
                document.getElementById('section').value = "";
                document.getElementById('division').value = "";
                document.getElementById('department_code').value = "";
                document.getElementById('section_code').value = "";
                document.getElementById('division_code').value = "";
                document.getElementById('company_name').value = "";
                document.getElementById('company_id').value = "";
                document.getElementById('worklocation_code').value = "";
                document.getElementById('worklocation_name').value = "";
                document.getElementById('worklocation_lat_long').value = "";
                var selectedManager = $(this).val();
                var newUrl = "{{ url('get-user-sf') }}" +'/'+ selectedManager;
                // Lakukan permintaan AJAX
                $.ajax({
                    url: newUrl,
                    type: 'GET',
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function (data) {
                        document.getElementById('departement').value = "";
                        document.getElementById('section').value = "";
                        document.getElementById('division').value = "";
                        document.getElementById('department_code').value = "";
                        document.getElementById('section_code').value = "";
                        document.getElementById('division_code').value = "";
                        document.getElementById('company_name').value = "";
                        document.getElementById('company_id').value = "";
                        document.getElementById('worklocation_code').value = "";
                        document.getElementById('worklocation_name').value = "";
                        document.getElementById('worklocation_lat_long').value = "";
                        $.each(data, function (key, value) {
                            document.getElementById('departement').value = value.department;
                            document.getElementById('section').value = value.section;
                            document.getElementById('division').value = value.Division;
                            document.getElementById('department_code').value = value.department_code;
                            document.getElementById('section_code').value = value.section_code;
                            document.getElementById('division_code').value = value.division_code;
                            document.getElementById('company_name').value = value.company_name;
                            document.getElementById('company_id').value = value.company_id;
                            document.getElementById('worklocation_code').value = value.worklocation_code;
                            document.getElementById('worklocation_name').value = value.worklocation_name;
                            document.getElementById('worklocation_lat_long').value = value.worklocation_lat_long;
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>
@endsection