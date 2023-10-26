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
                                        
                    <div class="row mx-0 mt-3 gy-5 g-xl-8">
                        
                        <div class="card card-flush h-xl-100">
                            
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" id="navbar-menu-history">
                            
                        </ul>
                            <div class="card-header pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-gray-800"> Assets Management </span>
                                </h3>
                                <div class="card-toolbar">
                                    <div class="card-toolbar">
                                        <a class="btn btn-sm btn-success mt-6"
                                    data-bs-toggle="modal" data-bs-target="#kt_modal_submission">Scan</a>
                                    </div>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive" style="max-height: 350px; overflow-y:auto;">
                                    <!--begin::Table-->
                                    <table class="table table-row-dashed align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead class="">
                                            <tr class="fs-7 fw-bold border-0 text-gray-400">
                                                <th>#</th>
                                                <th class="min-w-100px">Request</th>
                                                <th class="text-end min-w-100px">Desc</th>
                                                <th class="text-end min-w-100px">Detail</th>
                                                <th class="text-end min-w-50px">Status</th>
                                                <th class="text-center min-w-70px">Actions</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->

                                        <!--begin::Table body-->
                                        <tbody id="tabledata">
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Body-->
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Index-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Container-->
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
                <div class="fv-row" id="form-approval" style="display: none"> 
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Your Remark?</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <input type="hidden" name="id_req" id="id_req">
                        <input type="hidden" name="action" id="action">
                        <input type="hidden" name="title" id="title">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="remark" id="remark" placeholder="Type your remark here"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                             <span  style="display: none;" id="Loading"  class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                <button class="btn btn-lg btn-danger btn-submit-approval" id="reject_sbmt" type="button"  onclick="ActionApproval('reject')" >Reject</button>
                                <button class="btn btn-lg btn-success btn-submit-approval" id="approval_sbmt" type="button"  onclick="ActionApproval('submit')" style="margin-left: 10px;">Approve</button>
                            
                        </label>
                    </div>
                    <!-- </form> -->
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_submission" tabindex="-1" aria-hidden="true">
<!--begin::Modal dialog-->
    <div class="modal-dialog modal-xl">
    <!--begin::Modal content-->
    <div class="modal-content rounded">
        <!--begin::Modal header-->
        <div class="modal-header justify-content-end border-0 pb-0">
            <!--begin::Close-->
            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                <span class="svg-icon svg-icon-1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="6" y="17.3137" width="16"
                            height="2" rx="1" transform="rotate(-45 6 17.3137)"
                            fill="currentColor" />
                        <rect x="7.41422" y="6" width="16" height="2"
                            rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </div>
            <!--end::Close-->
        </div>
        <!--end::Modal header-->
        <!--begin::Modal body-->
        <div class="modal-body pt-0 pb-15 px-5 px-xl-20">
            <!--begin::Heading-->
            <div class="mb-13 text-center">
                <h1 class="mb-3">Asset Tacking</h1>
                <div class="text-muted fw-semibold fs-5">Scan QR Asset
                    <a href="#" class="link-primary fw-bold">For Update your Asset</a>.
                </div>
            </div>
            <!--end::Heading-->
            <!--begin::Plans-->
            <div class="d-flex flex-column">

                <!--begin::Row-->
                <div class="row mt-10">
                        
                    <div class="col-lg-6 mb-10 mb-lg-0">
                        <div class="w-100" id="form_submission" style="display: block;">
                            <div id="reader" class="h-auto" style="height: 100%"></div>
                            <br>
                            <div class="fv-row mb-10">
                                <div id="custom-search-input">
                                    <div class="input-group col-md-12">
                                        <input id="asset_src" autocomplete="none" onkeyup="searchAsset(this.value)" type="text" class="form-control input-lg" placeholder="Search" />
                                    </div>
                                    <ul class="list-group" id="asset-result" style="max-height: 250px; overflow-y:auto;">
                                    </ul>
                                </div>
                            </div>
                           
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" >
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active" type="submit" onclick="getApprovalWfa()">History Asset</a>
                                </li>
                                <li class="nav-item mt-2">
                                    <div class="table-responsive border-bottom mb-12">
                                    <table class="table mb-3">
                                        <tbody id="tablehistoryasset">
                                           
                                        </tbody>
                                    </table>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div> 
                        <br>
                    <div class="col-lg-6">
                        <!--begin::Tab content-->
                        <div class="tab-content rounded h-100 p-10" style="background-color: #e6f0f7;">
                            <!--begin::Tab Pane-->
                            <form action="{{ url('update-assets-detail') }}" method="POST">
                                                    @csrf
                            <div class="tab-pane fade show active" id="kt_upgrade_plan_startup">
                                <div class="row">
                                    <div class="col fv-row mb-10">
                                        <label class="fs-5 fw-bold mb-2">Update Asset</label><br>
                                        <input type="hidden" name="asset_id" id="asset_id"> <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Asset Number:</div>
                                        <div class="fw-bolder text-gray-800 fs-6" id="asset_num_value">-</div>
                                    </div>
                                    <div class="mb-6">
                                        <div class="fw-bold text-gray-600 fs-7">Asset Description:</div>
                                        <div class="fw-bolder text-gray-800 fs-6"  id="asset_desc_value">-</div>
                                </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col fv-row mb-10">
                                        <label class="required fs-5 fw-bold mb-2">Asset By</label>
                                         <select data-control="select2" class="form-select form-select-solid form-select-lg"
                                            name="pic" required="" id="selectpic" >
                                            <option value="">-</option>
                                        </select>
                                    </div>
                                    <div class="col fv-row mb-10">
                                        <label class="required fs-5 fw-bold mb-2">Asset Status</label>
                                         <select data-control="select2" class="form-select form-select-solid form-select-lg"
                                            name="asset_condition" required="" id="selectstatus">
                                            <option value="">-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                   
                                    <div class="col fv-row mb-10">
                                        <label class="required fs-5 fw-bold mb-2">Room</label>
                                        <select data-control="select2" class="form-select form-select-solid form-select-lg"
                                            name="room" required="" id="selectroom">
                                            <option value="">-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="fv-row mb-10">
                                    <label class="required fs-5 fw-bold mb-2">Note</label>
                                    <textarea class="form-control form-control-lg form-control-solid" name="note" id="note"
                                        placeholder="Type your agenda of room booking" rows="3" required ></textarea>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div> 
                </div>
                    <br>
                        <div class="col-lg-6 fv-row mb-10" style="float:right">
                            <button onclick="mySubmitLoad()"
                                class="submit-button btn btn-lg btn-primary w-100 mb-5">
                                <span id="submit-label" class="indicator-label">Submit
                                    <span id="submit-progress" class="indicator-progress"
                                        style="display: none">Please wait... <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </span>
                            </button>
                        </div>
            </div>
                            </form>
        </div>
    </div>
