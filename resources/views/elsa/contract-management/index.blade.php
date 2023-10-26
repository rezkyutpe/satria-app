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
            <h1>Contract Management </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Contract No</th>
                            <th>Contract Desc</th>
                            <th>Category</th>
                            <th>Company</th>
                            <th>Contract Date</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['kontrak'] as $kontrak)
                        <tr>
                            <td>{{ $kontrak->no }}</td>
                            <td>{{ $kontrak->kontrak_no_kontrak }}</td>
                            <td>{{ $kontrak->kontrak_desc }}</td>
                            <td>@if($kontrak->kontrak_category==1)
                                {{ "Contract" }}
                                @else
                                {{ "License" }}
                                @endif
                            </td>
                            <td>{{ $kontrak->kontrak_perusahaan }}</td>
                            <td>{{ $kontrak->kontrak_date }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                <a  href="{{ url('detail-contract/'.$kontrak->kontrak_id)}}"><i class="fa fa-eye fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-kontrak-{{ $kontrak->kontrak_id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-kontrak-{{ $kontrak->kontrak_id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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
@foreach($data['kontrak'] as $kontrak)
    <!-- Modal Delete -->
    <div id="modal-edit-kontrak-{{ $kontrak->kontrak_id }}" class="modal fade">
        <form method="post" action="{{url('update-contract')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Contract</h2>
                        <br>
                        <div class="form-group text-left">
                            <input type="hidden" name="kontrak_id" value="{{ $kontrak->kontrak_id }}"/>
                            <label class="form-control-label">Company Contract: *</label>
                            <input type="text" name="kontrak_perusahaan" class="form-control" autocomplete="off" value="{{ $kontrak->kontrak_perusahaan }}" required="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="modal-delete-kontrak-{{ $kontrak->kontrak_id }}" class="modal fade">
        <form method="post" action="{{url('delete-contract')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $kontrak->kontrak_id }}"/>
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
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        <?php if($data['actionmenu']->c==1){ ?>
        $("div.toolbar").html('<a class="float-right btn btn-success" href="{{url('add-contract')}}">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
