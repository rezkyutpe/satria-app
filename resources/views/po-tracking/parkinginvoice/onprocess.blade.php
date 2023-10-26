@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>On Process Parking Invoice</h2>
                    <div class="clearfix"></div>
                </div>

                @if(session()->has('err_message'))
                    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('err_message') }}
                    </div>
                @endif

                @if(session()->has('suc_message'))
                    <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('suc_message') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table text-center table-striped table-bordered dt-responsive" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Vendor</th>
                                            <th>PO Creator</th>
                                            <th>PO Number</th>
                                            <th>Invoice Date</th>
                                            <th>Invoice Number</th>
                                            <th>Tax Invoice Number</th>
                                            <th>Created at</th>
                                            <th>Parking Number</th>
                                            <th>Received</th>
                                            <th>Approved</th>
                                            <th>Action/Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataPI as $item)
                                                <tr>
                                                    <td>{{ $item->Name }}</td>
                                                    <td>{{ $item->PurchaseOrderCreator }}</td>
                                                    <td class="po_number align-middle text-center">
                                                        <a href="javascript:;"
                                                            class="text-dark btn-edit"
                                                            data-toggle="modal"
                                                            data-target="#detailsPO"
                                                            data-po-date="{{ (new DateTime($item->Date))->format('d/m/Y') }}"
                                                            data-vendor-code="{{ $item->VendorCode }}"
                                                            data-vendor-code-new="{{ $item->VendorCode_new }}"
                                                            data-vendor="{{ $item->Name }}"
                                                            data-release-date="{{ (new DateTime($item->ReleaseDate))->format('d/m/Y') }}"
                                                            data-po-creator="{{ $item->PurchaseOrderCreator }}"
                                                            data-po-nrp="{{ $item->NRP }}"
                                                            style="cursor: pointer;">
                                                            {{ $item->Number }}
                                                        </a>
                                                    </td>
                                                    <td class="align-middle text-center" data-order="{{ $item->InvoiceDate }}">{{ date('d/m/Y', strtotime($item->InvoiceDate)) }}</td>
                                                    <td class="align-middle text-center">{{ $item->InvoiceNumber }}</td>
                                                    <td class="align-middle text-center">{{ $item->Reference }}</td>
                                                    <td class="align-middle text-center" data-order="{{ $item->created_at }}">
                                                        {{ (new DateTime($item->created_at))->format('d/m/Y H:i:s') }}
                                                    </td>
                                                    <td>{{ $item->ParkingNumber == null ? '-' : $item->ParkingNumber }}</td>
                                                    <td class="align-middle text-center">
                                                        @if($item->ReceiveDocumentDate != NULL)
                                                            <span class="badge badge-success"><i class="fa fa-check"></i></span></br>
                                                            <small>
                                                                @php
                                                                    $receive_date = new DateTime($item->ReceiveDocumentDate);
                                                                    echo $receive_date->format('d-m-Y');
                                                                @endphp
                                                            </small>
                                                        @else
                                                            <span class="badge badge-secondary"><i class="fa fa-minus"></i></span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        @if($item->ApproveParkingDate != NULL)
                                                                <span class="badge badge-success"><i class="fa fa-check"></i></span></br>
                                                                <small>
                                                                    @php
                                                                        $approve_date = new DateTime($item->ApproveParkingDate);
                                                                        echo $approve_date->format('d-m-Y');
                                                                    @endphp
                                                                </small>
                                                        @else
                                                            <span class="badge badge-secondary"><i class="fa fa-minus"></i></span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <a href="javascript:;" id="parking_detail" class="badge badge-info parkinginvoice_detail" data-parking="{{ $item->InvoiceNumber }}" data-created="{{ $item->created_at }}">View Document</a>
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
@endsection

@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable-newparking').DataTable({
                dom: 'Bfrtip',
                scrollCollapse: true,
                stateSave: true,
                columnDefs: [{
                    visible: false
                }],
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });
        });
    </script>

    {{-- Modal detailPO  --}}
    @include('po-tracking.panel.modaldetailPO')

    {{-- Modal parking invoive detail --}}
    @include('po-tracking.parkinginvoice.modal.parking_invoice_detail')
@endsection
