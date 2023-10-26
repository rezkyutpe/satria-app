<!--begin::Stepper-->
<div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
    <!--begin::Aside-->
    <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
        <!--begin::Nav-->
        <div class="stepper-nav ps-lg-10">
            <!--begin::Step 1-->
            <div class="stepper-item current" data-kt-stepper-element="nav">
                <!--begin::Line-->
                <div class="stepper-line w-40px"></div>
                <!--end::Line-->
                <!--begin::Icon-->
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">1</span>
                </div>
                <!--end::Icon-->
                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">Select Loan</h3>
                    <div class="stepper-desc">Select Loan & Date</div>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Step 1-->
            <!--begin::Step 2-->
            <div class="stepper-item" data-kt-stepper-element="nav">
                <!--begin::Line-->
                <div class="stepper-line w-40px"></div>
                <!--end::Line-->
                <!--begin::Icon-->
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">2</span>
                </div>
                <!--begin::Icon-->
                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">Schedule</h3>
                    <div class="stepper-desc">List Schedule</div>
                </div>
                <!--begin::Label-->
            </div>
            <!--end::Step 2-->
            <!--begin::Step 3-->
            <div class="stepper-item" data-kt-stepper-element="nav">
                <!--begin::Line-->
                <div class="stepper-line w-40px"></div>
                <!--end::Line-->
                <!--begin::Icon-->
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">3</span>
                </div>
                <!--end::Icon-->
                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">Form Loan</h3>
                    <div class="stepper-desc">Input Form Load</div>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Step 3-->
            <!--begin::Step 4-->
            <!--end::Step 4-->
            <!--begin::Step 5-->
            <div class="stepper-item" data-kt-stepper-element="nav">
                <!--begin::Line-->
                <div class="stepper-line w-40px"></div>
                <!--end::Line-->
                <!--begin::Icon-->
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">5</span>
                </div>
                <!--end::Icon-->
                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">Completed</h3>
                    <div class="stepper-desc">Review and Submit</div>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Step 5-->
        </div>
        <!--end::Nav-->
    </div>
    <!--begin::Aside-->
    <!--begin::Content-->
    <div class="flex-row-fluid py-lg-5 px-lg-15">
        <!--begin::Form-->
        <form class="form" action="{{ url('add-loan-action') }}" novalidate="novalidate" id="kt_modal_create_app_form">
            @csrf
            <!--begin::Step 1-->
            <div class="current" data-kt-stepper-element="content">
                <div class="w-100">
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6" onclick="loanRoom()">
                                <!--begin::Option-->
                                <input id="btn_loan_room" type="radio" class="btn-check" name="actionloan" checked="checked" value="room" id="kt_create_account_form_account_type_corporate" />
                                <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="kt_create_account_form_account_type_corporate">
                                    <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                    <span class="svg-icon svg-icon-3x me-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="currentColor" />
                                            <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--begin::Info-->
                                    <span class="d-block fw-bold text-start">
                                        <span class="text-dark fw-bolder d-block fs-4 mb-2">Loan A Room</span>
                                    </span>
                                    <!--end::Info-->
                                </label>
                                <!--end::Option-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-lg-6" onclick="loanCar()">
                                <!--begin::Option-->
                                <input id="btn_loan_car" type="radio" class="btn-check" name="actionloan" value="car" id="kt_create_account_form_account_type_personal" />
                                <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-10" for="kt_create_account_form_account_type_personal">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                                    <span class="svg-icon svg-icon-3x me-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor" />
                                            <path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--begin::Info-->
                                    <span class="d-block fw-bold text-start">
                                        <span class="text-dark fw-bolder d-block fs-4 mb-2">Loan A Car</span>

                                    </span>
                                    <!--end::Info-->
                                </label>
                                <!--end::Option-->
                            </div>
                            <!--end::Col-->
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-5 fw-bold mb-4">
                            <span class="required">Select Date</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Select your app category"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin:Options-->
                        <div id="calendarRoom"></div>
                        <div id="calendarCar" style="display: none"></div>
                        <!--end:Options-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Step 1-->
            <!--begin::Step 2-->
            <div data-kt-stepper-element="content">
                <div class="w-100">
                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-5 fw-bold mb-4">
                            <span id="title_schedule" class="required">List Schedule At </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify your apps framework"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin:Option-->
                        <label id="schedule_continer" class="d-flex flex-column cursor-pointer mb-5">
                            {{-- filled by ajax --}}
                        </label>
                        <!--end::Option-->
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-5 fw-bold mb-4">
                            <span>Are you sure to add loan on this date?</span>
                        </label>
                        <!--end::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Step 2-->
            <!--begin::Step 3-->
            <div data-kt-stepper-element="content">
                <div class="w-100" id="form_car" style="display: block;">
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fs-5 fw-bold mb-2">Subject</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea class="form-control form-control-lg form-control-solid" name="dbname" placeholder="Type your subject of car loan" rows="3"></textarea>
                        <!--end::Input-->
                    </div>
                    <div class="row">
                        <div class="col fv-row mb-10">
                            <!--begin::Label-->
                            <label class="required fs-5 fw-bold mb-2">Destination</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-lg form-control-solid" name="dbname" placeholder="Type ypur destination" />
                            <!--end::Input-->
                        </div>
                        <div class="col fv-row mb-10">
                            <!--begin::Label-->
                            <label class="required fs-5 fw-bold mb-2">Region</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-lg form-control-solid" name="dbname" placeholder="Type your destination region"  />
                            <!--end::Input-->
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-5 fw-bold mb-4">
                            <span class="required">Type of Trip</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""></i>
                        </label>
                        <!--end::Label-->
                        <div class="row">
                            <!--begin:Option-->
                            <label class="col d-flex flex-stack cursor-pointer mb-5">
                                <!--begin::Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin::Input-->
                                    <span class="form-check form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="radio" name="jenis_perjalanan" checked="checked" value="1" onclick="isOneWay()"/>
                                    </span>
                                    <!--end::Input-->
                                    <!--begin::Icon-->
                                    <span class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-arrow-right text-primary fs-2x"></i>
                                        </span>
                                    </span>
                                    <!--end::Icon-->
                                    <!--begin::Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6">One Way</span>
                                    </span>
                                    <!--end::Info-->
                                </span>
                                <!--end::Label-->
                            </label>
                            <!--end::Option-->
                            <!--begin:Option-->
                            <label class="col d-flex flex-stack cursor-pointer mb-5">
                                <!--begin::Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin::Input-->
                                    <span class="form-check form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="radio" name="jenis_perjalanan" value="2" onclick="isRoundTrip()"/>
                                    </span>
                                    <!--end::Input-->
                                    <!--begin::Icon-->
                                    <span class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-exchange-alt text-primary fs-2x"></i>
                                        </span>
                                    </span>
                                    <!--end::Icon-->
                                    <!--begin::Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6">Round Trip</span>
                                    </span>
                                    <!--end::Info-->
                                </span>
                                <!--end::Label-->
                            </label>
                            <!--end::Option-->
                        </div>
                        
                    </div>
                    <!--end::Input group-->
                    <br>
                    <div class="row">
                        <div class="col fv-row mb-10">
                            <!--begin::Label-->
                            <label class="required fs-5 fw-bold mb-2">Leave</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="datetime-local" class="form-control form-control-lg form-control-solid" id="waktu_berangkat" name="waktu_berangkat" placeholder="" />
                            <!--end::Input-->
                        </div>
                        <div id="return" class="col fv-row mb-10" style="display: none">
                            <!--begin::Label-->
                            <label class="required fs-5 fw-bold mb-2">Return</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="datetime-local" class="form-control form-control-lg form-control-solid" name="waktu_pulang" name="waktu_pulang" placeholder="" />
                            <!--end::Input-->
                        </div>
                    </div>
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Passengers 
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Include your name in passengers"></i></label>
                        <!--End::Label-->
                        <!--begin::Tagify-->
                        {{-- <input class="form-control d-flex align-items-center" value="" id="kt_modal_create_campaign_location" data-kt-flags-path="/ceres-html-pro/assets/media/flags/" /> --}}
                        <div class="w-100"> 
                            <select id="passanger_chosen" multiple class="form-control form-control-solid chzn-select" >
                                @foreach ($data['passengers'] as $penumpang)
                                    <option value="{{ $penumpang->email }}" {{ (old('penumpang') == $penumpang->name)? 'selected' : '' }}>
                                        {{ $penumpang->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Tagify-->
                    </div>
                </div>
                <div class="w-100" id="form_room" style="display: block;">
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fs-5 fw-bold mb-2">Agenda</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea class="form-control form-control-lg form-control-solid" name="dbname" placeholder="Type your agenda of room loan" rows="3"></textarea>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-5 fw-bold mb-4">
                            <span class="required">With Company</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        
                        <input class="form-control form-control-lg form-control-solid" name="perusahaan" id="perusahaan" placeholder="" list="companies">
                        <datalist id="companies">
                            @foreach ($data['companies'] as $perusahaan)
                                <option value="{{ $perusahaan->nama }}" {{ (old('perusahaan') == $perusahaan->name)? 'selected' : '' }}>
                            @endforeach
                        </datalist>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <!--begin::Label-->
                    <label class="d-flex align-items-center fs-5 fw-bold mb-4">
                        <span class="required">Room</span>
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""></i>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <div class="row mb-2" data-kt-buttons="true">
                        <!--begin::Col-->
                        <div class="col">
                              @foreach($data['rooms'] as $room)
                                <label class="btn btn-outline btn-outline-dashed btn-outline-default  p-1 m-2" onclick="showFacilites()" >
                                    <input type="radio" class="btn-check" name="assets"  value="{{ $room->id }}" />
                                    <span class="fw-bolder fs-8">{{ $room->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                        <!--end::Col-->
                        <!--begin::Collapse-->
                        <div class="collapse">
                            <div class="card card-body">
                                Facility
                            </div>
                        </div>
                        <!--end::Collapse-->
                    </div>
                    <!--end::Input-->
                    <!--end::Input group-->
                    <br>
                    <div class="row">
                        <div class="col fv-row mb-10">
                            <!--begin::Label-->
                            <label class="required fs-5 fw-bold mb-2">Start</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="datetime-local" class="form-control form-control-lg form-control-solid" id="start" name="start" placeholder="" />
                            <!--end::Input-->
                        </div>
                        <div class="col fv-row mb-10">
                            <!--begin::Label-->
                            <label class="required fs-5 fw-bold mb-2">End</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="datetime-local" class="form-control form-control-lg form-control-solid" id="end" name="end" placeholder="" />
                            <!--end::Input-->
                        </div>
                    </div>
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="fs-5 fw-bold mb-2">Color</label>
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Include your name in passengers"></i></label>
                        <!--End::Label-->
                        <!--begin::Input-->
                            {{-- <input class="form-control d-flex align-items-center" value="" id="kt_modal_create_campaign_location" data-kt-flags-path="/ceres-html-pro/assets/media/flags/" /> --}}
                            <div class="d-flex">
                            <input class="color-picker-custom" type="radio" name="color" id="red" value="#DB2828" {{ (old('color')=='#DB2828')? 'checked' : '' }}/>
                            <label class="label" for="red"><span class="red"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="green" value="#21BA45" {{ (old('color')=='#21BA45')? 'checked' : '' }}/>
                            <label class="label" for="green"><span class="green"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="yellow" value="#FBBD08" {{ (old('color')=='#FBBD08')? 'checked' : '' }}/>
                            <label class="label" for="yellow"><span class="yellow"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="olive" value="#B5CC18" {{ (old('color')=='#B5CC18')? 'checked' : '' }}/>
                            <label class="label" for="olive"><span class="olive"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="orange" value="#F2711C" {{ (old('color')=='#F2711C')? 'checked' : '' }}/>
                            <label class="label" for="orange"><span class="orange"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="teal" value="#00B5AD" {{ (old('color')=='#00B5AD')? 'checked' : '' }}/>
                            <label class="label" for="teal"><span class="teal"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="blue" value="#2185D0" {{ (old('color')=='#2185D0')? 'checked' : '' }}/>
                            <label class="label" for="blue"><span class="blue"></span></label>
    
                            <input class="color-picker-custom" type="radio" name="color" id="violet" value="#6435C9" {{ (old('color')=='#6435C9')? 'checked' : '' }}/>
                            <label class="label" for="violet"><span class="violet"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="purple" value="#A333C8" {{ (old('color')=='#A333C8')? 'checked' : '' }}/>
                            <label class="label" for="purple"><span class="purple"></span></label>
                            
                            <input class="color-picker-custom" type="radio" name="color" id="pink" value="#E03997" {{ (old('color')=='#E03997')? 'checked' : '' }}/>
                            <label class="label" for="pink"><span class="pink"></span></label>
                        </div>
                        <!--end::Input-->
                    </div>
                </div>
            </div>
            <!--end::Step 3-->
            <!--begin::Step 4-->
            <!--end::Step 4-->
            <!--begin::Step 5-->
            <div data-kt-stepper-element="content">
                <div class="w-100 text-center">
                    <!--begin::Heading-->
                    <h1 class="fw-bolder text-dark mb-3">Done!</h1>
                    <!--end::Heading-->
                    <!--begin::Description-->
                    <div class="text-muted fw-bold fs-3">Submit your loan.</div>
                    <!--end::Description-->
                    <!--begin::Illustration-->
                    <div class="text-center p-5">
                        <img src="assets/media/illustrations/dozzy-1/16.png" alt="" class="img-fluid w-50" />
                    </div>
                    <!--end::Illustration-->
                </div>
            </div>
            <!--end::Step 5-->
            <!--begin::Actions-->
            <div class="d-flex flex-stack pt-10">
                <!--begin::Wrapper-->
                <div class="me-2">
                    <button class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                        <span class="svg-icon svg-icon-3 me-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="black" />
                                <path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->Back
                    </button>
                </div>
                <!--end::Wrapper-->
                <!--begin::Wrapper-->
                <div>
                    <button type="submit" class="btn btn-lg btn-primary" data-kt-stepper-action="submit">
                        <span class="indicator-label">Submit
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                        <span class="svg-icon svg-icon-3 ms-2 me-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
                                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon--></span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button id="btn_continue" type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next" style="display: none">Continue
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                        <span class="svg-icon svg-icon-3 ms-1 me-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
                                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </button>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
</div>
<!--end::Stepper-->