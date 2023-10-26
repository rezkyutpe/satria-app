@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search ID Ticket" />
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">All</option>
                            <option value="Opened">Opened</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Escalated">Escalated</option>
                            <option value="Closed">Closed</option>
                            <option value="Canceled">Canceled</option>
                        </select>
                    </div>
                    </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th class="min-w-100px">ID Ticket</th>
                            <th class="min-w-100px">Subject</th>
                            <th class="min-w-70px">Assister</th>
                            <th class="min-w-50px">Last Modified</th>
                            <th class="text-end min-w-50px">Rating</th>
                            <th class="text-end min-w-50px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        
                    @php($no=0)
                    @foreach($data['ticketdata'] as $ticket)
                    @php($no=$no+1)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">{{$no}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <div>
                                        <a class="text-gray-800 text-hover-primary fs-5 fw-bolder" data-kt-ecommerce-product-filter="product_name">#{{ $ticket->ticket_id }}</a>
                                        @if($ticket->status <=3) 
                                            @if($ticket->status ==3) 
                                            <div class="badge badge-light-primary">
                                                {{ "Fully Approved" }} 
                                            </div> 
                                            @else
                                             <div class="badge badge-light-danger">
                                                {{ "Need to Approved" }} 
                                            </div> 
                                            @endif 
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">{{ $ticket->subject }}</span>
                            </td>
                            <td class="pe-0" data-order="20">
                                <span class="fw-bolder">{{ $ticket->assist_name }}<br>
                                <span style="color: grey;font-size:12px">{{ $ticket->dept_assist }}</span></span>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder"> {{ date('d F Y',strtotime($ticket->created_at))}}</span>
                            </td>
                            <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <span class="svg-icon svg-icon-2 text-dark">
                                        {{ $ticket->rate }} <i class="bi bi-star-fill text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0" data-order="{{ $ticket->flow_name}}">
                                <div class="badge badge-light-primary" style="background-color: {{ $ticket->bg_color }}; color: {{ $ticket->fg_color }};">{{ $ticket->flow_name}}</div>
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
                                        <a href="#" onclick="getHistory({{ $ticket->id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-ticket" class="menu-link px-3">Detail</a>
                                    </div>
                                    
                                    @if($ticket->rate=='')
                                        @if($ticket->flag=='5' || $ticket->flag=='3')
                                        <div class="menu-item px-3">
                                            <a href="#" onclick="review({{ $ticket->id }})"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#rate-ticket" class="menu-link px-3">@if($ticket->flag=='5' ) Rate Ticket  @else Close Ticket @endif</a>
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

<div class="modal fade" id="detail-ticket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Ticket</h2>
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
                                    <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten" id="detailTicketData">
                                       
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
<div class="modal fade" id="rate-ticket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <div class="text-center mb-13">
                    <h1 class="mb-3">Closed Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Please sent
                    <a class="link-primary fw-bolder">Your</a> review about the overall quality of this service</div>
                </div>                
                <form action="{{url('rate-ticket')}}" method="post" enctype="multipart/form-data">
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
                        <input type="hidden" name="id" id="ticket_id">
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
    document.getElementById('ticket_id').value=x;
}

</script>
@endsection