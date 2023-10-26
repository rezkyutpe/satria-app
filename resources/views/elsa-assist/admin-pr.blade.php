@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <div class="card card-flush">

            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <h2 class="text-end pe-0">List Purchasing Request</h2>
                <div class="pull-right">
                    <form action="{{url('export-pr')}}" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}

                            <div class="d-flex align-items-end">
                                <button type="submit" class="btn btn-primary fs-3 w-100">Export</a>
                            </div>
                        </form>
                </div>
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
                        <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search PR Num" />
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
                            <option value="Delivered">Delivered</option>
                        </select>
                    </div>
                    </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th class="min-w-100px">PR Num</th>
                            <th class="min-w-100px">Message</th>
                            <th class="min-w-70px">PR Name</th>
                            <th class="min-w-50px">Last Modified</th>
                            <th class="text-end min-w-50px">Rating</th>
                            <th class="text-end min-w-50px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                    @php($no=0)
                    @foreach($data['prdata'] as $pr)
                    @php($no=$no+1)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">{{$no}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <div>
                                        <a class="text-gray-800 text-hover-primary fs-5 fw-bolder" data-kt-ecommerce-product-filter="product_name">#{{ $pr->pr_number }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">{{ substr($pr->pr_description,0,50) }}</span>
                            </td>
                            <td class="pe-0" data-order="20">
                                <span class="fw-bolder">{{ $pr->pr_name }}<br>
                                <span style="color: grey;font-size:12px">{{ $pr->inventory_nama }}</span></span>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">{{ date('d F Y',strtotime($pr->created_at)) }}</span>
                            </td>
                            <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <span class="svg-icon svg-icon-2 text-dark">
                                        {{ $pr->rate }} <i class="bi bi-star-fill text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0" data-order="@if($pr->status=='0')
                                {{ 'New'}}
                                @elseif($pr->status=='1')
                                {{ 'Partial Approved'}}
                                @elseif($pr->status=='2')
                                {{ 'Fully Approved'}}
                                @elseif($pr->status=='3')
                                {{ 'Delivered'}}
                                @else
                                {{ '-' }}
                                @endif">
                                @if($pr->status=='0')
                                 <div class="badge badge-light-danger">{{ 'New'}}</div>
                                @elseif($pr->status=='1')
                                 <div class="badge badge-light-warning">{{ 'Partial Approved'}}</div>
                                @elseif($pr->status=='2')
                                 <div class="badge badge-light-success">{{ 'Fully Approved'}}</div>
                                @elseif($pr->status=='3')
                                 <div class="badge badge-light-primary">{{ 'Delivered'}}</div>
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
                                        <a href="#" onclick="getHistory({{ $pr->pr_id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-pr" class="menu-link px-3">Detail</a>
                                    </div>
                                    @if($data['actionmenu']->u==1)
                                    @if($pr->status>='1')
                                    <div class="menu-item px-3">
                                        <a href="#" onclick="deliver({{ $pr->pr_id }},'a')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#deliver-pr" class="menu-link px-3">Deliver PR</a>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
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

<div class="modal fade" id="detail-pr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9">
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
                        <div class="stepper-nav ps-lg-10" id="historyTablePR">
                           
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
            </div>
            </div>
        </div>
    </div>
</div>  
<div class="modal fade" id="deliver-pr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <div class="text-center mb-13">
                    <h1 class="mb-3">Deliver PR</h1>
                </div>                
                <form action="{{url('update-pr')}}" method="post" id="kt_account_profile_details_form" class="form"  enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="row g-5 mb-12">
                        <div class="col-sm-6">
                            <div class="fw-bold fs-7 text-gray-600 mb-1">Request By:</div>
                            <div class="fw-bolder fs-6 text-gray-800" id="pr_emp">Aku</div>
                            <div class="fw-bold fs-7 text-gray-600" id="pr_empdept">AKu dept</div>
                            <div class="fw-bold fs-7 text-gray-600 mb-1">Request To:</div>
                            <div class="fw-bold fs-7 text-gray-800" id="pr_reqto">IT</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="fw-bold fs-7 text-gray-600 mb-1">Message:</div>
                            <div class="fw-bold fs-7 text-gray-600" id="pr_desc"></div>
                            <div class="fw-bold fs-7 text-gray-600 mb-1">Category:</div>
                            <div class="fw-bold fs-7 text-gray-600"><span id="pr_cat">Inventory</span> - <strong id="pr_qty"></strong></div>
                            <div class="fw-bold fs-7 text-gray-600" id="pr_ket">Detail</div> 
                        </div>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-control-label">User Received: * </label>
                        <input type="text" list="list_employee" id="received_by" name="received_by" autocomplete="off" class="form-control" required />
                                                <datalist id="list_employee">
                                                    @foreach($data['emp'] as $emp)
                                                    <option value="{{ $emp['nama'] }}">{{ $emp['nama'] }}</option>
                                                        @endforeach
                                                </datalist> 
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Note</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <input type="hidden" name="pr_id" id="pr_id">
                        <input type="hidden" name="status" id="status" value="3">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="pr_remark" placeholder="Type or paste your note here"></textarea>
                    </div>

                    <div class="form-group text-left">
                        <label class="form-control-label">Approval : </label>
                        <input type="checkbox" name="approval" value="2" checked disabled> Fully Approved
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
        </form>
        </div>
    </div>