</div>
</div>
@section('myscript')

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>

        var value = '';
        let config = {
            fps: 10,
            qrbox: 500,
            rememberLastUsedCamera: true,
            
            // aspectRatio: 1.7777778,
            // Only support camera scan type.
            // supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        };

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", config, true);

       function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text or result.
            // console.log(`Scan result: ${decodedText}`, decodedResult);
            // alert(decodedText);
            // window.location.replace("{{ url('trip-check/') }}"+ "/" + decodedText);
            
            cekAsset(decodedText);
            
        }

        function onScanError(errorMessage) {
            // handle on error condition, with error message
            // console.log(`Scan error:` + errorMessage);
        }
        
        html5QrcodeScanner.render(onScanSuccess, onScanError);


        var value = '';

    function cekAsset(id){
        document.getElementById("asset_num_value").innerHTML = '-';
        document.getElementById("asset_desc_value").innerHTML = '-';
        $('#tablehistoryasset').empty();


        APP_URL = '{{url('/')}}' ;
        var selectstatus = $('#selectstatus');
        selectstatus.children().remove().end();
        var selectlocation = $('#selectlocation');
        selectlocation.children().remove().end();
        var selectroom = $('#selectroom');
        selectroom.children().remove().end()
        $.ajax({
            type:"GET",
            url: "{{url('detail-assets')}}/"+id,
            dataType: "json",                  
            success:function(data){
                if(data==[]){
                    alert('QR Result : '+id+' Not Found');
                }else{
                    alert('QR Result : '+id+' ');
                    document.getElementById("asset_num_value").innerHTML = data['req']['asset_num'];
                    document.getElementById("asset_desc_value").innerHTML = data['req']['asset_desc'];
                    document.getElementById("asset_id").value = data['req']['id'];
                    for (var i = 0; i < data['status'].length; i++) {
                        var added = document.createElement('option');
                        if(data['status'][i]['id'] == data['req']['asset_condition']){
                            added.value = data['status'][i]['id'];
                            added.innerHTML = data['status'][i]['name'];
                            added.setAttribute('selected', 'selected');
                        }else{
                            added.value = data['status'][i]['id'];
                            added.innerHTML = data['status'][i]['name'];
                        }
                        selectstatus.append(added);
                    }
                    for (var k = 0; k < data['room'].length; k++) {
                        var added = document.createElement('option');
                        if(data['room'][k]['id'] == data['req']['room']){
                            added.value = data['room'][k]['id'];
                            added.innerHTML = data['room'][k]['id'] +` | `+data['room'][k]['name'];
                            added.setAttribute('selected', 'selected');
                        }else{
                            added.value = data['room'][k]['id'];
                            added.innerHTML = data['room'][k]['id'] +` | `+data['room'][k]['name'];
                        }
                        selectroom.append(added);
                    }
                    for (var l = 0; l < data['user'].length; l++) {
                        var added = document.createElement('option');
                        if(data['user'][l]['id'] == data['req']['pic']){
                            added.value = data['user'][l]['id'];
                            added.innerHTML = data['user'][l]['name'];
                            added.setAttribute('selected', 'selected');
                        }else{
                            added.value = data['user'][l]['id'];
                            added.innerHTML = data['user'][l]['name'];
                        }
                        selectpic.append(added);
                    }

                    for (var m = 0; m < data['history'].length; m++) {
                        console.log(data['history'][m]['id']);
                        var tbl = `<tr class="fw-bolder text-gray-700 fs-5 text-end">
                                            <td class="d-flex align-items-center pt-6">
                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>`+data['history'][m]['name']+`</td>
                                            <td class="pt-6">`+data['history'][m]['location_name']+` - `+data['history'][m]['room_name']+` </td>
                                            <td class="pt-6">`+data['history'][m]['condition_name']+`</td>
                                        </tr>`;

                        $("#tablehistoryasset").append(tbl);
                    }

                }
            }
        });
    }

    function searchAsset(params) {
            document.getElementById("search-loading").style.display = "block";
            console.log('start seaarch');
        $('#asset-result').empty(); // Empty >
        APP_URL = '{{url('/')}}' ;
        $.ajax({
            type:"GET",
            url: "{{url('search-assets')}}/"+params,
            dataType: "json",                  
            success:function(data){
            document.getElementById("search-loading").style.display = "none";
                if(data['req']!=[]){
        $('#asset-result').empty(); // Empty >
                    for (i = 0; i < data['req'].length; i++) {
                        var ul = '<li class="list-group-item"><a type="submit" onclick="cekAsset('+data['req'][i]['asset_num']+')">'+data['req'][i]['asset_num']+' | '+data['req'][i]['asset_sn']+'<br> '+data['req'][i]['asset_desc']+'</a type="submit"></li>';
                        $("#asset-result").append(ul); 
                    }  
                }else{
                    var ul = '<li class="list-group-item">Location Not Found</li>';
                        $("#asset-result").append(ul); 
                }
            }
        });
    }
