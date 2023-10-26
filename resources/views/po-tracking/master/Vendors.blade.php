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
              <th>Code</th>
              <th>Name</th>
              <th>City</th>
              <th>Address</th>
              <th>AccountGroup</th>
              <th>Telephone</th>
              <th>Action</th>

            </tr>
          </thead>
          <tbody>

            @foreach ($data as $k=> $item)

            <tr>
              <td>{{ $k+1 }}</td>
              <td>{{ $item->Code }}</td>
              <td class="text-left">{{ $item->Name }}</td>
              <td>{{ $item->City }}</td>
              <td>{{ $item->Address }}</td>
              <td>{{ $item->AccountGroup }}</td>
              <td>{{ $item->Telephone1 }}</td>


              <td>

                <a href="#"  class="edit" data-toggle="modal" data-id="{{ $item->Code }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>

                <a href="#" class="delete" data-toggle="modal"  data-id="{{ $item->Code }}" ><i class="fa fa-trash fa-lg custom--1"></i></a>

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

<div id="modal-add-appsmenu" class="modal fade" aria-hidden="true">
    <form method="post" action="{{url('insert-vendors')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body text-center">
                    <h2>Add Vendors</h2>
                    <br>
                    <div class="row">

                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Code: *</label>
                                    <input type="text" name="code" class="form-control" autocomplete="off" value="" required="">
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Name: *</label>
                                    <input type="text" name="name" class="form-control" autocomplete="off" value="" required="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> City: *</label>
                                    <input type="text" name="city" class="form-control" autocomplete="off" value="" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Postal Code: *</label>
                                    <input type="text" name="postalcode" class="form-control" autocomplete="off" value="" >
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Region: *</label>
                                    <input type="text" name="region" class="form-control" autocomplete="off" value="" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Street: *</label>
                                    <input type="text" name="street" class="form-control" autocomplete="off" value="">
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Adrress: *</label>
                                    <input type="text" name="address" class="form-control" autocomplete="off" value="" required="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Account Group: *</label>
                                  <select name="accountgroup" id="" class="form-control" required>
                                  <option></option>
                                  <option >VGNT</option>
                                  <option >VGEM</option>
                                  </select>
                             </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Telepon: *</label>
                                    <input type="text" name="telephone1" class="form-control" autocomplete="off" value="" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Fax Number: *</label>
                                    <input type="text" name="faxnumber" class="form-control" autocomplete="off" value="" >

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
    <form method="post" action="{{url('delete-vendors')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Warning</h2>
                    <p>Are you sure delete ? </p>

                    </div>

                <input type="hidden" name="Code" id="Code" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modal-edit" aria-hidden="true">
    <form method="post" action="{{url('update-vendors')}}" enctype="multipart/form-data">
        {{csrf_field()}}
    <div class="modal-dialog">
       <form id="companydata">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit Vendors</h2>
                    <br>
                    <div class="row">
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Code: *</label>
                            <input type="text" name="code" id="Codes" class="form-control" autocomplete="off" value="" readonly>
                        </div>

                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Name: *</label>
                            <input type="text" name="name" id="Name" class="form-control" autocomplete="off" value="" required="" readonly>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> City: *</label>
                            <input type="text" name="city" id="City" class="form-control" autocomplete="off" value="" >
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Postal Code: *</label>
                            <input type="text" name="postalcode" id="PostalCode" class="form-control" autocomplete="off" value="" >
                        </div>

                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Region: *</label>
                            <input type="text" name="region" id="Region" class="form-control" autocomplete="off" value="" >
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Street: *</label>
                            <input type="text" name="street" id="Street" class="form-control" autocomplete="off" value="">
                        </div>

                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Adrress: *</label>
                            <input type="text" name="address" id="Address" class="form-control" autocomplete="off" value="" required="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Account Group: *</label>
                           <select name="accountgroup" class="form-control" id="AccountGroup">
                            {{-- @if (id="AccountGroup" == 'VGNT' )
                            <option></option>
                            <option >VGEM</option>
                            @elseif ({{ id="AccountGroup" }} == 'VGEM')
                            <option></option>
                            <option >VGNT</option>
                            @endif --}}
                            <option ></option>
                            <option >VGEM</option>
                            <option >VGNT</option>
                           </select>
                        </div>

                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Telepon: *</label>
                            <input type="text" name="telephone1" id="Telephone1" class="form-control" autocomplete="off" value="" >
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Fax Number: *</label>
                            <input type="text" name="faxnumber" id="FaxNumber" class="form-control" autocomplete="off" value="" >

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
</div>

<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
    //edit data
    $('.edit').on("click" ,function() {
    var id = $(this).attr('data-id');
    // fetch('http://localhost/editreason?id=2')
    $.ajax({
    url : "{{url('carivendors')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#Codes').val(data.Code);
        $('#Name').val(data.Name);
        $('#City').val(data.City);
        $('#PostalCode').val(data.PostalCode);
        $('#Region').val(data.Region);
        $('#Street').val(data.Street);
        $('#Address').val(data.Address);
        $('#AccountGroup').val(data.AccountGroup);
        $('#FaxNumber').val(data.FaxNumber);
        $('#Telephone1').val(data.Telephone1);
        $('#modal-edit').modal('show');
        }
        });
    });

    $('.delete').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url : "{{url('carivendors')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

        $('#Code').val(data.Code);
        $('#delete').modal('show');
        }
        });
    });

    });
    </script>
@endsection
