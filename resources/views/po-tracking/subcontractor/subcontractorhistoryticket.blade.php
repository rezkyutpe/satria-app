@extends('po-tracking.panel.master')
@section('content')

    <style>
        h6 {
            font-size: 0.9rem;
        }

        .blink-bg {
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            animation: blinkingBackground 2s infinite;
        }

        @keyframes blinkingBackground {
            0% {
                background-color: #FF0000;
            }

            25% {
                background-color: #FBBC05;
            }

            50% {
                background-color: #FF0000;
            }

            75% {
                background-color: #FBBC05;
            }

            100% {
                background-color: #FF0000;
            }
        }
    </style>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $header_title }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @include('po-tracking.panel.cardmenu_subcontractor')

                <div>
                    @if (session()->has('err_message'))
                        <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            {{ session()->get('err_message') }}
                        </div>
                    @endif
                    @if (session()->has('suc_message'))
                        <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            {{ session()->get('suc_message') }}
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-visibility" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>PO Item</th>
                                        <th>Material</th>
                                        <th>Qty</th>
                                        <th>ID Ticket</th>
                                        <th>DeliveryDate</th>
                                        <th>AcceptDate</th>
                                        <th>WarehouseRequestDate</th>
                                        <th>SecurityDate</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @php
                                            $PoDate = $item->Date;
                                            $confirmedDate = $item->ConfirmDeliveryDate;
                                            $deliveryDate = $item->DeliveryDate;
                                            $releaseDate = $item->ReleaseDate;
                                            $acceptedate = $item->AcceptedDate;
                                            $securitydate = $item->SecurityDate;
                                            $requestticket = $item->ConfirmTicketDate;
                                            $release = new DateTime($releaseDate);
                                            $confirmed = new DateTime($confirmedDate);
                                            $date = new DateTime($PoDate);
                                            $dates = new DateTime($deliveryDate);
                                            $accept = new DateTime($acceptedate);
                                            $whsrequest = new DateTime($requestticket);
                                            $security = new DateTime($securitydate);
                                            if ($item->ReleaseDate == null) {
                                                $releases = '-';
                                            } else {
                                                $releases = $release->format('d/m/Y');
                                            }
                                            if ($item->Number == null) {
                                                $number = $item->Numbers;
                                            } else {
                                                $number = $item->Number;
                                            }
                                            if ($item->ItemNumber == null) {
                                                $itemnumber = $item->ItemNumbers;
                                            } else {
                                                $itemnumber = $item->ItemNumber;
                                            }
                                            if ($item->Material == null || $item->Material == '') {
                                                $material = $item->Description;
                                            } else {
                                                $material = $item->Material;
                                            }
                                            if ($item->Description == null) {
                                                $description = $item->Descriptions;
                                            } else {
                                                $description = $item->Description;
                                            }

                                            if ($item->Quantity == null) {
                                                $releases = '-';
                                            } else {
                                                $releases = $release->format('d/m/Y');
                                            }
                                            if ($item->Description == null) {
                                                $releases = '-';
                                            } else {
                                                $releases = $release->format('d/m/Y');
                                            }
                                            if ($item->SecurityDate == null) {
                                                $securitys = '-';
                                            } else {
                                                $securitys = $security->format('d/m/Y');
                                            }
                                            if ($item->ConfirmTicketDate == null || $item->ConfirmTicketDate == 0000 - 00 - 00) {
                                                $requesticket = '-';
                                            } else {
                                                $requesticket = $whsrequest->format('d/m/Y');
                                            }
                                            if ($item->status == 'C') {
                                                $background = 'blink-bg';
                                            } else {
                                                $background = '';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="po_number">
                                                <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal"
                                                    data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}"
                                                    data-vendor-code="<?= $item->VendorCode ?>"
                                                    data-vendor-code-new="<?= $item->VendorCode_new ?>"
                                                    data-vendor="{{ $item->Vendor }}"
                                                    data-release-date="{{ $releases }}"
                                                    data-po-creator="{{ $item->PurchaseOrderCreator }}"
                                                    data-po-nrp="{{ $item->NRP }}"
                                                    style="cursor: pointer;">{{ $item->Number }}</a>
                                            </td>
                                            <td>{{ $itemnumber }}</td>
                                            <td class="text-left" data-toggle="tooltip" title="{{ $description }}">
                                                {{ $material }}</td>
                                            <td>{{ $item->Quantity }}</td>
                                            <td><a href="javascript:void(0);" class="text-dark btn-edit view_ticket"
                                                    data-toggle="modal" data-id="{{ $item->ID }}"
                                                    style="cursor: pointer;">{{ $item->TicketID }}</a></td>
                                            <td class="po_date">{{ $dates->format('d/m/Y') }}</td>
                                            <td class="po_date">{{ $accept->format('d/m/Y h:i:s') }}</td>
                                            <td class="po_date">{{ $requesticket }}</td>
                                            <td class="text-center">{{ $securitys }}</td>
                                            <td class="{{ $background }}">
                                                @if ($item->status == 'C')
                                                    <span class="badge badge-danger" data-toggle="tooltip"
                                                        title="{{ $item->remarks }}">Ticket Cancel </a> </span>
                                                @elseif ($item->status == 'S')
                                                    <span class="badge badge-warning">Security UTPE</span>
                                                @elseif ($item->status == 'Q')
                                                    <span class="badge badge-success">Ticket Close</span>
                                                @elseif ($item->status == 'E')
                                                    <span class="badge badge-secondary">Ticket Expired</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($actionmenu->d == 1 && $item->status == 'S')
                                                    <button type="button" name="action"
                                                        class="btn btn-sm btn-danger btn-sm exampleModalDelete"
                                                        data-id="{{ $item->TicketID }}" data-number="{{ $item->Number }}"
                                                        data-status="Delete" data-toggle="modal"><i
                                                            class="fa fa-trash"></i></button>
                                                @else
                                                    {{ $item->remarks }}
                                                @endif
                                                @if ($actionmenu->u == 1 && $item->status == 'E')
                                                    <button type="button" name="action"
                                                        class="btn btn-warning btn-sm exampleModalUpdate"
                                                        data-id="{{ $item->TicketID }}" data-number="{{ $item->Number }}"
                                                        data-status="Expired" data-toggle="modal"><i
                                                            class="fa fa-edit"></i></button>
                                                @endif
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
    <div class="modal fade" id="Update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span id="headerss"></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ url('prosesreverseticket-subcont') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="">Security Date</label> <input class="form-control datepicker"
                                    type="text" name="securitydate" autocomplete="off">

                            </div>
                            <div class="col-12">
                                <label for="">Remarks</label>
                                <input type="hidden" name="ID" id="IDs">
                                <input type="hidden" name="status" id="Statuss">
                                <textarea name="remarks" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Action">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span id="headers"></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ url('prosesreverseticket-subcont') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="">Remarks</label>
                                <input type="hidden" name="ID" id="ID">
                                <input type="hidden" name="status" id="Status">
                                <textarea name="remarks" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-danger" value="Cancel">
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            //edit data
            $('.exampleModalDelete').on("click", function() {
                $('#headers').empty();
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                var number = $(this).attr('data-number');

                $("#headers").append(`
                            <h5 class="modal-title" id="exampleModalCenterTitle">Reason ` + status + ` Delivery PO ` +
                    number + `</h5>
                           `);
                $('#ID').val(id);
                $('#Status').val(status);
                $('#Delete').modal('show');
            });
            $('.exampleModalUpdate').on("click", function() {
                $('#headerss').empty();
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                var number = $(this).attr('data-number');

                $("#headerss").append(`
                            <h5 class="modal-title" id="exampleModalCenterTitle"> Update ` + status + ` Delivery PO ` +
                    number + `</h5>
                           `);
                $('#IDs').val(id);
                $('#Statuss').val(status);
                $('#Update').modal('show');
            });

        });
    </script>
    {{-- detailpo --}}
    @include('po-tracking.panel.modaldetailPO')
    {{-- Modal View_ticket  --}}
    @include('po-tracking.subcontractor.modal.view_ticket')
@endsection
