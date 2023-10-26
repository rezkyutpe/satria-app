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
            <h1>Picking Transaction </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" autocomplete="off" type="search" value="" placeholder="Search" class="form-control">
                    </div>
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div>

                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PRO</th>
                            <th>Picking</th>
                            <th>PO REF</th>
                            <th>PN</th>
                            <th>Product</th>
                            <th>Is Invoiced</th>
                            <th >Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['po'] as $key=> $po)
                        <tr>
                            <td>{{ $po->no }} </td>
                            <td>@if($key==0)
                                {{ $po->pro }}
                                @elseif($data['po'][$key]['pro']==$data['po'][$key-1]['pro'])
                                {{ "" }}
                                @else
                                {{ $po->pro }}
                                @endif
                            </td>
                            
                            <td>{{ $po->nopo }}</td>
                            <td >{{ $po->po_ref }}</td>
                            <td >{{ $po->pn }}</td>
                            <td >{{ $po->product }}</td>
                            <td >@if($po->is_invoiced==0)
                                    <a data-toggle="modal" data-target="#modal-invoice-transaction-{{ $po->nopo }}"  title="Invoice Picking" ><i class="fa fa-send fa-lg custom--1"></i>Sent</a>
                                @else
                                    {{ "Invoiced" }}
                                @endif
                            </td>
                            <td>@if($po->flag==0)
                                    {{"Picking Created"}}
                                @elseif($po->flag==1)
                                    {{"Picking Supplied"}}
                                @elseif($po->flag==2)
                                    {{"Picking Received"}}
                                @elseif($po->flag==3)
                                    {{"Picking Finished"}}
                                @else
                                    {{"Closed"}}
                                @endif </td>
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                <a href="{{ url('history-transaction/'.$po->nopo) }}" title="History Picking" ><i class="fa fa-history fa-lg custom--1"></i></a>
                                <a href="{{ url('print-supplyed/'.$po->nopo) }}"  title="Print Picking" ><i class="fa fa-file-pdf-o fa-lg custom--1"></i></a>
                                <a href="{{ url('export-picking/'.$po->nopo) }}"  title="Export Picking" ><i class="fa fa-download fa-lg custom--1"></i></a>
                                    @if($po->flag<=2)
                                    <a href="{{ url('view-transaction/'.$po->nopo) }}" title="Supply Picking" ><i class="fa fa-eye fa-lg custom--1"></i></a>
                                    @endif
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

@foreach($data['po'] as $po)

<div id="modal-invoice-transaction-{{ $po->nopo }}" class="modal fade">
        <form method="post" action="{{url('invoice-transaction')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure invoice has beem sent?</p>
                    </div>
                    <input type="hidden" name="nopo" value="{{ $po->nopo }}"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endforeach
@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            if (search == null || search == ""){
                window.location.href="transaction";
            } else {
                window.location.href="transaction?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     false,
            "searching":     false,
        } );

        $("div.toolbar").html('<div class="float-right"> <a class=" " href="#"> <br></a></div>');
    });
    </script>
@endsection
