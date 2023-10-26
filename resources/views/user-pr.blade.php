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
                            <th class="min-w-70px">Category</th>
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
                                <span class="fw-bolder">@if($pr->pr_category==1)
                                {{ "License" }}
                                @else
                                {{ "Inventory " }}
                                @endif<br>
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
                                {{ 'Partial Aproved'}}
                                @elseif($pr->status=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif">
                                @if($pr->status=='0')
                                 <div class="badge badge-light-danger">{{ 'New'}}</div>
                                @elseif($pr->status=='1')
                                 <div class="badge badge-light-warning">{{ 'Partial Aproved'}}</div>
                                @elseif($pr->status=='2')
                                 <div class="badge badge-light-success">{{ 'Fully Approved'}}</div>
                                @elseif($pr->status=='3')
                                 <div class="badge badge-light-primary">{{ 'Delivered'}}</div>
                                @elseif($pr->status=='9')
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
                                        <a href="#" onclick="getHistory({{ $pr->pr_id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-pr" class="menu-link px-3">Detail</a>
                                    </div>
                                    
                                    @if($pr->rate=='')
                                        @if($pr->pr_nrp==Auth::user()->email)
                                            @if($pr->status=='2')
                                            <div class="menu-item px-3">
                                                <a href="#" onclick="review({{ $pr->pr_id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#rate-pr" class="menu-link px-3">Rate PR</a>
                                                </div>
                                            </div>
                                            @endif
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
<div class="modal fade" id="rate-pr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <div class="text-center mb-13">
                    <h1 class="mb-3">Closed PR</h1>
                    <div class="text-muted fw-bold fs-5">Please sent
                    <a class="link-primary fw-bolder">Your</a> review about the overall quality of this service</div>
                </div>                
                <form action="{{url('rate-pr')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Your Rate?</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <div class="rate">
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5" title="Sangat Baik">5 stars</label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4" title="Baik">4 stars</label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3" title="Cukup">3 stars</label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2" title="Buruk">2 stars</label>
                            <input type="radio" id="star1" name="rate" value="1" />
                            <label for="star1" title="Sangat Buruk">1 star</label>
                        </div>
                        <br>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Your Review?</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <input type="hidden" name="id" id="pr_id">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="review" placeholder="Type or paste emails here"></textarea>
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
function review(x) {
    // indextr = x.rowIndex;
    console.log(x);
    document.getElementById('pr_id').value=x;
}

function getHistory(val){
    APP_URL = '{{url('/')}}' ;
    $.ajax({
    url: APP_URL+'/pr-approval-detail/' + val,
    type: 'get',
    dataType: 'json',
    success: function(response){
console.log(response['req']);
        $('#historyTablePR').empty(); // Empty >
        $('#detailData').empty(); // Empty >
        var sts = 0;
        if(response['req']['status']==0){
            sts = ' <span class="badge badge-light-danger me-2">New</span>';
        }else if(response['req']['status']==1){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
        }else if(response['req']['status']==2){
            sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
        }else if(response['req']['status']==3){
            sts=' <span class="badge badge-light-primary me-2">Delivered</span>';
        }else if(response['req']['status']==9){
            sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
        }else{ 
            sts=' <span class="badge me-2">-</span>';
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
        if(response['req']['accepted']!=null){
            accepted=response['req']['accepted_name'];
        }else{
            accepted = "<br>";
        }
        if(response['req']['approved']!=null){
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
                                            <td class="d-flex align-items-center pt-6">
                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>`+cat+`</td>
                                            <td class="pt-6">`+inventory+`</td>
                                            <td class="pt-6 text-dark fw-boldest">`+response['req']['qty']+` Unit</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <div class="mw-500px col-sm-6">
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
                                <div class="mw-500px col-sm-6">
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
        $("#historyTablePR").append(` <div class="pull-right">
                            `+sts+`
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
                                <div class="stepper-desc">By `+response['req']['accept_to_name']+`</div>
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
                                <h3 class="stepper-title">Partial Approveda</h3>
                                <div class="stepper-desc">By `+response['req']['approve_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['accepted_date']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['accepted_remark']+`</div>
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
                            <div class="stepper-label" style="width:70%;">
                                <h3 class="stepper-title">Partial Approved</h3>
                                <div class="stepper-desc">By `+response['req']['accept_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['accepted_date']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['accepted_remark']+`</div>
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
                                <h3 class="stepper-title">Fully Approved</h3>
                                <div class="stepper-desc">By `+response['req']['approve_to_name']+`</div>
                                <div class="stepper-desc">At `+response['req']['approved_date']+`</div>
                                <div class="stepper-desc">Note : `+response['req']['approved_remark']+`</div>
                            </div>
                        </div>`+reject;
            
            
        }
        $("#historyTablePR").append(tr_str);
        

        }
    });
}
</script>
@endsection