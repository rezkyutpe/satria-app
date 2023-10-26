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
                    <h2>Planning Unit</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {{-- Deskripsi --}}
                    <div class="row sticky shadow" id="deskripsi">
                        <div class="col-lg-12">
                            <div class='col-lg-12 d-flex justify-content-between'>
                                <div class="table-responsive">
                                    <table class="table table-borderless nowrap info">
                                        <tr style="border-bottom:1pt solid black;">
                                            <div class="col-md-12">
                                                <td style="width: 14%">
                                                    <a data-toggle="collapse" href="#collapsePRO" class="text-dark">
                                                        Production Order
                                                    </a>
                                                </td>
                                                <td style="width: 1%">:</td>
                                                <td style="width: 35%;line-height: normal">
                                                    @php
                                                        $pro            = [];
                                                        $jumlah_order   = [];
                                                        foreach ($data['apps'] as $apps) {
                                                            array_push($pro, $apps->production_order);
                                                            array_push($jumlah_order, $apps->quantity);
                                                            $product_number   = $apps->product_number;
                                                            $product_description    = $apps->product_description;
                                                        }
                                                        echo substr(implode(', ', $pro), 0, 47);
                                                    @endphp
                                                    <div class="collapse" id="collapsePRO">
                                                        @php
                                                            echo substr(implode(', ', $pro), 48);
                                                        @endphp
                                                    </div>
                                                </td>
                                                <td style="width: 14%">Product Number</td>
                                                <td style="width: 1%">:</td>
                                                <td style="line-height: normal">{{ $product_number }}</td>
                                            </div>
                                        </tr>
                                        <tr>
                                            <td>Total Order</td>
                                            <td>:</td>
                                            <td>{{ array_sum($jumlah_order) }}</td>
                                            <td>Product Name</td>
                                            <td>:</td>
                                            <td style="line-height: normal">{{ $product_description }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Tabel --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <input type="hidden" id="material_number" value='<?= $product_number ?>'>
                                <table class="table nowrap table-striped table-bordered order-column dt-responsive"
                                    id="myTable" cellspacing="0" width="100%" style="text-align:center">
                                    <thead style="background-color: rgb(42,63,84);color:white;">
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle">No.</th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Material Number', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Description', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Mat. Type', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Qty Req.', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Good Issue', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Stock', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('on Order', 3, "<br>\n") ?></th>
                                            @foreach ($data['sn'] as $sn)
                                                @php
                                                    $list_pro[] = $sn->production_order;
                                                @endphp
                                                <th>
                                                    <a href="{{ url('production-order-planning/' . $sn->production_order) }}" class="text-light">
                                                        {{ $sn->production_order }}
                                                    </a>
                                                </th>
                                            @endforeach
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Shortage', 3, "<br>\n") ?></th>
                                            <th rowspan="2" style="vertical-align: middle"><?= wordwrap('Status', 3, "<br>\n") ?></th>
                                        </tr>
                                        <tr>
                                            @foreach ($data['sn'] as $sn)
                                                @php
                                                    $persen = $sn->persen == null ? " (0%)" : " (". $sn->persen. "% )";
                                                @endphp 
                                                <th data-toggle="tooltip" title="{{ $sn->sch_start_date == null ? 'Start Date Not Found' : date('d-m-Y', strtotime($sn->sch_start_date)) }}">{{ $sn->serial_number.$persen }}</th>
                                            @endforeach
                                        </tr>
                                        <input type="hidden" id="data_pro" value='{{ implode('#', $list_pro) }}'>
                                    </thead>
                                    <tbody>
                                        @php
                                            $qty_unit   = 0;
                                            $plot       = 0;
                                            $a          = $data['sn']->firstItem()-1;
                                            foreach ($data['sn'] as $paginate_sn) {
                                                $current                = $paginate_sn->toArray();
                                                $current['sn_index']    = $a++;
                                                $new_sn[]               = (object) $current;
                                            }
                                        @endphp
                                        @foreach ($data['material'] as $unit)
                                            @php
                                                $gi         = null;
                                                $id_matnr   = 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-left" data-toggle="tooltip" data-placement="left" title="{{ $unit->material_description }}">{{ $unit->material_number }}</td>
                                                <td class="text-left">{{ $unit->material_description }}</td>
                                                <td>{{ $unit->material_type }}</td>
                                                <td data-order="{{ round($unit->requirement_quantity, 3) }}">{{ str_replace('.', ',', round($unit->requirement_quantity, 3)) }}</td>
                                                <td data-order="{{ round($unit->good_issue, 3) }}">{{ str_replace('.', ',', round($unit->good_issue, 3)) }}</td>
                                                <td data-order="{{ round($unit->stock, 3) }}" data-toggle="tooltip" data-placement="left" title="Detail Stock">                                                    
                                                    <a href="#" class="detailStock text-dark"
                                                        data-toggle="modal" data-target="#StockModal"
                                                        data-material-number="{{ $unit->material_number }}"
                                                        data-order="{{ round($unit->stock, 3) }}"
                                                    >
                                                        {{ str_replace('.', ',', round($unit->stock, 3)) }}
                                                    </a>                                             
                                                </td>
                                                <td data-order="{{ round($unit->total_open_qty, 3) }}" data-toggle="tooltip" data-placement="left" title="Detail on Order">
                                                    <a href="{{ url('material-detail/' . $unit->material_number) }}" class="text-dark">
                                                        {{ str_replace('.', ',', round($unit->total_open_qty, 3)) }}
                                                    </a>                                                   
                                                </td>

                                                @foreach ($new_sn as $sn)
                                                    @php
                                                        $color            = 'black; color: black;';
                                                        $content          = '-';
                                                        $date_material    = null;
                                                        $class            = "text-dark";
                                                        $matnr            = null;
                                                        $aufnr            = null;
                                                    @endphp
                                                    @foreach ($data['material_list'] as $material)
                                                        @if ($sn->production_order == $material->production_order)
                                                            @if ($material->material_number == $unit->material_number)
                                                                @php
                                                                    $id_matnr         = $material->id;
                                                                    $date_material    = $material->requirement_date == null ? '-' : date('d-m-Y', strtotime($material->requirement_date));
                                                                    $quantity         = $material->quantity;
                                                                    $qty_unit         = $material->requirement_quantity == 0 ? 1 : $material->requirement_quantity/$quantity;
                                                                    $matnr            = $material->material_number;
                                                                    $aufnr            = $material->production_order;
                                                                    $plot             = $material->good_issue + $material->alokasi_stock;
                                                                    $gi               = $material->good_issue;
                                                                    
                                                                    
                                                                    $prevUsage = array_filter($data['sn_all'], function($item) use($aufnr, $sn){
                                                                        return $item['production_order'] == $aufnr && $item['sn_index']< $sn->sn_index;
                                                                    });
                                                                    
                                                                    $plot       -= $qty_unit * count($prevUsage);
                                                                    $gi         -= $qty_unit * count($prevUsage);
                                                                    
                                                                    $plot_gi    = $gi >= $qty_unit ? $qty_unit : $gi;
                                                                    $plot_stock = $plot >= $qty_unit ? $qty_unit : $plot;
                                                                    
                                                                    // cek if stok lebih dari 0, jika iya keluarkan qty, jika tidak 0
                                                                    $hasil_gi   = $plot_gi > 0 ? $plot_gi : 0;
                                                                    $hasil_plot = $plot_stock > 0 ? $plot_stock : 0;
                                                                                                                                        
                                                                    $slot_gi    = number_format($hasil_gi / $qty_unit, 3);
                                                                    $slot_stock = number_format($hasil_plot / $qty_unit, 3);

                                                                    if ($material->requirement_quantity == 0) {
                                                                        // GREEN
                                                                        $color    = '#06FF00';
                                                                        $content  = 0;
                                                                    } else {
                                                                        if ($slot_stock >= 1) {
                                                                            if ($slot_gi == 1) {
                                                                                // GREEN
                                                                                $color    = '#06FF00';
                                                                                $content  = round($qty_unit, 3);
                                                                            } elseif ($slot_gi == 0) {
                                                                                // YELLOW
                                                                                $color    = '#fbff69';
                                                                                $content  = '0 / ' . round($qty_unit, 3);
                                                                            } else {
                                                                                // ORANGE
                                                                                $color    = '#FFB830';
                                                                                $content  = round($hasil_gi, 3) . ' / ' . round($qty_unit, 3);
                                                                            }
                                                                        } elseif ($slot_stock < 1) {
                                                                            // RED
                                                                            $color = '#CF1B1B;color: white;';
                                                                            $class = "text-light";   
                                                                            if ($slot_gi == 0) {
                                                                                $content = 0;
                                                                            } else {
                                                                                $content = round($hasil_gi, 3) . ' / ' . round($qty_unit, 3);
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                    <td style="background-color: {{ $color }}" data-toggle="tooltip" data-placement="left" title="{{ $date_material }}" data-order="{{ $date_material }}">
                                                        @if ($data['actionmenu_komentar']->u == 1)
                                                            <a href="javascript:void(0)"
                                                                data-matnr="{{ $product_number }}"
                                                                data-material="{{ $matnr }}"
                                                                data-id="{{ $id_matnr }}"
                                                                data-pro="{{ $aufnr }}"
                                                                {{-- data-sn="{{ $sn->SN }}" --}}
                                                                class="comment {{ $class }}"
                                                            >
                                                                {{ str_replace('.', ',', $content) }}
                                                            </a>
                                                        @else
                                                            {{ str_replace('.', ',', $content) }} 
                                                        @endif
                                                        
                                                    </td>
                                                @endforeach
                                                @php
                                                    $unit->kekurangan_stock = number_format($unit->kekurangan_stock, 3);
                                                @endphp
                                                @if ($unit->kekurangan_stock < 0)
                                                    <td data-toggle="tooltip" data-placement="right" title="Detail Plotting Stock">
                                                        <a href="#" class="plottingUnit text-danger"
                                                            data-toggle="modal" data-target="#plottingStockModal" 
                                                            data-matnr="{{ $unit->material_number }}" 
                                                            data-plnbez="{{ $product_number }}"
                                                            data-order="{{ round($unit->kekurangan_stock, 3) }}"
                                                        >
                                                            {{ str_replace('.', ',', round($unit->kekurangan_stock, 3)) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger text-light">OPEN</span>
                                                    </td>
                                                @else
                                                    <td data-order="{{ round($unit->kekurangan_stock, 3) }}">
                                                        {{ str_replace('.', ',', round(number_format($unit->kekurangan_stock, 3), 3)) }}
                                                    </td>
                                                    @if ($unit->good_issue >= $unit->requirement_quantity || $unit->requirement_quantity == 0)
                                                        <td>
                                                            <span class="badge badge-success text-dark" style="background-color:#06FF00;">CLOSE</span>
                                                        </td>
                                                    @elseif($unit->good_issue == 0)
                                                        <td>
                                                            <span class="badge badge-warning" style="background-color: #fbff69;">ALLOCATED</span>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge badge-warning" style="background-color: #FFB830">PARTIAL</span>
                                                        </td>
                                                    @endif
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (array_sum($jumlah_order) > 5)
                        <div class="row">
                            <div class="col-lg-12">
                                <p style="color: black"><strong>Production Order pages :</strong></p> {{ $data['sn']->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal Komentar-->
    <div class="modal fade" id="komentarModal" tabindex="-1" role="dialog" aria-labelledby="komentarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="komentarModalLabel">Comments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <span id="dataComment"></span>
                        <label for="comments">Comment</label>
                        <select name="comments" id="comments" class="form-control"></select>
                    </div>
                    <div class="modal-footer"></div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@section('myscript')        
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "flBrtip",
                scrollCollapse: true,
                responsive: true,
                fixedHeader: true,
                paging: true,
                columnDefs: [{
                    targets: [2],
                    visible: false
                }],
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
                                    return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                                }
                            }
                        }
                    }
                    <?php } ?>
                ],
                fixedColumns: {
                    left: 4
                },
                scrollX: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                }
            });

            // modal minus/shortage / plotting stock
            $('#myTable tbody').on('click', '.plottingUnit', function() {
                var matnr = $(this).attr('data-matnr');
                var plnbez = $(this).attr('data-plnbez');
                $.ajax({
                    url: "{{ url('detailMinusStockUnit') }}?matnr=" + matnr + "&plnbez="+ plnbez,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#detailPlotting').empty();
                        $('#dataMaterial').empty();

                        $('#dataMaterial').html(
                            data.desc.MATNR + ' - ' + data.desc.MAKTX
                        );

                        if (data.minus.length == 0) {
                            if (data.desc.STOCK == 0) {
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
                                var pro = data.minus[i].AUFNR;
                                var matnr = data.minus[i].PLNBEZ;
                                var date = new Date(data.minus[i].BDTER);
                                var tanggal = String(date.getDate()).padStart(2, '0');
                                var bulan = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                                var tahun = date.getFullYear();
                                var bdter = tanggal + '-' + bulan + '-' + tahun;

                                $("#detailPlotting").append(`
                                    <tr>
                                        <td>` + no + `</td>
                                        <td>` + pro + `</td>
                                        <td>` + matnr + `</td>
                                        <td>` + bdter + `</td>
                                        <td>` + data.minus[i].BDMNG + `</td>
                                        <td>` + data.minus[i].RESERVE + `</td>
                                        <td>` + data.minus[i].RESTOCK + `</td>
                                    </tr>
                                `);
                            }
                        }
                        $('#plottingStockModal').modal('show');
                    }
                });
            });
            
            $('#myTable tbody').on('click', '.comment', function() {
                var material    = $(this).attr('data-material');
                var matnr       = $(this).attr('data-matnr');
                var pro         = $(this).attr('data-pro');
                var id_material    = $(this).attr('data-id');
                $('#dataComment').empty();
                html = `
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Product Number</label>
                        <input type="text" class="form-control" name="matnr" value="` + matnr + `" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Production Order</label>
                        <input type="text" class="form-control" name="pro" value="` + pro + `" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Material</label>
                        <input type="text" class="form-control" name="material" value="` + material + `" readonly>
                    </div>
                `;

                $('#dataComment').append(html);

                $.ajax({
                    url: "{{ url('ccr-get-komentar-material') }}",
                    type: "GET",
                    data:{production_order: pro, material_number: material},
                    dataType: "JSON",
                    success: function(detail) {
                        $.ajax({
                            url: "{{ url('ccr-get-komentar') }}",
                            type: "GET",
                            dataType: "JSON",
                            success: function(data) {
                                $('#comments').empty();
                                $("#comments").append(`
                                    <option value="0"  @if (`+ 0 == comment +`) selected @endif>-</option>
                                `);
                                for (i = 0; i < data.komentar.length; i++) {
                                    if (data.komentar[i].id != detail.id.komentar) {
                                        $("#comments").append(`
                                            <option value="` + data.komentar[i].id + `">` + data.komentar[i].komentar +`</option>
                                        `);
                                        $("#comments").attr('data-id', id_material);
                                    } else {
                                        $("#comments").append(`
                                            <option value="` + data.komentar[i].id +`" selected >` + data.komentar[i].komentar +`</option>
                                        `);
                                        $("#comments").attr('data-id', id_material);
                                    }
                                }
                            }
                        });
                    }
                });

                $('#komentarModal').modal('show');

            });

            
            $('#komentarModal').on('change', '#comments', function() {
                var id_material    = $(this).attr('data-id');
                var value_komentar    = $(this).find(':selected').val();
                if (value_komentar != '' || value_komentar != null) {
                    $.ajax({
                        url  : "{{ url('create-comment-pro') }}",
                        type : "POST",
                        data :{
                            id_material    : id_material,
                            value          : value_komentar
                        },
                        success: function(data) {
                            if (data.kode == 1) {
                                $('#komentarModal').modal('hide');
                                toastr.success("Data successfully updated", "Success !!");
                            }else{
                                toastr.error("Data failed to save", "Error !!");
                            }
                        }
                    });           
                }
            });

        });
    </script>
@endsection
