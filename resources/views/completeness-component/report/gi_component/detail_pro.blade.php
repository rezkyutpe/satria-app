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
                    <h2>{{ $data['title'] }}</h2>
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
                                <table class="table nowrap table-striped table-bordered" id="myTable" cellspacing="0" width="100%" style="text-align:center">
                                    <thead style="background-color: rgb(42,63,84);color:white;">
                                        <tr style="min-height: 80px;">
                                            <th class="align-middle">No.</th>
                                            <th class="align-middle"><?= wordwrap('Material Number', 3, "<br>\n") ?></th>
                                            <th class="align-middle"><?= wordwrap('Description', 3, "<br>\n") ?></th>
                                            <th class="align-middle"><?= wordwrap('Mat. Type', 3, "<br>\n") ?></th>
                                            <th class="align-middle"><?= wordwrap('Stock', 3, "<br>\n") ?></th>
                                            <th class="align-middle"><?= wordwrap('Req. Date', 3, "<br>\n") ?></th>
                                            <th class="align-middle"><?= wordwrap('Qty Req.', 3, "<br>\n") ?></th>
                                            <th class="align-middle"><?= wordwrap('Good Issue ('.round($data['persen']->gi, 2).'%)', 3, "<br>\n") ?></th>
                                            <th class="align-middle"><?= wordwrap('Allocated Stock ('.round($data['persen']->alokasi, 2).'%)', 3, "<br>\n") ?></th>
                                            @if ($data['actionmenu_komentar']->u == 1 || $data['actionmenu_komentar']->c == 1)
                                                <th class="align-middle"><?= wordwrap('Comments', 3, "<br>\n") ?></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count = 0;
                                            $a = $data['sn']->firstItem()-1;
                                            foreach ($data['sn'] as $paginate_sn) {
                                                $current                = $paginate_sn->toArray();
                                                $current['sn_index']    = $a++;
                                                $new_sn[]               = (object) $current;
                                            }
                                        @endphp
                                        @foreach ($data['material'] as $material)
                                            @php
                                                $id_material_number   = 0;
                                                $class      = "text-dark";
                                                $color      = 'black';
                                                $content    = null;
                                                if ($material->requirement_quantity == 0) {
                                                    $persen_gi = 100;
                                                    $persen_allocated = 100;
                                                } else {
                                                    $persen_gi = ($material->good_issue/$material->requirement_quantity)*100;
                                                    $persen_allocated = ($material->allocated/$material->requirement_quantity)*100;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle" style="text-align: left" data-toggle="tooltip" data-placement="right" title="{{ $material->material_description }}">
                                                    {{ $material->material_number }}  
                                                </td>
                                                <td class="align-middle" style="text-align: left">{{ trim($material->material_description) }}</td>
                                                <td class="align-middle">{{ trim($material->material_type) }}</td>
                                                <td class="align-middle" data-order="{{ round($material->stock, 3) }}">
                                                    <a href="#" class="detailStock text-dark"
                                                        data-toggle="modal" data-target="#StockModal"
                                                        data-material-number="{{ $material->material_number }}"
                                                        data-order="{{ round($material->stock, 3) }}"
                                                    >
                                                        {{ str_replace('.', ',', round($material->stock, 3)) }}
                                                    </a>
                                                </td>
                                                <td class="align-middle" data-order="{{ $material->requirement_date }}">{{ date('d-m-Y', strtotime($material->requirement_date)) }}</td>
                                                <td class="align-middle" data-order="{{ round($material->requirement_quantity, 3) }}">{{ str_replace('.', ',', round($material->requirement_quantity, 3)) }}</td>
                                                <td class="align-middle" data-order="{{ round($material->good_issue, 3) }}">{{ str_replace('.', ',', round($material->good_issue, 3)) }}</td>
                                                <td class="align-middle" data-order="{{ round($material->allocated, 3) }}">{{ str_replace('.', ',', round($material->allocated, 3)) }}</td>
                                                @if ($data['actionmenu_komentar']->u == 1 || $data['actionmenu_komentar']->c == 1)
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
                                                @endif
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
    <script>
        $(document).ready(function() {
            // Datatable
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
                    <?php } ?>
                ],
                columnDefs: [{
                    targets                     : [2, 4],
                    visible                     : false
                }],
                stateSave: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });
        });
    </script>
    
@endsection
