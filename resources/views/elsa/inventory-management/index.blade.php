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
            <h1>Inventory Management </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Min Qty</th>
                            <th>Uom</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Departement</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['inventory'] as $inventory)
                        <tr>
                            <td>{{ $inventory->no }}</td>
                            <td>@if($inventory->inventory_qty<=$inventory->inventory_qty_min)
                                    @if($inventory->inventory_qty<=0)
                                    <i data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Empty" class="fa fa-flag fa-lg custom--1" style="color:red"></i>
                                    @else                                    
                                    <i data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Need Restock" class="fa fa-flag fa-lg custom--1" style="color:orange"></i>
                                    @endif
                                @else
                                    <i data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Enough Stock" class="fa fa-flag fa-lg custom--1" style="color:green"></i>
                                @endif
                            </td>
                            <td>{{ $inventory->inventory_nama }}</td>
                            <td>{{ $inventory->inventory_qty }}</td>
                            <td>{{ $inventory->inventory_qty_min }}</td>
                            <td>{{ $inventory->inventory_satuan }}</td>
                            <td>{{ $inventory->brand_name }}</td>
                            <td>{{ $inventory->cat_name }}</td>
                            <td>{{ $inventory->dept_name }}</td>
                            
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Reduce Stock Inventory" data-target="#modal-reduce-inventory-{{ $inventory->inventory_id }}"><i class="fa fa-retweet fa-lg custom--1" style="color:red"></i></a>
                                <a href="#" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Add Income Inventory" data-target="#modal-income-inventory-{{ $inventory->inventory_id }}"><i class="fa fa-cart-arrow-down fa-lg custom--1" style="color:green"></i></a>
                                <a href="{{ url('inventory-tracking/'.$inventory->inventory_id) }}" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Tracking Inventory"><i class="fa fa-search-plus fa-lg custom--1" style="color:orange"></i></a>
                                <a href="#" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Edit Inventory" data-target="#modal-edit-inventory-{{ $inventory->inventory_id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                <!-- <a href="#" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Delete Inventory" data-target="#modal-reset-inventory-{{ $inventory->id }}"><i class="fa fa-key fa-lg custom--1"></i></a> -->
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Delete Inventory" data-target="#modal-delete-inventory-{{ $inventory->inventory_id }}"><i class="fa fa-trash fa-lg custom--1" ></i></a>
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

    
    <div id="modal-add-inventory" class="modal fade">
        <form method="post" action="{{ url('insert-inventory') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Inventory</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Name: *</label>
                            <input type="text" name="inventory_nama" class="form-control" autocomplete="off"
                                required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Group: </label>
                            <input type="text" name="inventory_group" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">File: </label>
                            <input type="file" name="inventory_file" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group text-left">
                            <label class="form-control-label">Qty: *</label>
                            <input type="number" name="inventory_qty" class="form-control" autocomplete="off"
                                required="">
                        </div>

                        <div class="form-group text-left">
                            <label class="form-control-label">Min Qty: *</label>
                            <input type="number" name="inventory_qty_min" class="form-control" value="1"
                                autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Uom: *</label>
                            <input type="text" name="inventory_satuan" class="form-control" autocomplete="off"
                                value="Pcs" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Category: *</label>
                            <select style="width: 100%" name="inventory_category_id"
                                class="form-control js-example-basic-single" data-live-search="true" required>
                                <option>Please Select Category</option>
                                @foreach ($data['category'] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Brand: *</label>
                            <select style="width: 100%" name="inventory_brand_id"
                                class="form-control js-example-basic-single" data-live-search="true" required>
                                <option value="1">Please Select Brand</option>
                                @foreach ($data['brand'] as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="" />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal Edit inventory -->
    @foreach($data['inventory'] as $inventory)
    
    <div id="modal-income-inventory-{{ $inventory->inventory_id }}" class="modal fade">
        <form method="post" action="{{url('add-income-inventory')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Income Inventory</h2>
                        <br>
                        <div class="table-responsive custom--2">
                        <table id="sorting-table" class="table">
                            <tr>
                                <td class="text-left">Name</td>
                                <td class="text-left">: {{ $inventory->inventory_nama }} </td>
                             </tr>
                             <tr>
                                <td class="text-left">Stock</td>
                                <td class="text-left">: {{ $inventory->inventory_qty }} {{ $inventory->inventory_satuan }}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Brand/Category</td>
                                <td class="text-left">: {{ $inventory->brand_name }}/{{ $inventory->cat_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Departement</td>
                                <td class="text-left">: {{ $inventory->dept_name }}</td>
                            </tr>
                            <tr></tr>
                        </table>
                        </div>
                        <br>
                        <div class="form-group text-left">
                                <input type="hidden" name="stock" id="stock" class="form-control" value="{{ $inventory->inventory_qty }}" readonly autocomplete="off" required="">
                            <input type="hidden" name="inventory_id" value="{{ $inventory->inventory_id }}"/>
                            <label class="form-control-label">Income Qty: *</label>
                            <input type="number" name="in_qty" class="form-control" autocomplete="off" required="">
                        </div>
                        
                        <div class="form-group text-left">
                            <label class="form-control-label">Price: *</label>
                            <input type="number" name="in_price" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Vendor: *</label>
                        <select style="width: 100%" name="vendor" class="form-control js-example-basic-single" data-live-search="true" require="">
                            <option>Please Select Vendor</option>
                                @foreach($data['vendor'] as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Note: *</label>
                            <textarea name="in_note" class="form-control" id="note" cols="20" rows="5" required></textarea>
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
    
    <div id="modal-reduce-inventory-{{ $inventory->inventory_id }}" class="modal fade">
        <form method="post" action="{{url('add-reduce-inventory')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Reduce Inventory</h2>
                        <br>
                        <div class="table-responsive custom--2">
                        <table id="sorting-table" class="table">
                            <tr>
                                <td class="text-left">Name</td>
                                <td class="text-left">: {{ $inventory->inventory_nama }} </td>
                             </tr>
                             <tr>
                                <td class="text-left">Stock</td>
                                <td class="text-left">: {{ $inventory->inventory_qty }} {{ $inventory->inventory_satuan }}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Brand/Category</td>
                                <td class="text-left">: {{ $inventory->brand_name }}/{{ $inventory->cat_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Departement</td>
                                <td class="text-left">: {{ $inventory->dept_name }}</td>
                            </tr>
                            <tr></tr>
                        </table>
                        </div>
                        <br>
                        <div class="form-group text-left">
                            <input type="hidden" name="stock" id="stock" class="form-control" value="{{ $inventory->inventory_qty }}" readonly autocomplete="off" required="">
                            <input type="hidden" name="inventory_id" value="{{ $inventory->inventory_id }}"/>
                            <label class="form-control-label">Reduce Qty: *</label>
                            <input type="number" name="out_qty" id="out_qty"  onchange="cekQty(this);" class="form-control" autocomplete="off" required="">
                            <span class="pull-right" id="alertqty" style="color:red; margin:5px;"></span>
                        </div>
                        <div class="form-group text-left">
                            <label for="form-control-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="Self Consumable">Self Consumable</option>
                                <option value="Berita Acara">Berita Acara</option>
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Note: *</label>
                            <textarea name="out_note" class="form-control" id="note" cols="20" rows="5" required></textarea>
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
    <div id="modal-edit-inventory-{{ $inventory->inventory_id }}" class="modal fade">
            <form method="post" action="{{ url('update-inventory') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h2>Edit Inventory</h2>
                            <br>
                            <div class="form-group text-left">
                                <input type="hidden" name="inventory_id" value="{{ $inventory->inventory_id }}" />
                                <label class="form-control-label">Name: *</label>

                                <input type="text" name="inventory_nama" class="form-control" autocomplete="off"
                                    value="{{ $inventory->inventory_nama }}" required="">
                            </div>

                            <div class="form-group text-left">
                                <label class="form-control-label">Group: </label>
                                <input type="text" name="inventory_group" class="form-control" autocomplete="off"
                                    value="{{ $inventory->inventory_group }}">
                            </div>

                            <div class="form-group text-left">
                                <label class="form-control-label">File: </label>
                                <input type="file" name="inventory_file" class="form-control" autocomplete="off"
                                    value="{{ $inventory->inventory_file }}">
                            </div>

                            <div class="form-group text-left">
                                <label class="form-control-label">Qty: *</label>
                                <input type="number" name="inventory_qty" class="form-control" autocomplete="off"
                                    value="{{ $inventory->inventory_qty }}" required="">
                            </div>

                            <div class="form-group text-left">
                                <label class="form-control-label">Min Qty: *</label>
                                <input type="number" name="inventory_qty_min" class="form-control" autocomplete="off"
                                    value="{{ $inventory->inventory_qty_min }}" required="">
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label">Uom: *</label>
                                <input type="text" name="inventory_satuan" class="form-control" autocomplete="off"
                                    value="{{ $inventory->inventory_satuan }}" required="">
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label">Category: *</label>
                                <select style="width: 100%" name="inventory_category_id"
                                    class="form-control js-example-basic-single" data-live-search="true" require="">
                                    <option>Please Select Category</option>
                                    @foreach ($data['category'] as $category)
                                        @if ($inventory->inventory_category_id == $category->id)
                                            <option selected value="{{ $category->id }}">{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label">Brand: *</label>
                                <select style="width: 100%" name="inventory_brand_id"
                                    class="form-control js-example-basic-single" data-live-search="true" require="">
                                    <option>Please Select Brand</option>
                                    @foreach ($data['brand'] as $brand)
                                        @if ($inventory->inventory_brand_id == $brand->id)
                                            <option selected value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @else
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
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
    <div id="modal-delete-inventory-{{ $inventory->inventory_id }}" class="modal fade">
        <form method="post" action="{{url('delete-inventory')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="inventory_id" value="{{ $inventory->inventory_id }}"/>
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
    function cekQty(sel)
    {
        // var out_qty=document.getElementById('out_qty').value;
        // var stock = document.getElementById('stock').value;
        var stock = $('#stock').val();
        if(stock<sel.value){
            $("#alertqty").text("*Not Enough Stock");
            out_qty=document.getElementById('out_qty').value=stock;
            $('#alertqty').show(0).delay(3000).hide(0);
        }else{

        }
    }
    $(function () {
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        <?php if($data['actionmenu']->c==1){ ?>
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-inventory" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
