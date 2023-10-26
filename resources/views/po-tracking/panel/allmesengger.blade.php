@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>

    <div class="row">

        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Messenger </h2>
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
                                <table id="datatable-visibility"
                                    class="table table-striped table-bordered dt-responsive nowrap text-center"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Number</th>
                                            <th>ItemNumber</th>
                                            <th>User By</th>
                                            <th>Menu</th>
                                            <th>Comment</th>
                                            <th>Status</th>
                                            <th>Send</th>
                                            <th>Read</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notif as $k => $item)
                                            @php
                                                if ($item->is_read == 1) {
                                                    $status = 'Read';
                                                } else {
                                                    $status = '';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-left">{{ $item->Number }}</td>
                                                <td class="text-left">{{ $item->ItemNumber }}</td>
                                                <td class="text-left">{{ $item->user_by }}</td>
                                                <td class="text-left">{{ $item->menu }}</td>
                                                <td class="text-left">{{ $item->comment }}</td>
                                                <td class="text-left">{{ $status }}</td>
                                                <td class="text-left">{{ $item->created_at }}</td>
                                                <td class="text-left">{{ $item->updated_at }}</td>

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
