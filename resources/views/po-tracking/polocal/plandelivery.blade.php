@extends('po-tracking.panel.master')
@section('content')

    <style>
        h6 {
            font-size: 0.9rem;
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

                @include('po-tracking.panel.cardmenu')

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

                <form method="post" action="{{ url('confirmticketlocal') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                @if ($actionmenu->c == 1)
                                    <input type="submit" name="action" class="btn btn-primary" value="Confirm Delivery">
                                @endif
                                @if ($actionmenu->u == 1)
                                    <input type="submit" name="action" class="btn btn-warning" value="Update Delivery"
                                        data-toggle="modal"
                                        onclick="return confirm('Apakah anda  Yakin Update Pengiriman ?');">
                                    <button type="button" name="action" class="btn btn-danger" value="Cancel Delivery"
                                        data-toggle="modal" data-target="#exampleModalCancel">Cancel Delivery</button>
                                    <button type="button" class="btn btn-info fullcalendarclass" style="float: right;">Show
                                        Calendar</button>
                                @endif

                                <a href="{{ url("$menu_ticket") }}" style="float: right;" class="btn btn-success">History
                                    Ticket</a>

                                <table id="datatable-visibility" class="table table-striped table-bordered"
                                    style="width:100%">
                                    <thead>
                                        <tr>

                                            <th>Vendor Name</th>
                                            <th>PO Number</th>
                                            <th>PO Number OLD</th>
                                            <th>PO Item</th>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>PO Qty</th>
                                            <th>ID Ticket</th>
                                            <th>Ticket Create Date</th>
                                            <th>Ticket Qty</th>
                                            <th>SPB Number</th>
                                            <th>SPB Date</th>
                                            <th>Delivery Date Agreed</th>
                                            <th>Ticket Delivery Date</th>
                                            <th>Ticket Delivery Time</th>
                                            @if ($actionmenu->v == 1)
                                                <th>Stock CCR</th>
                                                <th>Req. Date CCR</th>
                                                <th>PO Type</th>
                                            @endif
                                            @if ($actionmenu->u == 1)
                                                <th><input type="checkbox" name="ID" class="checkItem" id="allpayment">
                                                </th>
                                            @else
                                                <th>Status</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr class="baseBlock">
                                                @php
                                                    $deliveryDate = $item->DeliveryDate;
                                                    $deliveryDates = $item->DeliveryDateS;
                                                    $releaseDate = $item->ReleaseDate;
                                                    $release = new DateTime($releaseDate);
                                                    $dates = new DateTime($deliveryDate);
                                                    $date = new DateTime($deliveryDates);
                                                    $ticketcreatedate = new DateTime($item->created_at);
                                                    $spbdate = new DateTime($item->SPBDate);
                                                    if ($item->ReleaseDate == null) {
                                                        $releases = '-';
                                                    } else {
                                                        $releases = $release->format('d/m/Y');
                                                    }

                                                    if ($item->req_date == null) {
                                                        $reqdateccr = '-';
                                                    } else {
                                                        $reqdateccr = new DateTime($item->req_date);
                                                        $reqdateccr = $reqdateccr->format('d/m/Y');
                                                    }

                                                    if ($item->stock == null) {
                                                        $totalstockccr = 0;
                                                    } else {
                                                        $totalstockccr = $item->stock;
                                                    }

                                                    $confirmeddate = new DateTime($item->ConfirmDeliveryDate);
                                                    $confirmeddates = $confirmeddate->format('d/m/Y');

                                                    if ($item->VendorType == 'Vendor Local') {
                                                        $potype = 'Local';
                                                    } elseif ($item->VendorType == 'Vendor SubCont') {
                                                        $potype = 'SubCont';
                                                    } else {
                                                        $potype = '-';
                                                    }
                                                @endphp
                                                <td>{{ $item->Vendor }}</td>
                                                <td class="text-left po_number">
                                                    <a href="javascript:void(0);" class="text-dark btn-edit"
                                                        data-toggle="modal" data-target="#detailsPO"
                                                        data-po-date="{{ $date->format('d/m/Y') }}"
                                                        data-vendor-code="<?= $item->VendorCode ?>"
                                                        data-vendor-code-new="<?= $item->VendorCode_new ?>"
                                                        data-vendor="{{ $item->Vendor }}"
                                                        data-release-date="{{ $releases }}"
                                                        data-po-creator="{{ $item->PurchaseOrderCreator }}"
                                                        data-po-nrp="{{ $item->NRP }}"
                                                        style="cursor: pointer;">{{ $item->Number }}</a>
                                                </td>
                                                <td>{{ $item->Number_old == null ? '-' : $item->Number_old }}</td>
                                                <td>{{ $item->ItemNumber }}</td>
                                                <td class="text-left">{{ $item->Material }}</td>
                                                <td class="text-left">{{ $item->Description }}</td>
                                                <td>{{ $item->QtySAP }}</td>
                                                <td><a href="javascript:void(0);" class="text-dark btn-edit view_ticket"
                                                        data-toggle="modal" data-id="{{ $item->ID }}"
                                                        style="cursor: pointer;">{{ $item->TicketID }}</a></td>

                                                <td>{{ $ticketcreatedate->format('d/m/Y H:i:s') }}</td>
                                                <td>{{ $item->Quantity }}</td>
                                                <td>{{ $item->DeliveryNote }}</td>
                                                <td>{{ $spbdate->format('d/m/Y') }}</td>
                                                <td>{{ $confirmeddates }}</td>
                                                @if ($actionmenu->u == 1)
                                                    <td data-order="{{ $item->DeliveryDate }}"><input type="text"
                                                            style="width: auto;" class="form-control datepicker"
                                                            name="deliverydate[]" value="{{ $dates->format('d/m/Y') }}"
                                                            required> </td>
                                                    <td><input type="time" class="form-control" name="time[]"
                                                            value="{{ $dates->format('H:i:s') }}"> </td>
                                                    <input type="hidden" name="IDS[]" value="{{ $item->ID }}">
                                                @else
                                                    <td class="po_date">{{ $dates->format('d/m/Y') }}</td>
                                                    <td>{{ $dates->format('H:i:s') }}</td>
                                                @endif
                                                @if ($actionmenu->v == 1)
                                                    <td>{{ $totalstockccr }}</td>
                                                    <td>{{ $reqdateccr }}</td>
                                                    <td>{{ $potype }}</td>
                                                @endif
                                                @if ($actionmenu->u == 1)
                                                    <td><input type="checkbox" id="id" name="ID[]"
                                                            class="checkItem" value="{{ $item->ID }}"></td>
                                                @else
                                                    <td><span class="badge badge-warning ">Wait Approve WHS</span></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal fade" id="exampleModalCancel" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Reason Cancel Delivery</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <label for="">Request Delivery Date</label> <input
                                        class="form-control datepicker" type="text" name="ConfirmTicketDate"
                                        id="">
                                </div>
                                <div class="col-12">
                                    <label for="">Remarks</label>
                                    <textarea name="remarks" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="action" class="btn btn-danger" value="Cancel">
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#allpayment').click(function() {

                if ($(this).is(':checked') == true) {
                    $('.checkItem').prop('checked', true);
                } else {
                    $('.checkItem').prop('checked', false);
                }
            });
            $(".datepicker").datepicker({
                format: "dd/mm/yyyy",
                startDate: '+2',
                autoclose: true,
                todayHighlight: true,
                toggleActive: true,

            });
        });
    </script>

@endsection
@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    {{-- detailpo --}}
    @include('po-tracking.panel.modaldetailPO')
    @include('po-tracking.panel.calendar_ticket')
@endsection
