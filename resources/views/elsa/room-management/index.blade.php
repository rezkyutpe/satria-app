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
            <h1>Room Management </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['room'] as $room)
                        <tr>
                            <td>{{ $room->no }}</td>
                            <td>{{ $room->id }}</td>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->location }}</td>
                         
                            <td class="text-right">
                                <a href="#" data-toggle="modal" data-target="#modal-edit-room-{{ $room->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                <!-- <a href="#" data-toggle="modal" data-target="#modal-reset-room-{{ $room->id }}"><i class="fa fa-key fa-lg custom--1"></i></a> -->
                                
                                <a href="#" data-toggle="modal" data-target="#modal-delete-room-{{ $room->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                                

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    
    <div id="modal-add-room" class="modal fade">
        <form method="post" action="{{url('insert-room')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Docation</h2>
                        <br> 
                        <div class="form-group text-left">
                            <label class="form-control-label">ID : *</label>
                            <input type="number" name="id" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Name: *</label>
                            <input type="text" name="name" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Location: *</label>
                        <select style="width: 100%" name="location" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option>Please Select Location</option>
                                @foreach($data['location'] as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
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
    <!-- Modal Edit room -->
    @foreach($data['room'] as $room)
    <div id="modal-edit-room-{{ $room->id }}" class="modal fade">
        <form method="post" action="{{url('update-room')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit room</h2>
                        <br>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $room->id }}"/>
                            <label class="form-control-label">Name: *</label>
                            <input type="text" name="name" class="form-control" autocomplete="off" value="{{ $room->name }}" required="">
                        </div>
                        <div class="form-group text-left">
                        <label class="form-control-label">Location: *</label>
                        <select style="width: 100%" name="location" class="form-control js-example-basic-single" data-live-search="true" require="">
                            <option>Please Select Location</option>
                                @foreach($data['location'] as $location)
                                @if($room->location==$location->id)
                                    <option selected value="{{ $location->id }}">{{ $location->name }}</option>
                                @else
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endif
                                @endforeach
                            </select>
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
    <div id="modal-delete-room-{{ $room->id }}" class="modal fade">
        <form method="post" action="{{url('delete-room')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $room->id }}"/>
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
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-room" href="#">Tambah</a>');
        
    });
    </script>
@endsection
