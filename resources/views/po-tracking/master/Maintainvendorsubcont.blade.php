@extends('po-tracking.panel.master')
@section('content')


        <div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Maintain Sequence Leadtime Subcont </h2>
        <a class="btn btn-success btn-sm pull-right" href="{{url('exportmaintainvendorsubcont')}}" target="_blank"> Export CSV <i class="fa fa-print"></i></a> <a class="btn mr-1 pull-right btn-danger btn-sm" href="#" data-toggle="modal" data-target="#importExcel"> Import CSV <i class="fa fa-file-excel-o"></i></a>
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
              <th>Vendor Code</th>
              <th>Material</th>
              <th>Description</th>
              <th>PB</th>
              <th>Primer</th>
              <th>Action</th>

            </tr>
          </thead>
          <tbody>

            @foreach ($data as $k=> $item)

            <tr>
              <td>{{ $k+1 }}</td>
              <td>{{ $item->VendorCOde }}</td>
              <td>{{ $item->Material }}</td>
              <td class="text-left">{{ $item->Description }}</td>
              <td>{{ $item->PB }}</td>
              <td>{{ $item->Primer }}</td>


              <td class="text-center">

                <a href="#"  class="edit" data-toggle="modal" data-id="{{ $item->ID }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>

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
    <form method="post" action="{{url('insert-maintainvendorsubcont')}}" enctype="multipart/form-data">
        {{csrf_field()}}
    <div class="modal-dialog">
       <form id="companydata">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit Sequence Progress Reason</h2>
                    <br>
                    <div class="row">


                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Vendor Code: *</label>
                                <input type="text" name="VendorCode" id="VendorCode" class="form-control" autocomplete="off" value="" required="" id="Code" readonly>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Material: *</label>
                                <input type="text" name="Material" id="Material" class="form-control" autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Description: *</label>
                                <input type="text" name="Description" id="Description" class="form-control" autocomplete="off" value="" readonly>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> DailyLeadTime: *</label>
                                <input type="text" name="DailyLeadTime" id="DailyLeadTime" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> MonthlyLeadTime: *</label>
                                <input type="text" name="MonthlyLeadTime" id="MonthlyLeadTime" class="form-control" autocomplete="off" value="">
                            </div>

                        </div>

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> PB: *</label>
                                <input type="text" name="PB" id="PB" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Setting: *</label>
                                <input type="text" name="Setting" id="Setting" class="form-control" autocomplete="off" value="" >

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Fullweld: *</label>
                                <input type="text" name="Fullweld" id="Fullweld" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Primer: *</label>
                                <input type="text" name="Primer" id="Primer" class="form-control" autocomplete="off" value="" >

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> MonthlyCapacity: *</label>
                                <input type="text" name="MonthlyCapacity" id="MonthlyCapacity" class="form-control" autocomplete="off" value="" >
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
    </form>
</div>
<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{url('import-maintainvendorsubcont')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                </div>
                <div class="modal-body">

                    <label>Pilih file excel</label>
                    <div class="form-group">
                        <input type="file" name="file" required="required">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
    //edit data

    $('.edit').on("click" ,function() {
    var id = $(this).attr('data-id');

    $.ajax({
    url : "{{url('carivwposubcont')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#VendorCode').val(data.VendorCOde);
        $('#Material').val(data.Material);
        $('#Description').val(data.Description);




        $('#edit').modal('show');
        }
        });
    });





    });
    </script>
@endsection
