@extends('fe-layouts.master')
@section('content')

<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
    <div class="card card-flush">
        <div style="margin:20px;margin-left: 10px;">
                    <h2>{{ $data['title'] }}</h2></div>
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search ID Req" />
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">All</option>
                            <option value="New">New</option>
                            <option value="Partial Approved">Partial Approved</option>
                            <option value="Fully Approved">Fully Approved</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th class="min-w-100px">ID Req</th>
                            <th class="text-end min-w-100px">Message</th>
                            <th class="text-end min-w-70px">Detail</th>
                            <th class="text-end min-w-50px">Last Modified</th>
                            <th class="text-end min-w-50px">Rating</th>
                            <th class="text-end min-w-50px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                    @php($no=0)
                    @foreach($data['req'] as $req)
                    @php($no=$no+1)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">{{$no}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        <a class="text-gray-800 text-hover-primary fs-5 fw-bolder" data-kt-ecommerce-product-filter="product_name">#{{ $req->fk_id }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bolder">{{ $req->message }}</span>
                            </td>
                            <td class="text-end pe-0" data-order="20">
                                <span class="fw-bolder ms-3">@if($req->fk_desc==1)
                                {{ "License" }}
                                @else
                                {{ "Inventory " }}
                                @endif<br>
                                <span style="color: grey;font-size:12px">{{ $req->ket }}</span></span>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bolder">{{ date('d F Y',strtotime($req->created_at)) }}</span>
                            </td>
                            <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <span class="svg-icon svg-icon-2 text-dark">
                                        {{ $req->rate }} <i class="bi bi-star-fill text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0" data-order="@if($req->status=='0')
                                {{ 'New'}}
                                @elseif($req->status=='1')
                                {{ 'Partial Aproved'}}
                                @elseif($req->status=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif">
                                @if($req->status=='0')
                                 <div class="badge badge-light-danger">{{ 'New'}}</div>
                                @elseif($req->status=='1')
                                 <div class="badge badge-light-warning">{{ 'Partial Aproved'}}</div>
                                @elseif($req->status=='2')
                                 <div class="badge badge-light-success">{{ 'Fully Approved'}}</div>
                                @elseif($req->status=='3')
                                 <div class="badge badge-light-primary">{{ 'Delivered'}}</div>
                                @elseif($req->status=='9')
                                 <div class="badge badge-light-danger">{{ 'Rejected'}}</div>
                                @else
                                 <div class="badge badge-light-danger">{{ '-' }}</div>
                                @endif 
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
                                    @if($req->status=='0')
                                        @if($req->accept_to==Auth::user()->email)
                                        <a onclick="getDetailApproval({{ $req->id_req }},'{{ $data['title'] }}','Need to Accept')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-approval" >Detail</a>
                                        @else
                                        <a  onclick="getDetailApproval({{ $req->id_req }},'{{ $data['title'] }}','Waiting to be Accepted')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-approval">Detail</a>
                                        @endif
                                    @elseif($req->status=='1')
                                        @if($req->approve_to==Auth::user()->email)
                                        <a onclick="getDetailApproval({{ $req->id_req }},'{{ $data['title'] }}','Need to Approve')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-approval" >Detail</a>
                                        @else
                                        <a  onclick="getDetailApproval({{ $req->id_req }},'{{ $data['title'] }}','Waiting to be Approved')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-approval" >Detail</a>
                                        @endif
                                    @else
                                        <a onclick="getDetailApproval({{ $req->id_req }},'{{ $data['title'] }}','-')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-approval" >Detail</a>
                                    @endif
                                    </div>
                                    
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Post-->
</div>
		
@endsection

<div class="modal fade" id="detail-approval" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Approval</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
                    <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                        <div class="stepper-nav ps-lg-10" id="historyTable">
                           
                        </div>
                    </div>
                    
                    <div class="flex-row-fluid py-lg-5 px-lg-15">
                        <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
                            <div class="current" data-kt-stepper-element="content">
                                <div class="w-100">
                                    <div class="mb-0" id="detailData">
                                       
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="fv-row" id="form-approval"> 
                    <!-- <form action="@if($data['title']=='Purchasing Request Approval'){{url('pr-approval-post')}}@elseif($data['title']=='Ticket Request Approval'){{url('ticket-approval-post')}}@endif" method="post" enctype="multipart/form-data">
                    {{csrf_field()}} -->
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Your Remark?</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <input type="hidden" name="id_req" id="id_req">
                        <input type="hidden" name="action" id="action"><input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="remark" id="remark" placeholder="Type your remark here"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                             <span  style="display: none;" id="Loading"  class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            @if($data['title']=='Purchasing Request Approval')
                                <button class="btn btn-lg btn-danger btn-submit-approval" id="reject_sbmt" type="button"  onclick="ActionPrApproval('reject','{{ $data['title'] }}')" >Reject</button>
                                <button class="btn btn-lg btn-success btn-submit-approval" id="approval_sbmt" type="button"  onclick="ActionPrApproval('submit','{{ $data['title'] }}')" style="margin-left: 10px;">Approve</button>
                            @elseif($data['title']=='Ticket Request Approval')
                                <button class="btn btn-lg btn-danger btn-submit-approval" id="reject_sbmt" type="button"  onclick="ActionTicketApproval('reject','{{ $data['title'] }}')" >Reject</button>
                                <button class="btn btn-lg btn-success btn-submit-approval" id="approval_sbmt" type="button"  onclick="ActionTicketApproval('submit','{{ $data['title'] }}')" style="margin-left: 10px;">Approve</button>
                            @endif
                        </label>
                    </div>
                    <!-- </form> -->
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('myscript')
<script>

function getDetailApproval(val,title,string){
    var action='';
    var Accept='';
        document.getElementById('id_req').value=val;
    if(string=='-'){
        document.getElementById("form-approval").style.display = "none";
    }else if(string=='Waiting to be Accepted'){
        document.getElementById("form-approval").style.display = "none";
    }else if(string=='Waiting to be Approved'){
        document.getElementById("form-approval").style.display = "none";
    }else{
        // if(string=='Need to Accept'){
        // document.getElementById('action').value='accept';
        // document.getElementById("form-approval").style.display = "block";
        // }else if(string=='Need to Approve'){
        // document.getElementById('action').value='approve';
        // document.getElementById("form-approval").style.display = "block";
        // }else{
        // document.getElementById("form-approval").style.display = "block";
        // }
    }
    APP_URL = '{{url('/')}}' ;
    AuthUser = '{{ Auth::user()->email }}' ;
    AuthUser2 = '{{ Auth::user()->personal_number }}' ;
    if(title=='Purchasing Request Approval'){
        DETAIL_URL = '/pr-approval-detail/';
        Accept = '';
    }else if(title=='Ticket Request Approval'){
        DETAIL_URL = '/ticket-approval-detail/';
        Accept='';
    }else{
        DETAIL_URL ='';
        Accept='';
    }
    $.ajax({
    url: APP_URL+DETAIL_URL + val,
    type: 'get',
    dataType: 'json',
    success: function(response){

        $('#historyTable').empty(); // Empty >
        $('#detailData').empty(); // Empty >
        var sts = 0;
    console.log(response['req']);
        if(response['req']['status']==0){
            sts = ' <span class="badge badge-light-danger me-2">New</span>';
            if(response['req']['accept_to'] == AuthUser || response['req']['accept_to'] == AuthUser2 ){
	            document.getElementById('action').value='accept';
	            document.getElementById("form-approval").style.display = "block";
        	}
        }else if(response['req']['status']==1){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            if(response['req']['approve_to'] == AuthUser || response['req']['approve_to'] == AuthUser2 ){
	            document.getElementById('action').value='approve';
	            document.getElementById("form-approval").style.display = "block";
	        }	
        }else if(response['req']['status']==2){
            sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
        document.getElementById("form-approval").style.display = "none";
        }else if(response['req']['status']==3){
            sts=' <span class="badge badge-light-primary me-2">Delivered</span>';
        document.getElementById("form-approval").style.display = "none";
        }else if(response['req']['status']==9){
            sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
        document.getElementById("form-approval").style.display = "none";
        }else{ 
            sts=' <span class="badge me-2">-</span>';
        }
        var cat=1;
        var prTo="";
        var inventory="";
        var accepted="";
        var approved="";
        var acceptedtitle="";
        var approvedtitle="";
        if(response['req']['fk_desc']==1){
            inventory=" - ";
            cat = "License";
        }else if(response['req']['fk_desc']==9){
            inventory="Inventory";
            if(response['req']['ket']!=''){
                inventory=response['req']['ket'];
            }
        }else{
            cat = response['req']['fk_desc'];
            if(response['req']['ket']!=''){
                inventory=response['req']['ket'];
            }
        }
        if(response['req']['accepted']!=null){
            accepted=response['req']['accepted_name'];
            acceptedtitle = response['req']['accept_title']; 
        }else{
            accepted = "<br>";
            acceptedtitle =  "<br>";
        }
        if(response['req']['approved']!=null){
            approved=response['req']['approved_name'];
            approvedtitle = response['req']['approve_title'];
        }else{
            approved = "<br>";
            approvedtitle = response['req']['approve_title'];
        }
        if(response['req']['rejected']!=null){
            approved=`<span style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;color: red;">Rejected</span>`;
            approvedtitle = response['req']['approve_title'];
        }else{
            approved = "<br>";
            approvedtitle = response['req']['approve_title'];
        }

        if(title=='Purchasing Request Approval'){
            Accept = ` <div class="mw-500px col-sm-6">
                                        <div class="d-flex flex-stack mb-4">
                                            <div class="fw-bold pe-10 text-gray-600 fs-7">Accept By:</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-4">
                                            <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+accepted+` </div>
                                        </div>
                                        <div class="d-flex flex-stack mb-4">
                                            <div class="fs-8 text-gray-500">`+acceptedtitle+` </div>
                                        </div>
                                    </div>`;
            prTo = response['req']['pr_to'];
        }else if(title=='Ticket Request Approval'){
            Accept='';
        }else{
            Accept='';
        }
        var data_str = `<div class="fw-bolder fs-3 text-gray-800 mb-8">REQ #`+response['req']['fk_id']+`
                    </div>
                        <div class="row g-5 mb-12">
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Request By:</div>
                                <div class="fw-bolder fs-6 text-gray-800">`+response['req']['emp_name']+`.</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['emp_dept']+`</div>
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Request To:</div>
                                <div class="fw-bold fs-7 text-gray-800">`+response['req']['approval_to']+`</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Message:</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['message']+`</div>
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Allocation For:</div>
                                <div class="fw-bold fs-7 text-gray-600">`+prTo+`</div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="table-responsive border-bottom mb-9">
                                <table class="table mb-3">
                                    <thead>
                                        <tr class="border-bottom fs-6 fw-bolder text-gray-400">
                                            <th class="min-w-175px pb-2">Category</th>
                                            <th class="min-w-70px text-end pb-2">Detail</th>
                                            <th class="min-w-100px text-end pb-2">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="fw-bolder text-gray-700 fs-5 text-end">
                                            <td class="d-flex align-items-center pt-6" style="font-size: 12px;">
                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>`+cat+`</td>
                                            <td class="pt-6"  style="font-size: 12px;">`+inventory+`</td>
                                            <td class="pt-6 text-dark fw-boldest"  style="font-size: 12px;">`+response['req']['qty']+` Unit</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                               `+Accept+`
                                <div class="mw-500px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Approved By:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved+`</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+approvedtitle+` </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
        $("#detailData").append(data_str);
        $("#historyTable").append(` <div class="pull-right">
                            `+sts+`
                            <span class="badge badge-light-info">`+string+`</span>
                            <span class="badge badge-light-warning">`+response['req']['rate']+` <i class="bi bi-star-fill text-warning"></i></span>
                        </div><br>`);
        var reject = '';
        if(response['req']['rejected']!=null){
         reject = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-times text-danger fs-2"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Rejected</h3>
                                <div class="stepper-desc">By `+response['req']['rejected']+`</div>
                                <div class="stepper-desc">At `+response['req']['rejected_date']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['rejected_remark']+`</div>
                            </div>
                        </div>`;
        }
        var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div>`+reject;
            var acc = "";
            var apprv = "";
        if(response['req']['accepted']!=null){
            var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">Partial Approved</h3>
                                <div class="stepper-desc">to `+response['req']['accept_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['accepted_date']+`</div>
                            </div>
                        </div>`+reject;
        }else{
            acc = "Need";
             var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-spinner"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">Need Partial Approval</h3>
                                <div class="stepper-desc">by `+response['req']['accept_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['accepted_date']+`</div>
                            </div>
                        </div>`+reject;
        }
        if(response['req']['approved']!=null){
            var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">`+acc+` Partial Approved</h3>
                                <div class="stepper-desc">by `+response['req']['accept_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['accepted_date']+`</div>
                            </div>
                        </div>
                        <div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">Fully Approved</h3>
                                <div class="stepper-desc">by `+response['req']['approve_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approved_date']+`</div>
                            </div>
                        </div>`+reject;
        }else{
            apprv = "Need";
            var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">`+acc+` Partial Approved</h3>
                                <div class="stepper-desc">by `+response['req']['accept_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['accepted_date']+`</div>
                            </div>
                        </div>
                        <div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">`+apprv+` Fully Approved</h3>
                                <div class="stepper-desc">by `+response['req']['approve_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approved_date']+`</div>
                            </div>
                        </div>`+reject;
        }
        $("#historyTable").append(tr_str);
        

        }
    });
}

function ActionPrApproval(val,title){
        var id_req =  document.getElementById("id_req").value;
        var action = document.getElementById("action").value;
        var remark = document.getElementById("remark").value;
        var token  = $('#token').val();
        document.getElementById("reject_sbmt").style.display = "none";
        document.getElementById("approval_sbmt").style.display = "none";
        document.getElementById("Loading").style.display = "block";

        APP_URL = '{{url('/')}}' ;
        console.log(APP_URL);
        $.ajax({
            type:"POST",
            url: "{{url('pr-approval-post-ajax')}}",
            data:{id_req:id_req, action:action, remark:remark,submit:val,"_token": token},
            dataType: "json",                  
            success:function(data){
                console.log("actiob-approval");
                // getHistory(pr_out_prid);     
                getDetailApproval(id_req,title);   
                document.getElementById("remark").value = "";
                document.getElementById("reject_sbmt").style.display = "block";
                document.getElementById("approval_sbmt").style.display = "block";
                document.getElementById("Loading").style.display = "none";
            }
            });
    };
    
function ActionTicketApproval(val,title){
        var id_req =  document.getElementById("id_req").value;
        var action = document.getElementById("action").value;
        var remark = document.getElementById("remark").value;
        var token  = $('#token').val();
        document.getElementById("reject_sbmt").style.display = "none";
        document.getElementById("approval_sbmt").style.display = "none";
        document.getElementById("Loading").style.display = "block";

        APP_URL = '{{url('/')}}' ;
        console.log(APP_URL);
        $.ajax({
            type:"POST",
            url: "{{url('ticket-approval-post-ajax')}}",
            data:{id_req:id_req, action:action, remark:remark,submit:val,"_token": token},
            dataType: "json",                  
            success:function(data){
                console.log("actiob-approval");
                // getHistory(pr_out_prid);     
                getDetailApproval(id_req,title);   
                document.getElementById("remark").value = "";
                document.getElementById("reject_sbmt").style.display = "block";
                document.getElementById("approval_sbmt").style.display = "block";
                document.getElementById("Loading").style.display = "none";
            }
            });
    };
</script>
@endsection