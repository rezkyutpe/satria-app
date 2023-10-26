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
            <h1>PR Management </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PR Num</th>
                            <th>Qty</th>
                            <th>Cat</th>
                            <th>By</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['pr'] as $pr)
                        <tr>
                            <td>{{ $pr->no }}</td>
                            <td>{{ $pr->pr_number }}</td>
                            <td>{{ $pr->pr_quantity }}</td>
                            <td>@if($pr->pr_category==1)
                                {{ "License" }}
                                @else
                                {{ "Inventory" }}
                                @endif
                            </td>
                            <td>{{ $pr->pr_name }}</td>
                            <td>@if($pr->updated_by=='')
                               <span style="background-color: grey; color: white;" class="badge badge-secondary">Request</span> 
                                @else
                               <span style="background-color: #00a1e6; color: white;" class="badge badge-secondary">Delivered</span> 
                                @endif 
                            </td>
                            <td>@if($pr->status=='0')
                                {{ 'New'}}
                                @elseif($pr->status=='1')
                                {{ 'Partial Aproved'}}
                                @elseif($pr->status=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif 
                            </td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                    @if($pr->status=='2')
                                    <a href="#"  data-placement="top" title="Proccess PR"  data-toggle="modal" data-target="#modal-edit-pr-{{ $pr->pr_id }}"><i class="fa fa-paper-plane-o  fa-lg custom--1"></i></a>
                                    @endif
                                @endif
                                @if($data['actionmenu']->d==1)
                                <!-- <a href="#" data-toggle="modal" data-target="#modal-delete-pr-{{ $pr->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a> -->
                                @endif

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    
    <div id="modal-add-pr" class="modal fade">
        <form method="post" action="{{url('insert-pr')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add PR</h2>
                        <br>
                        <div class="form-group text-left">
                        <label class="form-control-label">Employee: *</label>
                        <input type="text" list="list_pic" name="name" id="pic"  onchange="getsf(this)" autocomplete="off" class="form-control" required />
                            <datalist id="list_pic">
                                @foreach($data['emp'] as $emp)
                                <option value="{{ $emp['nrp'] }}">{{ $emp['nama'] }}</option>
                                    @endforeach
                            </datalist> 
                        </div>
                  
                        <div class="form-group text-left">
                            <label class="form-control-label">Nrp: *</label>
                        <input type="text" name="nrp" id="nrp" class="form-control" autocomplete="off">  
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Department: *</label>
                            <input type="text" name="departement" id="dept" class="form-control" autocomplete="off"> </td>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Category: *</label>
                            <select style="width: 100%;" name="category" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option value="0">Account</option>
                                <option value="1">Inventory</option>
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">PR to Departement: *</label>
                            <select style="width: 100%;" name="dept" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option value="">Choose Departement</option>
                            @foreach($data['dept'] as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Qty: *</label>
                            <input type="text" name="qty" class="form-control" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Message: * </label>
                            <textarea name="message" class="form-control" cols="30" rows="5" ></textarea>
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
    <!-- Modal Edit pr -->
    @foreach($data['pr'] as $pr)
    <div id="modal-edit-pr-{{ $pr->pr_id }}" class="modal fade">
        <form method="post" action="{{url('update-pr')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Proccess PR</h2>
                        <br>
                        
                        <div class="form-group text-left">
                          <input type="hidden" name="pr_id" value="{{ $pr->pr_id }}"/>
                            <label class="form-control-label">PR Note: * </label>
                          <textarea name="note" class="form-control" readonly cols="30" rows="5" >{{ $pr->pr_description }}</textarea>
                        </div>
                        @if($pr->pr_category==0)
                        <div class="form-group text-left">
                            <label class="form-control-label">Available Inventory: * </label>
                          <select style="width:100%" name="pr_inventory_id" class="form-control js-example-basic-single" data-live-search="true">
                                @foreach($data['inventory'] as $inventory)
                                   
                                        @if($pr->pr_inventory_id == $inventory->inventory_id)
                                        <option value="{{ $inventory->inventory_id }}" selected>{{ $inventory->inventory_nama ." | ".$inventory->inventory_qty }}</option>
                                        @else
                                            @if($pr->pr_quantity <= $inventory->inventory_qty)
                                            <option value="{{ $inventory->inventory_id }}" >{{ $inventory->inventory_nama ." | ".$inventory->inventory_qty }}</option>
                                            @endif
                                        @endif
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group text-left">
                            <label class="form-control-label">Remark: * </label>
                          <textarea name="pr_remark" class="form-control" cols="30" rows="5" >{{ $pr->pr_remark }}</textarea>
                        </div>
                        @if($pr->status==0) 
                        <div class="form-group text-left">
                            <label class="form-control-label">Approval : </label>
                            <input type="checkbox" name="approval" value="2"> Fully Approved
                        </div>
                        @endif

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
    <div id="modal-delete-pr-{{ $pr->pr_id }}" class="modal fade">
        <form method="post" action="{{url('delete-pr')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $pr->pr_id }}"/>
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
        <?php if($data['actionmenu']->c==1){ ?>
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-pr" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    
    function getsf(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url: 'get-usersf/' + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                document.getElementById("pic").value = response['data'].nama;
                document.getElementById("nrp").value = response['data'].nrp;
                document.getElementById("dept").value = response['data'].department;
                // document.getElementById("val_pn_patria").value = response['data'].pn_patria;
                // document.getElementById("val_pn_vendor").value = response['data'].pn_vendor;
            }
        });
    }
    </script>
@endsection
