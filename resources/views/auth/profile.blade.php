@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
						
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Layout - Overview-->
        <div class="d-flex flex-column flex-xl-row">
            @include('fe-layouts.sidebar')
            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-10">
                <!--begin::details View-->
                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bolder m-0">Profile Details</h3>
                        </div>
						@if(Auth::user()->dept=="")
                        <div class="d-flex flex-center">
                            <a class="btn btn-sm btn-light-danger py-2 px-4 fw-bolder me-2" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_chat">Please, Complete your profile</a>
                        </div>
                        @endif
                    </div>
                    
												
                    <form id="kt_account_profile_details_form" class="form"  method="POST" action="{{ url('update-profile-user') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body border-top p-9">
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
                                <div class="col-lg-8">
                                    <div class="image-input image-input-outline" data-kt-image-input="true">
                                        <div class="form-group">
                                            <img id="blah" class="image-input-wrapper w-125px h-125px" src="@if($data['user']->photo!='') {{ asset('public/profile/'.$data['user']->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" style="margin-bottom:5px;border:solid 1px #c2cad8;" /><br>
                                            <input id="upload-img" name="photo"  accept=".png, .jpg, .jpeg" type="file" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                        </div>
                                    </div>
                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Full Name</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="full_name" class="form-control form-control-lg form-control-solid" placeholder="Full Name" value="{{ $data['user']->name }}" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Email
                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Email address must be active"></i></label>
                                <div class="col-lg-8 fv-row">
                                    <input type="hidden" name="email" class="form-control form-control-lg form-control-solid" placeholder="Email" value="{{ $data['user']->email }}" readonly/>
                                    <input type="email" name="email_sf" class="form-control form-control-lg form-control-solid" placeholder="Email" value="{{ $data['user']->email_sf }}" readonly/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="required">Contact Phone</span>
                                </label>
                                <div class="col-lg-8 fv-row">
                                    <input type="tel" name="phone" class="form-control form-control-lg form-control-solid" placeholder="Phone number" value="{{ $data['user']->phone }}" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Department</label>
                                <div class="col-lg-8 fv-row">
                                      <select name="dept" required="" aria-label="Select a Currency" data-control="select2" data-placeholder="Select your Department.." class="form-select form-select-solid form-select-lg" @if($data['user']->dept!='')readonly @endif>
                                        <option value="">Select your Department..</option>
                                        @foreach($data['dept'] as $dept)
                                            @if($dept->id==$data['user']->dept)
                                                <option value="{{ $dept->id }}" selected>{{ $dept->nama }}</option>
                                            @else
                                                <option value="{{ $dept->id }}" @if($data['user']->dept!='') disabled @endif>{{ $dept->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Division</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="division" class="form-control form-control-lg form-control-solid" placeholder="Division" value="{{ $data['user']->division }}"  @if($data['user']->dept!='')readonly @endif/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Title</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="title" class="form-control form-control-lg form-control-solid" placeholder="Title" value="{{ $data['user']->title }}"  @if($data['user']->dept!='')readonly @endif/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Company</label>
                                <div class="col-lg-8 fv-row">
                                     <select name="company" aria-label="Select a Currency" data-control="select2" data-placeholder="Select your Company.." class="form-select form-select-solid form-select-lg" @if($data['user']->company_name!='')readonly @endif>
                                        <option value="">Select your Company..</option>
                                        @foreach($data['company'] as $company)
                                            @if($company->company_name==$data['user']->company_name)
                                                <option value="{{ $company->company_name }}" selected>{{ $company->company_name }}</option>
                                            @else
                                                <option value="{{ $company->company_name }}" @if($data['user']->company_name!='') disabled @endif>{{ $company->company_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
                        </div>
                    </form>
                    <!--end::Card body-->
                </div>
                <!--end::details View-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Layout - Overview-->
    </div>
    <!--end::Post-->
</div>
@endsection