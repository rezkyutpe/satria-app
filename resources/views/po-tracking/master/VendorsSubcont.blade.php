@extends('po-tracking.panel.master')
@section('content')


        <div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Vendor Subcont </h2>
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
              <th>Vendor Code</th>
              <th>Material</th>
              <th>Description</th>
              <th>Daily Lead Time</th>
              <th>Monthly Lead Time</th>
              <th>PB</th>
              <th>Monthly Capacity</th>
              <th>Action</th>

            </tr>
          </thead>
          <tbody>

            @foreach ($data as $k=> $item)

            <tr>
              <td>{{ $k+1 }}</td>
              <td>{{ $item->VendorCode }}</td>
              <td>{{ $item->Material }}</td>
              <td class="text-left">{{ $item->Description }}</td>
              <td>{{ $item->DailyLeadTime }}</td>
              <td>{{ $item->MonthlyLeadTime }}</td>
              <td>{{ $item->PB }}</td>
              <td>{{ $item->MonthlyCapacity }}</td>


              <td class="text-right">

                <a href="#"  class="edit" data-toggle="modal" data-id="{{ $item->ID }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                <a href="#" class="delete" data-toggle="modal"  data-id="{{ $item->ID }}" ><i class="fa fa-trash fa-lg custom--1"></i></a>
                <a href="#" class="view" data-toggle="modal"  data-id="{{ $item->ID }}" ><i class="fa fa-info-circle fa-lg custom--1"></i></a>

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
    <form method="post" action="{{url('insert-vendorssubcont')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add Vendors SubCont</h2>
                    <br>
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">Select Name Vendor: *</label>
                                <select name="" id="code" class="form-control select2" style="width: 100%">
                                    {{-- <select name="" id="code" class="form-control form-control-chosen"> --}}
                                    <option value=""></option>
                                @foreach ($vendor as $item)
                                <option value="{{ $item->Code }}">{{ $item->Name }}</option>
                                @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Vendor Code: *</label>
                                <input type="text" name="VendorCode" id="Code" class="form-control" autocomplete="off" value="" required="" id="Code" readonly>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Material: *</label>
                                <input type="text" name="Material" id="" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Description: *</label>
                                <input type="text" name="Description" id="" class="form-control" autocomplete="off" value="" >
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> DailyLeadTime: *</label>
                                <input type="text" name="DailyLeadTime" id="" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> MonthlyLeadTime: *</label>
                                <input type="text" name="MonthlyLeadTime" id="" class="form-control" autocomplete="off" value="">
                            </div>

                        </div>

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> PB: *</label>
                                <input type="text" name="PB" id="" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Setting: *</label>
                                <input type="text" name="Setting" id="" class="form-control" autocomplete="off" value="" >

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Fullweld: *</label>
                                <input type="text" name="Fullweld" id="" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Primer: *</label>
                                <input type="text" name="Primer" id="" class="form-control" autocomplete="off" value="" >

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group text-left">
                                <label class="form-control-label"> MonthlyCapacity: *</label>
                                <input type="text" name="MonthlyCapacity" id="" class="form-control" autocomplete="off" value="" >
                            </div>
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
<div class="modal fade" id="delete" aria-hidden="true">
    <form method="post" action="{{url('delete-vendorssubcont')}}" enctype="multipart/form-data">
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
<div class="modal fade" id="edit" aria-hidden="true">
    <form method="post" action="{{url('update-vendorssubcont')}}" enctype="multipart/form-data">
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
                                <input type="text" name="name" id="VendorCode" class="form-control" autocomplete="off" value="" required="" id="Code" readonly>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Material: *</label>
                                <input type="text" name="Material" id="Material" class="form-control" autocomplete="off" value="" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Description: *</label>
                                <input type="text" name="Description" id="Description" class="form-control" autocomplete="off" value="" >
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
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> DailyCapacity: *</label>
                                <input type="text" name="DailyCapacity" id="DailyCapacity" class="form-control" autocomplete="off" value="" >

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> isNeedSequence: *</label>
                                <input type="checkbox" name="isNeedSequence" checked id="isNeedSequence">
                            </div>
                        </div>

                </div>
                </div>
                <input type="hidden" name="id" id="IDs" value="">
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
    <div class="modal fade" id="view" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>View Sequence Progress Reason</h2>
                        <br>
                        <div class="row">


                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Vendor Code: *</label>
                                    <input type="text" name="name" id="VendorCodes" class="form-control" autocomplete="off" value="" required="" id="Code" readonly>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Material: *</label>
                                    <input type="text" name="city" id="Materials" class="form-control" autocomplete="off" value="" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Description: *</label>
                                    <input type="text" name="postalcode" id="Descriptions" class="form-control" autocomplete="off" value="" readonly>
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> DailyLeadTime: *</label>
                                    <input type="text" name="region" id="DailyLeadTimes" class="form-control" autocomplete="off" value="" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> MonthlyLeadTime: *</label>
                                    <input type="text" name="street" id="MonthlyLeadTimes" class="form-control" autocomplete="off" value="" readonly>
                                </div>

                            </div>

                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> PB: *</label>
                                    <input type="text" name="telephone1" id="PBs" class="form-control" autocomplete="off" value="" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Setting: *</label>
                                    <input type="text" name="faxnumber" id="Settings" class="form-control" autocomplete="off" value="" readonly>

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Fullweld: *</label>
                                    <input type="text" name="telephone1" id="Fullwelds" class="form-control" autocomplete="off" value="" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Primer: *</label>
                                    <input type="text" name="faxnumber" id="Primers" class="form-control" autocomplete="off" value="" readonly>

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> MonthlyCapacity: *</label>
                                    <input type="text" name="telephone1" id="MonthlyCapacitys" class="form-control" autocomplete="off" value="" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> DailyCapacity: *</label>
                                    <input type="text" name="faxnumber" id="DailyCapacitys" class="form-control" autocomplete="off" value="" readonly>

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> isNeedSequence: *</label>
                                    <input type="checkbox" checked id="isNeedSequences" readonly>
                                </div>
                            </div>

                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Back</button>

                    </div>
                </div>
        </div>
    </div>
