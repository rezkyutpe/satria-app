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
            <h1>Sla Management </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Resolution Time</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['sla'] as $sla)
                        <tr>
                            <td>{{ $sla->no }}</td>
                            <td>{{ $sla->name }}</td>
                            <td>{{ $sla->resolution_time }}</td>
                            <!-- <td>@if($sla->flag==1)
                                {{ "Active" }}
                                @else
                                {{ "Inactive" }}
                                @endif
                            </td> -->
                            <td class="text-right">
                                <a href="#" data-toggle="modal" data-target="#modal-edit-sla-{{ $sla->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                <!-- <a href="#" data-toggle="modal" data-target="#modal-reset-sla-{{ $sla->id }}"><i class="fa fa-key fa-lg custom--1"></i></a> -->
                                
                                <a href="#" data-toggle="modal" data-target="#modal-delete-sla-{{ $sla->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                                

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    
    <div id="modal-add-sla" class="modal fade">
        <form method="post" action="{{url('insert-sla')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Sla</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Name: *</label>
                            <input type="text" name="name" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Resolution Time: *</label>
                            <input type="number" name="resolution_time" class="form-control" autocomplete="off" required="">
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
    <!-- Modal Edit sla -->
    @foreach($data['sla'] as $sla)
    <div id="modal-edit-sla-{{ $sla->id }}" class="modal fade">
        <form method="post" action="{{url('update-sla')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Sla</h2>
                        <br>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $sla->id }}"/>
                            <label class="form-control-label">Name: *</label>
                            <input type="text" name="name" class="form-control" autocomplete="off" value="{{ $sla->name }}" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Resolution Time: *</label>
                            <input type="number" name="resolution_time" class="form-control"  value="{{ $sla->resolution_time }}" autocomplete="off" required="">
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
    
    <!-- Modal Delete -->
    <div id="modal-delete-sla-{{ $sla->id }}" class="modal fade">
        <form method="post" action="{{url('delete-sla')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $sla->id }}"/>
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
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-sla" href="#">Tambah</a>');
        
    });
    </script>
@endsection