</div>      
<script>
    
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
function deliver(x,y) {
    // indextr = x.rowIndex;
    console.log(x);
    document.getElementById('pr_id').value=x;
    APP_URL = '{{url('/')}}' ;
    $.ajax({
    url: APP_URL+'/pr-approval-detail/' + x,
    type: 'get',
    dataType: 'json',
    success: function(response){
    // console.log(response['req']);
    var inventory="";
    if(response['req']['fk_desc']==1){
        inventory=" - ";
        cat = "License";
    }else{
        cat = "Inventory";
        if(response['req']['ket']!=''){
            inventory=response['req']['ket'];
        }
    }
    document.getElementById('pr_desc').innerHTML=response['req']['message'];
    document.getElementById('pr_qty').innerHTML=response['req']['qty']+" Unit";
    document.getElementById('pr_ket').innerHTML=inventory;
    document.getElementById('pr_emp').innerHTML=response['req']['emp_name'];
    document.getElementById('pr_empdept').innerHTML=response['req']['emp_dept'];
    document.getElementById('pr_reqto').innerHTML=response['req']['approval_to'];
    }
    });
}

function getHistory(val){
    APP_URL = '{{url('/')}}' ;
    $.ajax({
    url: APP_URL+'/pr-approval-detail/' + val,
    type: 'get',
    dataType: 'json',
    success: function(response){
        // console.log(response['prreqout']);
        $('#historyTablePR').empty(); // Empty >
        $('#detailData').empty(); // Empty >
        var sts = 0;
        if(response['req']['status']==0){
            sts = ' <span class="badge badge-light-danger me-2">New</span>';
        }else if(response['req']['status']==1){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
        }else if(response['req']['status']==2){
            sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
        }else{ 
            sts=' <span class="badge me-2">-</span>';
        }
        var wa;
        if(response['req']['phone']!=null){
            wa =  `<a data-fslightbox="lightbox-hot-sales" target="_blank" href="https://wa.me/62`+response['req']['phone'].substr(1)+`?text=Hai%20`+response['req']['emp_name']+`%20,Saya%20ingin%20bertanya%20terkait%20PR%20anda%20`+response['req']['fk_id']+`"><span class="badge badge-light-success">Wa.me <i class="bi bi-whatsapp text-success"></i></span></a>`;
        }else{ 
            
            wa ='<span class="badge badge-light-success"><s>Wa.me <i class="bi bi-whatsapp text-success"></i></s></span>';
        }
        var cat=1;
        var inventory="";
        var accepted="";
        var approved="";
        if(response['req']['fk_desc']==1){
            inventory=" - ";
            cat = "License";
        }else{
            cat = "Inventory";
            if(response['req']['ket']!=''){
                inventory=response['req']['ket'];
            }
        }
        if(response['req']['accepted']!=0){
            accepted=response['req']['accepted_name'];
        }else{
            accepted = "<br>";
        }
        if(response['req']['approved']!=0){
            approved=response['req']['approved_name'];
        }else{
            approved = "<br>";
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
                                <div class="fw-bolder fs-6 text-gray-800">Qty : `+response['req']['qty']+` Unit</div>
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Allocation For:</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['pr_to']+`</div>
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Received By:</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['pr_received']+`</div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="table-responsive border-bottom mb-9">
                                <a onclick="addEvent()" id="addevent" class="btn btn-lg btn-success">Add</a></td> 
                                <table class="table mb-3">
                                    <thead>
                                        <tr class="fw-bolder text-gray-700 fs-7 text-end">
                                            <td class="d-flex align-items-center pt-6">#
                                            <td>
                                            <select id="pr_out_inventory" style="display: none;" name="pr_out_inventory" aria-label="Select a Inventory" data-control="select2" data-placeholder="Select a Inventory" class="form-select form-select-solid form-select-lg">
                                                @foreach($data['inventory'] as $inventory)
                                                    <option value="{{ $inventory->inventory_id }}" >{{ $inventory->inventory_nama ." | ".$inventory->inventory_qty }}</option>
                                                @endforeach
                                            </select>
                                            </td>
                                            <td>
                                            <input type="text" list="list_emp" id="pr_out_name" style="display: none;" name="pr_out_name" autocomplete="off" class="form-control" required />
                                                <datalist id="list_emp">
                                                    @foreach($data['emp'] as $emp)
                                                    <option value="{{ $emp['nrp'] }}">{{ $emp['nama'] }}</option>
                                                        @endforeach
                                                </datalist> 
                                            </td>
                                            <td><input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input id="pr_out_prid" style="display: none;width: 70px;" name="pr_out_prid" type="hidden" class="form-control" value="`+val+`">
                                            <input id="pr_out_qty" style="display: none;width: 70px;" name="pr_out_qty" type="number" class="form-control"  value="1"> </td>
                                            <td><input id="pr_out_note" style="display: none;width: 100px;" name="pr_out_note" type="text"  class="form-control" placehoder="Note"></td>
                                            <td class="d-flex align-items-center pt-6"><span  style="display: none;" id="LoadingAddPrOutInventory"  class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            <button class="btn btn-lg btn-success btn-submit-prout" id="pr_out_sbmt" type="button"  onclick="AddPrOutInventory()" style="display: none;">Add</button></td>
                                        </tr>
                                        <tr class="border-bottom fs-6 fw-bolder text-gray-400">
                                            <th class="min-w-20px pb-2">Sts</th>
                                            <th class="min-w-70px pb-2">Inventory</th>
                                            <th class="min-w-70px pb-2">PR to</th>
                                            <th class="min-w-30px">Qty</th>
                                            <th class="min-w-50px">Note</th>
                                            <th class="min-w-30px">#</th>
                                        </tr>
                                        
                                    </thead>
                                    <tbody id="datainventoryout">
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                           
                                <div class="mw-400px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Accept By:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+accepted+` </div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['req']['accept_title']+` </div>
                                    </div>
                                </div>
                                <div class="mw-400px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Approved By:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved+`</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['req']['approve_title']+` </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
        $("#detailData").append(data_str);
        
        for(i=0; i<response['prreqout'].length; i++){
            userpr = response['prreqout'][i]['name'];
        if(response['prreqout'][i]['name']==null){
            userpr = response['prreqout'][i]['user'];
        }
        var datainventoryout = `<tr class="fw-bolder text-gray-700 fs-7 text-end">
                                    <td class="d-flex align-items-center pt-6">
                                    <i class="fa fa-genderless text-danger fs-2 me-2"></i></td>
                                    <td><input id="edit_pr_out_inventory`+response['prreqout'][i]['id']+`" name="edit_pr_out_inventory" type="text"  class="form-control" placehoder="Inventory" readonly value="`+response['prreqout'][i]['inventory_nama']+`"></td>
                                    <td>
                                    <input type="text" list="list_emp"  id="edit_pr_out_name`+response['prreqout'][i]['id']+`" name="edit_pr_out_name"  value="`+userpr+`" autocomplete="off" class="form-control" required />
                                    <datalist id="list_emp">
                                        @foreach($data['emp'] as $emp)
                                                <option value="{{ $emp['nrp'] }}" >{{ $emp['nama'] }}</option>
                                        @endforeach
                                    </datalist> 
                                    </td>
                                    <td><input type="hidden" name="_token" id="token_edit" value="{{ csrf_token() }}">
                                    <input id="edit_pr_out_prid`+response['prreqout'][i]['id']+`" name="edit_pr_out_prid" type="hidden" class="form-control" style="width: 70px;" value="`+val+`">
                                    <input id="edit_pr_out_qty`+response['prreqout'][i]['id']+`" name="edit_pr_out_qty" type="number" class="form-control" style="width: 70px;" value=`+response['prreqout'][i]['qty']+`> </td>
                                    <td><input id="edit_pr_out_note`+response['prreqout'][i]['id']+`" name="edit_pr_out_note" type="text"  class="form-control" style="width: 100px;" placehoder="Note" value="`+response['prreqout'][i]['text']+`"></td>
                                    <td class="d-flex align-items-center pt-6" ><span  style="display: none;" id="LoadingUpdatePrOutInventory`+response['prreqout'][i]['id']+`"  class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    <a id="BtnUpdatePrOutInventory`+response['prreqout'][i]['id']+`" onclick="UpdatePrOutInventory(`+response['prreqout'][i]['id']+`)"><i class="fa fa-paper-plane text-success fs-2 me-2"></i></a> | 
                                    <span  style="display: none; margin-left: 5px;" id="LoadingDeletePrOutInventory`+response['prreqout'][i]['id']+`"  class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    <a style="margin-left: 5px;" id="BtnDeletePrOutInventory`+response['prreqout'][i]['id']+`" onclick="DeletePrOutInventory(`+response['prreqout'][i]['id']+`,`+val+`)"><i class="fa fa-trash text-danger fs-2 me-2"></i></a></td>
                                </tr>`;
          $('#datainventoryout').append(datainventoryout);
        }     
        $("#historyTablePR").append(` <div class="pull-right">
                            `+sts+`
                            <span class="badge badge-light-warning">`+response['req']['rate']+` <i class="bi bi-star-fill text-warning"></i></span>
                        
                       `+wa+`
                        </div><br>`);
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
                            </div>`;
        if(response['req']['accepted']!=0){
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
                                <h3 class="stepper-title">Partial Approveda</h3>
                                <div class="stepper-desc">By `+response['req']['accept_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['accepted_date']+`</div>
                            </div>
                        </div>`;
        }
        if(response['req']['approved']!=0){
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
                                <div class="stepper-desc">to `+response['req']['approve_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approved_date']+`</div>
                            </div>
                        </div>`;
        }
        $("#historyTablePR").append(tr_str);
        

        }
    });
}
function addEvent(){
    var lbl = document.getElementById("addevent").innerHTML;
    if(lbl=='Add'){
        document.getElementById("addevent").innerHTML = "Cancel";
        document.getElementById("pr_out_inventory").style.display = "block";
        document.getElementById("pr_out_name").style.display = "block";
        document.getElementById("pr_out_qty").style.display = "block";
        document.getElementById("pr_out_note").style.display = "block";
        document.getElementById("pr_out_sbmt").style.display = "block";
    }else{
        document.getElementById("addevent").innerHTML = "Add";
        document.getElementById("pr_out_inventory").style.display = "none";
        document.getElementById("pr_out_name").style.display = "none";
        document.getElementById("pr_out_qty").style.display = "none";
        document.getElementById("pr_out_note").style.display = "none";
        document.getElementById("pr_out_sbmt").style.display = "none";
    }
}

function AddPrOutInventory(){
    var pr_out_inventory =  document.getElementById("pr_out_inventory").value;
    var pr_out_name = document.getElementById("pr_out_name").value;
    var pr_out_qty = document.getElementById("pr_out_qty").value;
    var pr_out_note = document.getElementById("pr_out_note").value;
    var pr_out_prid = document.getElementById("pr_out_prid").value;
    var token  = $('#token').val()
    document.getElementById("pr_out_sbmt").style.display = "none";
    document.getElementById("LoadingAddPrOutInventory").style.display = "block";

    APP_URL = '{{url('/')}}' ;
    console.log(APP_URL);
    $.ajax({
        type:"POST",
        url: "{{url('add-pr-inventory-out')}}",
        data:{pr_out_inventory:pr_out_inventory, pr_out_name:pr_out_name, pr_out_qty:pr_out_qty, pr_out_note:pr_out_note, pr_out_prid:pr_out_prid,"_token": token},
        dataType: "json",                  
        success:function(data){
            console.log("success-add-pr-inventory");
            getHistory(pr_out_prid);        
        }
        });
};

function UpdatePrOutInventory(val){
    var edit_pr_out_id =  val;
    var edit_pr_out_name = document.getElementById("edit_pr_out_name"+val).value;
    var edit_pr_out_qty = document.getElementById("edit_pr_out_qty"+val).value;
    var edit_pr_out_note = document.getElementById("edit_pr_out_note"+val).value;
    var edit_pr_out_prid = document.getElementById("edit_pr_out_prid"+val).value;
    var token  = $('#token_edit').val()
    document.getElementById("BtnUpdatePrOutInventory"+val).style.display = "none";
    document.getElementById("LoadingUpdatePrOutInventory"+val).style.display = "block";
    APP_URL = '{{url('/')}}' ;
    console.log(edit_pr_out_id);
    $.ajax({
        type:"POST",
        url: "{{url('update-pr-inventory-out')}}",
        data:{edit_pr_out_id:edit_pr_out_id, edit_pr_out_name:edit_pr_out_name, edit_pr_out_qty:edit_pr_out_qty, edit_pr_out_note:edit_pr_out_note, edit_pr_out_prid:edit_pr_out_prid,"_token": token},
        dataType: "json",                  
        success:function(data){
            getHistory(edit_pr_out_prid);        
        }
        });
};
function DeletePrOutInventory(val,pr){
    var token  = $('#token_edit').val()
    document.getElementById("BtnDeletePrOutInventory"+val).style.display = "none";
    document.getElementById("LoadingDeletePrOutInventory"+val).style.display = "block";
    APP_URL = '{{url('/')}}' ;
    $.ajax({
        type:"POST",
        url: "{{url('delete-pr-inventory-out')}}",
        data:{id:val,"_token": token},
        dataType: "json",                  
        success:function(data){
            console.log(data);
            getHistory(pr);        
        }
        });
};
</script>
@endsection