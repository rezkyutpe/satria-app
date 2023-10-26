@extends('panel.master')

@section('css')
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>

<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="loader" style="display:none;">
    <div class="loader-main"><i class="fa fa-spinner fa-pulse"></i></div>
</div>

<div class="content-body-white">

    <form method="post" action="{{url('insert-contract')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="page-head">
            <div class="page-title">
                <h1>Create New Contract</h1>
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
                                            <label class="form-control-label">Select Company : </label>
                                          
                                            <select name="company" class="form-control">
                                            @foreach($data['company'] as $company)
                                                    <option value="{{ $company['company_name'] }}">{{ $company['company_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label class="form-control-label">Contract Duration : </label>
                                            <select name="duration" class="form-control">
                                                @foreach($data['cat'] as $cat)
                                                    <option value="{{ $cat['id'] }}">{{ $cat['note'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-control-label">Employee: *</label>
                                            <input type="text" list="list_emp" name="name" id="pic"  onchange="getsf(this)" autocomplete="off" class="form-control" required />
                                                <datalist id="list_emp">
                                                    @foreach($data['emp'] as $emp)
                                                    <option value="{{ $emp['nrp'] }}">{{ $emp['nama'] }}</option>
                                                        @endforeach
                                                </datalist> 
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-control-label">File : </label>
                                            <input type="file" name="file_contract" class="form-control"
                                                autocomplete="off"  required />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Contract/Serial Number : </label>
                                            <input type="text" name="contract_num" class="form-control"
                                             autocomplete="off"  required />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Deal Date : </label>
                                            <input type="date" name="contract_date" class="form-control"
                                                autocomplete="off"  required />
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label">Email : </label>
                                            <input type="text" name="email" id="email" class="form-control" autocomplete="off"> 
                                            <input type="hidden" name="nrp" id="nrp" class="form-control" autocomplete="off"> 
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        
                                        <div class="form-group">
                                            <label class="form-control-label">Category : </label>
                                            <select name="category" class="form-control">
                                                <option ></i>Choose Contract Category</option>
                                                <option value="2" ></i>License</option>
                                                <option value="1" selected ></i>Contract</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Priority : </label>
                                            <select name="priority" class="form-control">
                                                <optio ></i>Choose Contract Priority</option>
                                                <option value="3" ></i>High Priority</option>
                                                <option value="2" ></i>Medium Priority</option>
                                                <option value="1" ></i>Small Priority</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Note: *</label>
                                            <textarea name="desc" class="form-control" cols="10" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 m-b-10px">
                                        <fieldset>
                                            
                                            <div class="col-xl-6 col-md-6 m-b-10px">
                                            <legend>Add CounterPart</legend>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Counterpart Company</th>
                                                        <th>Counterpart Contract</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                    	<td>1</td>
                                                        <td><input type="text" name="counterpart_company[]" id="val_counterpart_company"
                                                                class="form-control" autocomplete="off" required ></td>
                                                        <td><input type="text" name="counterpart_contact[]" id="val_counterpart_contact"
                                                                class="form-control" autocomplete="off" required ></td>
                                                        <td><a href="#" class="btn btn-danger remove"><i
                                                                    class="glyphicon glyphicon-remove"></i></a></td>
                                                    </tr>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        
                                                        <td align="right"><input id="myBtn" type="submit" name=""
                                                                value="Submit" class="btn btn-success"></td>
                                                        <td><a href="#" id="addRow" class="btn btn-warning addRow"><i
                                                                    class="glyphicon glyphicon-plus"></i></a></td>
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
        var no = lgtr+1;
        console.log("row tr");
        console.log(lgtr);
        var tr = '<tr>' +
            '<td>' + no + '</td>'+
            '<td><input type="text" name="counterpart_company[]" id="val_counterpart_company' + lgtr + '" class="form-control" autocomplete="off" required="" ></td>' +
            '<td><input type="text" name="counterpart_contact[]" id="val_counterpart_contact' + lgtr + '" class="form-control" autocomplete="off" required="" ></td>' +
            '<td><a  class="btn btn-danger remove"><i class="glyphicon glyphicon-remove"></i></a></td>' +
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
    
    function getsf(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url: 'get-usersf/' + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                
                document.getElementById("pic").value = response['data'][0].nama;
                document.getElementById("nrp").value = response['data'][0].nrp;
                document.getElementById("email").value = response['data'][0].email;
                // document.getElementById("val_pn_patria").value = response['data'].pn_patria;
                // document.getElementById("val_pn_vendor").value = response['data'].pn_vendor;
            }
        });
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