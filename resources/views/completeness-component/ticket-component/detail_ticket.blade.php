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
                                                        Ticket No.
                                                    </a>
                                                </td>
                                                <td>:</td>
                                                <td style="width: 30%">{{ $data['apps']->ticket }}</td>
                                            </div>
                                            <div class="col-md-6">
                                                <td>Request By</td>
                                                <td>:</td>
                                                <td>{{ $data['apps']->created_by }}</td>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <div class="col-md-12">
                                            <div class="col-md-7">
                                                <td style="width: 13%">Production Order</td>
                                                <td>:</td>
                                                <td>{{ $data['apps']->production_order }}</td>
                                            </div>
                                            <div class="col-md-5">
                                                <td>Request Date</td>
                                                <td>:</td>
                                                <td style="line-height: normal">{{ date('d-m-Y', strtotime($data['apps']->req_date)) }}</td>
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
                            <form action="{{ route('proses-ticket-ccr.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($data['actionmenu']->u==1)
                                    @if ($data['qty_checked'] < $data['qty_all'])
                                        <div class="d-flex justify-content-between mb-3">
                                            <button class="btn btn-primary" name="save" disabled>Submit</button>
                                        </div>
                                    @endif
                                @endif
                                <div class="table-responsive">
                                    <input type="hidden" id="pro-number" value='<?= $data['apps']->AUFNR ?>'>
                                    <table class="table nowrap table-striped table-bordered dt-responsive" id="myTable" width="100%" style="text-align:center">
                                        <thead style="background-color: rgb(42,63,84);color:white">
                                            <tr>
                                                @if ($data['actionmenu']->u==1)
                                                    <input type="hidden" id="checked_qty" value="{{ $data['qty_checked'] }}" readonly>
                                                    <th>
                                                        <input type="checkbox" name="id" class="checkItem" id="allpayment" @if ($data['qty_checked'] == $data['qty_all']) checked disabled  @endif>
                                                    </th>
                                                @endif
                                                <th style="vertical-align: middle"><?= wordwrap('Material Number', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Description', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Material Type', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Stock', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Requirement Quantity', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Requirement Date', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Request Quantity', 3, "<br>\n") ?></th>
                                                <th style="vertical-align: middle"><?= wordwrap('Accepted Quantity', 3, "<br>\n") ?></th>
                                                @if ($data['actionmenu']->u==1)
                                                    <th style="vertical-align: middle"><?= wordwrap('Quantity', 3, "<br>\n") ?></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach ($data['material'] as $material)
                                                @php
                                                    $qty_unit   = $material->requirement_quantity == 0 ? 1 :$material->requirement_quantity;
                                                    $id_matnr   = 0;
                                                    $color      = 'black';
                                                    $content    = null;
                                                @endphp
                                                <tr>
                                                    @if ($data['actionmenu']->u==1)
                                                        <input type="hidden" name="id_ticket" value="{{ $material->id_ticket }}" @if ($material->request_quantity - $material->accepted_quantity <= 0) disabled @endif>
                                                        <input type="hidden" name="id_all[]" value="{{ $material->id }}" @if ($material->request_quantity - $material->accepted_quantity <= 0) disabled @endif>
                                                        <td>
                                                            <input type="checkbox" name="id_checked[]" id="checked" value="{{ $material->id }}" @if ($material->status == 1)
                                                                checked disabled
                                                            @else
                                                                class="checkItem"
                                                            @endif>
                                                        </td>
                                                    @endif
                                                    <td style="text-align: left">
                                                        {{ trim($material->material_number) }}
                                                    </td>
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
                                                    <td data-order="{{ $material->requirement_date }}">{{ date('d-m-Y', strtotime($material->requirement_date)) }}</td>
                                                    <td>{{ $material->request_quantity }}</td>
                                                    <td>{{ $material->accepted_quantity }}</td>
                                                    @if ($data['actionmenu']->u==1)
                                                        <td>
                                                            <input type="number" name="acc_qty[]" id="acc_qty" step=".01"  max="{{ $material->request_quantity - $material->accepted_quantity  }}" value="{{ $material->request_quantity - $material->accepted_quantity  }}" class="form-control" @if ($material->request_quantity - $material->accepted_quantity <= 0) disabled @endif>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
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
            var events = $('#events');
            var table =    
            $('#myTable').DataTable({
                dom: "fBrtip",
                paging: false,
                scrollCollapse: true,
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
                                    data = $('<p>' + data + '</p>').text();
                                    return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                                }
                            }
                        },
                    }
                    <?php } ?>
                ],
                columnDefs: [
                    <?php if ($data['actionmenu']->u==1) { ?>
                        { 
                            targets: [0, 9], 
                            searchable: false, 
                            orderable: false, 
                            visible: true 
                        },
                    <?php } ?>
                    { 
                        targets: [5], 
                        searchable: true, 
                        visible: false 
                    },
                ],
                stateSave: true,
                scrollX: true,
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

    <script>
        var qty_ceklist = document.getElementById("checked_qty").getAttribute("value");
        $('input[type=checkbox]').on('change', function(evt) {
            var checked = $('input[id=checked]:checked');
            if(checked.length - qty_ceklist < 1){
                $("button[name=save]").prop("disabled", true);
            }else{
                $("button[name=save]").prop("disabled", false);
            }
        });
    </script>

@endsection
