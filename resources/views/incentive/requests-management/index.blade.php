@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

<div class="content-body-white">
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
    <div class="page-head">
        <div class="page-title">
            <h1>Requests Management</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" type="search" value="" autocomplete="off" placeholder="Search" class="form-control">
                    </div>
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div>
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>NRP</th>
                            <th>Name</th>
                            <th>Cahsin Month</th>
                            <th>Amount Incentive</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['requests'] as $requests)
                        <tr>
                            <td>{{ $requests->no }}</td>
                            <td>{{ $requests->id_req }}</td>
                            <td>{{ $requests->sales }}</td>
                            <td>{{ $requests->sales_name }}</td>
                            <td>{{ date('Y-m',strtotime($requests->req_month)) }}</td>
                            <td>{{ 'Rp '.number_format($requests->total_inc,0,',','.') }}</td>
                            <td>@if($requests['status']=='0')
                                {{ 'New'}}
                                @elseif($requests['status']=='1')
                                {{ 'Partial Approved'}}
                                @elseif($requests['status']=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif 
                            </td>
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                <a href="{{ url('requests-view/'.$requests->id_req) }}"><i class="fa fa-eye fa-lg custom--1"></i></a>
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



@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            if (search == null || search == ""){
                window.location.href="requests-management";
            } else {
                window.location.href="requests-management?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     false,
            "searching":     false,
        } );
        <?php if($data['actionmenu']->c==1){ ?>
            $("div.toolbar").html('<br>'); 
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