$(document).ready(function(){
    getAssets();
});

function getAssets(){
    $('#navbar-menu-history').empty();
    // var navbar = `<li class="nav-item mt-2">
    //                             <a class="nav-link text-active-primary ms-0 me-10 py-5 active" type="submit" onclick="getApprovalWfa()">WFA Approval</a>
    //                         </li>
    //                         <li class="nav-item mt-2">
    //                             <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalPr()">PR Approval</a>
    //                         </li>
    //                         <li class="nav-item mt-2">
    //                             <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalTicket()">Ticket Approval</a>
    //                         </li>
    //                         <li class="nav-item mt-2">
    //                             <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit">Form A1 Approval</a>
    //                         </li>`;
    // $("#navbar-menu-history").append(navbar);
    APP_URL = '{{url('/')}}' ;
    $.ajax({
    url: APP_URL+'/get-assets',
    type: 'get',
    dataType: 'json',
    success: function(response){
        $('#tabledata').empty();
        for (var i = 0; i < response['req'].length; i++) {
            // if(response['req'][i]['status']==0){
            //     sts = ' <span class="badge badge-light-danger me-2">New</span>';
            // }else if(response['req'][i]['status']==9){
            //     sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
            // }else if(response['req'][i]['status']!=response['req'][i]['status_level']){
            //     sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            // }else if(response['req'][i]['status']==response['req'][i]['status_level']){
            //     sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
            // }else if(response['req'][i]['status']==9){
            //     sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
            // }else{ 
            //     sts=' <span class="badge me-2">-</span>';
            // }
            // var valkm = "";
            // if(response['req'][i]['distance']!='International'){
            //     valkm = 'KM';
            // }
            var tbl = `<tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">`+(i+1)+`
                                </div>
                            </td>
                            <td>
                                <div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                    <a class="mb-1 text-dark text-hover-primary fw-bold">`+response['req'][i]['asset_num']+`</a>
                                    <div class="fs-7 text-muted fw-bold"> `+response['req'][i]['asset_desc']+`</div>
                                </div>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bolder">`+response['req'][i]['pic']+`</span>
                                <div class="fs-7 text-muted fw-bold">At : `+response['req'][i]['location']+` | `+response['req'][i]['room']+`</div>
                            </td>
                            <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <span class="svg-icon svg-icon-2 text-dark">
                                        `+response['req'][i]['asset_sn']+`
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0" >
                            `+response['req'][i]['asset_condition']+`
                            </td>
                            <td class="text-end">
                                <a  onclick="getDetailApprovalWfa( `+response['req'][i]['id']+`,'wfa','Need to Accept')" 
                                tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-approval" 
                                class="btn btn-sm btn-light btn-active-light-primary" >Detail 
                               </a>
                                    
                            </td>
                        </tr>`;
            $("#tabledata").append(tbl);
        }
    }
    });
};

