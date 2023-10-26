@extends('panel.master')

@section('css')

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>
@endsection

@section('content')

<div class="loader" style="display:none;">
    <div class="loader-main"><i class="fa fa-spinner fa-pulse"></i></div>
</div>

<div class="content-body-white">

        {{csrf_field()}}
        <div class="page-head">
            <div class="page-title">
                <h1>Edit Picking List Komponen</h1>
            </div>
            
        </div>
        <div class="wrapper">
            <div class="row">
                @if(session()->has('err_message'))
                <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('err_message') }}
                </div>
                @endif
                @if(session()->has('succ_message'))
                <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('succ_message') }}
                </div>
                @endif
                <div class="col-md-12 element">
                    <div class="box-pencarian-family-tree" style=" background: #fff; ">
                        <div class="row">

                            <div class="col-xl-12 col-md-12 m-b-12px">
                                <div class="row">

                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">PRO : </label>
                                          
                                            <input type="number" list="list_pro" id="num" name="pro" value="{{ $data['pro']->pro }}" autocomplete="off"
                                                onchange="getpro(this)" class="form-control" readonly required />
                                            

                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Qty : </label>
                                            <input type="number" id="val_qty" name="qty"   value="{{ $data['pro']->qty }}" class="form-control"
                                             autocomplete="off"  required />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">PO References : </label>
                                            <input type="text" id="po_ref" name="po_ref"  value="{{ $data['po']->po_ref }}" class="form-control" autocomplete="off"
                                                value="" placeholder="Optional" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Part Number : </label>
                                            <input type="text" id="val_pn" name="pn" value="{{ $data['pro']->pn }}"  class="form-control"
                                                autocomplete="off" readonly required />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Customer : </label>
                                            <input type="text" id="val_cust" name="cust" value="{{ $data['pro']->cust }}" class="form-control"
                                                autocomplete="off" readonly required />
                                        </div>

                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Product : </label>
                                            <input type="text" name="product" id="val_product" value="{{ $data['pro']->product }}" class="form-control"
                                                autocomplete="off" readonly required />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Picking List : </label>
                                            <input type="text" id="nopo" name="nopo" class="form-control" value="{{ $data['po']->nopo }}"  autocomplete="off"
                                                value="" required readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 m-b-10px">
                                        <fieldset>
                                            
                                            <div class="col-xl-6 col-md-6 m-b-10px">
                                            <legend>Add Component</legend>
                                            </div>
                                            <div class="col-xl-2 col-md-2 m-b-10px ">
                                                <!-- <label for="">Package : </label> -->
                                            </div>
                                            <div class="col-xl-4 col-md-2 m-b-10px pull-right">
                                                <!-- <input type="text" id="package" name="package" class="form-control" onchange="cekPackage(this);" autocomplete="off" placeholder="Optional"> -->
                                           <div class="float-right">  <a class=" btn btn-success" data-toggle="modal" data-target="#modal-add-picking" >Add komponen</a></div>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>PN Patria</th>
                                                        <th>Description</th>
                                                        <th>PN Vendor</th>
                                                        <th>Quantity</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php ($i = 1)
                                                    @foreach($data['tkomponen'] as $tkomponen)
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $tkomponen->pn_patria }}</td>
                                                        <td>{{ $tkomponen->description }}</td>
                                                        <td>{{ $tkomponen->pn_vendor }}</td>
                                                        <td>{{ $tkomponen->qty_order }}</td>
                                                        <td>{{ $tkomponen->pn_patria }}</td>
                                                       
                                                        <td>
                                                            <a href="#" data-toggle="modal" data-target="#modal-edit-picking-{{ $tkomponen->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                                            <a href="#" data-toggle="modal" data-target="#modal-delete-picking-{{ $tkomponen->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                                                           
                                                        </td>
                                                    </tr>
                                                    @php ($i = $i+1)
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <!-- <td align="right"><input id="myBtn" type="submit" name=""
                                                                value="Submit" class="btn btn-success"></td> -->
                                                        <!-- <td><a href="#" id="addRow" class="btn btn-warning addRow"><i
                                                                    class="glyphicon glyphicon-plus"></i></a></td> -->
                                                        <td style="border: none"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         
        </div>
</div>

