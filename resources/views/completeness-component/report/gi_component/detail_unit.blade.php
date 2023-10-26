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
                                                        $plnbez = $apps->material_number;
                                                        $maktx = $apps->material_description;
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
                                                <td style="line-height: normal">{{ $plnbez }}</td>
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
                                <input type="hidden" id="material_number" value='{{ $plnbez }}'>
                                <table class="table nowrap table-striped table-bordered order-column" id="myTable" cellspacing="0" width="100%" style="text-align:center">
                                    <thead style="background-color: rgb(42,63,84);color:white;">
                                        <tr>
                                            <th style="vertical-align: middle">No.</th>
                                            <th style="vertical-align: middle"><?= wordwrap('Material Number', 3, "<br>\n") ?></th>
                                            <th style="vertical-align: middle"><?= wordwrap('Description', 3, "<br>\n") ?></th>
                                            <th style="vertical-align: middle"><?= wordwrap('Mat. Type', 3, "<br>\n") ?></th>
                                            <th style="vertical-align: middle"><?= wordwrap('Qty Req.', 3, "<br>\n") ?></th>
                                            <th style="vertical-align: middle"><?= wordwrap('Good Issue', 3, "<br>\n") ?></th>
                                            <th style="vertical-align: middle"><?= wordwrap('Allocated', 3, "<br>\n") ?></th>
                                            <th style="vertical-align: middle"><?= wordwrap('Persen Good Issue', 3, "<br>\n") ?></th>
                                            <th style="vertical-align: middle"><?= wordwrap('Persen Allocated', 3, "<br>\n") ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['material'] as $unit)
                                            @php
                                                if ($unit->sum_requirement_quantity == 0) {
                                                    $persen_gi = 100;
                                                    $persen_allocated = 100;
                                                } else {
                                                    $persen_gi = ($unit->sum_good_issue/$unit->sum_requirement_quantity)*100;
                                                    $persen_allocated = ($unit->sum_allocated/$unit->sum_requirement_quantity)*100;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-left">
                                                    {{ $unit->material_number }}
                                                </td>
                                                <td class="text-left">{{ $unit->material_description }}</td>
                                                <td>{{ $unit->material_type }}</td>
                                                <td data-order="{{ round($unit->sum_requirement_quantity, 3) }}">{{ str_replace('.', ',', round($unit->sum_requirement_quantity, 3)) }}</td>
                                                <td data-order="{{ round($unit->sum_good_issue, 3) }}">{{ str_replace('.', ',', round($unit->sum_good_issue, 3)) }}</td>
                                                <td data-order="{{ round($unit->sum_allocated, 3) }}">{{ str_replace('.', ',', round($unit->sum_allocated, 3)) }}</td>
                                                <td data-order="{{ round($persen_gi, 2) }}">{{ str_replace('.', ',', round($persen_gi, 2))."%" }}</td>
                                                <td data-order="{{ round($persen_allocated, 2) }}">{{ str_replace('.', ',', round($persen_allocated, 2))."%" }}</td>
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
                <form action="{{ url('comments-material-history') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <span id="dataComment"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
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
                responsive: true,
                scrollCollapse: true,
                fixedHeader: true,
                paging: true,
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> {
                        text: 'Download',
                        extend: 'excelHtml5',
                        exportOptions: {
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
                select:true,
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

            $('#myTable tbody').on('click', '.comment', function() {
                var material = $(this).attr('data-material');
                var matnr = $(this).attr('data-matnr');
                var pro = $(this).attr('data-pro');
                // var sn = $(this).attr('data-sn');
                var comment = $(this).attr('data-comment');
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
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Comments</label>
                        <textarea class="form-control" id="comments" name="comments">` + comment + `</textarea>
                    </div>
                `;

                $('#dataComment').append(html);
                $('#komentarModal').modal('show');

            });
        });
    </script>
@endsection