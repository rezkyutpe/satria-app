@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">

        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Detail Data Delivery </h2>
                    <a class="btn btn-warning btn-sm " href="{{ url('potracking') }}"><i class="fa fa-chevron-left">
                        </i> back </a>
                    <div class="clearfix"></div>

                </div>
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
                <div class="x_content">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <table id="datatable-visibility" class="table table-striped table-bordered text-center"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>PO Item</th>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            <th>Delivery Date</th>
                                            <th>Security Date</th>
                                            <th>Vendor</th>
                                            <th>No Ticket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $k => $item)
                                            <tr>
                                                <td>{{ $item->Number }}</td>
                                                <td>{{ $item->ItemNumber }}</td>
                                                <td>{{ $item->Material }}</td>
                                                <td>{{ $item->Description }}</td>
                                                <td>{{ $item->Quantity }}</td>
                                                <td>{{ date('d/m/Y H:i:s', strtotime($item->DeliveryDate)) }}</td>
                                                <td>{{ date('d/m/Y H:i:s', strtotime($item->SecurityDate)) }}</td>
                                                <td>{{ $item->CreatedBy }}</td>
                                                <td>{{ $item->TicketID }}</td>
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

    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assetss/vendors/select2/js/select2.full.min.js') }}"></script>
@endsection
