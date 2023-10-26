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
              <th>User Name</th>
              <th>Vendor Code</th>

              <th>Action</th>

            </tr>
          </thead>
          <tbody>

            @foreach ($subcontvendor1 as $k=> $item)

            <tr>
              <td>{{ $k+1 }}</td>
              <td class="text-left">{{ $item->Username }}</td>

              <td class="text-left">
                @foreach ($subcontvendor as $k=> $item1)
                @if ($item->Username == $item1->Username)
                    {{ $item1->VendorCode }},
                @endif

                @endforeach
            </td>

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
</div>
<div class="modal fade" id="edit" aria-hidden="true">
    <form method="post" action="{{url('update-subcontdevvendor')}}" enctype="multipart/form-data">
        {{csrf_field()}}
    <div class="modal-dialog">
       <form id="companydata">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit Sequence Progress Reason</h2>
                    <br>
                    <div class=row>

                        <div class="col-12">
                    <div class="form-group text-left">
                        <label class="form-control-label"> Vendor: *</label><br>
                        <select name="Vendorcode[]" class="form-control " id="selectcode" multiple="multiple" style="width: 200px;">

                        </select>
                        </div>
                </div>
                    </div>
                </div>

                <input type="hidden" name="ID" id="IDreason" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
       </form>
    </div>
</div>
<div class="modal fade" id="delete" aria-hidden="true">
    <form method="post" action="{{url('delete-subcontdevvendor')}}" enctype="multipart/form-data">
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
<div id="modal-add-appsmenu" class="modal fade">
    <form method="post" action="{{url('insert-subcontdevvendor')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add Subcont Dev Vendor</h2>
                    <br>
                    <div class=row>
                        <div class="col-6">

                    <div class="form-group text-left">

                        <label class="form-control-label"> Username: *</label>

                        <select name="Username" class="form-control select2" style="width: 100%">
                            <option></option>
                            @foreach ($user as $item)


                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                     </div>

                        </div>
                        <div class="col-6">
                    <div class="form-group text-left select2-purple" >
                        <label class="form-control-label"> Vendor: *</label><br>
                        <select name="Vendorcode[]" class="form-control select2" data-dropdown-css-class="select2-purple" style="width: 100%;"  multiple="multiple" style="width: 200px;">
                            <option disabled>--Select Vendors--</option>
                            @foreach ($vendor as $item)

                            <option value="{{ $item->Code }}">{{ $item->Code }} - {{ $item->Name }}</option>
                            @endforeach
                        </select>
                        </div>
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



<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/select2/js/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function() {
    //edit data
    $('.edit').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
    url : "{{url('carisubcontdevvendor')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(response) {
               // $("#breeds").attr('disabled', false);
               $("#selectcode").empty();
                $.each(response,function(key, value)
                {
                    $("#selectcode").append('<option value=' + value.Code+ '>' +  value.Code + ' - ' +  value.Name + '</option>');
                    $('#edit').modal('show');
                });
             }
    });

    $('.delete').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url : "{{url('deletesubcontdevvendor')}}?id="+id,
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
    });
    </script>
<script>
$('.select2').select2();
$('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endsection

