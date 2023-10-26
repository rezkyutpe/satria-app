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

    <form method="post" action="{{url('insert-package')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="page-head">
            <div class="page-title">
                <h1>Create Picking List</h1>
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
                                            <!-- <select class="form-control select2" name='pro'  onchange="getpro(this);" required>
                                                <option value='0'>-- Select department --</option>
                                                @foreach($data['getpro'] as $pro)
                                                <option value="{{ $pro['Number'] }}">{{ $pro['Number'] }}</option>
                                                @endforeach

                                            </select> -->
                                            <input type="number" list="list_pro" id="num" name="pro" autocomplete="off"
                                                onchange="getpro(this)" class="form-control"  />
                                            <datalist id="list_pro">
                                                @foreach($data['getpro'] as $pro)
                                                <option value="{{ $pro['Number'] }}">
                                                    @endforeach
                                            </datalist>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Qty : </label>
                                            <input type="number" id="val_qty" name="qty" class="form-control"
                                             autocomplete="off"   />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Part Number : </label>
                                            <input type="text" id="val_pn" name="pn" class="form-control"
                                                autocomplete="off" readonly  />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Customer : </label>
                                            <input type="text" id="val_cust" name="cust" class="form-control"
                                                autocomplete="off" readonly  />
                                        </div>

                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Product : </label>
                                            <input type="text" name="product" id="val_product" class="form-control"
                                                autocomplete="off" readonly  />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Package Name : </label>
                                            <input type="text" id="package" name="nopo"  class="form-control" autocomplete="off"
                                                value="" required />
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
                                               
                                                <!-- Calculate :  <input type="checkbox" id="myCheck" disabled onclick="calculate()" required> -->
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
                                                    @foreach($data['package'] as $package)
                                                    @if($package['name']!='')
                                                    <tr>
                                                    	<td>{{ $i }}</td>
                                                    <td><input type="text" value="{{ $package['name'] }}" name="name[]" id="val_pn_patria1"
                                                                class="form-control" autocomplete="off" required readonly>
                                                                <input type="hidden" value="{{ $package['ket'] }}" name="ket[]" id="val_pn_patria1"
                                                                class="form-control" autocomplete="off" required readonly>
                                                        <!-- <td><input type="text" name="desc[]" class="form-control" autocomplete="off"></td>    -->
                                                        <td><input type="text" value="{{ $package['descr'] }}" name="descr[]"
                                                                class="form-control" autocomplete="off" required readonly>
                                                           </td>
                                                        </td>
                                                        <td><input type="text" name="pn_eaton[]" id="vaL_pn_vendor" value=""
                                                                class="form-control" autocomplete="off" readonly ></td>
                                                        <td><input type="number" id="quantity" onchange="cekQty(this);" name="qty[]"
                                                                class="form-control quantity" autocomplete="off" value="{{ $package['qty'] }}"
                                                                required="" readonly></td>
                                                        <td><a href="#" class="btn btn-danger remove" disabled><i
                                                                    class="glyphicon glyphicon-remove"></i></a></td>
                                                    </tr>
                                                    @php ($i = $i+1)
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td colspan="2" align="right"><input id="myBtn" type="submit" name=""
                                                                value="Submit" class="btn btn-success"></td>
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
    </form>
</div>

@endsection

@section('myscript')

<script src="{{ asset('assets/global/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/add-family.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
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
        var lgtr = $('tbody tr').length;
        // console.log("row tr");
        // console.log(lgtr);
        var tr = '<tr>' +
            '<td><input type="text" name="pn_patria[]" id="val_pn_patria' + lgtr + '" class="form-control" autocomplete="off" required="" ></td>' +
            '<td> <select id="sel_desc" class="form-control" name="desc[]"   onchange="getvalue(this);" required> <option value="0">-- Select department --</option> @foreach($data["komponen"] as $komponen)<option value="{{ $komponen->pn_patria }}">{{ $komponen->description }}</option> @endforeach</select> <input type="hidden" name="description[]" id="val_descrip' + lgtr + '" class="form-control" autocomplete="off" readonly></td>' +
            '<td><input type="text" name="pn_vendor[]" id="val_pn_vendor' + lgtr + '" class="form-control" autocomplete="off" readonly=""></td>' +
            '<td><input type="number" id="quantity' + lgtr + '" name="quantity[]" class="form-control quantity"  autocomplete="off" required=""></td>' +
            '<td><a href="#" class="btn btn-danger remove"><i class="glyphicon glyphicon-remove"></i></a></td>' +
            '</tr>';
        $('tbody').append(tr);
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
    function getval(sel,i) {
        var id = sel.value;
        var no = i.value;

        // AJAX request 
        $.ajax({
            url:  "{{ url('get-komponen') }}/" + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                console.log(response['data'].pn_patria);
                console.log(response['data'].description);
                document.getElementById("val_pn_patria1").value = response['data'].pn_patria;
                document.getElementById("val_descrip").value = response['data'].description;
                document.getElementById("val_pn_vendor").value = response['data'].pn_patria;
            }
        });
    }
    
    function cekPackage(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url: "{{ url('get-package') }}/" + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                if(response['data']==null){
                    console.log("Blm ada");
                    document.getElementById("myBtn").disabled = false;
                }else{
                    console.log("Sudah ada");
                    alert("Package Name Already Exist");
                    document.getElementById("package").value = '';
                }
            }
        });
    }
    function getvalue(sel) {
        var id = sel.value;
        var lg = $('tbody tr').length - 1;
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
    function getpro(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url: "{{ url('get-pro') }}/"+id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                console.log(response['data'][0]);
                console.log(response['pro']);
                console.log(response['data'][0].PN);
                document.getElementById("val_pn").value = response['data'][0].PN;
                document.getElementById("val_product").value = response['data'][0].Name;
                document.getElementById("val_qty").value = response['data'][0].Quantity;
                document.getElementById("val_cust").value = response['data'][0].CustomerName;
                document.getElementById("package").value = response['data'][0].PN;
            }
        });
        if(document.getElementById("quantity").value != ''){
            document.getElementById("myCheck").disabled = false;
        }
    }
    function calculate(){
		var checkBox = document.getElementById("myCheck");
        var lg = $('tbody tr').length - 1;
        console.log(lg);
        if(document.getElementById("quantity").value != ''){
            if (checkBox.checked == true){
                document.getElementById("addRow").style.pointerEvents = "none";
                document.getElementById("val_qty").readOnly = true;
                document.getElementById("quantity").value = document.getElementById("val_qty").value*document.getElementById("quantity").value; 
                for (i = 1; i <= lg; i++) {
                    document.getElementById("quantity" + i + "").value = document.getElementById("val_qty").value*document.getElementById("quantity" + i + "").value;
                }
            }else{
                document.getElementById("addRow").style.pointerEvents = "auto";
                document.getElementById("val_qty").readOnly = false;
                document.getElementById("quantity").value = document.getElementById("quantity").value/document.getElementById("val_qty").value;
                for (i = 1; i <= lg; i++) {
                    document.getElementById("quantity" + i + "").value = document.getElementById("quantity" + i + "").value/document.getElementById("val_qty").value;
                }
            }
        }
    }
    function cekQty(){
        if(document.getElementById("val_qty").value != ''){
            document.getElementById("myCheck").disabled = false;
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