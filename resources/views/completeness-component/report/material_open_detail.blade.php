@extends('completeness-component.panel.master')
@section('mycss')
    <link rel="stylesheet" href="{{ asset('public/assets/datatable/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/datatable/css/select.dataTables.min.css') }}">
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
                                            <td>Production Order</td>
                                            <td>Product Number</td>
                                            <td>Description</td>
                                            <td class="text-center">Req. Date</td>
                                            <td class="text-center">Minus</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($data['material'] as $material)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td><a href="{{ url('production-order-planning/' . $material->AUFNR) }}"
                                                        class="text-dark">{{ $material->AUFNR }}</a></td>
                                                <td class="text-center"><a href="{{ url('planning-unit/' . $material->PLNBEZ) }}"
                                                    class="text-dark">{{ $material->PLNBEZ }}</a></td>
                                                <td class="text-left">{{ $material->DESC_PLNBEZ }}</td>
                                                <td class="text-center">{{ date('d-m-Y', strtotime($material->BDTER)) }}
                                                </td>
                                                <td class="text-center"><a
                                                    href="{{ url('material-detail/' . $material->MATNR) }}"
                                                    class="text-dark">{{ $material->MINUS_PLOTTING . ' ' . $material->MEINS }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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
    <script src="{{ asset('public/assets/datatable/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatable/js/jszip.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "flBrtip",
                fixedHeader: true,
                scrollCollapse: true,
                paging: true,
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> {
                        text: 'Download',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    data = $('<p>' + data + '</p>').text();
                                    return $.isNumeric(data.replace(',', '.')) ? data.replace(',',
                                        '.') : data;
                                }
                            }
                        }
                    }
                    <?php } ?>
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
                select: true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });
        });
    </script>
@endsection
