@extends('po-tracking.panel.master')
@section('content')


        <div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>SubCont Dev Vendor </h2>
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
              <th>ParentID</th>
              <th>User Name</th>
              <th>FullName</th>
              <th>NRP</th>
              <th>Email</th>
              <th>Action</th>

            </tr>
          </thead>
          <tbody>

            @foreach ($data as $k=> $item)

            <tr>
              <td>{{ $k+1 }}</td>
              <td>{{ $item->ParentID }}</td>
              <td class="text-left">{{ $item->Username }}</td>
              <td class="text-left">{{ $item->FullName }}</td>
              <td>{{ $item->NRP }}</td>
              <td>{{ $item->Email }}</td>
              <td>


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
</div>

<div id="modal-add-appsmenu" class="modal fade">
    <form method="post" action="{{url('insert-userprocurementsuperior')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add User Procurement Superior</h2>
                    <br>
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> FullName: *</label>
                                <input type="text" name="FullName"  class="form-control" autocomplete="off" value="" >
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  Username: *</label>
                                <input type="text" name="Username" class="form-control" autocomplete="off" value="" required="" id="Code" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> ParentID: *</label>
                                <input type="text" name="ParentID" class="form-control" autocomplete="off" value="" >
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  NRP : *</label>
                                <input type="text" name="NRP" class="form-control" autocomplete="off" value="" required="" id="Code" >
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Email: *</label>
                                <input type="text" name="Email" class="form-control" autocomplete="off" value="">
                            </div>

                        </div>



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
<div class="modal fade" id="delete" aria-hidden="true">
    <form method="post" action="{{url('delete-userprocurementsuperior')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Warning</h2>
                    <p>Are you sure delete ? </p>

                    </div>

                <input type="hidden" name="id" id="ID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="edit" aria-hidden="true">
    <form method="post" action="{{url('update-userprocurementsuperior')}}" enctype="multipart/form-data">
        {{csrf_field()}}
    <div class="modal-dialog">
       <form id="companydata">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit User Procurement Superior</h2>
                    <br>
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> FullName: *</label>
                                <input type="text" name="FullName" id="FullName" class="form-control" autocomplete="off" value="" >
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  Username: *</label>
                                <input type="text" name="Username" id="Username" class="form-control" autocomplete="off" value="" required=""  >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> ParentID: *</label>
                                <input type="text" name="ParentID" id="ParentID" class="form-control" autocomplete="off" value="" readonly>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  NRP : *</label>
                                <input type="text" name="NRP" id="NRP" class="form-control" autocomplete="off" value="" required="" readonly >
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Email: *</label>
                                <input type="text" name="Email" id="Email" class="form-control" autocomplete="off" value="">
                            </div>

                        </div>



                </div>
                </div>

                <input type="hidden" name="id" id="IDS">
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

    $.ajax({
    url : "{{url('cariuserprocurementsuperior')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#IDS').val(data.ID);
        $('#ParentID').val(data.ParentID);
        $('#Username').val(data.Username);
        $('#FullName').val(data.FullName);
        $('#NRP').val(data.NRP);
        $('#Email').val(data.Email);


        $('#edit').modal('show');
        }
        });
    });

    $('.delete').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url : "{{url('cariuserprocurementsuperior')}}?id="+id,
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