function getDetailApprovalWfa(val,title,string){
    var action='';
        document.getElementById('id_req').value=val;
        document.getElementById('title').value=title;
    APP_URL = '{{url('/')}}' ;
    AuthUser = '{{ Auth::user()->email }}' ;
    $.ajax({
    url: APP_URL+'/user-submission-detail/' + val,
    type: 'get',
    dataType: 'json',
    success: function(response){
        $('#historyTable').empty(); // Empty >
        $('#detailData').empty(); // Empty >
        var diffdays = getdateCounts(response['req']['start_date'],response['req']['end_date']);
        var sts = 0;
                document.getElementById("form-approval").style.display = "none";
        if(response['req']['status']==0){
            sts = ' <span class="badge badge-light-danger me-2">New</span>';
            if(response['req']['approve_dept_to'] == AuthUser ){
                document.getElementById('action').value='approve_dept';
                document.getElementById("form-approval").style.display = "block";
            }else if(response['req']['approve_div_to'] == AuthUser ){
                document.getElementById('action').value='approve_div';
                document.getElementById("form-approval").style.display = "block";
            }else if(response['req']['approve_dic_to'] == AuthUser ){
                if(response['req']['approve_div'] != null ){
                    document.getElementById('action').value='approve_dic';
                    document.getElementById("form-approval").style.display = "block";
                }else if(response['req']['approve_div_to'] == 'not required' ){
                    document.getElementById('action').value='approve_dic';
                    document.getElementById("form-approval").style.display = "block";
                }else{
                document.getElementById("form-approval").style.display = "none";
                }
            }
        }else if(response['req']['status']==9){
            sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
        }else if(response['req']['status']!=response['req']['status_level']){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            if(response['req']['approve_div_to'] == AuthUser && response['req']['approve_div'] == null ){
                document.getElementById('action').value='approve_div';
                document.getElementById("form-approval").style.display = "block";
            }else{
                if(response['req']['approve_dic_to'] == AuthUser ){
                    document.getElementById('action').value='approve_dic';
                    document.getElementById("form-approval").style.display = "block";
                }
            }
        }else if(response['req']['status']==response['req']['status_level']){
            sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
        }else{ 
            sts=' <span class="badge me-2">-</span>';
        }
        var created_at =  response['req']['created_at'].replace('T', ' ').replace('.000000Z', '');
        var cat=1;
        var inventory="";
        var approved_dept="";
        var approved_div="";
        var dept_assign = "";
        var div_assign = "";
        var dic_assign = "";
        var valkm = "";
        if(response['req']['distance']!='International'){
            valkm = 'KM';
        }
        if(response['req']['approve_dept']!=null){
            approved_dept=response['req']['dept_name'];
        }else{
            approved_dept = "<br>";
        }
        if(response['req']['approve_div']!=null){
            approved_div=response['req']['div_name'];
        }else{
            approved_div = "<br>";
        }
        if(response['req']['approve_dic']!=null){
            approved_dic=response['req']['dic_name'];
        }else{
            approved_dic = "<br>";
        }
        if(response['req']['status']==9){
            if(response['req']['approve_dept']==null){
                    approved_dept="<span style='color: red' >Rejected<span>";
            }else if(response['req']['approve_div']==null){
                    approved_div="<span style='color: red' >Rejected<span>";
            }else if(response['req']['approve_dic']==null){
                    approved_dic="<span style='color: red' >Rejected<span>";
            }
        }
        if(response['req']['approve_dept_to'] != 'not required'){
            dept_assign = `<div class="mw-500px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Department Head Approval:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved_dept+` </div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['req']['dept_title']+` </div>
                                    </div>
                                </div>`;
        }
        if(response['req']['approve_div_to'] != 'not required'){
            div_assign = `<div class="mw-500px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Division Head Approval:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved_div+`</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['req']['div_title']+` </div>
                                    </div>
                                </div>`;
        }
        if(response['req']['approve_dic_to'] != 'not required'){
            dic_assign = `<div class="mw-500px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Director In Charge Approval:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved_dic+`</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['req']['dic_title']+` </div>
                                    </div>
                                </div>`;
        }
        var data_str = `<div class="fw-bolder fs-3 text-gray-800 mb-8">  #SUB`+response['req']['id']+` <span style="float:right"> `+sts+` </span>
                           
                    </div>
                        <div class="row g-5 mb-12">
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Request By:</div>
                                <div class="fw-bolder fs-6 text-gray-800">`+response['req']['name']+`.</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['department']+`</div>
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Permission Date:</div>
                                <div class="fw-bold fs-7 text-gray-800">`+response['req']['start_date']+`  |  `+response['req']['end_date']+` <br> (<strong id="workdays">`+diffdays+`</strong> Work Days) </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Reason:</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['reason']+`</div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="table-responsive border-bottom mb-9">
                                <table class="table mb-3">
                                    <thead>
                                        <tr class="border-bottom fs-6 fw-bolder text-gray-400">
                                            <th class="min-w-175px pb-2">Work Location</th>
                                            <th class="min-w-70px text-end pb-2">Destinantion</th>
                                            <th class="min-w-100px text-end pb-2">Distance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="fw-bolder text-gray-700 fs-5 text-end">
                                            <td class="d-flex align-items-center pt-6">
                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>`+response['req']['worklocation_name']+`</td>
                                            <td class="pt-6">`+response['req']['destination_address']+` </td>
                                            <td class="pt-6 text-dark fw-boldest">`+response['req']['distance']+` `+valkm+`</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end" style="margin-top: 100px">
                                `+dept_assign+`
                                `+div_assign+`
                                `+dic_assign+`
                            </div>
                        </div>`;
        $("#detailData").append(data_str);
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
                                <div class="stepper-desc">By `+response['req']['reject_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['rejected_at']+`</div>
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
                                <div class="stepper-desc">At `+created_at+`</div>
                            </div>
                        </div>`+reject;
        if(response['req']['approve_dept']!=null){
            var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+created_at+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Approved By</h3>
                                <div class="stepper-desc">By `+response['req']['dept_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approve_dept_at']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approve_dept_remark']+`</div>
                            </div>
                        </div>`+reject;
        }
        if(response['req']['approve_div']!=null){
            var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+created_at+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Approved By</h3>
                                <div class="stepper-desc">By `+response['req']['dept_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approve_dept_at']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approve_dept_remark']+`</div>
                            </div>
                        </div>
                        <div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Approved By</h3>
                                <div class="stepper-desc">By `+response['req']['div_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approve_div_at']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approve_div_remark']+`</div>
                            </div>
                        </div>`+reject;
        }
        if(response['req']['approve_dic']!=null){
            var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">New</h3>
                                <div class="stepper-desc">At `+created_at+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Approved By</h3>
                                <div class="stepper-desc">By `+response['req']['dept_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approve_dept_at']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approve_dept_remark']+`</div>
                            </div>
                        </div>
                        <div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Approved By</h3>
                                <div class="stepper-desc">By `+response['req']['div_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approve_div_at']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approve_div_remark']+`</div>
                            </div>
                        </div>
                        <div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Acknowledged By</h3>
                                <div class="stepper-desc">By `+response['req']['dic_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approve_dic_at']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approve_dic_remark']+`</div>
                            </div>
                        </div>`+reject;
        }
        $("#historyTable").append(tr_str);
        

        }
    });
};

function ActionApproval(val){
    var id_req =  document.getElementById("id_req").value;
    var action = document.getElementById("action").value;
    var title = document.getElementById("title").value;
    var remark = document.getElementById("remark").value;
    var token  = $('#token').val();
    document.getElementById("reject_sbmt").style.display = "none";
    document.getElementById("approval_sbmt").style.display = "none";
    document.getElementById("Loading").style.display = "block";

    url = '{{url('/')}}' ;
    if(title=='wfa'){
        url = "{{url('wfa-approval-submit')}}";
    }else if(title=='pr'){
        url = "{{url('pr-approval-submit')}}";
    }else if(title=='ticket'){
        url = "{{url('ticket-approval-submit')}}";
    }
    $.ajax({
        type:"POST",
        url: url,
        data:{id_req:id_req, action:action, remark:remark,submit:val,"_token": token},
        dataType: "json",                  
        success:function(data){
            // getHistory(pr_out_prid);     
            document.getElementById("remark").value = "";
            document.getElementById("reject_sbmt").style.display = "block";
            document.getElementById("approval_sbmt").style.display = "block";
            document.getElementById("Loading").style.display = "none";
            document.getElementById("form-approval").style.display = "none";
            if(title=='wfa'){
            getDetailApprovalWfa(id_req,title);   
                getApprovalWfa();
            }else if(title=='pr'){
            getDetailApprovalPR(id_req,title);   
                getApprovalPR();
            }else if(title=='ticket'){
            getDetailApprovalTicket(id_req,title);   
                getApprovalTicket();
            }
        }
        });
};
       
</script>
@endsection