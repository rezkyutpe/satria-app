@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Log Parking Invoice</h2>
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
                                            <th>PO Number</th>
                                            <th>Invoice Number</th>
                                            <th>Description</th>
                                            <th>Name</th>
                                            <th>User/Email</th>
                                            <th>Date Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataPI as $item)
                                                <tr>
                                                    <td>{{ $item->Number }}</td>
                                                    <td>{{ $item->InvoiceNumber }}</td>
                                                    <td>{{ $item->Description }}</td>
                                                    <td>{{ $item->Name }}</td>
                                                    <td>{{ $item->updated_by }}</td>
                                                    <td data-order="{{ $item->created_at }}">{{ date('d-m-Y H:i:s',strtotime($item->created_at)) }}</td>
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
@endsection
