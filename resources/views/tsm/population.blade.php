@extends('cogs.panel.master')
@section('mycss')
<style>
::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 7px;
}

::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(0, 0, 0, .25);
    -webkit-box-shadow: 0 0 1px rgba(255, 255, 255, .25);
    box-shadow: 0 0 1px rgba(255, 255, 255, .25);
}

</style>
@endsection

@section('content')
<div class="clearfix"></div>
<div class="row">

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Population</h2>
                @if($actionmenu->u==1)
                <a class="btn btn-primary btn-sm pull-right addPopulation" data-toggle="modal" href="#">
                    <i class="fa fa-plus"></i> New Population
                </a>

                <a class="btn btn-warning mr-2 btn-sm pull-right" data-toggle="modal" data-target="#Import" href="#">
                    <i class="fa fa-upload"></i> Import Population
                </a>
                
                <a class="btn btn-refresh btn-success mr-2 btn-sm pull-right" href="{{ url('tsm-import-population') }}">Update
                    Data <i class="fa fa-refresh">
                    </i>
                </a>
                
                @endif
                <div class="clearfix"></div>
            </div>
            @if(session()->has('err_message'))
            <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('err_message') }}
            </div>
            @endif
            @if(session()->has('suc_message'))
            <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('suc_message') }}
            </div>
            @endif
            <div class="x_content">
                <div class="row content-table">
                    <div class="col-sm-12 ">
                        <div class="card-box">
                            <table id="datatable-visibility-population"
                                class="table text-center table-striped table-bordered nowrap" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Serial Number</th>
                                        <th>Description</th>
                                        <th>Part Number</th>
                                        <th>Area</th>
                                        <th>Plant</th>
                                        <th>End Customer</th>
                                        {{-- <th>Jenis Service</th> --}}
                                        <th>Customer</th>
                                        <th>Deliv Date</th>
                                        <th>Commisioning Date</th>
                                        <th>General Category</th>
                                        <th>Last Service Type</th>
                                        <th>No. Lambung</th>
                                        <th>HM/KM</th>
                                        <th>Unit</th>
                                        <th>Last Service Date</th>
                                        <th>Brand</th>
                                        <th>Not Brand</th>
                                        <th>Created_at</th>
                                        <th>Updated_at</th>
                                        <th>Created_by</th>
                                        <th>Updated_by</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data))
                                    @foreach ($data as $i => $item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td class="text-left"><b>{{ $item->serial_number }}</b></td>
                                        <td>{{ $item->description }}</td>
                                        <td><b>{{ $item->part_number }}</b></td>
                                        <td>{{ $item->area }}</td>
                                        <td>{{ $item->plant }}</td>
                                        <td>{{ $item->end_customer }}</td>
                                        {{-- <td>{{ $item->jenis_service }}</td> --}}
                                        <td>{{ $item->customer }}</td>
                                        <td>{{ $item->deliv_date }}</td>
                                        <td>{{ $item->commisioning_date }}</td>
                                        <td>{{ $item->general_category }}</td>
                                        <td>{{ $item->type_of_service }}</td>
                                        <td>{{ $item->no_lambung }}</td>
                                        <td>{{ $item->hm_km }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>{{ $item->tgl_service }}</td>
                                        <td>{{ $item->brand }}</td>
                                        <td>{{ $item->notbrand }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>{{ $item->created_by }}</td>
                                        <td>{{ $item->updated_by }}</td>
                                        <td>
                                            <!-- <a href="#" class="editPopulation" data-toggle="modal"
                                                IDPopulation="{{ $item->id }}"><i class=" fa fa-pencil fa-lg"></i></a> -->
                                            <a href="#" class="deletePopulation" data-toggle="modal"
                                                IDPopulation="{{ $item->id_population_monitoring }}"><i class=" fa fa-trash fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="my-2 data-time-update">
                            <div class="mt-2">
                                <a><i class="fa fa-clock-o"></i></a>
                                <span class="d-none data_update_time">{{ $updated_at }}</span>
                                <span class="text-dark update_time"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAddPopulation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg h-100 my-0 mx-auto d-flex flex-column justify-content-center" role="document"
        style="max-width: 80%">
        <form method="post"action="{{url('tsm-add-population')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-content m-2">
                <div class="modal-header bg-primary">
                    <h2 class="modal-title">Add Population</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <label class="my-2">Serial Number: * </label>
                        </div>
                        <div class="col-4">
                            <input id="addSerialNumber" name="addSerialNumber" type="text" class="form-control"
                            autocomplete="off" value="" required>
                            <input id="addGeneralCatgeory" name="addGeneralCatgeory" type="hidden" class="form-control"
                            value="" required>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Unit: *</label>
                        </div>
                        <div class="col-4">
                            <select id="hm_km" name="hm_km" class="form-control" required
                                style="width: 100%">
                                <option value="">--Choose HM/KM--</option>
                                <option value="HoursMeter">HoursMeter</option>
                                <option value="KiloMeter">KiloMeter</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Description: *</label>
                        </div>
                        <div class="col-4">
                            <input id="addDescription" name="addDescription" type="text" class="form-control" readonly
                            autocomplete="off" value="" required>
                            
                        </div>
                        <div class="col-2">
                            <label class="my-2">HM/KM: *</label>
                        </div>
                        <div class="col-4">
                            <input id="satuan" name="satuan" type="number"
                                        class="form-control" autocomplete="off" value="" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Part Number: *</label>
                        </div>
                        <div class="col-4">
                            <input id="addPartNumber" name="addPartNumber" type="text" class="form-control" readonly
                                        autocomplete="off" value="" required>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Last Service Type: *</label>
                        </div>
                        <div class="col-4">
                            <select id="TypeOfService" name="TypeOfService" class="form-control" required
                            style="width: 100%">
                            <option value="">--Choose Last Service Type--</option>
                            <option value="PS1 GS1">PS1 GS1</option>
                            <option value="PS2 GS1">PS2 GS1</option>
                            <option value="PS3 GS1">PS3 GS1</option>
                            <option value="PS4 GS1">PS4 GS1</option>
                            <option value="PS5 GS1">PS5 GS1</option>
                            <option value="PS1 GS2">PS1 GS2</option>
                            <option value="PS2 GS2">PS2 GS2</option>
                            <option value="PS3 GS2">PS3 GS2</option>
                            <option value="PS4 GS2">PS4 GS2</option>
                            <option value="PS5 GS2">PS5 GS2</option>
                            <option value="PS1 GS3">PS1 GS3</option>
                            <option value="PS2 GS3">PS2 GS3</option>
                            <option value="PS3 GS3">PS3 GS3</option>
                            <option value="PS4 GS3">PS4 GS3</option>
                            <option value="PS5 GS3">PS5 GS3</option>
                            <option value="PS1 GS4">PS1 GS4</option>
                            <option value="PS2 GS4">PS2 GS4</option>
                            <option value="PS3 GS4">PS3 GS4</option>
                            <option value="PS4 GS4">PS4 GS4</option>
                            <option value="PS5 GS4">PS5 GS4</option>
                            <option value="GS1">GS1</option>
                            <option value="GS2">GS2</option>
                            <option value="GS3">GS3</option>
                            <option value="GS4">GS4</option>
                        </select>
                        </div>
                        
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Area: *</label>
                        </div>
                        <div class="col-4">
                            <input id="addArea" name="addArea" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Service Date: *</label>
                        </div>
                        <div class="col-4">
                            <input id="addTglService" name="addTglService" type="date"
                            class="form-control datepicker" autocomplete="off" value="" required>
                        </div>
                        
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Plant: * </label>
                        </div>
                        <div class="col-4">
                            <input id="addPlant" name="addPlant" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Deliv Date: *</label>
                        </div>
                        <div class="col-4">
                            <input id="addDelivDate" name="addDelivDate" type="date"
                            class="form-control datepicker" autocomplete="off" value="" required>
                        </div>
                       
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Customer: *</label>
                        </div>
                        <div class="col-4" >
                            <input id="addCustomer" name="addCustomer" type="text" class="form-control" readonly
                                        autocomplete="off" value="" required>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Commisioning Date:</label>
                        </div>
                        <div class="col-4">
                            <input id="addCommisioningDate" name="addCommisioningDate" type="date"
                                        class="form-control datepicker" autocomplete="off" value="">
                        </div>
                       
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">End Customer: * </label>
                        </div>
                        <div class="col-4">
                            <input id="addEndCustomer" name="addEndCustomer" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                        </div>
                        
                        <div class="col-2">
                            <label class="my-2">Brand: *</label>
                        </div>
                        <div class="col-4">
                            <select id="brand" name="brand" class="form-control" required
                                style="width: 100%" onchange="notBrand()">
                                <option value="">--Pilih Brand--</option>
                                <option value="Patria Brand">Patria </option>
                                <option value="Not Brand">Brand non- patria</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">No Lambung: *</label>
                        </div>
                        <div class="col-4">
                            <input id="addNoLambung" name="addNoLambung" type="text" class="form-control"
                            autocomplete="off" value="" >
                        </div>
                        <div class="col-2">
                            <label class="my-2">Ket. Not Brand: *</label>
                        </div>
                        <div class="col-4">
                            <input id="notbrand" name="notbrand" type="text"
                            class="form-control" autocomplete="off" value="" readonly required>
                            <input id="addVariant" name="variant" type="hidden"
                            class="form-control" autocomplete="off" value="">
                        </div>
                        
                    </div>
                    <!-- <div class="row mt-2">
                        <select name="skills" class="ui fluid search dropdown">
                            <option value="">Skills</option>
                            <option value="angular">Angular</option>
                            <option value="css">CSS</option>
                            <option value="design">Graphic Design</option>
                            <option value="ember">Ember</option>
                            <option value="html">HTML</option>
                            <option value="ia">Information Architecture</option>
                            <option value="javascript">Javascript</option>
                            <option value="mech">Mechanical Engineering</option>
                            <option value="meteor">Meteor</option>
                            <option value="node">NodeJS</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="python">Python</option>
                            <option value="rails">Rails</option>
                            <option value="react">React</option>
                            <option value="repair">Kitchen Repair</option>
                            <option value="ruby">Ruby</option>
                            <option value="ui">UI Design</option>
                            <option value="ux">User Experience</option>
                        </select>
                    </div> -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" style="color:white;" class="btn btn-sm btn-primary" pcrid="">
                        Create 
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalDeletePopulation">
    <form method="post" action="{{url('tsm-delete-population')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h2 class="modal-title">Delete Population</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h3>Warning</h3>
                        <h2 id="messageDeletePopulation"></h2>
                    </div>
                    <input id="deleteIDPopulation" name="deleteIDPopulation" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="Import">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Import Data</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{url('tsm-import-population-monitoring')}}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">Download Template</label><br>
                        <a href="{{ asset('public/assets-cogs/template-tsm/population_template.xlsx')}}" class = "btn btn-warning btn-sm"><i class="fa fa-download" ></i> Download Template</a>
                    </div>
                   
                    
                    <div class="form-group">
                        <label for="">Upload File</label>
                        <input type="file" name = "file" class="form-control" required>
                    </div>            
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('myscript')
<script>
$('#TypeOfService').on('change', function(e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    console.log(optionSelected);
    console.log(optionSelected);
});

$(".datepicker").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true,
    orientation: "top auto",
    todayBtn: true,
    todayHighlight: true,
});
var SerialNumber = function() {
    var SerialNumber = null;
    $.ajax({
        'global': false,
        'async': false,
        url: "{{url('tsm-search-serialnumber')}}",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            SerialNumber = data.ListSerialNumber;
        }
    });
    return SerialNumber;
}();

