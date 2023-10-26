@extends('completeness-component.panel.master')

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data['title'] }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="container">
                        <form action="{{ url('report-gi-component') }}" method="post">
                            @csrf
                           <div class="row justify-content-between">
                                <div class="col-md-6">
                                    <div class="col-md-auto">
                                        <strong>Group Product :</strong>
                                    </div>
                                    <div class="col">
                                        <input type="hidden" id="request_group_product" value="{{ json_encode(request()->session()->get('group_product_gi') )}}" readonly>
                                        <select name="group_product[]" id="group_product" class="form-control select2-multiple" multiple>
                                            @foreach ($data['group_product'] as $item)                                            
                                                <option value="{{ $item->GroupProduct }}">{{ $item->GroupProduct }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-auto">
                                        <strong>Status :</strong>
                                    </div>
                                    <div class="col">
                                        <input type="hidden" id="request_kolom_status" value="{{ json_encode(request()->session()->get('kolom_status_gi') )}}" readonly>
                                        <select name="status[]" id="status" class="form-control select2-multiple" multiple>
                                            <option value="CRTD">Created (CRTD)</option>
                                            <option value="REL">Released (REL)</option>
                                            <option value="PDLV">Partially Delivered (PDLV)</option>
                                            <option value="DLV">Delivered (DLV)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3 mb-3">
                                    <div class="col">
                                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                        <a href="{{ url('report-gi-component?reset=1') }}" class="btn btn-sm  btn-danger">Reset</a>
                                    </div>
                                </div>
                           </div>
                        </form>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped table-bordered nowrap" style="text-align: center"
                                    cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td style="vertical-align: middle"><?= wordwrap('No.', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Production Order', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Product Number', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Product Description', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Group Product', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Status', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Status Date', 2, "<br>\n") ?></td>
                                            
                                            <td style="vertical-align: middle"><?= wordwrap('Req. Qty ZCOM', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('GI ZCOM', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Allocated ZCOM', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen GI ZCOM', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen Allocated ZCOM', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Req.Qty ZBUP', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('GI ZBUP', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Allocated ZBUP', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen GI ZBUP', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen Allocated ZBUP', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Req.Qty ZCNS', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('GI ZCNS', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Allocated ZCNS', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen GI ZCNS', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen Allocated ZCNS', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Req.Qty ZRAW', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('GI ZRAW', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Allocated ZRAW', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen GI ZRAW', 2, "<br>\n") ?></td>
                                            <td style="vertical-align: middle"><?= wordwrap('Persen Allocated ZRAW', 2, "<br>\n") ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['item'] as $item)
                                            @php
                                                if ($item->req_qty_zcom == 0) {
                                                    $persen_gi_zcom         = 100;
                                                    $persen_allocated_zcom  = 100;
                                                }else {
                                                    $persen_gi_zcom         = ($item->gi_zcom/$item->req_qty_zcom)*100;
                                                    $persen_allocated_zcom  = ($item->allocated_zcom/$item->req_qty_zcom)*100;
                                                }

                                                if ($item->req_qty_zbup == 0) {
                                                    $persen_gi_zbup         = 100;
                                                    $persen_allocated_zbup  = 100;
                                                }else {
                                                    $persen_gi_zbup         = ($item->gi_zbup/$item->req_qty_zbup)*100;
                                                    $persen_allocated_zbup  = ($item->allocated_zbup/$item->req_qty_zbup)*100;
                                                }

                                                if ($item->req_qty_zcns == 0) {
                                                    $persen_gi_zcns         = 100;
                                                    $persen_allocated_zcns  = 100;
                                                }else {
                                                    $persen_gi_zcns         = ($item->gi_zcns/$item->req_qty_zcns)*100;
                                                    $persen_allocated_zcns  = ($item->allocated_zcns/$item->req_qty_zcns)*100;
                                                }

                                                if ($item->req_qty_zraw == 0) {
                                                    $persen_gi_zraw         = 100;
                                                    $persen_allocated_zraw  = 100;
                                                }else {
                                                    $persen_gi_zraw         = ($item->gi_zraw/$item->req_qty_zraw)*100;
                                                    $persen_allocated_zraw  = ($item->allocated_zraw/$item->req_qty_zraw)*100;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td data-order="{{ $item->production_order }}">
                                                    <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order) }}" class="text-dark">
                                                        {{ $item->production_order }}
                                                    </a>
                                                </td>
                                                <td data-toggle="tooltip" data-placement="left" title="{{ $item->material_description }}" data-order="{{ $item->material_number }}">
                                                    <a href="{{ url('report-gi-component-detail-unit/'.$item->material_number) }}" class="text-dark">
                                                        {{ $item->material_number }}
                                                    </a>
                                                </td>
                                                <td class="text-left" data-order="{{ $item->material_description }}">{{ $item->material_description }}</td>
                                                <td class="text-left" data-order="{{ $item->group_product }}">{{ $item->group_product }}</td>
                                                <td data-order="{{ $item->status }}">{{ $item->status }}</td>
                                                <td data-order="{{ $item->date_status_created }}">{{ $item->date_status_created == null ? '-' : date('d-m-Y', strtotime($item->date_status_created)) }}</td>
                                                {{-- ZCOM --}}
                                                    <td style="background-color: #B4FF9F" data-order="{{ round($item->req_qty_zcom, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCOM') }}" class="text-dark">
                                                            {{ round($item->req_qty_zcom, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #B4FF9F" data-order="{{ round($item->gi_zcom, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCOM') }}" class="text-dark">
                                                            {{ round($item->gi_zcom, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #B4FF9F" data-order="{{ round($item->allocated_zcom, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCOM') }}" class="text-dark">
                                                            {{ round($item->allocated_zcom, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #B4FF9F" data-order="{{ round($persen_gi_zcom , 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCOM') }}" class="text-dark">
                                                            {{ round($persen_gi_zcom , 2).'%' }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #B4FF9F" data-order="{{ round($persen_allocated_zcom , 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCOM') }}" class="text-dark">
                                                            {{ round($persen_allocated_zcom , 2).'%' }}
                                                        </a>
                                                    </td>
                                                {{-- END ZCOM --}}
                                                {{-- ZBUP --}}
                                                    <td style="background-color: #F9FFA4" data-order="{{ round($item->req_qty_zbup, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZBUP') }}" class="text-dark">
                                                            {{ round($item->req_qty_zbup, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #F9FFA4" data-order="{{ round($item->gi_zbup, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZBUP') }}" class="text-dark">
                                                            {{ round($item->gi_zbup, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #F9FFA4" data-order="{{ round($item->allocated_zbup, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZBUP') }}" class="text-dark">
                                                            {{ round($item->allocated_zbup, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #F9FFA4" data-order="{{ round($persen_gi_zbup, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZBUP') }}" class="text-dark">
                                                            {{ round($persen_gi_zbup, 2).'%' }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #F9FFA4" data-order="{{ round($persen_allocated_zbup, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZBUP') }}" class="text-dark">
                                                            {{round($persen_allocated_zbup, 2).'%' }}
                                                        </a>
                                                    </td>
                                                {{-- END ZBUP --}}
                                                {{-- ZCNS --}}
                                                    <td style="background-color: #FFD59E" data-order="{{ round($item->req_qty_zcns, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCNS') }}" class="text-dark">
                                                            {{ round($item->req_qty_zcns, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #FFD59E" data-order="{{ round($item->gi_zcns, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCNS') }}" class="text-dark">
                                                            {{ round($item->gi_zcns, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #FFD59E" data-order="{{ round($item->allocated_zcns, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCNS') }}" class="text-dark">
                                                            {{ round($item->allocated_zcns, 2) }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #FFD59E" data-order="{{ round($persen_gi_zcns, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCNS') }}" class="text-dark">
                                                            {{ round($persen_gi_zcns, 2).'%' }}
                                                        </a>
                                                    </td>
                                                    <td style="background-color: #FFD59E" data-order="{{ round($persen_allocated_zcns, 2) }}">
                                                        <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZCNS') }}" class="text-dark">
                                                            {{ round($persen_allocated_zcns, 2).'%' }}
                                                        </a>
                                                    </td>
                                                {{-- END ZCNS --}}
                                                {{-- ZRAW --}}
                                                <td style="background-color: #FFA1A1" data-order="{{ round($item->req_qty_zraw, 2) }}">
                                                    <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZRAW') }}" class="text-dark">
                                                        {{ round($item->req_qty_zraw, 2) }}
                                                    </a>
                                                </td>
                                                <td style="background-color: #FFA1A1" data-order="{{ round($item->gi_zraw, 2) }}">
                                                    <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZRAW') }}" class="text-dark">
                                                        {{ round($item->gi_zraw, 2) }}
                                                    </a>
                                                </td>
                                                <td style="background-color: #FFA1A1" data-order="{{ round($item->allocated_zraw, 2) }}">
                                                    <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZRAW') }}" class="text-dark">
                                                        {{ round($item->allocated_zraw, 2) }}
                                                    </a>
                                                </td>
                                                <td style="background-color: #FFA1A1" data-order="{{ round($persen_gi_zraw, 2) }}">
                                                    <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZRAW') }}" class="text-dark">
                                                        {{ round($persen_gi_zraw, 2).'%' }}
                                                    </a>
                                                </td>
                                                <td style="background-color: #FFA1A1" data-order="{{ round($persen_allocated_zraw, 2) }}">
                                                    <a href="{{ url('report-gi-component-detail-pro/'.$item->production_order.'?type=ZRAW') }}" class="text-dark">
                                                        {{ round($persen_allocated_zraw, 2).'%' }}
                                                    </a>
                                                </td>
                                                {{-- END ZRAW --}}
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
    <script>
        $(document).ready(function() {
            var status = JSON.parse($('#request_kolom_status').val());
            var group_product = JSON.parse($('#request_group_product').val());
            $('#status').val(status).change();
            $('#group_product').val(group_product).change();

            // Datattable
            const urlParameter = new URLSearchParams(window.location.search);
            const pro = urlParameter.get('pro');
            if (pro != null) {
                $('#myTable').DataTable({
                    dom: "flBrtip",
                    fixedHeader: true,
                    scrollCollapse: true,
                    scrollX: true,
                    deferRender: true,
                    buttons: [
                        'colvis',
                        <?php if ($data['actionmenu']->v==1){ ?> {
                            text: 'Download',
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                        <?php } ?>
                    ],
                    columnDefs: [{
                        targets                     : [2, 3, 4, 5, 6],
                        visible                     : false
                    }],
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, ['All']]
                    ],
                    oSearch: {
                        "sSearch": pro
                    },
                    stateSave: true,
                    drawCallback: function(settings) {
                        $(".right_col").css("min-height", "615px");
                        $(".child_menu").css("display", "none");
                        $("#sidebar-menu li").removeClass("active");
                    },
                });
            } else {
                $('#myTable').DataTable({
                    dom: "flBrtip",
                    fixedHeader: true,
                    scrollCollapse: true,
                    scrollX: true,
                    deferRender: true,
                    buttons: [
                        'colvis',
                        <?php if ($data['actionmenu']->v==1){ ?> {
                            text: 'Download',
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                        <?php } ?>
                    ],
                    columnDefs: [{
                        targets                     : [2, 3, 4, 5, 6, 7, 8, 9, 12, 13, 14, 17, 18, 19, 22,23,24],
                        visible                     : false
                    }],
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
            }
        });
    </script>
@endsection
