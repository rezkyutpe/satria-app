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
            <h1>Category Maintenance </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Note</th>
                            <th>Durasi</th>
                            <th>Alert</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['cat'] as $cat)
                        <tr>
                            <td>{{ $cat->no }}</td>
                            <td>{{ $cat->note }}</td>
                            <td>{{ $cat->durasi }} Days</td>
                            <td>{{ $cat->start_alert }} Days</td>
                            <!-- <td>@if($cat->flag==1)
                                {{ "Active" }}
                                @else
                                {{ "Inactive" }}
                                @endif
                            </td> -->
                            <td class="text-right">
                                <a href="#" data-toggle="modal" data-target="#modal-edit-cat-{{ $cat->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                <!-- <a href="#" data-toggle="modal" data-target="#modal-reset-cat-{{ $cat->id }}"><i class="fa fa-key fa-lg custom--1"></i></a> -->
                                
                                <a href="#" data-toggle="modal" data-target="#modal-delete-cat-{{ $cat->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                                

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    
    <div id="modal-add-cat" class="modal fade">
        <form method="post" action="{{url('insert-cat-maintenance')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Category</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Note: *</label>
                            <input type="text" name="note" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Durasi: *</label>
                            <input type="number" name="durasi" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Start Alert: *</label>
                            <input type="number" name="start_alert" class="form-control" autocomplete="off" required="">
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
    <!-- Modal Edit cat -->
    @foreach($data['cat'] as $cat)
    <div id="modal-edit-cat-{{ $cat->id }}" class="modal fade">
        <form method="post" action="{{url('update-cat-maintenance')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Category</h2>
                        <br>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $cat->id }}"/>
                            <label class="form-control-label">Note: *</label>
                            <input type="text" name="note" class="form-control" autocomplete="off" value="{{ $cat->note }}" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Durasi: *</label>
                            <input type="number" name="durasi" class="form-control"  value="{{ $cat->durasi }}" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Start Alert: *</label>
                            <input type="number" name="start_alert" class="form-control"  value="{{ $cat->start_alert }}" autocomplete="off" required="">
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
    <div id="modal-delete-cat-{{ $cat->id }}" class="modal fade">
        <form method="post" action="{{url('delete-cat-maintenance')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $cat->id }}"/>
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
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-cat" href="#">Tambah</a>');
        
    });
    </script>
@endsection