var Area = function() {
    var Area = null;
    $.ajax({
        'global': false,
        'async': false,
        url: "{{url('tsm-search-area')}}",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            Area = data.ListArea;
        }
    });
    return Area;
}();

$('#addSerialNumber').autocomplete({
    lookup: SerialNumber,
    onSelect: function(item) {
        $.ajax({
            url: "{{url('tsm-get-serialnumber')}}",
            type: "GET",
            dataType: "JSON",
            data: {
                serialnumber: item.value,
            },
            success: function(data) {
                function first(data) {
                    console.log(data);
                    // alert(count(data));
                    $('#addDescription').val(data.DataSerialNumber.description);
                    $('#addPartNumber').val(data.DataSerialNumber.part_number);
                    $('#addCustomer').val(data.DataSerialNumber.customer);
                    if(data.DataSerialNumber.customer != 'UNITED TRACTORS TBK'){
                        $('#addEndCustomer').val(data.DataSerialNumber.customer);
                    } else {
                        $('#addEndCustomer').val("");
                    }
                    $('#addDelivDate').val(data.DataSerialNumber.deliv_date);
                    $('#addArea').val(data.DataSerialNumber.area);
                    $('#addNoLambung').val(data.DataSerialNumber.no_lambung);
                    $('#addPlant').val(data.DataSerialNumber.plant);
                    $('#addGeneralCategory').val(data.DataSerialNumber.general_category);
                    $('#addVariant').val(data.DataSerialNumber.variant);
                    // alert(data.DataArea.plant);
                    // $('#addPlant').val('');
                    // if(data.DataArea.plant != ''){

                    //     $('#addPlant').val(data.DataArea.plant);
                    // } 
                };
                first(data);
            }
        });
    }
});

