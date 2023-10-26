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
                        {{-- <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">All</option>
                            <option value="Opened">Opened</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Escalated">Escalated</option>
                            <option value="Closed">Closed</option>
                            <option value="Canceled">Canceled</option>
                        </select> --}}
                    </div>
                    </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th class="min-w-100px">ID Loan</th>
                            <th class="min-w-100px">Agenda</th>
                            <th class="min-w-50px">Room</th>
                            <th class="min-w-50px">Time</th>
                            <th class="text-end min-w-50px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        
                        @php($no=0)
                        @foreach($data['roomLoans'] as $room)
                        @php($no=$no+1)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">{{$no}}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div>
                                            <a class="text-gray-800 text-hover-primary fs-5 fw-bolder" data-kt-ecommerce-product-filter="product_name">#{{ $room->id }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="pe-0">
                                    <span class="fw-bolder">{{ $room->agenda }}</span>
                                </td>
                                <td class="pe-0">
                                    <span class="fw-bolder">{{ $room->ruangan }}</span>
                                </td>
                                <td class="pe-0">
                                    <span class="fw-bolder"> 
                                        @if (date("Y-m-d",strtotime($room->start)) == date("Y-m-d",strtotime($room->end)))
                                            {{ date("d M Y H:i",strtotime($room->start)) }} - {{ date("H:i",strtotime($room->end)) }}
                                        @else
                                            {{ date("d M Y H:i",strtotime($room->start)) }} - {{ date("d M Y H:i",strtotime($room->end)) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="text-end pe-0">
                                    @if ($room->end >= $data['date'])    
                                        <div class="badge badge-light-success" >Active</div>
                                    @else
                                        <div class="badge badge-light-secondary" >Not Active</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions 
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a onclick="getRoom('{{ $room->id }}')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-room" class="menu-link px-3">Detail</a>
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

<div class="modal fade" id="detail-room" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Loan</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
                    <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                        <div class="stepper-nav ps-lg-10" id="historyRoom">
                           
                        </div>
                    </div>
                    <div class="flex-row-fluid py-lg-5 px-lg-15">
                        <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
                            <div class="current" data-kt-stepper-element="content">
                                <div class="w-100">
                                    <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten" id="detailRoomData">
                                       
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
<script>
function getRoom(id){
    APP_URL = '{{url('/detail-user-room')}}' ;
    ASSET_URL = '{{asset('/public')}}' ;
    var date = new Date();
    $.ajax({
    url: APP_URL+'/'+ id,
        type: 'get',
        dataType: 'json',
        success: function(data){
        var len = 0;
        var status;
        if(data['end'] > date){
           status = '<span style="background-color: green; color:white;" class="badge me-2">Active</span>';
        } else {
            status = '<span style="background-color: lightgrey; color:black;" class="badge me-2">Not Active</span>';
        }
        
        if(data != null){
            len = data.length;
        }
                $('#historyRoom').empty(); // Empty >
                $('#detailRoomData').empty(); // Empty >
                var data_str = `<div class="mb-8">`+status+`</div>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">ID Book:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">`+data['id']+`</div>
                                </div>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Agenda:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">`+data['agenda']+`</div>
                                </div>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Room:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">`+data['ruangan']+`</div>
                                </div>
                                <div class="mb-15">
                                    <div class="fw-bold text-gray-600 fs-7">Company</div>
                                    <div class="fw-bolder fs-6 text-gray-800">`+data['perusahaan']+`</div>
                                </div>
                                <div class="mb-6">
                                    
                                    <div class="fw-bold text-gray-600 fs-7">Time</div>
                                    <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">`+formatDateTime(data['start'])+`
                                    <span class="fs-7 text-gray-800 d-flex align-items-center"> <span class="bullet bullet-dot bg-success mx-2"></span>
                                    `+formatDateTime(data['end'])+`</div>
                                </div>
                                <div class="m-0">
                                    <div class="fw-bold text-gray-600 fs-7">By:</div>
                                    <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">`+data['peminjam']+`<br>
                                    <span class="fs-7 text-success d-flex align-items-center">
                                    <span class="bullet bullet-dot bg-success mx-2"></span>`+data['divisi']+`</span></div>
                                </div>`;
                $("#detailRoomData").append(data_str);
                $("#historyRoom").append('<h6 class="mb-8 fw-boldest text-gray-600 text-hover-primary">HISTORY ROOM</h6><div class="stepper-item current" data-kt-stepper-element="nav">');
                
                var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                                    <div class="stepper-line w-40px"></div>
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">
                                        <i class="fas fa-spinner text-white"></i></span>
                                    </div>
                                    <div class="stepper-label">
                                        <h3 class="stepper-title">Created</h3>
                                        <div class="stepper-desc">`+formatDateTime(data['created_at'])+`</div>
                                    </div>
                                </div>`;
                $("#historyRoom").append(tr_str);
        

            }
        });
    }
</script>
@endsection