@extends('po-tracking.panel.master')
@section('mycss')
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        #container {
            height: 400px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        /*  */
        #containers {
            height: 400px;
        }

        .highcharts-figures,
        .highcharts-data-tables table {
            min-width: 320px;
            max-width: 500px;
            margin: 1em auto;
        }

        .highcharts-data-tables table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-tables caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-tables th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-tables td,
        .highcharts-data-tables th,
        .highcharts-data-tables caption {
            padding: 0.5em;
        }

        .highcharts-data-tables thead tr,
        .highcharts-data-tables tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-tables tr:hover {
            background: #f1f7ff;
        }

        .jam {
            margin: 0;
            text-align: center;
            color: #fff
        }

        .tile_count .tile_stats_count .count {
            font-size: 29px;
        }
    </style>
@endsection
@section('content')
    <div class="x_panel">
        @if ($actionmenu->c == 1)
            <div class="container-fluid">
                <div class="row">
                    <div class="col detailuser" style="cursor: pointer;" data-toggle="modal" data-id="detailuser"
                        data-status="Active">
                        <div class="card baseBlock h-35" style=" background: rgb(139, 7, 2);">
                            <div class="card-body text-white">
                                <div class="media d-flex" style="position: relative">
                                    <div class="media-body text-left">
                                        <h3>
                                            {{ $datauseractive }}
                                            <div style="display: inline-block; font-size:1rem">User</div>
                                        </h3>
                                        <h6 style="font-weight: bold;">Active </h6>
                                    </div>
                                    <div class="cardicons">
                                        <i class="fa fa-users fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col detailuser" data-toggle="modal" data-id="detailuser" data-status="All">
                        <div class="card baseBlock h-35" style="background: rgb(10, 110, 1); cursor: pointer;">
                            <div class="card-body text-white">
                                <div class="media d-flex" style="position: relative">
                                    <div class="media-body text-left">
                                        <h3>
                                            {{ $datatotaluser }}
                                            <div style="display: inline-block; font-size:1rem">User</div>
                                        </h3>
                                        <h6 style="font-weight: bold;">Total All</h6>
                                    </div>
                                    <div class="cardicons">
                                        <i class="fa fa-users fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col detailuser" data-toggle="modal" data-id="detailuser" data-status="Internal">
                        <div class="card baseBlock h-35" style="background:  rgb(2, 128, 111); cursor: pointer;">
                            <div class="card-body text-white">
                                <div class="media d-flex" style="position: relative">
                                    <div class="media-body text-left">
                                        <h3>
                                            {{ $datatotalinternal }}
                                            <div style="display: inline-block; font-size:1rem">User</div>
                                        </h3>
                                        <h6 style="font-weight: bold;">Total Internal</h6>
                                    </div>
                                    <div class="cardicons">
                                        <i class="fa fa-user fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col detailuser" data-toggle="modal" data-id="detailuser" data-status="Vendor">
                        <div class="card baseBlock h-35" style="background: rgb(1, 12, 138); cursor: pointer;">
                            <div class="card-body text-white">
                                <div class="media d-flex" style="position: relative">
                                    <div class="media-body text-left">
                                        <h3>
                                            {{ $datatotalvendor }}
                                            <div style="display: inline-block; font-size:1rem">User</div>
                                        </h3>
                                        <h6 style="font-weight: bold;">Total Vendor</h6>
                                    </div>
                                    <div class="cardicons">
                                        <i class="fa fa-user fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif


        <div class="row">

            <div class="col-md-8">
                <div id="containerz" style="min-width: 310px; min-height: 450px; margin: 0 auto"></div>

            </div>
            <div class="col-md-4">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Calculation Reference By Ticket</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="tile_count">
                        <div class="col-md-6 col-sm-4  tile_stats_count" style="color: #dc3545">
                            <span class="count_top">Delivery / Today </span>
                            <div class="count details" style="color: #dc3545; cursor: pointer;" data-toggle="modal"
                                data-id="deliverytoday" data-status="Delivery Today">
                                {{ $totaldeliverytoday->TotalTicket }}
                                <small style="font-size:12px; margin-left:-8px; ">
                                    PO </small>{{ $totaldeliverytoday->TotalItem }}
                                <small style="font-size:12px; margin-left:-8px;">Item</small>
                            </div>
                        </div>
                    </div>
                    <div class="tile_count">
                        <div class="col-md-6 col-sm-4  tile_stats_count" style="color: #039d22">
                            <span class="count_top">Arrived / Today </span>
                            <div class="count details" style="cursor: pointer;" data-toggle="modal" data-id="arrivedtoday"
                                data-status="Arrived Today">
                                {{ $totalarrivedtoday->TotalTicket }} <small
                                    style="font-size:12px; margin-left:-8px;  cursor: pointer;">PO
                                </small>{{ $totalarrivedtoday->TotalItem }}
                                <small style="font-size:12px; margin-left:-8px;">Item</small>
                            </div>
                        </div>
                    </div>
                    <div class="tile_count">
                        <div class="col-md-6 col-sm-4  tile_stats_count" style="color: #ffc107">
                            <span class="count_top">PO ON Delivery</span>
                            <div class="count details" style="cursor: pointer;" data-toggle="modal" data-id="delivery"
                                data-status="Delivery">
                                {{ $totaldeliverypo }} <small
                                    style="font-size:12px; margin-left:-8px;  cursor: pointer;">Item
                                </small></div>

                        </div>
                    </div>
                    <div class="tile_count">
                        <div class="col-md-6 col-sm-4  tile_stats_count" style="color: #007bff">
                            <span class="count_top">PO Arrived</span>
                            <div class="count details" style="cursor: pointer;" data-toggle="modal" data-id="arrived"
                                data-status="Arrived">
                                {{ $totalarrivedpo }}<small style="font-size:12px; margin-left:2px;  cursor: pointer;">Item
                                </small></div>
                        </div>
                    </div>
                    @php
                        if ($mostmaterial == null) {
                            $totalmaterial = 0;
                            $description = '-';
                            $material = '-';
                        } else {
                            $totalmaterial = $mostmaterial->TotalMaterial;
                            $description = $mostmaterial->Description;
                            $material = $mostmaterial->Material;
                        }
                        if ($mostVvendors == null) {
                            $totalvendor = 0;
                            $totalitem = 0;
                            $createdby = '-';
                        } else {
                            $totalvendor = $mostVvendors->TotalVendors;
                            $totalitem = $mostVvendors->TotalItem;
                            $createdby = $mostVvendors->CreatedBy;
                        }
                    @endphp
                    <div class="tile_count">
                        <div class="col-md-6 col-sm-4  tile_stats_count" style="color: #fd7e14">
                            <span class="count_top">Most Materials Arrived</span>
                            <div class="count detailss" style="cursor: pointer;" data-toggle="modal" data-id="material"
                                data-status="Material">{{ $totalmaterial }} <small
                                    style="font-size:12px; margin-left:-8px;">Qty</small></div>
                            <div class="count_top"> <small style="font-size:12px; ">{{ $material }}</small>
                            </div>
                            <div class="count_top"> <small style="font-size:11px;">{{ $description }}</small>
                            </div>
                        </div>
                        <div class="tile_count">
                            <div class="col-md-6 col-sm-4  tile_stats_count" style="color: #17a2b8">
                                <span class="count_top">Most Vendors Arrived</span>
                                <div class="count detailss" style="cursor: pointer;" data-toggle="modal"
                                    data-id="vendor" data-status="Vendor">{{ $totalvendor }} <small
                                        style="font-size:12px; margin-left:-8px;">PO</small>
                                    {{ $totalitem }}
                                    <small style="font-size:12px; margin-left:-8px;">Item</small>
                                </div>
                                <div class="count_top"> <small style="font-size:12px;" data-toggle="tooltip"
                                        title="{{ $createdby }}">{{ $createdby }}</small>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-12">
                    <figure class="highcharts-figures">
                        <div id="containers"></div>
                    </figure>
                </div> --}}

        </div>

    </div>
    </div>
    <div class="modal fade" id="Deliverys" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info" id="Juduls">
                </div>
                <div class="table-responsive">
                    <div class="modal-body text-center" id="datadelivery"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="Users" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info" id="JudulUser">
                </div>
                <div class="table-responsive">
                    <div class="modal-body text-center" id="datauser"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('myscript')
    {{-- highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        $(document).ready(function() {


            $('.detailuser').on('click', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');

                $.ajax({
                    url: "{{ url('view_cekuser') }}?id=" + id + "&status=" + status,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#JudulUser').empty();
                        $('#datauser').empty();
                        if (status != 'Active') {
                            var html =
                                `
                        <div class="">

                                <table class="table table-bordered text-center table-hover datatable-responsive1">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Total Login</th>
                                            <th>Last Login</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;

                            for (i = 0; i < data.data.length; i++) {
                                var createat = new Date(data.data[i].created_at);
                                var lastlogin = moment(createat).format("DD/MM/YYYY HH:mm:ss");
                                html +=
                                    `
                                <tr>
                                    <td>` + data.data[i].CreatedBy + `</td>
                                    <td>` + data.data[i].TotalLogin + `</td>
                                    <td>` + lastlogin + `</td>
                                </tr>
                            `;
                            }

                        } else {
                            var html =
                                `
                        <div class="">

                                <table class="table table-bordered text-center table-hover datatable-responsive1">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;

                            for (i = 0; i < data.data.length; i++) {

                                html +=
                                    `
                                <tr>
                                    <td>` + data.data[i].CreatedBy + `</td>
                                </tr>
                            `;
                            }
                        }

                        html += `</tbody></table></div>`;
                        $('#datauser').append(html);
                        $("#JudulUser").append(`<h6 text-center> Data User</h6>`);
                        $('#Users').modal('show');
                        $('.datatable-responsive1').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, 'All']
                            ],
                            fixedHeader: true,
                            scrollCollapse: true,
                            columnDefs: [{
                                orderable: false,
                                targets: "no-sort"
                            }],
                            buttons: [
                                'pageLength', 'copy', 'csv', 'excel', 'pdf',
                                'print',
                            ],

                            select: true,
                            stateSave: true,

                        });
                    }
                });
            });
            $('.details').on('click', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                $.ajax({
                    url: "{{ url('view_cekdelivery') }}?id=" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#Juduls').empty();
                        $('#datadelivery').empty();
                        if (data.tanggal == 'DeliveryDate') {
                            var stat = 'DeliveryDate';
                        } else {
                            var stat = 'Security Date';
                        }
                        var html =
                            `
                        <div class="">

                                <table class="table table-bordered text-center table-hover datatable-responsive2">
                                    <thead>
                                        <tr>
                                            <th>Vendor</th>
                                            <th>Number</th>
                                            <th>Item Number</th>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>` + stat + `</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;

                        for (i = 0; i < data.data.length; i++) {
                            if (data.tanggal == 'DeliveryDate') {
                                var delivery = new Date(data.data[i].DeliveryDate);
                            } else {
                                var delivery = new Date(data.data[i].SecurityDate);
                            }

                            var deliverydate = moment(delivery).format(
                                "DD/MM/YYYY HH:mm:ss");


                            html +=
                                `
                                <tr>
                                    <td>` + data.data[i].CreatedBy + `</td>
                                    <td>` + data.data[i].Number + `</td>
                                    <td>` + data.data[i].ItemNumber + `</td>
                                    <td>` + data.data[i].Material + `</td>
                                    <td>` + data.data[i].Description + `</td>
                                    <td>` + deliverydate + `</td>
                                </tr>
                            `;
                        }
                        html += `</tbody></table></div>`;
                        $('#datadelivery').append(html);
                        $("#Juduls").append(`<h6 text-center> Data ` + status + `</h6>`);
                        $('#Deliverys').modal('show');
                        $('.datatable-responsive2').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, 'All']
                            ],
                            fixedHeader: true,
                            scrollCollapse: true,
                            columnDefs: [{
                                orderable: false,
                                targets: "no-sort"
                            }],
                            buttons: [
                                'pageLength', 'copy', 'csv', 'excel', 'pdf',
                                'print',
                            ],

                            select: true,
                            stateSave: true,

                        });

                    }
                });
            });
            $('.detailss').on('click', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');

                $.ajax({
                    url: "{{ url('view_cekmaterialvendor') }}?id=" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#Juduls').empty();
                        $('#datadelivery').empty();
                        if (status == 'Material') {
                            var html =
                                `
                        <div class="">

                                <table  class="table table-bordered text-center table-hover datatable-responsive3">
                                    <thead>
                                        <tr>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>Total Material</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;

                            for (i = 0; i < data.data.length; i++) {


                                html +=
                                    `
                                <tr>
                                    <td>` + data.data[i].Material + `</td>
                                    <td>` + data.data[i].Description + `</td>
                                    <td>` + data.data[i].TotalMaterial + `</td>

                                </tr>
                            `;
                            }
                            html += `</tbody></table></div>`;
                        } else {
                            var html =
                                `
                        <div class="">

                                <table class="table table-bordered text-center table-hover datatable-responsive3">
                                    <thead>
                                        <tr>
                                            <th>Vendor</th>
                                            <th>Total PO</th>
                                            <th>Total Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;

                            for (i = 0; i < data.data.length; i++) {


                                html +=
                                    `
                                <tr>
                                    <td>` + data.data[i].CreatedBy + `</td>
                                    <td>` + data.data[i].TotalVendors + `</td>
                                    <td>` + data.data[i].TotalItem + `</td>

                                </tr>
                            `;
                            }
                            html += `</tbody></table></div>`;
                        }

                        $('#datadelivery').append(html);
                        $("#Juduls").append(`<h6 text-center> Data ` + status + `</h6>`);
                        $('#Deliverys').modal('show');
                        $('.datatable-responsive3').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, 'All']
                            ],
                            fixedHeader: true,
                            scrollCollapse: true,

                            columnDefs: [{
                                orderable: false,
                                targets: "no-sort"
                            }],
                            buttons: [
                                'pageLength', 'copy', 'csv', 'excel', 'pdf',
                                'print',
                            ],

                            select: true,
                            stateSave: true,

                        });

                    }
                });
            });
        });
    </script>
    <script>
        Highcharts.chart('containerz', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Monthly Ticket Arrived'
            },

            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Total Ticket'
                }
            },
            plotOptions: {

                series: {
                    cursor: 'pointer',
                    point: {
                        events: {

                            click: function() {
                                location.href = '{{ url('detail-delivery') }}/' + this.category;
                            }
                        }
                    }
                },

            },


            series: [{
                name: 'Ticket Arrived',
                data: <?= $ticketchart ?>
            }]

        });
    </script>
@endsection
