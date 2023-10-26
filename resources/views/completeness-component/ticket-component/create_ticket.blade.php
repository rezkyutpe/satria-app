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
                    <form action="{{ url('proses-ticket-ccr') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                @if ($data['actionmenu']->c==1)
                                    <div class="d-flex justify-content-between mb-3">
                                        <label for="request_date" style="width: 10%; margin-top:10px;"><strong>Request Date</strong></label>
                                        <input type="date" class="form-control" name="request_date" min="{{ date('Y-m-d') }}" required>
                                        <button class="btn btn-primary" style="margin-left : 1%">Submit</button>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <input type="hidden" id="pro-number" value='<?= $data['apps']->production_order ?>'>
                                    <table class="table nowrap table-striped table-bordered dt-responsive" id="myTable" width="100%" style="text-align:center">
                                        <thead style="background-color: rgb(42,63,84);color:white">
                                            <tr style="line-height: 30px;">
                                                @if ($data['actionmenu']->c==1)
                                                    <th><input type="checkbox" name="id" class="checkItem" id="allpayment"></th>
                                                @endif
                                                <th style="vertical-align: middle"><?= wordwrap('Material Number', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Description', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Material Type', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Stock', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Requirement Quantity', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Good Issue', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Requested Quantity', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Requirement Date', 3, "<br>\n") ?></th>
                                                @if ($data['actionmenu']->c==1)
                                                    <th style="vertical-align: middle"><?= wordwrap('Request Quantity', 3, "<br>\n") ?></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['material'] as $material)
                                                @php
                                                    $max_request    = $material->requirement_quantity - $material->good_issue - $material->request_qty;
                                                    $color          = 'black';
                                                    $content        = null;
                                                @endphp
                                                <tr>
                                                    @if ($data['actionmenu']->c==1)
                                                        <input type="hidden" name="production_order" value="{{ $data['apps']->production_order }}">
                                                        <input type="hidden" name="id_all[]" value="{{ $material->id }}">
                                                        <td>
                                                            <input type="checkbox" name="id_checked[]" class="checkItem" value="{{ $material->id }}">
                                                        </td>
                                                    @endif
                                                    <td style="text-align: left">{{ trim($material->material_number) }}</td>
                                                    <td style="text-align: left">{{ trim($material->material_description) }}</td>
                                                    <td>{{ trim($material->material_type) }}</td>
                                                    <td data-order="{{ round($material->stock, 3) }}" data-toggle="tooltip" data-placement='left' title="Detail Stock">
                                                        <a href="#" class="detailStock text-dark"
                                                            data-toggle="modal" data-target="#StockModal"
                                                            data-material-number="{{ $material->material_number }}"
                                                        >
                                                            {{ str_replace('.', ',', round($material->stock, 3)) }}
                                                        </a>
                                                    </td>
                                                    <td data-order="{{ round($material->requirement_quantity, 3) }}">{{ str_replace('.', ',', round($material->requirement_quantity, 3)) }}</td>
                                                    <td data-order="{{ round($material->good_issue, 3) }}">{{ str_replace('.', ',', round($material->good_issue, 3)) }}</td>
                                                    <td data-order="{{ round($material->request_qty, 3) }}">{{ str_replace('.', ',', round($material->request_qty, 3)) }}</td>
                                                    <td data-order="{{ $material->requirement_date }}">{{ date('d-m-Y', strtotime($material->requirement_date)) }}</td>
                                                    @if ($data['actionmenu']->c==1)
                                                        <td>
                                                            <input type="number" name="request_qty[]" value="{{ $max_request }}" step=".01" max="{{ $max_request }}" class="form-control">
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
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
                dom               : "fBrtip",
                paging            : false,
                scrollCollapse    : true,
                fixedHeader       : true,
                buttons: [
                    'colvis'
                ],
                <?php if($data['actionmenu']->c==1) { ?>
                    columnDefs: [
                        { 
                            targets       : [0, 9],
                            searchable    : false,
                            orderable     : false,
                            visible       : true
                        },
                    ],
                <?php } ?>
                stateSave: true,
                scrollX   : true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                }
            });
        });

        $('#allpayment').click(function(){
            if ($(this).is(':checked') ==  true)
            {
                $('.checkItem').prop('checked', true);
            }
            else
            {
                $('.checkItem').prop('checked', false);
            }
        });

    </script>

@endsection
