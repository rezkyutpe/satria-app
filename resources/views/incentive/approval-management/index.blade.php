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
            <h1>Approval Management</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" type="search" value="" autocomplete="off" placeholder="Search NRP" class="form-control">
                    </div>
                     <div class="float-left col-xl-2 col-md-2 col-xs-4 m-b-10px">
                        <label>Fully Approved at :</label>
                    </div>
                    <div class="col-xl-12 col-md-3 m-b-10px">
                       
                        <div class="form-group">
                            <input id="month-value" type="month" @if(isset($_GET['month'])) value="{{ $_GET['month'] }}" @endif name="date" class="form-control date" min="" max="" required/>
                        </div>
                    </div>
                   
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                        @if(isset($_GET['month']))
                        <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-export" href="#">Export</a>
                        @endif
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
                            <th>Fully Approved</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['approval'] as $approval)
                        <tr>
                            <td>{{ $approval->no }}</td>
                            <td>{{ $approval->id_req }}</td>
                            <td>{{ $approval->sales }}</td>
                            <td>{{ $approval->sales_name }}</td>
                            <td>{{ date('Y-m',strtotime($approval->req_month)) }}</td>
                            <td>{{ 'Rp '.number_format($approval->total_inc,0,',','.') }}</td>
                            <td>@if($approval['status']=='0')
                                {{ 'New'}}
                                @elseif($approval['status']=='1')
                                {{ 'Partial Approved'}}
                                @elseif($approval['status']=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif 
                            </td>

                            <td>@if($approval['status']=='2')
                                    {{ date('Y-m-d',strtotime($approval->updated_at)) }}
                                 @else
                                    {{ '-' }}
                                @endif </td>
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                <a href="{{ url('approval-view/'.$approval->id_req) }}"><i class="fa fa-eye fa-lg custom--1"></i></a>
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


<div id="modal-export" class="modal fade">
        <form method="post" action="{{url('export-requests')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to export report Fully Approved at @if(isset($_GET['month'])){{ $_GET['month'] }} @endif?</p>
                    </div>
                    <input type="hidden" name="date" @if(isset($_GET['month'])) value="{{ $_GET['month'] }}" @endif required/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                       
                        <button type="submit" class="btn btn-success">Yes</button>
                        
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            var month = $('#month-value').val();
            if (search == "" && month == ""){
                window.location.href="approval-management";
            }else if (search == "" ){
                window.location.href="approval-management?month="+month;
            }else if (month == "" ){
                window.location.href="approval-management?search="+search;
            }else {
                window.location.href="approval-management?search="+search+"&month="+month;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
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
