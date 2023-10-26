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
                            <table id="datatable-responsive" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                    <th>PO Number</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice Number</th>
                                    <th>No. Faktur Pajak</th>
                                    <th>Created at</th>
                                    <th>Received</th>
                                    <th>Approved</th>
                                    <th>Document Number</th>
                                    <th>Fiscal Year</th>
                                    <th>Action/Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="baseBlock">
                                            @php
                                                $acceptdate = $item->InvoiceDate;
                                                $date = new DateTime($acceptdate);
                                                $release = new DateTime($item->ReleaseDate);
                                                $createdtime = new DateTime($item->created_at);
                                                $temp = 0;
                                            @endphp
                                            <td class="text-left">{{ $item->Number }}</td>
                                            <td class="po_date">{{ $date->format('d/m/Y') }}</td>
                                            <td>{{ $item->InvoiceNumber }}</td>
                                            <td>{{ $item->Reference }}</td>
                                            <td class="align-middle text-center">
                                                {{ $createdtime->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($item->ReceiveDocumentDate != NULL)
                                                    <span class="badge badge-success"><i class="fa fa-check"></i></span></br>
                                                    <small>@php $receive_date = new DateTime($item->ReceiveDocumentDate); echo $receive_date->format('d-m-Y'); @endphp</small>
                                                @else
                                                    <span class="badge badge-secondary"><i class="fa fa-minus"></i></span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($item->ApproveParkingDate != NULL)
                                                        <span class="badge badge-success"><i class="fa fa-check"></i></span></br>
                                                        <small>@php $approve_date = new DateTime($item->ApproveParkingDate); echo $approve_date->format('d-m-Y'); @endphp</small>
                                                @else
                                                    <span class="badge badge-secondary"><i class="fa fa-minus"></i></span>
                                                @endif
                                            </td>
                                            <td>{{ $item->DocumentNumberSAP == null ? '-' : $item->DocumentNumberSAP }}</td>
                                            <td>{{ $item->FiscalYear == null ? '-' : $item->FiscalYear }}</td>
                                            <td class="align-middle text-center">
                                                @if($item->Status == "Request Parking" || $item->Status == "Validate Document" || $item->Status == "Receive Document")
                                                <a href="javascript:;" id="parking_detail" class="badge badge-info parkinginvoice_detail" data-parking="{{ $item->InvoiceNumber }}" data-created="{{ $item->created_at }}">View Document</a>
                                                @elseif($item->Status == "Reject Parking")
                                                <a href="javascript:;" id="parking_detail" class="badge badge-danger parkinginvoice_detail" data-parking="{{ $item->InvoiceNumber }}" data-created="{{ $item->created_at }}">Rejected</a>
                                                @else
                                                <a href="javascript:;" id="parking_detail" class="badge badge-success parkinginvoice_detail" data-parking="{{ $item->InvoiceNumber }}" data-created="{{ $item->created_at }}">Done</a>
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


    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>


@endsection
@section('myscript')
@section('top_javascript')

    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

    {{-- Modal parking invoive  --}}
    @include('po-tracking.panel.parking_invoice_detail')
@endsection
