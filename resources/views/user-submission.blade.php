@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <div class="card card-flush">

            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <h2 class="text-end pe-0">{{ Route::currentRouteName() }}</h2>
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
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th class="min-w-100px">Destinantion</th>
                            <th class="min-w-100px">Reason</th>
                            <th class="min-w-70px">Distance</th>
                            <th class="min-w-70px">Country</th>
                            <th class="min-w-50px">Submission Date</th>
                            <th class="text-end min-w-50px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                    @php($no=0)
                    @foreach($data['submission'] as $res)
                    @php($no=$no+1)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">{{$no}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <div>
                                        <a class="text-gray-800 text-hover-primary fs-5 fw-bolder" data-kt-ecommerce-product-filter="product_name">#{{ $res->destination_address }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">{{ substr($res->reason,0,50) }}</span>
                            </td>
                            <td class="pe-0" data-order="20">
                                <span class="fw-bolder">{{ $res->distance }} @if( $res->distance!="International") KM @endif</span>
                            </td>
                            <td class="pe-0" data-order="20">
                                <span class="fw-bolder">{{ $res->country }}</span>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">{{ date('d F Y',strtotime($res->start_date)) .' - '.date('d F Y',strtotime($res->end_date)) }}</span>
                            </td>
                            <td class="text-end pe-0" data-order="@if($res->status=='0')
                                {{ 'New'}}
                                @elseif($res->status=='1')
                                {{ 'Rejected'}}
                                @elseif($res->status!=$res->status_level)
                                {{ 'Partial Aproved'}}
                                @elseif($res->status==$res->status_level)
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif">
                                @if($res->status=='0')
                                 <div class="badge badge-light-danger">{{ 'New'}}</div>
                                @elseif($res->status=='9')
                                 <div class="badge badge-light-danger">{{ 'Rejected'}}</div>
                                @elseif($res->status!=$res->status_level)
                                 <div class="badge badge-light-warning">{{ 'Partial Approved'}}</div>
                                @elseif($res->status==$res->status_level)
                                 <div class="badge badge-light-success">{{ 'Fully Approved'}}</div>
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
                                        <a href="#" onclick="getHistory({{ $res->id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-submission" class="menu-link px-3">Detail</a>
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

@endsection

@section('myscript')

<div class="modal fade" id="detail-submission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Submission</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
                    <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                        <div class="stepper-nav ps-lg-10" id="historyUserSubmission">
                           
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
<script>
function review(x) {
    // indextr = x.rowIndex;
    console.log(x);
    document.getElementById('id').value=x;
}

function getHistory(val){
    APP_URL = '{{url('/')}}' ;
    AuthUser = '{{ Auth::user()->email }}' ;
    $.ajax({
    url: APP_URL+'/user-submission-detail/' + val,
    type: 'get',
    dataType: 'json',
    success: function(response){
        $('#historyUserSubmission').empty(); // Empty >
        $('#detailData').empty(); // Empty >
        var sts = 0;
         if(response['req']['status']==0){
            sts = ' <span class="badge badge-light-danger me-2">New</span>';
            if(response['req']['approve_dept_to'] == AuthUser ){
            }
        }else if(response['req']['status']==9){
            sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
        }else if(response['req']['status']!=response['req']['status_level']){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
        }else if(response['req']['status']==response['req']['status_level']){
            sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
        }else{ 
            sts=' <span class="badge me-2">-</span>';
        }
        var cat=1;
        var inventory="";
        var approved_dept="";
        var approved_div="";
        var dept_assign = "";
        var div_assign = "";
        var dic_assign = "";
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
                                <div class="fw-bold fs-7 text-gray-800">`+response['req']['start_date']+`  |  `+response['req']['end_date']+`</div>
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
                                            <td class="pt-6 text-dark fw-boldest">`+response['req']['distance']+` KM</td>
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
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
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
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Dept Approved</h3>
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
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Dept Approved</h3>
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
                                <h3 class="stepper-title">Div Approved</h3>
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
                                <div class="stepper-desc">At `+response['req']['created_at']+`</div>
                            </div>
                        </div><div class="stepper-item current" data-kt-stepper-element="nav">
                            <div class="stepper-line w-40px"></div>
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">
                                <i class="fas fa-spinner text-white"></i></span>
                            </div>
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Dept Approved</h3>
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
                                <h3 class="stepper-title">Div Approved</h3>
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
                                <h3 class="stepper-title">Dic Approved</h3>
                                <div class="stepper-desc">By `+response['req']['dic_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approve_dic_at']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approve_dic_remark']+`</div>
                            </div>
                        </div>`+reject;
        }
        $("#historyUserSubmission").append(tr_str);
        

        }
    });
}
</script>
@endsection