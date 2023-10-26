@extends('po-tracking.panel.master')
@section('content')


<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Data Vendors </h2>
                <a class="btn btn-primary btn-sm pull-right"  data-toggle="modal" data-target="#modal-add-appsmenu" href="#">Create New <i class="fa fa-plus"> </i></a>
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
                            <table id="datatable-visibility" class="table table-striped table-bordered text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>VendorCode</th>
                                        <th>VendorCode NEW</th>
                                        <th>Address</th>
                                        <th>PhoneNo</th>
                                        <th>CountryCode</th>
                                        <th>VendorType</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $k=> $item)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td>{{ $item->Name }}</td>
                                            <td>{{ $item->Email }}</td>
                                            <td>{{ $item->VendorCode }}</td>
                                            <td>{{ $item->VendorCode_new }}</td>
                                            <td>{{ $item->Address }}</td>
                                            <td>{{ $item->PhoneNo }}</td>
                                            <td>{{ $item->CountryCode }}</td>
                                            <td>{{ $item->VendorType }}</td>
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

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Data Withholding Tax Vendor</h2>
                {{-- <a class="btn btn-primary btn-sm pull-right"  data-toggle="modal" data-target="#modal-add-appsmenu" href="#">Create New <i class="fa fa-plus"> </i></a> --}}
                <a class="btn btn-info btn-sm pull-right"  data-toggle="modal" data-target="#modal-upload-xlsx" href="#">Upload Excel File Vendor Withholding Tax  <i class="fa fa-file"></i></a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table class="table table-striped table-bordered text-center datatable" style="width:90%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Vendor Code</th>
                                        <th>Company Code</th>
                                        <th>Withholding Tax Type</th>
                                        <th>Subject to W/Tax</th>
                                        <th>Withholding Tax Code</th>
                                        <th>Exemption Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_wth as $k => $item2)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td >{{ $item2->VendorCode }}</td>
                                            <td >{{ $item2->CompanyCode }}</td>
                                            <td>{{ $item2->WithholdingTaxType }}</td>
                                            <td>{{ $item2->SubjectToWithholdingTax }}</td>
                                            <td>{{ $item2->WithholdingTaxCode }}</td>
                                            <td>{{ $item2->ExemptionRate }}</td>
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

@include('po-tracking.master.modal.UploadXLSX');

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{url('update-uservendor')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                <div class="modal-body text-center">
                    <h2>Edit Vendors</h2>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Name: *</label>
                                <input type="text" name="Name" class="form-control" autocomplete="off" value="" id="Name" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  VendorCode: *</label>
                                <input type="text" name="VendorCode" class="form-control" autocomplete="off" value="" required="" id="VendorCode" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Email: *</label>
                                <input type="text" name="Email" class="form-control" autocomplete="off" value="" id="Email" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  VendorCode NEW: *</label>
                                <input type="text" name="VendorCode_new" class="form-control" autocomplete="off" value="" required="" id="VendorCode_new" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> PhoneNo: *</label>
                                <input type="text" name="PhoneNo" class="form-control" autocomplete="off" value="" id="PhoneNo" >
                                <input type="hidden" name="ID" class="form-control" autocomplete="off" value="" id="ID" >
                          </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Address: *</label>
                                <textarea name="Address" id="Address" cols="1" rows="1" class="form-control"></textarea>

                            </div>
                        </div><div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> PostalCode: *</label>
                                <input type="text" name="PostalCode" class="form-control" autocomplete="off" value="" id="PostalCode" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> CountryCode: *</label>
                                <select name="CountryCode" id="CountryCode" class="form-control">
                                    @foreach ($CountryCode as $k=> $item)
                                    <option value="{{ $item->CountryCode }}">{{ $item->CountryCode }}</option>
                                    @endforeach
                                  </select>
                          </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> VendorType: *</label>
                              <select name="VendorType" id="VendorType" class="form-control">
                                @foreach ($VendorType as $k=> $item)
                                <option value="{{ $item->VendorType }}">{{ $item->VendorType }}</option>
                                @endforeach
                              </select>
                          </div>
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
                </div>
            </form>

            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="delete" aria-hidden="true">
    <form method="post" action="{{url('delete-uservendor')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Warning</h2>
                    <p>Are you sure delete ? </p>

                    </div>

                <input type="hidden" name="ID" id="IDS" >
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="modal-add-appsmenu" class="modal fade">
    <form method="post" action="{{url('insert-uservendor')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add User Vendors</h2>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Name: *</label>
                                <input type="text" name="Name" class="form-control" autocomplete="off" value="" id="Name" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  VendorCode: *</label>
                                <input type="text" name="VendorCode" class="form-control" autocomplete="off" value="" required="" id="VendorCode" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Email: *</label>
                                <input type="email" name="Email" class="form-control" autocomplete="off" >
                                    </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label">  VendorCode NEW: *</label>
                                <input type="text" name="VendorCode_new" class="form-control" autocomplete="off" value="" required="" id="VendorCode_new" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> PhoneNo: *</label>
                                <input type="text" name="PhoneNo" class="form-control" autocomplete="off" value="" id="PhoneNo" >
                          </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Address: *</label>
                                <textarea name="Address" id="" cols="1" rows="1" class="form-control"></textarea>
                            </div>
                        </div><div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> PostalCode: *</label>
                                <input type="text" name="PostalCode" class="form-control" autocomplete="off" value="" id="PhoneNo" >

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> CountryCode: *</label>
                                <select name="CountryCode" class="form-control">
                                    @foreach ($CountryCode as $k=> $item)
                                    <option value="{{ $item->CountryCode }}">{{ $item->CountryCode }}</option>
                                    @endforeach
                                </select>
                          </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="form-control-label"> VendorType: *</label>
                               <select name="VendorType" class="form-control">
                                @foreach ($VendorType as $k=> $item)
                                    <option value="{{ $item->VendorType }}">{{ $item->VendorType }}</option>
                                @endforeach
                               </select>
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
<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/select2/js/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function() {
    //edit data

    $('.edit').on("click" ,function() {
    var id = $(this).attr('data-id');

    $.ajax({
    url : "{{url('cariuservendor')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // alert(JSON.stringify(data.vendor));
        $('#Name').val(data.Name);
        $('#VendorCode').val(data.VendorCode);
        $('#VendorCode_new').val(data.VendorCode_new);
        $('#PhoneNo').val(data.PhoneNo);
        $('textarea#Address').val(data.Address);
        $('#PostalCode').val(data.PostalCode);
        $('#CountryCode').val(data.CountryCode);
        $('#VendorType').val(data.VendorType);
        $('#ID').val(data.ID);
        $('#Email').val(data.Email);
        $('#edit').modal('show');
        }
        });
    });

    $('.delete').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url : "{{url('cariuservendor')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

        $('#IDS').val(data.ID);
        $('#delete').modal('show');
        }
        });
    });

    });
    </script>

@endsection
