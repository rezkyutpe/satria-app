@extends('completeness-component.panel.master')

@section('mycss')
    <style>
        body {
            display: flex;
            flex-direction: row;
            margin: 0;
            padding: 0;
        }

        .sticky {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: rgb(248, 248, 248);
            margin-left: 0;
            margin-right: 0;
            margin-bottom: 20px;
        }

        div.ColVis {
            float: left;
        }

        .info {
            font-size: 13px;
            font-weight: 600;
            line-height: 10px;
            margin-bottom: 3px;
            width: 100%;
        }
    </style>
@endsection

@section('contents')
    <div class="row" id="media_wrapper">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel shadow">
                <div class="x_title">
                    <h2>Planning Production Order<small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {{-- Deskripsi --}}
                    <div class="row sticky shadow" id="deskripsi">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-borderless nowrap info">
                                    <tr style="border-bottom:1pt solid black;">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <td style="width: 13%">
                                                    <a data-toggle="collapse" href="#collapsePRO" class="text-dark">
                                                        Production Order
                                                    </a>
                                                </td>
                                                <td>:</td>
                                                <td style="width: 30%">
                                                    @php
                                                        echo substr($data['apps']->production_order, 0, 47);
                                                    @endphp
                                                    <div class="collapse" id="collapsePRO">
                                                        @php
                                                            echo substr($data['apps']->production_order, 48);
                                                        @endphp
                                                    </div>
                                                </td>
                                            </div>
                                            <div class="col-md-6">
                                                <td style="width: 13%">Product Number</td>
                                                <td>:</td>
                                                <td>{{ $data['apps']->product_number }}</td>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <div class="col-md-12">
                                            <div class="col-md-7">
                                                <td>Total Order</td>
                                                <td>:</td>
                                                <td>{{ $data['apps']->quantity }}</td>
                                            </div>
                                            <div class="col-md-5">
                                                <td>Product Name</td>
                                                <td>:</td>
                                                <td style="line-height: normal">{{ $data['apps']->product_description }}</td>
                                            </div>
                                        </div>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- Tabel --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <input type="hidden" id="pro-number" value='{{ $data['apps']->production_order }}'>
                                <table class="table nowrap table-striped table-bordered dt-responsive" id="myTable" cellspacing="0" width="100%" style="text-align:center">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle">No.</th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Material Number', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Description', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Mat. Type', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Qty Req.', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Good Issue', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Req. Date', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Stock', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('on Order', 3, "<br>\n") ?></th>
                                            <th colspan="{{ count($data['sn']) }}">{{ $data['apps']->production_order }}</th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Shortage', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Status', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Comments', 3, "<br>\n") ?></th>
                                        </tr>
                                        <tr>
                                            @foreach ($data['sn'] as $sn)
                                                @php
                                                    $persen = $sn->persen == null ? " (0%)" : " (". $sn->persen. "% )";
                                                @endphp
                                                <th data-order="{{ $sn->serial_number }}" data-toggle="tooltip" title="{{ $sn->sch_start_date == null ? 'Start Date Not Found' : date('d-m-Y', strtotime($sn->sch_start_date)) }}">
                                                    {{ $sn->serial_number.$persen }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count    = 0;
                                            $a        = $data['sn']->firstItem()-1;
                                            foreach ($data['sn'] as $paginate_sn) {
                                                $current            = $paginate_sn->toArray();
                                                $current['sn_index']= $a++;
                                                $new_sn[]           = (object) $current;
                                            }
                                        @endphp
                                        @foreach ($data['material'] as $material)
                                            @php
                                                $qty_unit       = $material->requirement_quantity == 0 ? 1 :$material->requirement_quantity / $material->quantity;
                                                $id_material    = 0;
                                                $class          = "text-dark";
                                                $color          = 'black';
                                                $content        = null;
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td style="text-align: left" data-toggle="tooltip" data-placement="left" title="{{ $material->material_description }}">
                                                    {{ trim($material->material_number) }}
                                                </td>
                                                <td style="text-align: left">
                                                    {{ trim($material->material_description) }}
                                                </td>
                                                <td>
                                                    {{ trim($material->material_type) }}
                                                </td>
                                                <td data-order="{{ round($material->requirement_quantity, 3) }}">
                                                    {{ str_replace('.', ',', round($material->requirement_quantity, 3)) }}
                                                </td>
                                                <td data-order="{{ round($material->good_issue, 3) }}">
                                                    {{ str_replace('.', ',', round($material->good_issue, 3)) }}
                                                </td>
                                                <td data-order="{{ $material->requirement_date }}">
                                                    {{ date('d-m-Y', strtotime($material->requirement_date)) }}
                                                </td>
                                                <td data-order="{{ round($material->stock, 3) }}" data-toggle="tooltip" data-placement="right" title="Detail Stock">
                                                    <a href="#" class="detailStock text-dark"
                                                        data-toggle="modal" data-target="#StockModal"
                                                        data-material-number="{{ $material->material_number }}"
                                                        data-order="{{ round($material->stock, 3) }}"
                                                    >
                                                        {{ str_replace('.', ',', round($material->stock, 3)) }}
                                                    </a>
                                                </td>
                                                <td data-order="{{ round($material->total_open_qty, 3) }}" data-toggle="tooltip" data-placement="right" title="Detail on Order">
                                                    <a href="{{ url('material-detail/' . $material->material_number) }}" class="text-dark">
                                                        {{ str_replace('.', ',', round($material->total_open_qty, 3)) }}
                                                    </a>
                                                </td>
                                                @foreach ($new_sn as $sn)
                                                    @php
                                                        $id_material   = $material->id;
                                                        $prevUsage = array_filter($data['sn_all'], function($item) use($sn){
                                                            return $item['production_order'] == $sn->production_order && $item['sn_index']< $sn->sn_index;
                                                        });
                                                        $count      = count($prevUsage);
                                                        
                                                        $gi         = $material->good_issue - ($qty_unit * count($prevUsage));
                                                        $plot       = $material->good_issue + $material->alokasi_stock - ($qty_unit * count($prevUsage));

                                                        // LOOPING WITHDRAW
                                                        // jika ENMNG lebih dari kebutuhan keluarkan jumlah, jika tidak maka keluarkan sisa stok

                                                        $plot_gi    = $gi >= $qty_unit ? $qty_unit : $gi;
                                                        $plot_stock = $plot >= $qty_unit ? $qty_unit : $plot;
                                                        
                                                        // cek if stok lebih dari 0, jika iya keluarkan qty, jika tidak 0
                                                        $hasil_gi   = $plot_gi > 0 ? $plot_gi : 0;
                                                        $hasil_plot = $plot_stock > 0 ? $plot_stock : 0;
                                                        
                                                        $slot_gi    = number_format($hasil_gi / $qty_unit, 3);
                                                        $slot_stock = number_format($hasil_plot / $qty_unit, 3);
                                                        
                                                        if ($material->requirement_quantity == 0) {
                                                            // green
                                                            $color = '#06FF00';
                                                            $content = 0;
                                                        } else {
                                                            if ($slot_stock >= 1) {
                                                                if ($slot_gi == 1) {
                                                                    // green
                                                                    $color    = '#06FF00';
                                                                    $content  = round($qty_unit, 3);
                                                                } elseif ($slot_gi == 0) {
                                                                    // yellow
                                                                    $color    = '#fbff69';
                                                                    $content  = '0 / ' . round($qty_unit, 3);
                                                                } else {
                                                                    // orange
                                                                    $color    = '#FFB830';
                                                                    $content  = round($hasil_gi, 3) . ' / ' . round($qty_unit, 3);
                                                                }
                                                            } elseif ($slot_stock < 1) {
                                                                $color        = '#CF1B1B;color: white;';
                                                                $class        = "text-light";
                                                                if ($slot_gi == 0) {
                                                                    $content  = 0;
                                                                    // if ($material->RESERVE > 0) {
                                                                    //     $content = '0 / '.$material->RESERVE;
                                                                    // }
                                                                } else {
                                                                    $content  = round($hasil_gi, 3) . ' / ' . round($qty_unit, 3);
                                                                }
                                                            }
                                                        }                                                        
                                                    @endphp
                                                    <td style="background-color: {{ $color }}">
                                                        {{ str_replace('.', ',', $content) }}
                                                    </td>
                                                @endforeach
                                                @if ($material->kekurangan_stock < 0)
                                                    <td data-toggle="tooltip" data-placement="right" title="Detail Ploting Stock">
                                                        <a href="#" class="plottingStock text-danger"
                                                            data-toggle="modal" data-target="#plottingStockModal"
                                                            data-material-number="{{ $material->material_number }}"
                                                            data-order="{{ round($material->kekurangan_stock, 3) }}"
                                                        >
                                                            {{ str_replace('.', ',', round($material->kekurangan_stock, 3)) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger text-light">OPEN</span>
                                                    </td>
                                                @else
                                                    <td data-order="{{ round($material->kekurangan_stock, 3) }}">
                                                        {{ str_replace('.', ',', round($material->kekurangan_stock, 3)) }}
                                                    </td>
                                                    @if ($material->good_issue >= $material->requirement_quantity || $material->requirement_quantity == 0)
                                                        <td>
                                                            <span class="badge badge-success text-dark" style="background-color:#06FF00;">CLOSE</span>
                                                        </td>
                                                    @elseif($material->good_issue == 0)
                                                        <td>
                                                            <span class="badge badge-warning"
                                                                style="background-color: #fbff69">ALLOCATED</span>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge badge-warning"
                                                                style="background-color: #FFB830">PARTIAL</span>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td class="text-left">
                                                    @if ($data['actionmenu_komentar']->u == 1)
                                                        <select name="md-komentar" id="md-komentar" class="form-control komentar-material-ongoing">
                                                            <option data-id="{{ $material->id }}" value="0"  @if (0 == $material->komentar) selected @endif>-</option>
                                                            @foreach ($data['komentar'] as $item)
                                                                <option data-id="{{ $material->id }}" value="{{ $item->id }}" @if ($item->id == $material->komentar) selected @endif>
                                                                    {{ $item->komentar }}
                                                                </option>
                                                            @endforeach
                                                        </select>                                                        
                                                    @else
                                                        @foreach ($data['komentar'] as $item)
                                                            @if ($item->id == $material->komentar)
                                                                {{ $item->komentar }}                           
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if (count($data['sn_all']) > 5)
                            <div class="col-lg-12">
                                <p style="color: black"><strong>Production Order pages :</strong></p> {{ $data['sn']->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('myscript')
    {{-- Datatable --}}
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "flBrtip",
                fixedColumns: {
                    left: 6,
                    right: 2
                },
                scrollCollapse: true,
                paging: true,
                fixedHeader: true,
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> {
                        text: 'Download',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    let node_text = '';
                                    const spacer = node.childNodes.length > 1 ? ' ' : '';
                                    node.childNodes.forEach(child_node => {
                                        const temp_text = child_node.nodeName == "SELECT" ? child_node.selectedOptions[0].textContent.trim() : child_node.textContent.trim();
                                        node_text += temp_text ? `${temp_text}${spacer}` : '';
                                    });
                                    return $.isNumeric(node_text.replace(',', '.')) ? node_text.replace(',', '.') : node_text;
                                }
                            }
                        },
                    },
                    <?php } if ($data['actionmenu_ticket']->c==1) { ?>
                    {
                        text: 'Create Ticket',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('production-order-ticket/'.$data['apps']->production_order) }}";
                        }
                    }
                    <?php } ?>
                ],
                columnDefs: [{
                    targets: [2],
                    visible: false
                }],
                stateSave: true,
                scrollX: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                }
            });
        });
    </script>
@endsection
