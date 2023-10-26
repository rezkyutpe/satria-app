<div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
    <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
        <div class="stepper-nav ps-lg-10" id="historyTms">
            <h6 class="mb-3 text-center fw-boldest text-gray-600 text-hover-primary">HISTORY CAR</h6>
            @if ($trip->status != 0 && $trip->status_trip != 0)    
                <div class="mb-8 text-center" id="qrcode-container">
                    {!! $qrcode !!}
                </div> 
            @endif
            <div class="stepper-item current" data-kt-stepper-element="nav">
                <div class="stepper-line w-40px"></div>
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">
                    <i class="fas fa-spinner text-white"></i></span>
                </div>
                <div class="stepper-label">
                    <h3 class="stepper-title">Waiting Head</h3>
                    <div class="stepper-desc">{{date("d M Y H:i",strtotime($trip->input_time))}}</div>
                </div>
            </div>

           @php
               $statusLength = $trip->status;
           @endphp

           @if ($statusLength != 0)
                @for($i=2; $i <=$statusLength; $i++)
                    @php
                        $state;
                        $date;
                        switch($i){
                            case 2 : $state = "Waiting GAD"; $date = $trip->approve_time; break;
                            case 3 : $state = "Responsed"; $date = $trip->response_time; break;
                            case 4 : $state = "Closed"; $date = $trip->close_time; break;
                        }
                    @endphp
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <div class="stepper-line w-40px"></div>
                        <div class="stepper-icon w-40px h-40px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">
                            <i class="fas fa-spinner text-white"></i></span>
                        </div>
                        <div class="stepper-label">
                            <h3 class="stepper-title">{{ $state }}</h3>
                            <div class="stepper-desc">{{ date("d M Y H:i",strtotime($date)) }}</div>
                        </div>
                    </div>
                @endfor
                @if ($trip->status_trip == 0 && $statusLength == 3)
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <div class="stepper-line w-40px"></div>
                        <div class="stepper-icon w-40px h-40px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">
                            <i class="fas fa-spinner text-white"></i></span>
                        </div>
                        <div class="stepper-label">
                            <h3 class="stepper-title">Canceled</h3>
                            <div class="stepper-desc">{{ date("d M Y H:i",strtotime($trip->cancel_time)) }}</div>
                        </div>
                    </div>
                @endif
           @else
                @if($trip->approve_time != '')
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <div class="stepper-line w-40px"></div>
                        <div class="stepper-icon w-40px h-40px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">
                            <i class="fas fa-spinner text-white"></i></span>
                        </div>
                        <div class="stepper-label">
                            <h3 class="stepper-title">Waiting GAD</h3>
                            <div class="stepper-desc">{{ date("d M Y H:i",strtotime($trip->approve_time)) }}</div>
                        </div>
                    </div>
                @endif
                
                <div class="stepper-item current" data-kt-stepper-element="nav">
                    <div class="stepper-line w-40px"></div>
                    <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">
                        <i class="fas fa-spinner text-white"></i></span>
                    </div>
                    <div class="stepper-label">
                        <h3 class="stepper-title">Rejected</h3>
                        <div class="stepper-desc">{{ date("d M Y H:i",strtotime($trip->reject_time)) }}</div>
                    </div>
                </div>
           @endif

        </div>
    </div>
    <div class="flex-row-fluid py-lg-5 px-lg-15">
        <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
            <div class="current" data-kt-stepper-element="content">
                <div class="w-100">
                    <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten" id="detailTmsData">
                       
                        <div class="mb-8">
                            @switch($trip->status)
                                @case (0) <span style='background-color: red; color:white;' class="badge me-2">Rejected</span> @break;
                                @case (1) <span style='background-color: lightgrey; color:black;' class="badge me-2">Waiting Head</span> @break;
                                @case (2) <span style='background-color: azure; color:white;' class="badge me-2">Waiting GAD</span> @break;
                                @case (3)
                                    @if ($trip->status_trip == 0)
                                        <span style='background-color: red; color:white;' class="badge me-2">Canceled</span> @break;
                                    @else
                                        <span style='background-color:  orange; color:black;' class="badge me-2">Responsed</span> @break;
                                    @endif
                                @case (4) <span style='background-color: green; color:white;' class="badge me-2">Closed</span> @break;
                            @endswitch
                            @if ($trip->kendaraan != '')    
                                @php
                                    $text = "halo *".$trip->supir."*, Perjalanan telah diatur dengan kode trip *".$trip->id_trip."* dan anda diminta sebagai driver untuk mengantar saya ke *".$trip->tujuan.", ".$trip->wilayah."* pada *".date("d M Y H:i",strtotime($trip->departure_time))."* dengan menggunakan *".$trip->kendaraan."(".$trip->nopol.")*.";
                                @endphp
                                @if ($trip->wa_supir != '')
                                    <div class="btn btn-sm btn-icon btn-active-color-success" style="margin-left: 45px;" {{ $trip->wa_supir == ''? 'disabled' : '' }}>
                                        <a data-fslightbox="lightbox-hot-sales" target="_blank" href="https://wa.me/62{{ $trip->wa_supir }}?text={{ $text }}">
                                            <span class="badge badge-light-success">Wa.me <i class="bi bi-whatsapp text-success"></i>
                                            </span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Booking ID:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">{{$trip->id_trip_request}}</div>
                                </div>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Subject:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">{{$trip->keperluan}}</div>
                                </div>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Company</div>
                                    <div class="fw-bolder fs-6 text-gray-800">{{$trip->tujuan.', '.$trip->wilayah}}</div>
                                </div>
                                @if ($trip->status == 0 || $trip->status_trip == 0)    
                                    <div class="mb-15">
                                        <div class="fw-bold text-gray-600 fs-7">Reason</div>
                                        <div class="fw-bolder fs-6 text-gray-800">{{$trip->status == 0 ? $trip->keterangan : $trip->keterangan_cancel}}</div>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Car:</div>
                                    @if ($trip->kendaraan == '')
                                        <div class="fw-bolder text-gray-800 fs-6">{{$trip->set_trip_time != ""? "Grab" : " - " }}</div>
                                    @else
                                        <div class="fw-bolder text-gray-800 fs-6">
                                            {{$trip->kendaraan}}
                                        </div>
                                    @endif
                                </div>
                                @if ($trip->set_trip_time != "" && $trip->kendaraan == "")
                                    <div class="mb-6">
                                        <div class="fw-bold text-gray-600 fs-7">Voucher:</div>
                                        @foreach ($voucher as $v)
                                            <div class="d-flex">
                                                <!--begin::Input-->
                                                <span id="voucher{{ $loop->iteration }}" class="fw-bolder text-gray-800 fs-6 my-auto">{{ $v->kode_voucher }}</span>
                                                <!--end::Input-->
                                    
                                                <!--begin::Button-->
                                                <a class="btn btn-link btn-color-primary btn-active-color-primary ms-auto" onclick="copyToClipboard('voucher{{ $loop->iteration }}', this)" data-clipboard-target="#voucher{{ $loop->iteration }}">
                                                    Copy
                                                </a>
                                                <!--end::Button-->
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mb-6">
                                        <div class="fw-bold text-gray-600 fs-7">Driver:</div>
                                        <div class="d-flex">
                                            <div class="fw-bolder text-gray-800 fs-6 flex-row">
                                                <span>{{$trip->supir != ""? $trip->supir : "-"}}</span>
                                                <span id="driver_phone">{{ $trip->wa_supir != ''? "+62".$trip->wa_supir : '' }}</span>
                                            </div>
                                            @if ($trip->wa_supir != '')
                                                <a class="btn btn-link btn-color-primary btn-active-color-primary ms-auto" onclick="copyToClipboard('driver_phone', this)" data-clipboard-target="#driver_phone">
                                                    Copy
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @php
                                    $passanger = "";
                                    $i = 0;
                                    $penumpangLength = count($penumpang);
                                    foreach ($penumpang as $p) {
                                        if($i != 0 && $i < $penumpangLength){
                                            $passanger = $passanger.", ";
                                        }
                                        $passanger = $passanger.$p->name;
                                        $i++;
                                    }
                                @endphp     
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Passengers:</div>
                                    @if ($trip->penumpang == '')
                                    <div class="fw-bolder text-gray-800 fs-6">-</div>
                                    @else
                                        <ul> 
                                            @foreach (explode(",", $trip->penumpang) as $p)
                                                <li>
                                                    <div class="fw-bolder text-gray-800 fs-6">{{$p}}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="fw-bold text-gray-600 fs-7">Time</div>
                            <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">{{date("d M Y H:i",strtotime($trip->waktu_berangkat))}}
                                @if ($trip->waktu_pulang != "")    
                                    <span class="fs-7 text-gray-800 d-flex align-items-center">
                                    <span class="bullet bullet-dot bg-success mx-2"></span>
                                    {{date("d M Y H:i",strtotime($trip->waktu_pulang))}}
                                @endif
                            </div>
                        </div>
                        <div class="m-0">
                            <div class="fw-bold text-gray-600 fs-7">By:</div>
                            <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">
                                {{$trip->pemohon}}
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
