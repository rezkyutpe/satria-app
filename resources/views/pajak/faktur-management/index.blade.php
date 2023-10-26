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
            <h1>Faktur Pajak Management</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <!-- <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" autocomplete="off" type="search" value="" placeholder="Search" class="form-control">
                    </div>
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-xl-12 col-md-3 m-b-10px">
                        <div class="form-group">
                            <label class="form-control-label">Filter :</label>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-3 m-b-10px">
                        <div class="form-group">
                            <label class="form-control-label">Bulan : </label>
                            <input id="date" type="month" @if(isset($_GET['date'])) value="{{ $_GET['date'] }}" @endif name="date" class="form-control date" min="" max="" required/>
                        </div>
                    </div>
                    <!-- <div class="col-xl-12 col-md-3 m-b-10px">
                        <div class="form-group">
                            <label class="form-control-label">Tanggal Akhir :</label>
                            <input id="date-end" type="date" name="to_date" class="form-control date" min="" max="" required/>
                        </div>
                    </div> -->
                    <div class="col-xl-12 col-md-3 m-b-10px">
                        <div class="form-group">
                            <label  class="form-control-label">Status : @if(isset($_GET['status'])) {{ $_GET['status'] }} @endif</label>
                            <select class="form-control" name="status" id="status">
                            @if(!isset($_GET['status']))
                                <option value="">SEMUA</option>
                                <option value="N">BELUM DI EXPORT</option>
                                <option value="Y">SUDAH DI EXPORT</option>
                            @elseif($_GET['status'] == 'N')
                                <option value="">SEMUA</option>
                                <option value="N" selected>BELUM DI EXPORT</option>
                                <option value="Y">SUDAH DI EXPORT</option>
                            @elseif($_GET['status'] == 'Y')
                                <option value="">SEMUA</option>
                                <option value="N">BELUM DI EXPORT</option>
                                <option value="Y" selected>SUDAH DI EXPORT</option>
                            @else
                            <option value="" selected>SEMUA</option>
                                <option value="N">BELUM DI EXPORT</option>
                                <option value="Y" >SUDAH DI EXPORT</option>
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-3 m-b-10px pull-right">   
                    <br>                        
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>

                        <button type="button" id="download-button"  class="btn btn-primary btn-report-download">Download <i class="fa fa-download"></i></button>
                    </div>
                </div>
                   <hr>
                        <!-- <input name="name" id="search-value" autocomplete="off" type="search" value="" placeholder="Search" class="form-control"> -->
                    
                      
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Faktur</th>
                            <th>Faktur Date</th>
                            <th>Penjual</th>
                            <th>Status Approval</th>
                            <th>Status Faktur</th>
                            <th class="text-center">Is Exp</th>
                            <th class="text-center">Referensi</th>
                            <th width="80" class="text-center">Action        </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['pajak'] as $pajak)
                        @php($exp_date =date('Y-m-', strtotime('+4 month', strtotime($pajak->tanggalfaktur))).'25')
                        <tr>
                            <td>{{ $pajak->no }}</td>
                            <td>{{ $pajak->nomorfaktur }}</td>
                            <td>{{ $pajak->tanggalfaktur }}</td>
                            <td>{{ $pajak->namapenjual." - ".$pajak->npwppenjual }}</td>
                            <td>{{ $pajak->statusapproval }}</td>
                            <td>{{ $pajak->statusfaktur }}</td>
                            <td>@if($exp_date>=date('Y-m-', strtotime($pajak->date_scan)))
                                {{ 0 }}
                                @else
                                {{ 1 }}
                                @endif
                            </td>
                            <td class="text-center">{{ $pajak->referensi }}</td>
                            <td class="text-center"> <a href="{{ url('faktur-export/'.$pajak->id) }}" ><i class="fa fa-download fa-lg custom--1"></i></a>|<a href="{{ url('print-faktur/'.$pajak->id) }}" ><i class="fa fa-file-pdf-o fa-lg custom--1"></i></a></td>
                        
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    <!-- Modal Edit pajak -->
   

@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var date = $('#date').val();
            var status = $('#status').val();
            if(date == "" && status == ""){ //jika status saja
                window.location.href="pajak-management";
            }else if(date == null || date == ""){ //jika status saja
                window.location.href="pajak-management?status="+status;
            }else if(status == null || status == ""){ //jika date saja
                window.location.href="pajak-management?date="+date;
            }else {
                window.location.href="pajak-management?date="+date+"&status="+status;
            }
        });
        $('#download-button').click(function(){
            var date = $('#date').val();
            var status = $('#status').val();
            if(date == "" && status == ""){ //jika status saja
                window.location.href="pajak-export";
            }else if(date == null || date == ""){ //jika status saja
                window.location.href="pajak-export?status="+status;
            }else if(status == null || status == ""){ //jika date saja
                window.location.href="pajak-export?date="+date;
            }else {
                window.location.href="pajak-export?date="+date+"&status="+status;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     true,
        } );

        // $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-pajak" href="#">Tambah</a>');
    });
    </script>
@endsection