$('#addArea').autocomplete({
    lookup: Area,
    onSelect: function(item) {
        $.ajax({
            url: "{{url('tsm-get-area')}}",
            type: "GET",
            dataType: "JSON",
            data: {
                area: item.value,
            },
            success: function(data) {
                function first(data) {
                    $('#addPlant').val(data.DataArea.plant);
                };
                first(data);
            }
        });
    }
});

$(document).ready(function() {
    $('.addPopulation').on("click", function(e) {
        e.preventDefault();
        $('#modalAddPopulation').modal('show');
    });

    $('.deletePopulation').on("click", function() {
        $.ajax({
            url: "{{url('tsm-search-population')}}",
            type: "GET",
            data: {
                id: $(this).attr('IDPopulation')
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalDeletePopulation').modal('show');
                $('#deleteIDPopulation').val(data.Population.id_population_monitoring);
                $('#messageDeletePopulation').html(
                    "Anda yakin ingin menghapus item ini ?");
            }
        });
    });
    $('#datatable-visibility-population').DataTable({
        scrollX: true,
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print'
        ],
        columnDefs: [
            // {
            //     targets: 7,
            //     type: 'num',
            //     render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            // },
            // {
            //     targets: 8,
            //     type: 'num',
            //     render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            // },
            // {
            //     targets: 9,
            //     type: 'num',
            //     render: $.fn.dataTable.render.number('.', ',', 2, '')
            // }
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('table').find('thead').find(
                'th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            $('.dataTables_scrollBody thead tr').css({
                visibility: 'collapse'
            });
        },
    });
    $('.card-box').attr({
        'style': 'overflow-x: scroll !important;',
    });

});

function notBrand(){
            var brand = $('#brand').val();
            if(brand == 'Not Brand'){
                document.getElementById('notbrand').readOnly = false;
            } else {
                document.getElementById('notbrand').readOnly = true;
            }
        }
</script>
@endsection
