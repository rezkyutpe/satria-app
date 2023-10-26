@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<style>

h3 {
    font-family: 'Josefin Sans', sans-serif
}

.box {
    padding: 0px 0px
}

.box-part {
    /* background: #F0FFFF; */
    padding: 40px 10px;
    margin: 30px 0px;
    /* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); */
    margin-bottom: 25px
}

.box-part:hover {
    /* background: #000000 */
}

.box-part:hover .fa,
.box-part:hover .title,
.box-part:hover .text,
.box-part:hover a {
    color: #FFF;
    -webkit-transition: all 1s ease-out;
    -moz-transition: all 1s ease-out;
    -o-transition: all 1s ease-out;
    transition: all 1s ease-out
}

.text {
    margin: 20px 0px
}

.fa {
    color: #00BFFF
}
</style>
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
            <h1>Ticket Detail #{{ $data->ticket_id }}</h1>
        </div>
        <div class="pull-right">
            <span style="background-color: {{ $data->bg_color }}; color: {{ $data->fg_color }};" class="badge badge-secondary">{{ $data->flow_name}}</span>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-12">
        <table class="table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Priority</th>
                            <th>: {{ $data->priority_name }}<i data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Empty" class="fa fa-flag fa-lg custom--1" style="color: {{ $data->color }}" ></i>
                            <th> </th>
                            <th>Asset</th>
                            <th>: </th>
                            <th>  </th>
                            <th>Submiter</th>
                            <th>: {{ $data->submitter_name }}</th>
                            <th>  </th>
                        </tr>
                        <tr>
                            <th>User</th>
                            <th>: {{ $data->reporter_name }}</th>
                            <th></th>
                            <th>Assist </th>
                            <th>: {{ $data->assist_name }}</th>
                            <th> </th>
                            <th>Last Modified</th>
                            <th>: {{ $data->updated_at }}</th>
                            <th>  </th>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <th>: {{ $data->dept_reporter }}</th>
                            <th>  </th>
                            
                            <th>Sla Category</th>
                            <th>: </th>
                            <th></th>
                            <th>Response At</th>
                            <th>: {{ $data->respond_time }}</th>
                            <th>  </th>
                        </tr>
                    </thead>
                </table>
                <br>
                <hr>
                <div class="page-head">
                    <div class="page-title">
                        <h1>List Permission Apps Menu :</h1>
                    </div>
                </div>
        </div>
    </div>

</div>


@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        $('#sorting-table1').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        $('#sorting-table2').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
    });
    </script>
@endsection
