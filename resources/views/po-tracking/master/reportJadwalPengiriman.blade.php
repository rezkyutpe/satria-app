@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Report Jadwal Pengiriman
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="well" style="overflow: auto;">
                    <form action="{{ url('jadwalpengiriman') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-2 mt-2">
                                    <strong>Ticket Delivery Date:</strong>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="datefilter" id="datefilter"
                                        value="{{ request()->session()->get('datefilter') }}" class="form-control"
                                        autocomplete="off" value="" placeholder="Range Date" required />

                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url('jadwalpengiriman?reset=1') }}" class="btn btn-danger">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="x_content">
                    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#terbaik" role="tab"
                                aria-controls="home" aria-selected="true">Jadwal Pengiriman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#terburuk" role="tab"
                                aria-controls="profile" aria-selected="false">History Pengiriman</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active table-responsive" id="terbaik" role="tabpanel">
                            <table
                                id="datatable-responsive-terbaik"class="table text-center table-striped table-bordered dt-responsive"
                                cellspacing="0" width="100%">
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
                                        <th>Delivery Date Agreed</th>
                                        <th>Ticket Delivery Date</th>
                                        <th>AcceptDate</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datapengiriman as $item)
                                        <tr class="baseBlock">
                                            @php
                                                $PoDate = $item->Date;
                                                $DeliveryDateSAP = $item->DeliveryDates;
                                                $confirmedDate = $item->ConfirmedDate;
                                                $deliveryDate = $item->DeliveryDate;
                                                $releaseDate = $item->ReleaseDate;
                                                $acceptedate = $item->AcceptedDate;
                                                $securitydate = $item->SecurityDate;
                                                $warehousedate = $item->WarehouseDate;
                                                if ($item->SecurityDate == null) {
                                                    $security = '';
                                                } else {
                                                    $securitys = new DateTime($securitydate);
                                                    $security = $securitys->format('d/m/Y H:i:s');
                                                }
                                                if ($item->WarehouseDate == null) {
                                                    $warehouse = '';
                                                } else {
                                                    $warehouses = new DateTime($warehousedate);
                                                    $warehouse = $warehouses->format('d/m/Y H:i:s');
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
                                                if ($item->QtySAP == 0) {
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

                                            @endphp
                                            <td>{{ $item->CreatedBy }}</td>
                                            <td class="po_number">
                                                <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal"
                                                    data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}"
                                                    data-vendor-code="<?= $item->VendorCode ?>"
                                                    data-vendor-code-new="<?= $item->VendorCode_new ?>"
                                                    data-vendor="{{ $item->Name }}"
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
                                            <td>{{ $confirmeddates }}</td>
                                            <td>{{ $dates->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $accept->format('d/m/Y H:i:s') }}</td>
                                            <td>
                                                @if ($item->status == 'A' || $item->status == 'D')
                                                    <span class="badge badge-danger"> On Delivery</span>
                                                @elseif ($item->status == 'S')
                                                    <span class="badge badge-danger"> In Security</span>
                                                @elseif ($item->status == 'W')
                                                    <span class="badge badge-success"> At Warehouse</span>
                                                @elseif ($item->status == 'R')
                                                    <span class="badge badge-success"> On Progress Parking</span>
                                                @elseif ($item->status == 'X')
                                                    <span class="badge badge-success"> Ticket Close</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="terburuk" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-pane table-responsive" id="terbaik" role="tabpanel">
                                <table id="datatable-responsive-terburuk"
                                    class="table text-center table-striped table-bordered dt-responsive" cellspacing="0"
                                    width="100%">
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
                                            <th>Delivery Date Agreed</th>
                                            <th>Ticket Delivery Date</th>
                                            <th>AcceptDate</th>
                                            <th>SecurityDate</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datahistorypengiriman as $item)
                                            <tr class="baseBlock">
                                                @php
                                                    $PoDate = $item->Date;
                                                    $DeliveryDateSAP = $item->DeliveryDates;
                                                    $confirmedDate = $item->ConfirmDeliveryDate;
                                                    $deliveryDate = $item->DeliveryDate;
                                                    $releaseDate = $item->ReleaseDate;
                                                    $acceptedate = $item->AcceptedDate;
                                                    $securitydate = $item->SecurityDate;
                                                    $warehousedate = $item->WarehouseDate;
                                                    if ($item->SecurityDate == null) {
                                                        $security = '';
                                                    } else {
                                                        $securitys = new DateTime($securitydate);
                                                        $security = $securitys->format('d/m/Y H:i:s');
                                                    }
                                                    if ($item->WarehouseDate == null) {
                                                        $warehouse = '';
                                                    } else {
                                                        $warehouses = new DateTime($warehousedate);
                                                        $warehouse = $warehouses->format('d/m/Y H:i:s');
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
                                                    if ($item->QtySAP == 0) {
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

                                                @endphp
                                                <td>{{ $item->CreatedBy }}</td>
                                                <td class=" po_number">
                                                    <a href="javascript:void(0);" class="text-dark btn-edit"
                                                        data-toggle="modal" data-target="#detailsPO"
                                                        data-target="#detailsPO"
                                                        data-po-date="{{ $date->format('d/m/Y') }}"
                                                        data-vendor-code="<?= $item->VendorCode ?>"
                                                        data-vendor="{{ $item->Name }}"
                                                        data-release-date="{{ $releases }}"
                                                        data-po-creator="{{ $item->PurchaseOrderCreator }}"
                                                        data-po-nrp="{{ $item->NRP }}"
                                                        style="cursor: pointer;">{{ $number }}</a>
                                                </td>
                                                <td>{{ $itemnumber }}</td>
                                                <td>{{ $material }}</td>
                                                <td>{{ $description }}</td>
                                                <td>{{ $item->QtySAP }}</td>
                                                <td><a href="javascript:void(0);" class="text-dark btn-edit view_ticket"
                                                        data-toggle="modal" data-id="{{ $item->ID }}"
                                                        style="cursor: pointer;">{{ $item->TicketID }}</a></td>
                                                <td>{{ $item->Quantity }}</td>
                                                <td>{{ $confirmeddates }}</td>
                                                <td>{{ $dates->format('d/m/Y H:i:s') }}</td>
                                                <td>{{ $accept->format('d/m/Y H:i:s') }}</td>
                                                <td>{{ $security }}</td>
                                                <td>
                                                    @if ($item->status == 'A' || $item->status == 'D')
                                                        <span class="badge badge-danger"> On Delivery</span>
                                                    @elseif ($item->status == 'S')
                                                        <span class="badge badge-danger"> In Security</span>
                                                    @elseif ($item->status == 'W')
                                                        <span class="badge badge-success"> At Warehouse</span>
                                                    @elseif ($item->status == 'R')
                                                        <span class="badge badge-success"> On Progress Parking</span>
                                                    @elseif ($item->status == 'X')
                                                        <span class="badge badge-success"> Ticket Close</span>
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
    </div>

@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('input[name="datefilter"]').daterangepicker({
                // ranges: {
                //     'Today': [moment(), moment()],
                //     'Next 7 Days': [moment(), moment().add(7, 'days')],
                //     'Next 30 Days': [moment(),moment().add(30, 'days')],
                //     'This Month': [moment().startOf('month'), moment().endOf('month')]
                // },
                alwaysShowCalendars: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });



        });
    </script>

    {{-- <script src="{{ asset('public/assetss/datatable/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/jszip.min.js') }}"></script> --}}
    {{-- Modal DetailPo  --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable-responsive-terbaik').DataTable({
                dom: 'Bfrtip',
                fixedHeader: true,
                scrollCollapse: true,
                ordering: false,
                columnDefs: [{
                    visible: false
                }],
                buttons: [
                    'pageLength', 'colvis',
                    <?php if ($action_menu->r==1 || $action_menu->u==1|| $action_menu->d==1|| $action_menu->v==1 || $action_menu->c==1){ ?> {
                        text: 'Download',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ url('jadwalpengiriman-download') }}";
                        }
                    }
                    <?php } ?>
                ],
                select: true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });
            $('#datatable-responsive-terburuk').DataTable({
                dom: 'Bfrtip',
                fixedHeader: true,
                scrollCollapse: true,
                ordering: false,
                columnDefs: [{
                    visible: false
                }],
                buttons: [
                    'pageLength', 'colvis',
                    <?php if ($action_menu->r==1 || $action_menu->u==1|| $action_menu->d==1|| $action_menu->v==1 || $action_menu->c==1){ ?> {
                        text: 'Download',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ url('jadwalpengiriman-download') }}";
                        }
                    }
                    <?php } ?>
                ],
                select: true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });


            $("#month").change(function() {
                $('#years').val('2022').change();
            });

        });
    </script>
    @include('po-tracking.panel.modaldetailPO')
@endsection
@endsection
