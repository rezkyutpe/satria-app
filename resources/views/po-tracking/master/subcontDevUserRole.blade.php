@extends('po-tracking.panel.master')
@section('content')


        <div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>List User Vendor </h2>
        <a class="btn btn-primary btn-sm pull-right"  data-toggle="modal" data-target="#modal-add-appsmenu" href="#">Create New <i class="fa fa-plus"> </i></a>
        {{-- <ul class="nav navbar-right panel_toolbox">

          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>

          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul> --}}
        <div class="clearfix"></div>


      </div>
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
      <div class="x_content">

          <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">

        <table id="datatable-responsive" class="table text-center table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>

            <tr>
              <th>No</th>
              <th>User Name</th>
              <th>Role Name</th>
              <th>Ishead</th>
              <th>Action</th>

            </tr>
          </thead>
          <tbody>

            @foreach ($data as $k=> $item)

            <tr>
              <td>{{ $k+1 }}</td>
              <td class="text-left">{{ $item->Username }}</td>
              <td>{{ $item->RoleID }}</td>
              <td><input type="checkbox"  disabled="disabled"></td>
              <td >

                <a href="#"  class="edit" data-toggle="modal" data-id="{{ $item->ID }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>

                <a href="#" class="delete" data-toggle="modal"  data-id="{{ $item->ID }}" ><i class="fa fa-trash fa-lg custom--1"></i></a>

            </td>
        </tr>
        @endforeach

          </tbody>
        </table>


      </div>
    </div>
  </div>
</div>
    </div>
  </div>
</div>
<div id="modal-add-appsmenu" class="modal fade">
    <form method="post" action="{{url('insert-subcontdevuserroles')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add User Role</h2>
                    <br>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Username: *</label>
                        <input type="text" name="Username" class="form-control" autocomplete="off" value="" required="">
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Roles Type: *</label>
                        <select name="RoleID" id="cariroles" class="form-control">
                            <option value=""></option>
                            @foreach ($dataRole as $item)
                            <option value="{{ $item->ID }}">{{ $item->Name }}</option>
                            @endforeach
                            </select>
                        </div>
                    <div class="form-group text-left">
                        <label class="form-control-label"> IsHead: *</label>
                        <input type="checkbox" class="check-box" name="IsHead" class="form-control" autocomplete="off" value="1" >
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
<div class="modal fade" id="delete" aria-hidden="true">
    <form method="post" action="{{url('delete-subcontdevuserroles')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Warning</h2>
                    <p>Are you sure delete ? </p>

                    </div>

                <input type="hidden" name="ID" id="ID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modal-edit" aria-hidden="true">
    <form method="post" action="{{url('update-delayreason')}}" enctype="multipart/form-data">
        {{csrf_field()}}
    <div class="modal-dialog">
       <form id="companydata">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit User Role</h2>
                    <br>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Username: *</label>
                        <input type="text" name="Username" id="Username" class="form-control" autocomplete="off" value="" required="">
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Roles Type: *</label>
                        <select name="RoleID"   class="form-control">
                            <option value="" id="RoleID"></option>

                            </select>
                        </div>
                    <div class="form-group text-left">
                        <label class="form-control-label"> IsHead: *</label>
                        <input type="checkbox" class="check-box" name="IsHead" id="IsHead" class="form-control" autocomplete="off" value="1" >
                    </div>
                <input type="hidden" name="ID" id="IDs" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
       </form>
    </div>



<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
    //edit data
    $('.edit').on("click" ,function() {
    var id = $(this).attr('data-id');
    // fetch('http://localhost/editreason?id=2')
    $.ajax({
    url : "{{url('carisubcontdevuserroles')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#IDs').val(data.ID);
        $('#Username').val(data.Username);
        $('#RoleID').val(data.RoleID);
        $('#IsHead').val(data.IsHead);
         $('#modal-edit').modal('show');
        }
        });
    });

    $('.delete').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url : "{{url('carisubcontdevuserroles')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

        $('#ID').val(data.ID);
        $('#delete').modal('show');
        }
        });
    });

    });
    </script>
@endsection
