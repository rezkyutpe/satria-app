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
            <h1>Schedule Maintenance </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Asset</th>
                            <th>Cat Maintenance</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['schedule'] as $schedule)
                        <tr>
                            <td>{{ $schedule->no }}</td>
                            <td>{{ $schedule->asset_name }}</td>
                            <td>{{ $schedule->note }} </td>
                          
                            <td class="text-right">
                                <a href="#" data-toggle="modal" data-target="#modal-edit-schedule-{{ $schedule->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                <!-- <a href="#" data-toggle="modal" data-target="#modal-reset-schedule-{{ $schedule->id }}"><i class="fa fa-key fa-lg custom--1"></i></a> -->
                                
                                <a href="#" data-toggle="modal" data-target="#modal-delete-schedule-{{ $schedule->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                                

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    
    <div id="modal-add-schedule" class="modal fade">
        <form method="post" action="{{url('insert-schedule-maintenance')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Schedule</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Assets: *</label>
                        <select style="width: 100%" name="asset" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option>Please Select Assets</option>
                                @foreach($data['assets'] as $assets)
                                    <option value="{{ $assets->asset_id }}">{{ $assets->asset_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Category: *</label>
                        <select style="width: 100%" name="category" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option>Please Select Category</option>
                                @foreach($data['cat'] as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->note }}</option>
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
    <!-- Modal Edit schedule -->
    @foreach($data['schedule'] as $schedule)
    <div id="modal-edit-schedule-{{ $schedule->id }}" class="modal fade">
        <form method="post" action="{{url('update-schedule-maintenance')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Schedule</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Assets: *</label>
                          <input type="hidden" name="id" value="{{ $schedule->id }}"/>
                        <select style="width: 100%" name="asset" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option>Please Select Assets</option>
                                @foreach($data['assets'] as $assets)
                                    @if($schedule->asset==$assets->asset_id)
                                        <option selected value="{{ $assets->asset_id }}">{{ $assets->asset_name }}</option>
                                    @else
                                        <option value="{{ $assets->asset_id }}">{{ $assets->asset_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Category: *</label>
                        <select style="width: 100%" name="category" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option>Please Select Category</option>
                                @foreach($data['cat'] as $cat)
                                    @if($schedule->category==$cat->id)
                                        <option selected value="{{ $cat->id }}">{{ $cat->note }}</option>
                                    @else
                                        <option value="{{ $cat->id }}">{{ $cat->note }}</option>
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
    <div id="modal-delete-schedule-{{ $schedule->id }}" class="modal fade">
        <form method="post" action="{{url('delete-schedule-maintenance')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $schedule->id }}"/>
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
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-schedule" href="#">Tambah</a>');
        
    });
    </script>
@endsection