<div id="modal-add-picking" class="modal fade">
    <form method="post" action="{{url('insert-komponen')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add Komponen</h2>

                    <div class="form-group text-left">
                        <label class="form-control-label">Description: *</label>
                         <select id='sel_desc' class="form-control js-example-basic-single" data-live-search="true"
                            name='desc' onchange="getval(this);" required>
                            <option value="">-- Select Komponen --</option>
                            @foreach($data['komponen'] as $komponen)
                                <option value='{{ $komponen->pn_patria }}'> {{ $komponen->pn_patria." - ".$komponen->description }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label">PN Patria: *</label>
                         <input type="text" name="pn_patria" id="val_pn_patria"
                                                                class="form-control" autocomplete="off" required readonly>
                        <input type="hidden" name="description" id="val_descrip"
                        class="form-control" autocomplete="off"  required=""
                        readonly>
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label">PN Vendor: *</label>
                         <input type="text" name="pn_vendor" id="val_pn_vendor" 
                                                                class="form-control" autocomplete="off" readonly >
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label">Qty: *</label>
                        <input type="text" id="quantity" onchange="cekQty(this);" name="quantity"
                                                                class="form-control quantity" autocomplete="off"
                                                                required="">
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
@foreach($data['tkomponen'] as $tkomponen)

<div id="modal-edit-picking-{{ $tkomponen->id }}" class="modal fade">
    <form method="post" action="{{url('update-komponen')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit Komponen</h2>

                    <div class="form-group text-left">
                        <label class="form-control-label">Description: *</label>
                         <select id='sel_desc' class="form-control js-example-basic-single" data-live-search="true"
                            name='desc' onchange="getval(this);" required>
                            <option value="">-- Select Komponen --</option>
                            @foreach($data['komponen'] as $komponen)
                                @if($tkomponen->pn_patria==$komponen->pn_patria)
                                <option value='{{ $komponen->pn_patria }}' selected> {{ $komponen->pn_patria." - ".$komponen->description }}</option>
                                @else
                                <option value='{{ $komponen->pn_patria }}'> {{ $komponen->pn_patria." - ".$komponen->description }}</option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label">PN Patria: *</label>
                         <input type="text" value="{{ $tkomponen->pn_patria }}" name="pn_patria" id="val_pn_patria"
                                                                class="form-control" autocomplete="off" required readonly>
                        <input type="hidden" name="description" id="val_descrip"
                        class="form-control" autocomplete="off"  value="{{ $tkomponen->description }}" required=""
                        readonly>
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label">PN Vendor: *</label>
                         <input type="text" name="pn_vendor" id="val_pn_vendor" value="{{ $tkomponen->pn_vendor }}"
                                                                class="form-control" autocomplete="off" readonly >
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label">Qty: *</label>
                        <input type="text" id="editquantity" onchange="cekQty1(this);" name="quantity"
                                                                class="form-control quantity" autocomplete="off" value="{{ $tkomponen->qty_order }}"
                                                                required="">
                    </div>
                   
                </div>
                <input type="hidden" name="id" value="{{ $tkomponen->id }}"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="modal-delete-picking-{{ $tkomponen->id }}" class="modal fade">
    <form method="post" action="{{url('delete-komponen')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Warning</h2>
                    <p>Are you sure?</p>
                </div>
                <input type="hidden" name="id" value="{{ $tkomponen->id }}"/>
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

<script type="text/javascript">
    $('tbody').delegate('.quantity,.budget', 'keyup', function () {
        var tr = $(this).parent().parent();
        var quantity = tr.find('.quantity').val();
        var budget = tr.find('.budget').val();
        var amount = (quantity * budget);
        tr.find('.amount').val(amount);
        total();
    });
    function total() {
        var total = 0;
        $('.amount').each(function (i, e) {
            var amount = $(this).val() - 0;
            total += amount;
        });
        $('.total').html(total + ".00 tk");
    }
    $('.addRow').on('click', function () {
        addRow();
    });
    function addRow() {
        // console.log($('tbody tr').length);
        var lgtr = $('tbody tr').length +1;
        console.log("row tr");
        console.log(lgtr);
        var tr = '<tr>' +
            '<td>' + lgtr + '</td>'+
            '<td><input type="text" name="pn_patria[]" id="val_pn_patria' + lgtr + '" class="form-control" autocomplete="off" required="" ></td>' +
            '<td> <select id="sel_desc" class="form-control js-example-basic-single" name="desc[]"   onchange="getvalue(this);" required> <option value="0">-- Select Komponen --</option> @foreach($data["komponen"] as $komponen)<option value="{{ $komponen->pn_patria }}">{{ $komponen->pn_patria." - ".$komponen->description }}</option> @endforeach</select> <input type="hidden" name="description[]" id="val_descrip' + lgtr + '" class="form-control" autocomplete="off" readonly></td>' +
            '<td><input type="text" name="pn_vendor[]" id="val_pn_vendor' + lgtr + '" class="form-control" autocomplete="off" readonly=""></td>' +
            '<td><input type="text" id="quantity' + lgtr + '" name="quantity[]" class="form-control quantity"  autocomplete="off" required=""></td>' +
            '<td><a href="#" class="btn btn-danger remove"><i class="glyphicon glyphicon-remove"></i></a></td>' +
            '</tr>';
        $('tbody').append(tr);
        $('.js-example-basic-single').select2();
    };
    $('.remove').live('click', function () {
        var last = $('tbody tr').length;
        if (last == 1) {
            alert("you can not remove last row");
        }
        else {
            $(this).parent().parent().remove();
        }

    });
    function getval(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url:  "{{ url('get-komponen') }}/" + id,

            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                console.log(response['data'].pn_patria);
                console.log(response['data'].pn_vendor);
                document.getElementById("val_descrip").value = response['data'].description;
                document.getElementById("val_pn_patria").value = response['data'].pn_patria;
                document.getElementById("val_pn_vendor").value = response['data'].pn_vendor;
            }
        });
    }

    function getvalue(sel) {
        var id = sel.value;
        var lg = $('tbody tr').length;
        // console.log("get val tr");
        // console.log(lg);
        // AJAX request 
        $.ajax({
            url: "{{ url('get-komponen') }}/" + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                console.log(response['data'].pn_patria);
                document.getElementById("val_descrip" + lg + "").value = response['data'].description;
                document.getElementById("val_pn_patria" + lg + "").value = response['data'].pn_patria;
                document.getElementById("val_pn_vendor" + lg + "").value = response['data'].pn_vendor;
            }
        });
    }

    function cekQty(e){
        if(document.getElementById("val_qty").value != ''){
            document.getElementById("quantity").value = document.getElementById("val_qty").value*document.getElementById("quantity").value;
        }
    }
    function cekQty1(e){
        if(document.getElementById("val_qty").value != ''){
            document.getElementById("editquantity").value = document.getElementById("val_qty").value*document.getElementById("editquantity").value;
        }
    }
</script>


<script>
    $('[type=tel]').on('change', function (e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    });
    $('[type=tel]').on('keypress', function (e) {
        keys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.']
        return keys.indexOf(event.key) > -1
    });
    $(document).on('submit', 'form', function () {
        $(".loader").attr("style", "display:block;");
    });
</script>
@endsection