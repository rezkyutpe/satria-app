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
            <h1>Picking Management</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" autocomplete="off" type="search" value="" placeholder="Search by Pro/PN" class="form-control">
                    </div>
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div>

                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Picking</th>
                            <th>PO REF</th>
                            <th>PRO</th>
                            <th>PN</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['po'] as $po)
                        <tr>
                            <td>{{ $po->no }} </td>
                            
                            <td>{{ $po->nopo }}</td>
                            <td >{{ $po->po_ref }}</td>
                            <td >{{ $po->pro }}</td>
                            <td >{{ $po->pn }}</td>
                            <td >{{ $po->product }}</td>
                            <td >{{ $po->cust }}</td>
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
                                @if($data['actionmenu']->c==1)
                                <a href="{{ url('view-picking/'.$po->nopo) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Create Picking from History" ><i class="fa fa-plus fa-lg custom--1"></i> Copy</a>
                                @endif
                                @if($data['actionmenu']->v==1)
                                <a href="{{ url('print-picking/'.$po->nopo) }}" ><i class="fa fa-file-pdf-o fa-lg custom--1"></i></a>
                                <a href="{{ url('export-picking/'.$po->nopo) }}" ><i class="fa fa-download fa-lg custom--1"></i></a>

                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-picking-{{ $po->nopo }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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
<div id="modal-add-history" class="modal fade">
        <form method="post" action="{{url('add-picking-package')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Select Package</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Package Name: *</label>
                            <select name="po" class="form-control selectpicker">
                                @foreach($data['po'] as $po)
                                    <option value="{{ $po->nopo }}">{{ $po->nopo }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                    <input type="hidden" name="id" value=""/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    </div>
<div id="modal-add-picking" class="modal fade">
        <form method="post" action="{{url('add-picking-package')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Select Package</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Package Name: *</label>
                            <select name="package" class="form-control selectpicker">
                                @foreach($data['package'] as $package)
                                    <option value="{{ $package->package }}">{{ $package->package }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                    <input type="hidden" name="id" value=""/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@foreach($data['po'] as $po)

<!-- Modal Delete po -->
<div id="modal-delete-picking-{{ $po->nopo }}" class="modal fade">
    <form method="post" action="{{url('delete-picking')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Warning</h2>
                    <p>Are you sure?</p>
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
                window.location.href="picking-management";
            } else {
                window.location.href="picking-management?search="+search;
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

        $("div.toolbar").html('<div class="float-right"> <a class=" btn btn-success"  data-toggle="modal" data-target="#modal-add-picking" href="#">Create From Package</a> <a class=" btn btn-success" href="{{ url('add-picking') }}">Create Manual</a></div>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
