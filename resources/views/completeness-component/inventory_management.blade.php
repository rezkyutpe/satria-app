@extends('completeness-component.panel.master')
@section('mycss')
    <link rel="stylesheet" href="{{ asset('public/assets/datatable/css/buttons.dataTables.min.css') }}">
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
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table nowrap table-striped table-bordered"
                                    style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td class="text-center">Material Number</td>
                                            <td class="text-center">Description</td>
                                            <td>Material Type</td>
                                            <td>Material Group</td>
                                            <td>Base Unit</td>
                                            <td>Plant</td>
                                            <td>StoreLoc</td>
                                            <td>Stock</td>
                                            <td>Allc. Stock</td>
                                            <td>Free Stock</td>
                                            <td>in QC</td>
                                            <td>on Order</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('myscript')
    <script src="{{ asset('public/assets/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatable/js/jszip.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "fBrtip",
                paging: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('inventory.data') }}",
                deferRender: true,
                columns: [{
                        data: "id",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'material_number',
                        name: 'material_number',
                        render: function(data, type, row) {
                            return "<span data-toggle='tooltip' title='" + row.material_description +"'>" + row.material_number + "</span>"
                        },
                        className: 'text-left middle'
                    },
                    {
                        data: 'material_description',
                        name: 'material_description',
                        className: 'text-left middle'
                    },
                    {
                        data: 'material_type',
                        name: 'material_type',
                        className: 'align-middle'
                    },
                    {
                        data: 'material_group',
                        name: 'material_group',
                        className: 'align-middle'
                    },
                    {
                        data: 'base_unit',
                        name: 'base_unit',
                        className: 'align-middle'
                    },
                    {
                        data: 'plant',
                        name: 'plant',
                        className: 'align-middle'
                    },
                    {
                        data: 'storage_location',
                        name: 'storage_location',
                        className: 'align-middle'
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                        className: 'align-middle'
                    },
                    {
                        data: 'alokasi_stock',
                        name: 'alokasi_stock',
                        render: function(data, type, row) {
                            return "<a href='javascript:void(0)' class='detailPlotting text-dark' data-toggle='modal' data-target='#modalPlottingStock' data-material-number='"+row.material_number+"'> "+row.alokasi_stock+" </a>"
                        },
                        className: 'align-middle'
                    },
                    {
                        data: 'free_stock',
                        name: 'free_stock',
                        render: function(value) {
                            if (value === null) return 0;
                            return value;
                        },
                        className: 'align-middle'
                    },
                    {
                        data: 'in_qc',
                        name: 'in_qc',
                        className: 'align-middle'
                    },
                    {
                        data: 'total_open_qty',
                        name: 'total_open_qty',
                        render: function(data, type, row) {
                            open_qty = row.total_open_qty;
                            if (open_qty === null) {
                                return "<a href='material-detail/" + row.material_number + "' class='text-dark'>0</a>"
                            } else {
                                return "<a href='material-detail/" + row.material_number + "' class='text-dark'>"+ open_qty +"</a>"
                            }
                        },
                        className: 'align-middle'
                    }

                ],
                columnDefs: [{
                    targets: [2],
                    visible: false
                }],
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> 
                        {
                            text: 'Download',
                            action: function ( e, dt, node, config ) {
                                window.location.href = "{{ route('inventory.download') }}";
                            }
                        }
                    <?php } ?>
                ],
                // stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });

            $('#myTable tbody').on('click', '.detailPlotting', function() {
                var material_number = $(this).attr('data-material-number');
                $.ajax({
                    url: "{{ url('ccr-detail-plotting-stock') }}?material_number=" + material_number,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#detailPlotting').empty();
                        $('#dataMaterial').empty();

                        $('#dataMaterial').html(
                            data.desc.material_number + ' - ' + data.desc.material_description
                        );

                        if (data.minus.length == 0) {
                            if (data.stock.stock == 0) {
                                $("#detailPlotting").append(`
                                    <tr>
                                        <td colspan= "7">No Data Available ( Stock 0 )</td>
                                    </tr>
                                `);
                            } else {
                                $("#detailPlotting").append(`
                                    <tr>
                                        <td colspan= "7">No Data Available</td>
                                    </tr>
                                `);
                            }
                        } else {
                            for (i = 0; i < data.minus.length; i++) {
                                no = i + 1;
                                var production_order    = data.minus[i].production_order;
                                
                                var product_number      = data.minus[i].product_number;
                                
                                var date                = new Date(data.minus[i].requirement_date);
                                var tanggal             = String(date.getDate()).padStart(2, '0');
                                var bulan               = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                                var tahun               = date.getFullYear();
                                var requirement_date    = tanggal + '-' + bulan + '-' + tahun;

                                $("#detailPlotting").append(`
                                    <tr>
                                        <td>` + no + `</td>
                                        <td>` + production_order + `</td>
                                        <td>` + product_number + `</td>
                                        <td>` + requirement_date + `</td>
                                        <td>` + data.minus[i].requirement_quantity + `</td>
                                        <td>` + data.minus[i].alokasi_stock + `</td>
                                        <td>` + data.minus[i].sisa_stock + `</td>
                                    </tr>
                                `);
                            }
                        }
                        $('#plottingStockModal').modal('show');
                    }
                });
            }); 
            
        });
    </script>
@endsection
