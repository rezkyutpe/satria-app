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
                            
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" id="navbar-menu-approval">
                            
                        </ul>
                            <div class="card-header pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-gray-800"> List Approval </span>
                                </h3>
                                <div class="card-toolbar">
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">Status:</label>
                                        </div>
                                        <select class="form-control-sm" >
                                            <option value="all">All</option>
                                            <option value="New">New</option>
                                            <option value="Partial Approved">Partial Approved</option>
                                            <option value="Fully Approved">Fully Approved</option>
                                        </select>
                                    </div>
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">Start date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="startdate_ata"
                                            id="startdate_ata" onchange="handlerFromDateATA(event);">
                                    </div>
                                    <div class="col me-1">
                                        <div class="row">
                                            <label for="">End date:</label>
                                        </div>
                                        <input type="date" class="form-control-sm" name="enddate_ata"
                                            id="enddate_ata" onchange="handlerToDateATA(event);">
                                    </div>
                                    <div class="card-toolbar">
                                        <a class="btn btn-sm btn-light mt-6" id="filter_ata"
                                            onclick="filterATA()">Filter</a>
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
                <!--begin::Card body-->
                <div class="card-body" style="margin-top: -80px">
                                        
                    <div class="row mx-0 mt-3 gy-5 g-xl-8">
                        
                        <div class="card card-flush h-xl-100">
                            
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                            
                        </ul>
                            <div class="card-header d-flex justify-content-end">
                                <form>
                                    @csrf
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        <div class="w-150 mw-150px">
                                            <select class="form-select form-select-solid" name="company_search" id="company_search" data-control="select2" data-hide-search="true" data-placeholder="Company" required>
                                                <option></option>
                                                <option value="patria" @if($company == "patria") selected @endif>Patria</option>
                                                <option value="triatra" @if($company == "triatra") selected @endif>Triatra</option>
                                            </select>
                                        </div>
                                        <div style="width:250px;">
                                            <select class="form-select form-select-solid" name="departement_search" id="departement_search" data-control="select2" data-hide-search="false" data-placeholder="Departement" required>
                                                <option></option>
                                                @foreach($departementForDropdown as $data)
                                                    <option value="{{ $data['position_id'] }}" @if($departement == $data['position_id']) selected @endif>
                                                        {{ $data['pos_name_en'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-150 mw-150px ms-auto">
                                            <input class="datepickeryearonly form-control" name="year_search" id="year_search" value="{{date("Y")}}"type="text" readonly/>
                                        </div>
                                        <div class="w-150 mw-150px">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            
                            <div class="card-body py-3">
                                <!--begin::highcharts-figure-->
                                <figure class="highcharts-figure">
                                    <div id="container"></div>
                                </figure>
                                <!--end::highcharts-figure-->
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

@section('myscript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>

//Tangani load pertama grafik attendance ratio
var newUrl = "{{ url('get-attendant-ratio-grafik') }}";
var departementSelectedValue = $('#departement_search option:selected').text().trim();
var companySelectedValue = $('#company_search option:selected').text().trim();
    var chart, options = {
        chart: {
            renderTo: 'container',
            type: 'column'
        },
        title: {
            text: 'Attendance Ratio',
            align: 'center',
            style: {
                color: '#000',
                fontSize: 20,
                fontWeight: 'bold'
            }
        },
        credits: {
            enabled: false
        },
        series: [{}]
        };
    $.ajax({
        url: newUrl,
        type: 'GET',
        data: {"_token": "{{ csrf_token() }}"},
        dataType: 'json',
        beforeSend: function () {
            // Menampilkan loading animation saat permintaan AJAX sedang berlangsung
            document.getElementById("submitBtn").disabled = true;
            chart = new Highcharts.Chart(options);
            chart.showLoading();
        },
        success: function (data) {
            // Menghilangkan loading animation saat data berhasil dimuat
            chart = new Highcharts.Chart(options);
            chart.hideLoading();
            document.getElementById("submitBtn").disabled = false;

            var Month = [];
            var PRS = [];
            data.forEach((datas) => {
                Month.push(datas.Month);
                PRS.push(datas.PRS);
            });
            
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Attendance Ratio {{$year}}',
                    align: 'center',
                    style: {
                        color: '#000',
                        fontSize: 20,
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: companySelectedValue + ' - ' + departementSelectedValue,
                    style: {
                        color: '#000',
                        fontSize: 20,
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    categories: Month,
                    crosshair: true,
                    accessibility: {
                        description: 'Attendance Ratio (%)'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Attendance Ratio (%)'
                    }
                },
                tooltip: {
                    valueSuffix: ' %'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                credits: {
                    enabled: false
                },
                series: [
                    {
                        name: 'Attendance Ratio (%)',
                        data: PRS
                    }
                ]
            });
        },
        error: function (data) {
            chart = new Highcharts.Chart(options);
            chart.hideLoading();
            document.getElementById("submitBtn").disabled = false;
        }
    });
    
$(document).ready(function(){
	getApprovalWfa();

    $(".datepickeryearonly").datepicker({
        autoclose: true,
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });
    
    // Tangani perubahan pada select company_search
    $('select[name="company_search"]').on('change', function () {
        $('#departement_search').empty();
        var selectedCompany = $(this).val();
        var newUrl = "{{ url('get-departement') }}" +'/'+ selectedCompany;
        // Lakukan permintaan AJAX
        $.ajax({
            url: newUrl,
            type: 'GET',
            data : {"_token":"{{ csrf_token() }}"},
            dataType: 'json',
            success: function (data) {
                $('#departement_search').empty();
                $.each(data, function (key, value) {
                    $('#departement_search').append('<option value="' + value.position_id + '">' + value.pos_name_en + '</option>');
                });
                $('#departement_search').trigger('change');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //Tangani pencarian grafik attendance ratio
    $('form').on('submit', function(event) {
        event.preventDefault(); // Mencegah tindakan submit default form
        var formData = $(this).serialize(); // Mengambil data form
        var departementSelectedValue = $('#departement_search option:selected').text().trim();
        var companySelectedValue = $('#company_search option:selected').text().trim();
        var yearSelectedValue = document.getElementById("year_search").value;
        $.ajax({
            type: "POST",
            url: "{{ url('get-attendant-ratio-grafik') }}",
            data: formData,
            beforeSend: function () {
                // Menampilkan loading animation saat permintaan AJAX sedang berlangsung
                document.getElementById("submitBtn").disabled = true;
                chart = new Highcharts.Chart(options);
                chart.showLoading();
            },
            success: function(response) {
                // Menghilangkan loading animation saat data berhasil dimuat
                chart = new Highcharts.Chart(options);
                chart.hideLoading();
                document.getElementById("submitBtn").disabled = false;

                var Month = [];
                var PRS = [];
                response.forEach((datas) => {
                    Month.push(datas.Month);
                    PRS.push(datas.PRS);
                });
                
                Highcharts.chart('container', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                    text: 'Attendance Ratio ' + yearSelectedValue,
                    align: 'center',
                    style: {
                        color: '#000',
                        fontSize: 20,
                        fontWeight: 'bold'
                    }
                    },
                    subtitle: {
                        text: companySelectedValue + ' - ' + departementSelectedValue,
                        style: {
                            color: '#000',
                            fontSize: 20,
                            fontWeight: 'bold'
                        }
                    },
                    xAxis: {
                        categories: Month,
                        crosshair: true,
                        accessibility: {
                            description: 'Attendance Ratio (%)'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Attendance Ratio (%)'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' %'
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [
                        {
                            name: 'Attendance Ratio (%)',
                            data: PRS
                        }
                    ]
                });
            },
            error: function(xhr, textStatus, errorThrown) {
                // Di sini Anda dapat menangani kesalahan jika terjadi
                console.log(errorThrown);
                chart = new Highcharts.Chart(options);
                chart.hideLoading();
                document.getElementById("submitBtn").disabled = false;
            }
        });
    });
});
    
function getdateCounts(e,f){
        
      let d0 = (f);
      let d1 = (e);
        console.log(d1);

      var holidays = ['2021-08-17','2021-08-11'];
      var startDate = parseDate(d1);
      var endDate = parseDate(d0);  

      var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
      startDate.setHours(0, 0, 0, 1);  // Start just after midnight
      endDate.setHours(23, 59, 59, 999);  // End just before midnight
      var diff = endDate - startDate;  // Milliseconds between datetime objects    
      var days = Math.ceil(diff / millisecondsPerDay);

      // Subtract two weekend days for every week in between
      var weeks = Math.floor(days / 7);
      days -= weeks * 2;

      // Handle special cases
      var startDay = startDate.getDay();
      var endDay = endDate.getDay();
        
      // Remove weekend not previously removed.   
      if (startDay - endDay > 1) {
        days -= 2;
      }
      // Remove start day if span starts on Sunday but ends before Saturday
      if (startDay == 0 && endDay != 6) {
        days--;  
      }
      // Remove end day if span ends on Saturday but starts after Sunday
      if (endDay == 6 && startDay != 0) {
        days--;
      }
      /* Here is the code */
      holidays.forEach(day => {
        if ((day >= d0) && (day <= d1)) {
          /* If it is not saturday (6) or sunday (0), substract it */
          if ((parseDate(day).getDay() % 6) != 0) {
            days--;
          }
        }
      });

    return days+1;
}
function getApprovalWfa(){
    $('#navbar-menu-approval').empty();
    var navbar = `<li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" type="submit" onclick="getApprovalWfa()">WFA Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalPr()">PR Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalTicket()">Ticket Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit">Form A1 Approval</a>
                            </li>`;
    $("#navbar-menu-approval").append(navbar);
    APP_URL = '{{url('/')}}' ;
    $.ajax({
    url: APP_URL+'/approval-submission',
    type: 'get',
    dataType: 'json',
    success: function(response){
        $('#tabledata').empty();
        for (var i = 0; i < response['req'].length; i++) {
            if(response['req'][i]['status']==0){
                sts = ' <span class="badge badge-light-danger me-2">New</span>';
            }else if(response['req'][i]['status']==9){
                sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
            }else if(response['req'][i]['status']!=response['req'][i]['status_level']){
                sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            }else if(response['req'][i]['status']==response['req'][i]['status_level']){
                sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
            }else if(response['req'][i]['status']==9){
                sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
            }else{ 
                sts=' <span class="badge me-2">-</span>';
            }
            var valkm = "";
            if(response['req'][i]['distance']!='International'){
                valkm = 'KM';
            }
            var tbl = `<tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">`+(i+1)+`
                                </div>
                            </td>
                            <td>
                                <div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                    <a class="mb-1 text-dark text-hover-primary fw-bold">`+response['req'][i]['name']+`</a>
                                    <div class="fs-7 text-muted fw-bold">Date : `+response['req'][i]['start_date']+` | `+response['req'][i]['end_date']+`</div>
                                </div>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bolder">`+response['req'][i]['destination_address']+`</span>
                                <div class="fs-7 text-muted fw-bold">Reason : `+response['req'][i]['reason']+`</div>
                            </td>
                            <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <span class="svg-icon svg-icon-2 text-dark">
                                        `+response['req'][i]['distance']+` `+valkm+`
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0" >
                            `+sts+`
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

function getApprovalPr(){
    $('#navbar-menu-approval').empty();
    var navbar = `<li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalWfa()">WFA Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" type="submit" onclick="getApprovalPr()">PR Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalTicket()">Ticket Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" >Form A1 Approval</a>
                            </li>`;
    $("#navbar-menu-approval").append(navbar);
    APP_URL = '{{url('/')}}' ;
    $.ajax({
    url: APP_URL+'/approval-pr',
    type: 'get',
    dataType: 'json',
    success: function(response){
        $('#tabledata').empty();
        for (var i = 0; i < response['req'].length; i++) {
            if(response['req'][i]['status']==0){
                sts = ' <span class="badge badge-light-danger me-2">New</span>';
            }else if(response['req'][i]['status']==1){
                sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            }else if(response['req'][i]['status']==2){
                sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
            }else if(response['req'][i]['status']==3){
                sts=' <span class="badge badge-light-primary me-2">Delivered</span>';
            }else if(response['req'][i]['status']==9){
                sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
            }else{ 
                sts=' <span class="badge me-2">-</span>';
            }
            var tbl = `<tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">`+(i+1)+`
                                </div>
                            </td>
                            <td>
                                <div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                    <a class="mb-1 text-dark text-hover-primary fw-bold">`+response['req'][i]['fk_id']+`</a>
                                    <div class="fs-7 text-muted fw-bold">At : `+response['req'][i]['created_at']+`</div>
                                </div>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bolder">`+response['req'][i]['ket']+`</span>
                                <div class="fs-7 text-muted fw-bold">Reason : `+response['req'][i]['message']+`</div>
                            </td>
                            <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <span class="svg-icon svg-icon-2 text-dark">
                                        `+response['req'][i]['emp_name']+` 
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0" >
                            `+sts+`
                            </td>
                            <td class="text-end">
                                <a  onclick="getDetailApprovalPR( `+response['req'][i]['id_req']+`,'pr','Need to Accept')" 
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
function getApprovalTicket(){
    console.log("ticket");
    $('#navbar-menu-approval').empty();
    var navbar = `<li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalWfa()">WFA Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" onclick="getApprovalPr()">PR Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" type="submit" onclick="getApprovalTicket()">Ticket Approval</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" type="submit" >Form A1 Approval</a>
                            </li>`;
    $("#navbar-menu-approval").append(navbar);
    APP_URL = '{{url('/')}}' ;
    $.ajax({
    url: APP_URL+'/approval-ticket',
    type: 'get',
    dataType: 'json',
    success: function(response){
        console.log(response);
        $('#tabledata').empty();
        for (var i = 0; i < response['req'].length; i++) {
        var created_at =  response['req'][i]['created_at'].replace('T', ' ').replace('.000000Z', '');
            if(response['req'][i]['status']==0){
                sts = ' <span class="badge badge-light-danger me-2">New</span>';
            }else if(response['req'][i]['status']==1){
                sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            }else if(response['req'][i]['status']==2){
                sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            }else if(response['req'][i]['status']==3){
                sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
            }else if(response['req'][i]['status']==9){
                sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
            }else{ 
                sts=' <span class="badge me-2">-</span>';
            }
            var tbl = `<tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">`+(i+1)+`
                                </div>
                            </td>
                            <td>
                                <div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                    <a class="mb-1 text-dark text-hover-primary fw-bold">`+response['req'][i]['fk_id']+`</a>
                                    <div class="fs-7 text-muted fw-bold">At : `+created_at+`</div>
                                </div>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bolder">`+response['req'][i]['fk_desc']+`</span>
                                <div class="fs-7 text-muted fw-bold">Message : `+response['req'][i]['message']+`</div>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bolder">`+response['req'][i]['ket']+`</span>
                                <div class="fs-7 text-muted fw-bold">`+response['req'][i]['dept_submiter']+`</div>
                            </td>
                            <td class="text-end pe-0" >
                            `+sts+`
                            </td>
                            <td class="text-end">
                                <a  onclick="getDetailApprovalTicket( `+response['req'][i]['id_req']+`,'ticket','Need to Accept')" 
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

function getDetailApprovalPR(val,title,string){
	// console.log(val);
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
    if(title=='pr'){
        DETAIL_URL = '/pr-approval-detail/';
        Accept = '';
    }else if(title=='ticket'){
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
    	// console.log(response);
        $('#historyTable').empty(); // Empty >
        $('#detailData').empty(); // Empty >
        var sts = 0;
        if(response['req']['status']==0){
            sts = ' <span class="badge badge-light-danger me-2">New</span>';
            if(response['req']['accept_to'] == AuthUser ){
	            document.getElementById('action').value='accept';
	            document.getElementById("form-approval").style.display = "block";
        	}
        }else if(response['req']['status']==1){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            if(response['req']['approve_to'] == AuthUser ){
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
        var created_at =  response['req']['created_at'].replace('T', ' ').replace('.000000Z', '');
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
                                <div class="stepper-desc">At `+created_at+`</div>
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
                                <div class="stepper-desc">At `+created_at+`</div>
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
                                <div class="stepper-desc">At `+created_at+`</div>
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
                                <div class="stepper-desc">At `+created_at+`</div>
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
                                <div class="stepper-desc">At `+created_at+`</div>
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

function getDetailApprovalTicket(val,title,string){
    console.log('detail-ticket');
        document.getElementById('title').value=title;
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
    DETAIL_URL = '/ticket-approval-detail/';
        ASSET_URL = '{{ asset('/public') }}';
        Accept='';
    $.ajax({
    url: APP_URL+DETAIL_URL + val,
    type: 'get',
    dataType: 'json',
    success: function(response){
        // console.log(response);
        $('#historyTable').empty(); // Empty >
        $('#detailData').empty(); // Empty >
        var sts = 0;
                document.getElementById("form-approval").style.display = "none";
        if(response['req']['status']==0){
            sts = ' <span class="badge badge-light-danger me-2">New</span>';
            if(response['req']['approve_dept_to'] == AuthUser ){
                document.getElementById('action').value='approve_dept';
                document.getElementById("form-approval").style.display = "block";
            }
        }else if(response['req']['status']==9){
            sts=' <span class="badge badge-light-danger me-2">Rejected</span>';
        }else if(response['req']['status']==1){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            if(response['req']['approve_div_to'] == AuthUser ){
                document.getElementById('action').value='approve_div';
                document.getElementById("form-approval").style.display = "block";
            }
        }else if(response['req']['status']==2){
            sts=' <span class="badge badge-light-warning me-2">Partial Approved</span>';
            if(response['req']['approve_dic_to'] == AuthUser ){
                document.getElementById('action').value='approve_dic';
                document.getElementById("form-approval").style.display = "block";
            }
        }else if(response['req']['status']==3){
            sts=' <span class="badge badge-light-success me-2">Fully Approved</span>';
        }else{ 
            sts=' <span class="badge me-2">-</span>';
        }
        var created_at =  response['req']['created_at'].replace('T', ' ').replace('.000000Z', '');
        var cat=1;
        var prTo="";
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
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Approved By:</div>
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
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Approved By:</div>
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
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Acknowledged By:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved_dic+`</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['req']['dic_title']+` </div>
                                    </div>
                                </div>`;
        }
        var data_str = `
        <div class="mb-8">
                        <span style="background-color: ` + response['req']['bg_color'] + `; color:` + response[
                    'req']['fg_color'] + `;" class="badge me-2">` + response['req']['flow_name'] + `</span>
                      <span class="badge badge-light-warning">` + response['req']['rate'] + ` <i class="bi bi-star-fill text-warning"></i></span>
            
                       
                        <div class="btn btn-sm btn-icon btn-active-color-success" style="margin-left: 35px;">
                        <a data-fslightbox="lightbox-hot-sales" href="` + ASSET_URL + `/ticket/` + response['req'][
                    'media'
                ] + `"><span class="badge badge-light-danger">Media <i class="bi bi-eye-fill text-danger"></i></span></a>
                        </div>
                      
                    </div>
                    <div class="fw-bolder fs-3 text-gray-800 mb-8">REQ #`+response['req']['fk_id']+`  <span style="float:right"> `+sts+` </span>
                    </div>
                        <div class="row g-5 mb-12">
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Request By:</div>
                                <div class="fw-bolder fs-6 text-gray-800">`+response['req']['reporter_name']+`.</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['emp_dept']+`</div>
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Subject:</div>
                                <div class="fw-bold fs-7 text-gray-800">`+response['req']['subject']+`</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Message:</div>
                                <div class="fw-bold fs-7 text-gray-600">`+response['req']['message']+`</div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            
                            <div class="d-flex justify-content-end" style="margin-top: 300px">
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
}
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