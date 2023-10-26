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
                            {{-- <option value="all">All</option> --}}
                            <option value="all">All</option>
                            <option value="Waiting Head">Waiting Head</option>
                            <option value="Waiting GAD">Waiting GAD</option>
                            <option value="Responsed">Responsed</option>
                            <option value="Closed">Closed</option>
                            <option value="Rejected">Rejected</option>
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
                            <th class="min-w-100px">ID Booking</th>
                            <th class="min-w-100px">Subject</th>
                            <th class="min-w-70px">Type</th>
                            <th class="min-w-70px">Leave</th>
                            <th class="min-w-70px">Return</th>
                            {{-- <th class="min-w-70px">Responser</th> --}}
                            {{-- <th class="text-end min-w-50px">Rating</th> --}}
                            <th class="text-end min-w-50px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        
                    @php($no=0)
                    @foreach($data['carLoans'] as $ticket)
                    @php($no=$no+1)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">{{$no}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <div>
                                        <a class="text-gray-800 text-hover-primary fs-5 fw-bolder" data-kt-ecommerce-product-filter="product_name">#{{ $ticket->id_trip_request }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">{{ $ticket->keperluan}}</span>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">
                                    @switch($ticket->jenis_perjalanan)
                                        @case(1)
                                            {{ "One Way" }}
                                            @break
                                        @case(2)
                                            {{ "Round Trip" }}
                                            @break
                                        @default
                                    @endswitch
                                </span>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">
                                    {{ date("d M Y H:i",strtotime($ticket->waktu_berangkat)) }}
                                </span>
                            </td>
                            <td class="pe-0">
                                <span class="fw-bolder">
                                    {{$ticket->waktu_pulang!=""? date("d M Y H:i",strtotime($ticket->waktu_pulang)):" - " }}
                                </span>
                            </td>
                            {{-- <td class="pe-0" data-order="20">
                                @if ($ticket->responden != null)
                                    <span class="fw-bolder">{{ $ticket->responden }}<br>
                                    <span style="color: grey;font-size:12px">General Services</span></span>
                                @endif
                            </td> --}}
                            {{-- <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <span class="svg-icon svg-icon-2 text-dark">
                                        {{ $ticket->rate }} <i class="bi bi-star-fill text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </td> --}}
                           
                            <td class="text-end pe-0" data-order= @switch($ticket->status)
                                    @case(1)
                                        {{ "Waiting Head" }}
                                        @break
                                    @case(2)
                                        {{ "Waiting GAD" }}
                                        @break
                                    @case(3)
                                        @if($ticket->status_trip == 0)
                                            {{ "Canceled" }}
                                        @else
                                            {{ "Responsed" }}
                                        @endif
                                        @break
                                    @case(4)
                                        {{ "Closed" }}
                                        @break
                                    @case(0)
                                        {{ "Rejected" }}
                                        @break
                                    @default
                                @endswitch>
                                {{-- //status
                                // 0 - rejected
                                // 1 - Waiting Head
                                // 2 - Waiting GAD
                                // 3 - Responded
                                // 4 - Closed --}}
                                @switch($ticket->status)
                                    @case(0)
                                        <div class="badge badge-light-danger">Rejected</div>
                                        @break
                                    @case(1)
                                        <div class="badge badge-light-secondary">Waiting Head</div>
                                        @break
                                    @case(2)
                                        <div class="badge badge-light-primary">Waiting GAD</div>
                                        @break
                                    @case(3)
                                        @if($ticket->status_trip == 0)
                                            <div class="badge badge-light-danger">Canceled</div>
                                        @else
                                            <div class="badge badge-light-warning">Responsed</div>
                                        @endif
                                        @break
                                    @case(4)
                                        <div class="badge badge-light-success">Closed</div>
                                        @break
                                    @case(0)
                                        <div class="badge badge-light-danger">Rejected</div>
                                        @break
                                    @default
                                @endswitch
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
                                        <a href="#" onclick="getTms('{{ $ticket->id_trip_request }}', '{{ $ticket->id_trip }}')"class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-tms" class="menu-link px-3">Detail</a>
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

<div class="modal fade" id="detail-tms" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Booking</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-10" id="container_detail">
                
            </div>
        </div>
    </div>
</div>      
<script>
    function getTms(id, id_trip){
        APP_URL = '{{url('/detail-user-tms')}}' ;
        ASSET_URL = '{{asset('/public')}}' ;
        var date = new Date();
        console.log(id);
        $.ajax({
        url: APP_URL+'/'+ id,
            type: 'get',
            success: function(data){
                $('#container_detail').empty();
                $('#container_detail').append(data);
            }, error: function(xhr, status, error){
                console.log(error);
            }
        });
    }

    // function getTms(id,id_trip){
    //     alert("halo");
    //     APP_URL = '{{url('/detail-user-tms')}}' ;
    //     ASSET_URL = '{{asset('/public')}}' ;
    //     var date = new Date();
    //     console.log(id);
    //     $.ajax({
    //     url: APP_URL+'/'+ id,
    //         type: 'get',
    //         dataType: 'json',
    //         success: function(data){
    //             console.log(data['trip']['kendaraan']);
            
    //         var len = 0;
    //         var status;
    //         var styleStatus;
    //         var kendaraan;
    //         var label;
    //         var labelData = "";
    //         var penumpang = "";
    //         var qrcode;

    //         //status
    //         // 0 - rejected
    //         // 1 - Waiting Head
    //         // 2 - Waiting GAD
    //         // 3 - Responded
    //         // 4 - Closed
            
    //         switch(data['trip']['status']){
    //             case 0: styleStatus ="style='background-color: red; color:white;'"; status="rejected"; break;
    //             case 1: styleStatus ="style='background-color: lightgrey; color:black;'"; status="Waiting Head"; break;
    //             case 2: styleStatus ="style='background-color: azure; color:white;'"; status="Waiting GAD"; break;
    //             case 3: styleStatus ="style='background-color: orange; color:black;'"; status="Responded"; break;
    //             case 4: styleStatus ="style='background-color: green; color:white;'"; status="Closed"; break;
    //         }

    //         if(data['trip']['set_trip_time'] != null && data['trip']['kendaraan'] == null){
    //             kendaraan = "Grab";
    //             label = "Voucher:"

    //             var voucherLength = data['voucher'].length;
    //             for(var i = 0; i < voucherLength; i++){
    //                 labelData = labelData + `<div class="d-flex">
    //                                                 <div>
    //                                                     <span>`+data['voucher'][i]['kode_voucher']+`</span><br>
    //                                                     <input id="voucher`+i+`" value="`+data['voucher'][i]['kode_voucher']+`" style="display: none"><br>
    //                                                 </div>
                                                    
    //                                                 <a class="btn ms-2" onclick="copyToClipboard('voucher`+i+`')" >
    //                                                     <i class="far fa-copy"></i>
    //                                                 </a>
    //                                             </div>` ;
    //             }
    //         } else {
    //             kendaraan = data['trip']['kendaraan']+" ("+data['trip']['nopol']+")" ;
    //             label = "Supir:"
    //             labelData = data['trip']['supir'];
    //             // id_trip = "'"+id_trip+"'";
    //             // console.log(id_trip);
                
    //         }

    //         var penumpangLength = data['penumpang'].length;
    //         for(var i = 0; i < penumpangLength; i++){
    //             if(i != 0 && i < penumpangLength){
    //                 penumpang = penumpang + ", ";
    //             }
    //             penumpang = penumpang + data['penumpang'][i]['name'];
    //         }
            
    //         if(data != null){
    //             len = data.length;
    //         }
    //                 $('#historyTms').empty(); // Empty >
    //                 $('#detailTmsData').empty(); // Empty >
    //                 var data_str = `<div class="mb-8">
    //                                     <span `+styleStatus+` class="badge me-2">`+status+`</span>
    //                                 </div>
    //                                 <div class="row">
    //                                     <div class="col">
    //                                         <div class="mb-6">
    //                                             <div class="fw-bold text-gray-600 fs-7">ID Booking:</div>
    //                                             <div class="fw-bolder text-gray-800 fs-6">`+data['trip']['id_trip_request']+`</div>
    //                                         </div>
    //                                         <div class="mb-6">
    //                                             <div class="fw-bold text-gray-600 fs-7">Subject:</div>
    //                                             <div class="fw-bolder text-gray-800 fs-6">`+data['trip']['keperluan']+`</div>
    //                                         </div>
    //                                         <div class="mb-15">
    //                                             <div class="fw-bold text-gray-600 fs-7">Company</div>
    //                                             <div class="fw-bolder fs-6 text-gray-800">`+data['trip']['tujuan']+', '+data['trip']['wilayah']+`</div>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col">
    //                                         <div class="mb-6">
    //                                             <div class="fw-bold text-gray-600 fs-7">Kendaraan:</div>
    //                                             <div class="fw-bolder text-gray-800 fs-6">`+kendaraan+`</div>
    //                                         </div>
    //                                         <div class="mb-6">
    //                                             <div class="fw-bold text-gray-600 fs-7">`+label+`</div>
    //                                             <div class="fw-bolder text-gray-800 fs-6">`+labelData+`</div>
    //                                         </div>
    //                                         <div class="mb-6">
    //                                             <div class="fw-bold text-gray-600 fs-7">Penumpang:</div>
    //                                             <div class="fw-bolder text-gray-800 fs-6">`+penumpang+`</div>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                                 <div class="mb-6">
    //                                     <div class="fw-bold text-gray-600 fs-7">Time</div>
    //                                     <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">`+formatDateTime(data['trip']['waktu_berangkat'])+`
    //                                     <span class="fs-7 text-gray-800 d-flex align-items-center"> <span class="bullet bullet-dot bg-success mx-2"></span>
    //                                     `+formatDateTime(data['trip']['waktu_pulang'])+`</div>
    //                                 </div>
    //                                 <div class="m-0">
    //                                     <div class="fw-bold text-gray-600 fs-7">By:</div>
    //                                     <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">
    //                                         `+data['trip']['pemohon']+`
    //                                     </div>
    //                                 </div>`;    
    //                 $("#detailTmsData").append(data_str);
    //                 $("#historyTms").append('<h6 class="mb-3 text-center fw-boldest text-gray-600 text-hover-primary">HISTORY CAR</h6>');
                    
    //                 //status
    //                 // 0 - rejected
    //                 // 1 - Waiting Head
    //                 // 2 - Waiting GAD
    //                 // 3 - Responded
    //                 // 4 - Closed

    //                 //waiting head
    //                 var tr_str = `
    //                                 <div class="mb-8 text-center" id="qrcode-container">
    //                                     `+data['qrcode']+`
    //                                 </div>  
    //                                 <div class="stepper-item current" data-kt-stepper-element="nav">
    //                                     <div class="stepper-line w-40px"></div>
    //                                     <div class="stepper-icon w-40px h-40px">
    //                                         <i class="stepper-check fas fa-check"></i>
    //                                         <span class="stepper-number">
    //                                         <i class="fas fa-spinner text-white"></i></span>
    //                                     </div>
    //                                     <div class="stepper-label">
    //                                         <h3 class="stepper-title">Waiting Head</h3>
    //                                         <div class="stepper-desc">`+formatDateTime(data['trip']['input_time'])+`</div>
    //                                     </div>
    //                                 </div>`;
    //                 $("#historyTms").append(tr_str);

    //                 var statusLength = data['trip']['status'];
    //                 if(statusLength != 0){
    //                     for(var i=2; i <=statusLength; i++){
    //                         var state;
    //                         var date;
    //                         switch(i){
    //                             case 2 : state = "Waiting GAD"; date = data['trip']['approve_time']; break;
    //                             case 3 : state = "Responsed"; date = data['trip']['response_time']; break;
    //                             case 4 : state = "Closed"; date = data['trip']['close_time']; break;
    //                         }
    //                         var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
    //                                     <div class="stepper-line w-40px"></div>
    //                                     <div class="stepper-icon w-40px h-40px">
    //                                         <i class="stepper-check fas fa-check"></i>
    //                                         <span class="stepper-number">
    //                                         <i class="fas fa-spinner text-white"></i></span>
    //                                     </div>
    //                                     <div class="stepper-label">
    //                                         <h3 class="stepper-title">`+state+`</h3>
    //                                         <div class="stepper-desc">`+formatDateTime(date)+`</div>
    //                                     </div>
    //                                 </div>`;
    //                         $("#historyTms").append(tr_str);
    //                     } 

    //                 } else {
                        
    //                     //rejected
    //                     var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
    //                                     <div class="stepper-line w-40px"></div>
    //                                     <div class="stepper-icon w-40px h-40px">
    //                                         <i class="stepper-check fas fa-check"></i>
    //                                         <span class="stepper-number">
    //                                         <i class="fas fa-spinner text-white"></i></span>
    //                                     </div>
    //                                     <div class="stepper-label">
    //                                         <h3 class="stepper-title">Rejected</h3>
    //                                         <div class="stepper-desc">`+formatDateTime(data['trip']['reject_time'])+`</div>
    //                                     </div>
    //                                 </div>`;
    //                         $("#historyTms").append(tr_str);
    //                 }


            

    //             }
    //         });
    // }

    // function copyToClipboard(id) {
    //     alert(id);
    //     var val = document.getElementById(id);
    //     val.select();
    //     val.setSelectionRange(0, 99999); /*For mobile devices*/

    //     try {
    //         /* Copy the text inside the text field */
    //         document.execCommand("copy");

    //         /* Copy the text inside the text field */
    //             navigator.clipboard.writeText(val.value);

    //         /* Alert the copied text */
    //         alert("Copied the text: " + val.value);

    //         // $('#redirectGrab').show();

    //         // showAlert('success', 'Salin Kode Voucher', 'Berhasil menyalin kode voucher');
            
    //     } catch (error) {
    //         console.log(error);
    //         // showAlert('danger', 'Salin Kode Voucher', 'Gagal menyalin kode voucher');
    //     }

    // }


</script>
@endsection