<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/select2/js/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function() {
    //edit data

    $('.edit').on("click" ,function() {
    var id = $(this).attr('data-id');

    $.ajax({
    url : "{{url('carivendorssubcont')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#IDs').val(data.ID);
        $('#VendorCode').val(data.VendorCode);
        $('#Material').val(data.Material);
        $('#Setting').val(data.Setting);
        $('#Description').val(data.Description);
        $('#DailyLeadTime').val(data.DailyLeadTime);
        $('#MonthlyLeadTime').val(data.MonthlyLeadTime);
        $('#PB').val(data.PB);
        $('#Fullweld').val(data.Fullweld);
        $('#Primer').val(data.Primer);
        $('#MonthlyCapacity').val(data.MonthlyCapacity);
        $('#DailyCapacity').val(data.DailyCapacity);
        $('#isNeedSequence').val(data.isNeedSequence);



        $('#edit').modal('show');
        }
        });
    });
    $('.view').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
    url : "{{url('carivendorssubcont')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#Settings').val(data.Setting);
        $('#VendorCodes').val(data.VendorCode);
        $('#Materials').val(data.Material);
        $('#Descriptions').val(data.Description);
        $('#DailyLeadTimes').val(data.DailyLeadTime);
        $('#MonthlyLeadTimes').val(data.MonthlyLeadTime);
        $('#PBs').val(data.PB);
        $('#Fullwelds').val(data.Fullweld);
        $('#Primers').val(data.Primer);
        $('#MonthlyCapacitys').val(data.MonthlyCapacity);
        $('#DailyCapacitys').val(data.DailyCapacity);
        $('#isNeedSequences').val(data.isNeedSequence);

        $('#view').modal('show');
        }
        });
    });


    //edit data

    $('.delete').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url : "{{url('carivendorssubcont')}}?id="+id,
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
    <script>
    $('.select2').select2();

    $('#code').on("change" ,function() {
        if(this.value){

    var id =$("#code").val();

    $.ajax({
    url : "{{url('carivendors')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#Code').val(data.Code);

              }
        });
    }
    });

    </script>
@endsection
