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
                    <h2>Planning Unit History</h2>
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
                                                    <?php
                                                    $pro = [];
                                                    $jumlah_order = [];
                                                    foreach ($data['apps'] as $apps) {
                                                        array_push($pro, $apps->production_order);
                                                        array_push($jumlah_order, $apps->quantity);
                                                        $product_number = $apps->product_number;
                                                        $maktx = $apps->product_description;
                                                    }
                                                    echo substr(implode(', ', $pro), 0, 47);
                                                    ?>
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
                                            <td style="line-height: normal">{{ $maktx }}</td>
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
                                            @foreach ($data['sn'] as $sn)
                                                @php
                                                    $list_pro[] = $sn->production_order;
                                                @endphp
                                                <th><a href="{{ url('production-order-planning-history/' . $sn->production_order) }}" class="text-light">{{ $sn->production_order }}</a></th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($data['sn'] as $sn)
                                                @php
                                                    $persen = $sn->persen == null ? " (0%)" : " (". $sn->persen. "% )";
                                                @endphp
                                                <th data-order="{{ $sn->serial_number }}" data-toggle="tooltip" title="{{ $sn->sch_start_date == null ? 'Start Date Not Found' : date('d-m-Y', strtotime($sn->sch_start_date)) }}">{{ $sn->serial_number.$persen }}</th>
                                            @endforeach
                                        </tr>
                                        <input type="hidden" id="data_pro" value='{{ implode('#', $list_pro) }}'>
                                    </thead>
                                    <tbody>
                                        @php
                                            $new_sn = [];
                                            $a = $data['sn']->firstItem()-1;
                                            foreach ($data['sn'] as $paginate_sn) {
                                                $current                = $paginate_sn->toArray();
                                                $current['sn_index']    = $a++;
                                                $new_sn[]               = (object) $current;
                                            }
                                            $production_order = null;
                                            $serial_number = null;
                                            $class = null;
                                        @endphp
                                        @foreach ($data['material'] as $unit)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-left" data-toggle="tooltip" data-placement="left" title="{{ $unit->material_description }}">
                                                    {{ trim($unit->material_number) }}
                                                </td>
                                                <td class="text-left">{{ $unit->material_description }}</td>
                                                <td>{{ $unit->material_type }}</td>
                                                <td data-order="{{ round($unit->requirement_quantity, 3) }}">{{ str_replace('.', ',', round($unit->requirement_quantity, 3)) }}</td>
                                                <td data-order="{{ round($unit->good_issue, 3) }}">{{ str_replace('.', ',', round($unit->good_issue, 3)) }}</td>
                                                @foreach ($new_sn as $sn)
                                                    @php
                                                        $color        = 'black';
                                                        $content      = '-';
                                                        $component    = null;
                                                        $id_material    = null;
                                                    @endphp
                                                    @foreach ($data['material_list'] as $material)
                                                        @if ($sn->production_order == $material->production_order)
                                                            @if ($material->material_number == $unit->material_number)
                                                                @php
                                                                    $id_material      = $material->id;
                                                                    $production_order = $material->production_order;
                                                                    $serial_number    = $sn->serial_number;
                                                                    $component        = $material->material_number;
                                                                    $color            = '#fbff69';
                                                                                                                                        
                                                                    if ($material->requirement_quantity != 0) {
                                                                        $req_pro      = $material->requirement_quantity / $material->quantity;
                                                                    } else {
                                                                        $req_pro      = 1;
                                                                    }
                                                                    
                                                                    $withdraw         = $material->good_issue;
                                                                    
                                                                    $prevUsage = array_filter($data['sn_all'], function($item) use($production_order, $sn){
                                                                        return $item['production_order'] == $production_order && $item['sn_index']< $sn->sn_index;
                                                                    });
                                                                    
                                                                    $withdraw         -= $req_pro * count($prevUsage);
                                                                    
                                                                    if ($material->requirement_quantity == 0) {
                                                                        // GREEN
                                                                        $color    = '#06FF00';
                                                                        $class    = "text-dark";
                                                                        $content  = 0;
                                                                    }else {
                                                                        if ($withdraw >= $req_pro) {
                                                                            // GREEN
                                                                            $color    = '#06FF00';
                                                                            $content  = round($req_pro, 3);
                                                                            $class    = "text-dark";
                                                                        } elseif ($withdraw <= 0) {
                                                                            // RED
                                                                            $color    = '#CF1B1B';
                                                                            $content  = 0;
                                                                            $class    = "text-light";
                                                                        } else {
                                                                            // ORANGE
                                                                            $class    = "text-dark";
                                                                            $color    = '#FFB830';
                                                                            $content  = round($withdraw, 3) . ' / ' . round($req_pro, 3);
                                                                        }
                                                                    }
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    @if ($data['actionmenu_komentar']->u != 1)
                                                        <td style="background-color: {{ $color }}" data-order="{{ $content }}" class="{{ $class }}">
                                                            {{ str_replace('.', ',', $content) }}
                                                        </td>
                                                    @else
                                                        <td style="background-color: {{ $color }}" data-order="{{ $content }}">
                                                            <a href="javascript:void(0)" data-product-number="{{ $product_number }}"
                                                                data-id="{{ $id_material }}"
                                                                data-material="{{ $component }}"
                                                                data-pro="{{ $production_order }}"
                                                                {{-- data-sn="{{ $serial_number }}" --}}
                                                                class="{{ $class }} comment">{{ str_replace('.', ',', $content) }}
                                                            </a>
                                                        </td>
                                                    @endif
                                                @endforeach
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
                // select:true,
                scrollX: true,
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

            $('#myTable tbody').on('click', '.comment', function() {
                var material          = $(this).attr('data-material');
                var product_number    = $(this).attr('data-product-number');
                var production_order  = $(this).attr('data-pro');
                var id_material_history       = $(this).attr('data-id');
                $('#dataComment').empty();
                html = `
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Product Number</label>
                        <input type="text" class="form-control" name="product_number" value="` + product_number + `" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Production Order</label>
                        <input type="text" class="form-control" name="production_order" value="` + production_order + `" readonly>
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
                    data:{production_order: production_order, material_number: material},
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
                                        $("#comments").attr('data-id', id_material_history);
                                    } else {
                                        $("#comments").append(`
                                            <option value="` + data.komentar[i].id +`" selected >` + data.komentar[i].komentar +`</option>
                                        `);
                                        $("#comments").attr('data-id', id_material_history);
                                    }
                                }
                            }
                        });
                    }
                });

                $('#komentarModal').modal('show');
            });

            $('#komentarModal').on('change', '#comments', function() {
                var id_material_history       = $(this).attr('data-id');
                var value_komentar    = $(this).find(':selected').val();
                if (value_komentar != '' || value_komentar != null) {
                    $.ajax({
                        url : "{{ url('create-comment-pro') }}",
                        type    : "POST",
                        data:{
                            id_material_history : id_material_history,
                            value               : value_komentar
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
