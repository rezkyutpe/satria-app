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
                    <h2>Planning Production Order</h2>
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
                                                <td>{{ $data['apps']->product_number }}
                                                </td>
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
                                            <th colspan="{{ count($data['sn']) }}">{{ $data['apps']->production_order }}</th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Comments', 3, "<br>\n") ?></th>
                                        </tr>
                                        <tr>
                                            @foreach ($data['sn'] as $sn)
                                                @php
                                                    $persen = $sn->persen == null ? " (0%)" : " (". $sn->persen. "% )";
                                                @endphp
                                                <th data-order="{{ $sn->serial_number }}" data-toggle="tooltip"  title="{{ $sn->sch_start_date == null ? 'Start Date Not Found' : date('d-m-Y', strtotime($sn->sch_start_date)) }}">{{ $sn->serial_number.$persen  }} </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $a      = $data['sn']->firstItem()-1;
                                            foreach ($data['sn'] as $paginate_sn) {
                                                $current                = $paginate_sn->toArray();
                                                $current['sn_index']    = $a++;
                                                $new_sn[]               = (object) $current;
                                            }
                                            $class  = 'text-dark';
                                        @endphp
                                        @foreach ($data['material'] as $material)
                                            @php
                                                if ($material->requirement_quantity != 0) {
                                                    $qty_unit = $material->requirement_quantity / $material->quantity;
                                                } else {
                                                    $qty_unit = 1;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: left" data-toggle="tooltip" data-placement="left" title="{{ $material->material_description }}">
                                                    <a href="{{ url('material-detail/'.$material->material_number) }}" class="text-dark">
                                                        {{ trim($material->material_number) }}
                                                    </a>
                                                </td>
                                                <td style="text-align: left">{{ trim($material->material_description) }}</td>
                                                <td>{{ trim($material->material_type) }}</td>
                                                <td data-order="{{ round($material->requirement_quantity, 3) }}">{{ str_replace('.', ',', round($material->requirement_quantity, 3)) }}</td>
                                                <td data-order="{{ round($material->good_issue, 3) }}">{{ str_replace('.', ',', round($material->good_issue, 3)) }}</td>
                                                @foreach ($new_sn as $sn)
                                                    @php
                                                        // LOOPING WITHDRAW
                                                        // jika ENMNG lebih dari kebutuhan keluarkan jumlah, jika tidak maka keluarkan sisa stok
                                                        $prevUsage = array_filter($data['sn_all'], function($item) use($sn){
                                                            return $item['production_order'] == $sn->production_order && $item['sn_index']< $sn->sn_index;
                                                        });
                                                        
                                                        $withdraw_stock   = $material->good_issue - ($qty_unit * count($prevUsage)) >= $qty_unit ? $qty_unit : $material->good_issue - ($qty_unit * count($prevUsage));
                                                        
                                                        // cek if stok lebih dari 0, jika iya keluarkan qty, jika tidak 0
                                                        $hasil_withdraw   = $withdraw_stock > 0 ? $withdraw_stock : 0;
                                                        
                                                        $stock_unit       = number_format($hasil_withdraw / $qty_unit, 1);
                                                        
                                                        if ($material->requirement_quantity == 0) {
                                                            // green
                                                            $bg_color    = '#06FF00';
                                                            $class    = 'text-dark';
                                                            $content  = 0;
                                                        } else {
                                                            if ($stock_unit >=1 ) {
                                                                // green
                                                                $bg_color = '#06FF00';
                                                                $content = round($hasil_withdraw, 3);
                                                                $class = 'text-dark';
                                                            } elseif ($stock_unit <= 0 ) {
                                                                // red
                                                                $bg_color = '#CF1B1B;';
                                                                $content = 0;
                                                                $class = 'text-white';
                                                            } else {
                                                                // orange
                                                                $bg_color = '#FFB830';
                                                                $class = 'text-dark';
                                                                $content = round($hasil_withdraw, 3) . ' / ' . round($qty_unit, 3);
                                                            }
                                                            
                                                        }
                                                                                                                
                                                    @endphp
                                                    <td style="background-color: {{ $bg_color }}" class="{{ $class }}" data-order="{{ $stock_unit }}">
                                                        {{ str_replace('.', ',', $content) }}
                                                    </td>
                                                @endforeach
                                                <td class="text-left">
                                                    @if ($data['actionmenu_komentar']->u == 1)
                                                        <select name="md-komentar" id="md-komentar" class="form-control komentar-material-history">
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
                scrollCollapse: true,
                fixedHeader: true,
                paging: true,
                columnDefs: [{
                    targets: [2],
                    visible: false
                }],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
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
                        }
                    }
                    <?php } ?>
                ],
                stateSave: true,
                scrollX: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });
        });
    </script>
@endsection
