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
                        <form action="{{ url('report-component-unit') }}" method="post">
                            @csrf
                           <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="col-md-1 mt-2">
                                        <strong>Filter by :</strong>
                                    </div>
                                    <div class="col-md-11 d-flex justify-content-between" id="div_filter">
                                        <input type="hidden" id="request_kolom" value="{{ request()->session()->get('kolom_list_unit') }}" readonly>
                                        <input type="hidden" id="request_hasil" value="{{ json_encode(request()->session()->get('hasil_list_unit')) }}" readonly>
                                        <select name="kolom" id="kolom" class="form-control select2">
                                            <option value="">Filter By</option>
                                            <option value="PLNBEZ">Product Number</option>
                                            <option value="GroupProduct">Group Product</option>
                                            <option value="MATNR">Material Number</option>
                                            <option value="MTART">Material Type</option>
                                            <option value="MATKL">Material Group</option>
                                        </select>
                                        <select name="hasil[]" id="hasil" class="form-control select2" multiple="multiple" disabled></select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('report-component-unit?reset=1') }}" class="btn btn-danger">Reset</a>
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
                                            <td class="text-center">Product Number</td>
                                            <td class="text-center">Product Desc.</td>
                                            <td>Group Product</td>
                                            <td class="text-center">Material Number</td>
                                            <td class="text-center">Material Desc.</td>
                                            <td>Material Type</td>
                                            <td>Material Group</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($no = $data['db']->firstItem())
                                        @foreach ($data['db'] as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td data-toggle="tooltip" data-placement="right" title="{{ $item->product_description }}">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('planning-unit/'.$item->product_number) }}" class="text-dark">
                                                            {{ $item->product_number }}
                                                        </a>    
                                                    @else
                                                        {{ $item->product_number }}
                                                    @endif
                                                </td>
                                                <td class="text-left">{{ $item->product_description }}</td>
                                                <td class="text-left">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('detail-product-ccr?groupProduct='.str_replace(' ', '_', str_replace('&', '^', $item->group_product))) }}" class="text-dark">
                                                            {{ $item->group_product }}
                                                        </a>  
                                                    @else
                                                        {{ $item->group_product }}
                                                    @endif
                                                </td>
                                                <td data-toggle="tooltip" data-placement="right" title="{{ $item->material_description }}" class="text-left">
                                                    <a href="{{ url('material-detail/'.$item->material_number) }}" class="text-dark">
                                                        {{ $item->material_number }}
                                                    </a> 
                                                </td>
                                                <td class="text-left">{{ $item->material_description }}</td>
                                                <td>{{ $item->material_type }}</td>
                                                <td>{{ $item->material_group }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($data['db']->total() > 0)
                                    <div>
                                        <div class="pull-left text-dark">
                                            @php($number = $no-1)
                                            {{ "Showing ".$data['db']->firstItem()." to ".$number." of ".$data['db']->total()." entries" }}
                                        </div>
                                        <div class="pull-right">
                                            {{ $data['db']->links() }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('myscript')
    <script>
        $(document).ready(function() {
            var session_kolom = document.getElementById("request_kolom").value;
            $('#kolom').val(session_kolom).change();

            var session_filter = $('#request_hasil').val();
            // Jika sudah ada filter
            if (session_filter != null) {
                $('#hasil').removeAttr('disabled');
                var data_filter = JSON.parse(session_filter);
                $.ajax({
                    url           : "{{ url('option-component-list') }}",
                    type          : "GET",
                    data:{
                        component_unit_kolom   : session_kolom,
                    },
                    dataType      : "JSON",
                    success: function(data) {
                        $('#hasil').empty();
                        optionSelect(data, data_filter, "#hasil");
                    }
                });
                pasteData("#div_filter", "#hasil > option", "#hasil");
            }

            //  Jika belum ada filter yang masuk ke session
            $("#kolom").change(function() { 
                click           = $(this).val();
                selected_pin    = [];
                $('#hasil').removeAttr('disabled');
                if (click != "") {
                    $.ajax({
                        url                         : "{{ url('option-component-list') }}",
                        type                        : "GET",
                        data:{
                            component_unit_kolom    : click,
                        },
                        dataType                    : "JSON",
                        success: function(data) {
                            $('#hasil').empty();
                            optionSelect(data, selected_pin, "#hasil");
                        }
                    });
                    pasteData("#div_filter", "#hasil > option", "#hasil");
                }
            });

            $('#myTable').DataTable({
                dom: "fBrt",
                fixedHeader: true,
                searching:false,
                columnDefs: [{
                    targets: [2, 5],
                    visible: false
                }],
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> 
                        {
                            text: 'Download',
                            action: function ( e, dt, node, config ) {
                                window.location.href = "{{ url('report-component-unit-download') }}";
                            }
                        }
                    <?php } ?>
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });

            function optionSelect(arr_filter, selected_pin, id) {
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
        });
    </script>
@endsection
