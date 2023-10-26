@extends('completeness-component.panel.master')

@section('mycss')
    <style>
        .select2-container .select2-selection {
            height: 50px;
            overflow: auto;
            overflow-x: hidden;
        } 
    </style>
@endsection

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data['title'] }}<small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="container">
                        <form action="{{ url('report-material-open') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-1 mt-2">
                                        <strong>Date :</strong>
                                    </div>
                                    <div class="col-md-11">
                                        <input type="text" name="datefilter" id="datefilter" class="form-control" autocomplete="off" value="" placeholder="Range Date" />
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="col-md-1 mt-2">
                                        <strong>Filter 1 :</strong>
                                    </div>
                                    <div class="col-md-11 d-flex justify-content-between" id="div_filter">
                                        <input type="hidden" value="{{ request()->session()->get('kolom') }}" id="sesi_kolom">
                                        <input type="hidden" value="{{ request()->session()->get('datefilter') }}" id="sesi_date">
                                        <input type="hidden" value="{{ json_encode(request()->session()->get('filter')) }}" id="sesi_filter">
                                        <select name="kolom" id="kolom" class="form-control select2">
                                            <option value="">Filter By</option>
                                            <option value="PLNBEZ">Product Number</option>
                                            <option value="AUFNR">Production Order</option>
                                            <option value="MATNR">Material Number</option>
                                            <option value="MTART">Material Type</option>
                                            <option value="MATKL">Material Group</option>
                                            <option value="MEINS">Base Unit</option>
                                        </select>
                                        <select name="filter[]" id="filter" class="form-control select2-multiple" multiple="multiple"></select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="col-md-1 mt-2">
                                        <strong>Filter 2 :</strong>
                                    </div>
                                    <div class="col-md-11 d-flex justify-content-between" id="div_filter1">
                                        <input type="hidden" value="{{ request()->session()->get('kolom1') }}" id="sesi_kolom1">
                                        <input type="hidden" value="{{ request()->session()->get('datefilter1') }}" id="sesi_date1">
                                        <input type="hidden" value="{{ json_encode(request()->session()->get('filter1')) }}" id="sesi_filter1">
                                        <select name="kolom1" id="kolom1" class="form-control select2">
                                            <option value="">Filter By</option>
                                            <option value="PLNBEZ">Product Number</option>
                                            <option value="AUFNR">Production Order</option>
                                            <option value="MATNR">Material Number</option>
                                            <option value="MTART">Material Type</option>
                                            <option value="MATKL">Material Group</option>
                                            <option value="MEINS">Base Unit</option>
                                        </select>
                                        <select name="filter1[]" id="filter1" class="form-control select2-multiple" multiple="multiple" disabled></select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('report-material-open?reset=1') }}" class="btn btn-danger">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table nowrap table-striped table-bordered"
                                    style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td>Material Number</td>
                                            <td>Description</td>
                                            <td>Mat. Type</td>
                                            <td>Mat. Group</td>
                                            <td>Stock</td>
                                            <td>Minus</td>
                                            <td>Total Minus</td>
                                            <td>on Order</td>
                                            <td>Unit</td>
                                            <td>Production Order</td>
                                            <td>Product Number</td>
                                            <td>Req. Date</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = $data['material']->firstItem();
                                        @endphp
                                        @foreach ($data['material'] as $material)
                                            @php
                                                $on_order = $material->on_order == null ? 0 : $material->on_order;
                                            @endphp
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td data-toggle="tooltip" data-placement="right" title="{{ $material->material_description }}" class="text-left">
                                                    {{ $material->material_number }}
                                                </td>
                                                <td class="text-left">{{ $material->material_description }}</td>
                                                <td>{{ $material->material_type }}</td>
                                                <td>{{ $material->material_group }}</td>
                                                <td data-order="{{ $material->stock }}" data-toggle="tooltip" data-placement='left' title="Detail Stock">
                                                    <a href="#" class="detailStock text-dark"
                                                            data-toggle="modal" data-target="#StockModal"
                                                            data-material-number="{{ $material->material_number }}"
                                                    >
                                                        {{ str_replace('.', ',', $material->stock) }}
                                                    </a>
                                                </td>
                                                <td data-order="{{ $material->kekurangan_stock }}">
                                                    {{ str_replace('.', ',', $material->kekurangan_stock) }}
                                                </td>
                                                <td data-order="{{ $material->sum_kekurangan_stock }}" data-toggle="tooltip" data-placement="left" title="Detail Plotting Stock">
                                                    <a href="#" class="plottingStock text-dark"
                                                        data-toggle="modal" data-target="#plottingStockModal"
                                                        data-material-number="{{ $material->material_number }}"
                                                    >
                                                        {{ str_replace('.',',', round($material->sum_kekurangan_stock, 2)) }}
                                                    </a>
                                                </td>
                                                <td data-order="{{ $on_order }}" data-toggle="tooltip" data-placement="left" title="Detail on Order">
                                                    <a href="{{ url('material-detail/' . $material->material_number) }}" class="text-dark">
                                                        {{ str_replace('.', ',', $on_order) }}
                                                    </a>
                                                </td>
                                                <td>{{ $material->base_unit }}</td>
                                                <td data-order="{{ $material->production_order }}" data-toggle="tooltip" data-placement="left" title="Detail Production Order">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('production-order-planning/'.$material->production_order) }}" class="text-dark">
                                                            {{ $material->production_order }}
                                                        </a>
                                                    @else
                                                        {{ $material->production_order }}
                                                    @endif
                                                </td>
                                                <td data-order="{{ $material->product_number }}" data-toggle="tooltip" data-placement="right" title="{{ $material->product_description }}">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('planning-unit/'.$material->product_number) }}" class="text-dark">
                                                            {{ $material->product_number }}
                                                        </a>
                                                    @else
                                                        {{ $material->product_number }}
                                                    @endif
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($material->requirement_date)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($data['material']->total() > 0)
                                <div>
                                    <div class="pull-left text-dark">
                                        @php($number = $no-1)
                                        {{ "Showing ".$data['material']->firstItem()." to ".$number." of ".$data['material']->total()." entries" }}
                                    </div>
                                    <div class="pull-right">
                                        {{ $data['material']->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal PO-->
    <div class="modal fade" id="modalPO" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><span id="judulPO"></span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table nowrap table-striped text-center" style="width: 100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>PO Number</th>
                                    <th>PO Item</th>
                                    <th>Open Quantity</th>
                                    <th>Delivery Date</th>
                                </tr>
                            </thead>
                            <tbody id="detailDataPO"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('myscript')    
    <script type="text/javascript">        
        $(document).ready(function() {            
            //  SESSION 
            var sesi_date = $('#sesi_date').val();
            if(sesi_date != ''){
                var tanggal = sesi_date.split(" - ");
                $('input[name="datefilter"]').daterangepicker({ 
                    alwaysShowCalendars   : true,
                    autoApply             : true,
                    locale  : {
                        format            : 'DD/MM/YYYY'
                    },
                    startDate             : tanggal[0],
                    endDate               : tanggal[1]
                });
            }

            var session_kolom = $('#sesi_kolom').val();
            if (session_kolom != '') {
                $('#kolom').val(session_kolom).change();
            }
            
            var session_kolom1 = $('#sesi_kolom1').val();
            if (session_kolom1 != '') {
                $('#kolom1').val(session_kolom1).change();
            }

            var session_filter = $('#sesi_filter').val();  
            if (session_filter != null) {
                var rentang_tanggal   = $("#datefilter").val();
                var filter_sesion     = JSON.parse(session_filter);

                if (rentang_tanggal != '') {
                    data    = { range_date : rentang_tanggal, kolom : session_kolom };
                    data_ajax(data, filter_sesion, "#filter");
                }else{
                    data    = { kolom : session_kolom };
                    data_ajax(data, filter_sesion, "#filter");
                }
                pasteData("#div_filter", "#filter > option", "#filter");
            }
            
            var session_filter1 = JSON.parse($('#sesi_filter1').val());
            if (session_filter1 != null) {
                var rentang_tanggal   = $("#datefilter").val();
                var data_filter_awal  = JSON.parse(session_filter);
                $('#filter1').removeAttr('disabled');

                if (rentang_tanggal != '') { 
                    data    = { range_date : rentang_tanggal, kolom_awal : session_kolom, kolom_dua : session_kolom1, filter_awal : data_filter_awal };
                    data_ajax(data, session_filter1, "#filter1");
                } else {
                    data    = { kolom_awal : session_kolom, kolom_dua : session_kolom1, filter_awal : data_filter_awal };
                    data_ajax(data, session_filter1, "#filter1");
                }
                pasteData("#div_filter1", "#filter1 > option", "#filter1");
            }

            // Kolom Filter 1
            $("#kolom").change(function() { 
                selected_pin          = [];
                kolom                 = $(this).val();
                var rentang_tanggal   = $("#datefilter").val();
                $('#filter').removeAttr('disabled');
                $('#filter').empty();
          
                if (kolom != "" || $kolom != []) {
                    if (rentang_tanggal != '') {
                        data    = { range_date : rentang_tanggal, kolom : kolom };
                        data_ajax(data, selected_pin, "#filter");
                    } else {
                        data    = { kolom : kolom };
                        data_ajax(data, selected_pin, "#filter");
                    }
                    pasteData("#div_filter", "#filter > option", "#filter");
                }
            });

            // Kolom Filter 2
            $("#kolom1").change(function() { 
                selected_pin          = [];
                click                 = $(this).val();
                var kolom             = $('#kolom').val();
                var filterawal        = $('#filter').val();
                var rentang_tanggal   = $("#datefilter").val();
                
                $('#filter1').removeAttr('disabled');

                if (click != "") {
                    if (rentang_tanggal != '') {
                        data    = { kolom_awal : kolom, filter_awal : filterawal, kolom_dua : click, range_date : rentang_tanggal };
                        data_ajax(data, selected_pin, "#filter1");
                    } else {
                        data    = { kolom_awal : kolom, filter_awal : filterawal, kolom_dua : click };
                        data_ajax(data, selected_pin, "#filter1")
                    }
                    pasteData("#div_filter1", "#filter1 > option", "#filter1");
                }
            });

            $("#filter").change(function(){
                kolom   = $('#kolom').val();
                filter  = $("#filter").val();
                kolom1  = $('#kolom1').val();
                filter1 = $("#filter1").val();
                // cek apakah kolom filter kedua terisi
                if (kolom1 != '') {
                    $('#filter1').removeAttr('disabled');

                    var range_date = $("#datefilter").val();
                    //  cek apakah kolom date terisi
                    if (range_date != '') {
                        data    = { kolom_awal : kolom, filter_awal : filter, kolom_dua : kolom1, range_date : range_date };
                        data_ajax(data, filter1, "#filter1")
                    } else {
                        data    = { kolom_awal : kolom, filter_awal : filter, kolom_dua : kolom1 };
                        data_ajax(data, filter1, "#filter1")
                    }
                }
            });
            
            $('#myTable').DataTable({
                dom                             : "fBrt",
                // fixedHeader                     : true,
                searching                       : false,
                ordering                        : false,
                scrollCollapse                  : true,
                columnDefs: [{
                    targets                     : [2, 7],
                    visible                     : false
                }],
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> 
                    {
                        text: 'Download',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('report-material-open-download') }}";
                        }
                    }
                    <?php } ?>
                ],
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });   
        });

        function data_ajax(data_filter, selected_pin, id) {
            $.ajax({
                url         : "{{ url('option-material-open-shortlist') }}",
                type        : "GET",
                data        : data_filter,
                dataType    : "JSON",
                success: function(data) {
                    optionSelect(data, selected_pin, id);
                }
            });
        }

        function optionSelect(arr_filter, selected_pin, id) {
            $(id).empty();
            $.each(arr_filter, function(key, element) {
                if ($.inArray(String(element), selected_pin) > -1) {
                    $(id).append($('<option>', {
                        value: element,
                        text: element,
                        selected: true,
                    }));
                } else {
                    $(id).append($('<option>', {
                        value: element,
                        text: element,
                    }));
                }
            });
        }
        
        function pasteData(div_filter, loop_option, id_filter){
            var current_pin = [];
            $(div_filter).on('paste', function (e) {
                var pastedData = e.originalEvent.clipboardData.getData('text').trim();
                tokens = pastedData.split(/\r\n|\r|\n/g); 
                                    
                $(loop_option).each(function(){
                    current_pin.push(this.value);
                });
                
                found_pin = tokens.filter(value => current_pin.includes(value));

                $(id_filter).val(found_pin).trigger("change");
            });
        }        
    </script>

    <script type="text/javascript">
        $(function() {
            $('input[name="datefilter"]').daterangepicker({
                alwaysShowCalendars : true,
                autoApply           : true,
                autoUpdateInput     : false,
                locale: {
                    format          : 'DD/MM/YYYY'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                var tanggal = $('input[name="datefilter"]').val();
                kolom           = $("#kolom").val();
                filter          = $("#filter").val();
                kolom1          = $("#kolom1").val();
                filter1         = $("#filter1").val();
                if (tanggal != "") {
                    if (kolom != '') {
                        data    = { range_date : tanggal, kolom : kolom };
                        id      = "#filter";
                        data_ajax(data, filter, id);
                        pasteData("#div_filter", "#filter > option", "#filter");
                    }
                    if (kolom1 != '') {
                        data    = { range_date : tanggal, kolom_awal : kolom, filter_awal : filter, kolom_dua : kolom1 };
                        id      = "#filter1";
                        data_ajax(data, filter1, id)
                        pasteData("#div_filter1", "#filter1 > option", "#filter1");
                    }
                }
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>

@endsection
