@extends('po-tracking.panel.master')
@section('content')
    <style>
        h6 {
            font-size: 0.9rem;
        }

        .contohPDF {
            display: inline-block;
            object-fit: cover;
            margin: 5px;
        }

        .contohPDF:hover {
            transform: scale(2);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
            -moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            -o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
    </style>
    <div class="clearfix"></div>
    <div class="row">

        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $header_title }}</h2>
                    <ul class="nav navbar-right panel_toolbox">

                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
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
                                        <th>Vendor Name</th>
                                        <th>PO Number</th>
                                        <th>PO Item</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>PO Qty</th>
                                        <th>ID Ticket</th>
                                        <th>Ticket Qty</th>
                                        <th>SPB Number</th>
                                        <th>SPB Date</th>
                                        <th>Delivery Date Agreed</th>
                                        <th>Ticket Delivery Date</th>
                                        <th>AcceptDate</th>
                                        <th>Status</th>
                                        @if ($actionmenu->v == 1 || $actionmenu->u == 1)
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="baseBlock" data-toggle="tooltip" title="{{ $item->remarks }}">
                                            @php
                                                $PoDate = $item->Date;
                                                $DeliveryDateSAP = $item->DeliveryDates;
                                                $confirmedDate = $item->ConfirmDeliveryDate;
                                                $deliveryDate = $item->DeliveryDate;
                                                $releaseDate = $item->ReleaseDate;
                                                $acceptedate = $item->AcceptedDate;
                                                $spbdate = new DateTime($item->SPBDate);
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
                                                if ($item->POQuantity == 0) {
                                                    $qtysap = $item->QtySAP;
                                                } else {
                                                    $qtysap = $item->POQuantity;
                                                }
                                                if ($item->Material == null) {
                                                    $material = $item->Materials;
                                                } else {
                                                    $material = $item->Material;
                                                }
                                                if ($item->Description == null) {
                                                    $description = $item->Descriptions;
                                                } else {
                                                    $description = $item->Description;
                                                }

                                                $release = new DateTime($releaseDate);
                                                $confirmed = new DateTime($confirmedDate);
                                                $deliverydates = new DateTime($DeliveryDateSAP);
                                                $date = new DateTime($PoDate);
                                                $dates = new DateTime($deliveryDate);

                                                $accept = new DateTime($acceptedate);
                                                if ($item->DeliveryDates == null) {
                                                    $confirmeddates = $confirmed->format('d/m/Y');
                                                } else {
                                                    $confirmeddates = $deliverydates->format('d/m/Y');
                                                }
                                                if ($item->ReleaseDate == null) {
                                                    $releases = '-';
                                                } else {
                                                    $releases = $release->format('d/m/Y');
                                                }
                                                if ($item->AcceptedDate == null) {
                                                    $accepts = '-';
                                                } else {
                                                    $accepts = $accept->format('d/m/Y H:i:s');
                                                }

                                            @endphp
                                            <td>{{ $item->CreatedBy }}</td>
                                            <td class="text-left po_number">
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
                                            <td>{{ $material }}</td>
                                            <td>{{ $description }}</td>
                                            <td>{{ $item->QtySAP }}</td>
                                            <td><a href="javascript:void(0);" class="text-dark btn-edit view_ticket"
                                                    data-toggle="modal" data-id="{{ $item->ID }}"
                                                    style="cursor: pointer;">{{ $item->TicketID }}</a></td>
                                            <td>{{ $item->Quantity }}</td>
                                            <td>{{ $item->DeliveryNote }}</td>
                                            <td>{{ $spbdate->format('d/m/Y') }}</td>
                                            <td>{{ $confirmeddates }}</td>
                                            <td>{{ $dates->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $accepts }}</td>
                                            <td>
                                                @if ($item->status == 'A' || $item->status == 'D')
                                                    <span class="badge badge-warning"> On Delivery</span>
                                                @elseif ($item->status == 'S')
                                                    <span class="badge badge-danger"> Security UTPE</span>
                                                @elseif ($item->status == 'R')
                                                    <span class="badge badge-primary">On Delivery UTPE</span>
                                                @endif

                                            </td>
                                            @if ($actionmenu->v == 1 || $actionmenu->u == 1)
                                                <td>
                                                    @if ($actionmenu->u == 1 && ($item->status == 'A' || $item->status == 'D'))
                                                        <button type="button" name="action"
                                                            class="btn btn-warning btn-sm exampleModalUpdate"
                                                            data-id="{{ $item->TicketID }}" data-status="Update"
                                                            data-toggle="modal"><i class="fa fa-edit"></i></button>
                                                    @endif
                                                    @if ($actionmenu->d == 1 && ($item->status == 'A' || $item->status == 'D' || $item->status == 'S'))
                                                        <button type="button" name="action"
                                                            class="btn btn-danger btn-sm exampleModalUpdate"
                                                            data-id="{{ $item->TicketID }}" data-status="Cancel"
                                                            data-toggle="modal"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                    @if ($actionmenu->v == 1)
                                                        @if ($item->DeliveryNote == null || $item->DeliveryNote == '-' || $item->DeliveryNote == '')
                                                            <button type="button" name="action"
                                                                class="btn btn-success btn-sm exampleModalUpdate"
                                                                data-id="{{ $item->TicketID }}"
                                                                data-status="DeliveryNote"><i
                                                                    class="fa fa-file-text-o"></i></button>
                                                        @else
                                                            <form method="post" action="{{ url('print-ticketsubcont') }}"
                                                                enctype="multipart/form-data">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="TicketID" class="checkItem"
                                                                    value="{{ $item->TicketID }}">
                                                                <button type="submit" class="btn btn-primary btn-sm"
                                                                    formtarget="_blank"><i
                                                                        class="fa fa-ticket"></i></button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endif
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
                    <span id="headers"></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ url('prosesreadydelivery-subcont') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body" id="Content">

                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Save">
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
            $('.exampleModalUpdate').on("click", function() {
                $('#headers').empty();
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');

                if (status == 'Update' || status == 'Cancel') {
                    $("#headers").append(`
                            <h5 class="modal-title" id="exampleModalCenterTitle">Reason ` + status + ` Delivery</h5>
                           `);
                    $('#Content').html(`
                        <div class="row">
                            <div class="col-12">
                                <label for="">Delivery Date</label> <input class="form-control datepicker"
                                    type="text" name="ConfirmTicketDate" autocomplete="off">

                            </div>
                            <div class="col-12">
                                <label for="">Remarks</label>
                                <input type="hidden" name="ID" value="` + id + `">
                                <input type="hidden" name="status" value="` + status + `">
                                <textarea name="remarks" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>

                    `);
                } else {
                    $("#headers").append(`
                            <h5 class="modal-title" id="exampleModalCenterTitle">Delivery Note</h5>
                           `);
                    $('#Content').html(`
                        <div class="row">
                            <code>*Wajib Isi</code>
                            <div class="col-12">
                                <input type="hidden" name="ID" value="` + id + `">
                                <input type="hidden" name="status" value="` + status + `">
                                <label for="">NO Delivery Note</label>
                                    <input class="form-control"
                                    type="text" name="DeliveryNote" autocomplete="off" required>
                            </div>
                            <div class="col-12">
                                    <label for="">Header Text</label>  <input class="form-control"
                                    type="text" name="HeaderText" autocomplete="off">
                            </div>

                        </div>

                    `);

                }
                $('#Update').modal('show');
                $(".datepicker").datepicker({
                    format: "dd/mm/yyyy",
                    autoclose: true,
                });
            });

        });
    </script>
    {{-- detailpo --}}
    @include('po-tracking.panel.modaldetailPO')
@endsection
