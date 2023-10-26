@extends('panel.master')

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>

@section('css')
<style>
.panel-heading .accordion-toggle:after {
    /* symbol for "opening" panels */
    font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
    content: "\e114";    /* adjust as needed, taken from bootstrap.css */
    float: right;        /* adjust as needed */
    color: grey;         /* adjust as needed */
}
.panel-heading .accordion-toggle.collapsed:after {
    /* symbol for "collapsed" panels */
    content: "\e080";    /* adjust as needed, taken from bootstrap.css */
}
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
    .timeline {
    position: relative;
    padding: 21px 0px 10px;
    margin-top: 4px;
    margin-bottom: 30px;
}

.timeline .line {
    position: absolute;
    width: 4px;
    display: block;
    background: currentColor;
    top: 0px;
    bottom: 0px;
    margin-left: 30px;
}

.timeline .separator {
    border-top: 1px solid currentColor;
    padding: 5px;
    padding-left: 40px;
    font-style: italic;
    font-size: .9em;
    margin-left: 30px;
}

.timeline .line::before { top: -4px; }
.timeline .line::after { bottom: -4px; }
.timeline .line::before,
.timeline .line::after {
    content: '';
    position: absolute;
    left: -4px;
    width: 12px;
    height: 12px;
    display: block;
    border-radius: 50%;
    background: currentColor;
}

.timeline .panel {
    position: relative;
    margin: 10px 0px 21px 70px;
    clear: both;
}

.timeline .panel::before {
    position: absolute;
    display: block;
    top: 8px;
    left: -24px;
    content: '';
    width: 0px;
    height: 0px;
    border: inherit;
    border-width: 12px;
    border-top-color: transparent;
    border-bottom-color: transparent;
    border-left-color: transparent;
}

.timeline .panel .panel-heading.icon * { font-size: 20px; vertical-align: middle; line-height: 40px; }
.timeline .panel .panel-heading.icon {
    position: absolute;
    left: -59px;
    display: block;
    width: 40px;
    height: 40px;
    padding: 0px;
    border-radius: 50%;
    text-align: center;
    float: left;
}

.timeline .panel-outline {
    border-color: transparent;
    background: transparent;
    box-shadow: none;
}

.timeline .panel-outline .panel-body {
    padding: 10px 0px;
}

.timeline .panel-outline .panel-heading:not(.icon),
.timeline .panel-outline .panel-footer {
    display: none;
}
</style>
@endsection

@section('content')

<div class="loader" style="display:none;">
    <div class="loader-main"><i class="fa fa-spinner fa-pulse"></i></div>
</div>

<div class="content-body-white">

    <form method="post" action="{{url('update-qfd')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="page-head">
            <div class="page-title">
                <h1>Edit QFD</h1>
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
                                            <label class="form-control-label">Material Number : </label>
                                            <input type="text" id="val_num" name="num" class="form-control"
                                             autocomplete="off" value="{{ $data['trxmat']->material_number }}" readonly required />
                                        </div>
                                        
                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Material Desc : </label>
                                            <input type="hidden" name="id" value="{{ $data['trxmat']->id }}" autocomplete="off"
                                               class="form-control" readonly />
                                           
                                            <input type="text" list="list_pro" id="matdesc" name="matdesc" value="{{ $data['trxmat']->material_description }}" autocomplete="off"
                                                onchange="getsapmat(this)" class="form-control" required readonly />
                                          
                                        </div>
                                        
                                    </div>
                                   
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Product Spec: </label>
                                            <a class="btn btn-success" href="{{ asset('public/qfd/'.$data['trxmat']->file) }}" target="_blank">{{ $data['trxmat']->file }}</a>
                                            <input type="file" name="productspec" class="form-control"
                                                autocomplete="off" />
                                        </div>
                                       
                                    </div>
                                   
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">No SO : </label>
                                           
                                            <input type="text"  name="no_so" autocomplete="off" class="form-control"  value="{{ $data['trxmat']->no_so }}"/>
                                           
                                        </div>
                                        
                                    </div>
                                     <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Customer : </label>
                                           
                                            <input type="text"  name="cust" autocomplete="off" class="form-control"  value="{{ $data['trxmat']->cust }}"/>
                                           
                                        </div>
                                        
                                    </div>
                                     <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Qty : </label>
                                           
                                            <input type="number"  name="qty_item" autocomplete="off" class="form-control"  value="{{ $data['trxmat']->qty }}" />
                                           
                                        </div>
                                        
                                    </div>
                                      <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Req Delivery Date : </label>
                                           
                                            <input type="date"  name="req_deliv_date" autocomplete="off" class="form-control"  value="{{ $data['trxmat']->req_deliv_date }}"/>
                                           
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Attendance : </label>
                                            <input type="email" name="attendance" list="attendance" multiple data-list-filter="^" class="form-control" value="{{ $data['trxmat']->attendance }}" placeholder="Type your mail attendance" />
                                            <datalist id="attendance">
                                                @foreach($data['emp'] as $emp)
                                                    <option value="{{ $emp['email'] }}">{{ $emp['nama'] }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                      <div class="col-xl-8 col-md-8 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Note : </label>
                                           
                                            <textarea autocomplete="off" cols="60" rows="10" class="form-control"  name="note">{{ $data['trxmat']->note }}</textarea>
                                           
                                        </div>
                                       
                                    </div>
    
           
                                    <div class="col-md-12 col-xl-12 m-b-10px">
                                        <div class="panel-group" id="accordion">
                                      <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                              List Detail BOM 
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse">
                                          <div class="panel-body">
                                             <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                           <!--  <th>Material QFD</th>
                                                            <th width="250">Material Desc</th> -->
                                                            <th>Item</th>
                                                            <th>Component</th>
                                                            <th width="250">Component Desc</th>
                                                            <th width="80">Qty</th>
                                                            <th width="80">OuM</th>
                                                            <th>Flag</th>
                                                            <th class="text-center" width="200">#</th>
                                                        </tr>
                                                    </thead>
                                                        @php($no=0)
                                                        @foreach($data['trxbomdetail'] as $bom)
                                                        @php($no=$no+1)
                                                        <tbody class="tbom">
                                                        <tr onclick="myFunctionBom(this)">
                                                             <td>{{ $no }}</td>
                                                            <!-- <td> -->
                                                                <input type="hidden" name="id_bom[]" id="id_bom" class="form-control" value="{{ $bom->id }}" readonly autocomplete="off">
                                                            <!-- </td> -->
                                                            <!-- <td> -->
                                                                <input type="hidden" name="material_qfd[]" id="material_qfd" class="form-control" value="{{ $bom->material_qfd }}" readonly autocomplete="off">
                                                            <!-- </td> -->
                                                            <td><input type="text" name="material_desc[]" id="material_desc" class="form-control" value="{{ $bom->material_desc }}" readonly autocomplete="off"></td>
                                                            <td><input type="text" name="item[]" id="item" class="form-control" value="{{ $bom->item }}" readonly autocomplete="off"></td>
                                                            <td><input type="text" id="component{{ $no }}" name="component[]" value="{{ $bom->component }}" autocomplete="off" class="form-control" readonly /></td>
                                                            <td><input type="text" id="component_desc{{ $no }}" name="component_desc[]" value="{{ $bom->component_desc }}" autocomplete="off" class="form-control" readonly /></td>
                                                            <td><input type="text" name="qty[]" id="qty{{ $no }}" class="form-control" value="{{ $bom->qty }}" autocomplete="off" readonly ></td>
                                                            <td><input type="text" name="oum[]" id="oum{{ $no }}" class="form-control" value="{{ $bom->oum }}" autocomplete="off" readonly >
                                                               </td>
                                                            <td><input type="hidden" name="flag[]" id="flag{{ $bom->id }}" class="form-control" value="1" autocomplete="off">
                                                            <input type="text" name="flag_val[]" id="flag_val{{ $bom->id }}" class="form-control" value="Used" autocomplete="off" readonly></td>
                                                            <td class="text-center">

                                                                <a href="#my_modal" class="btn btn-success" data-toggle="modal" data-id="my_id_value" onclick="editBom({{ $no }})"><i class="glyphicon glyphicon-edit"></i></a>
                                                                <a href="#" class="btn btn-danger" onclick="unused({{ $bom->id }})"><i
                                                                    class="glyphicon glyphicon-remove"></i></a>
                                                                <a href="#" class="btn btn-info" onclick="used({{ $bom->id }})"><i
                                                                    class="glyphicon glyphicon-check"></i></a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                        @endforeach
                                                     

                                                </table>
                                          </div>
                                        </div>
                                      </div>
                            </div>
                                        <fieldset>
                                            
                                            <div class="col-xl-6 col-md-6 m-b-10px">
                                            <legend>Edit Process</legend>
                                            </div>
                                            <div class="col-xl-2 col-md-2 m-b-10px ">
                                                <!-- <label for="">Package : </label> -->
                                            </div>
                                            <div class="col-xl-4 col-md-2 m-b-10px pull-right">
                                                <!-- <input type="text" id="package" name="package" class="form-control" onchange="cekPackage(this);" autocomplete="off" placeholder="Optional"> -->
                                               
                                                <!-- Calculate :  <input type="checkbox" id="myCheck" disabled onclick="calculate()" required> -->
                                            </div>
                                            <table class="table table-bordered timeline">
                                                <thead>
                                                    <tr>
                                                        <th width="150"><div class="line text-muted"></div></th>
                                                        <th>Process</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Lead Time</th>
                                                        <th>PIC</th>
                                                        <th>Remark</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php($no=0)
                                                    @php($totaldifproses=0)
                                                    @php($totaldif=0)
                                                    @foreach($data['trxmatdetail'] as $key => $trxmatdetail)
                                                    @php($no=$no+1)
                                                    @php($totaldif=$totaldif+($trxmatdetail->diff))
                                                    @php($diffproses =0)
                                                    <tr onclick="myFunction(this)">
                                                        <td>
                                                        <input type="hidden" name="id_detail[]" value="{{ $trxmatdetail->id }}" autocomplete="off"
                                                           class="form-control" readonly />
                                                            <article class="panel panel-info panel-outline">
                                                                <div class="panel-heading icon">
                                                                    <i class="glyphicon glyphicon-info-sign"></i>
                                                                </div>
                                                                <div class="panel-body diffproses{{ $no }} amountproses">
                                                                    @if(isset($data['trxmatdetail'][$key-1]))
                                                                        @php($diffproses = Helper::Datediff($data['trxmatdetail'][$key-1]['to'],$trxmatdetail->from))
                                                                        @php($totaldifproses=$totaldifproses+($diffproses))
                                                                        {{ $diffproses }} Days
                                                                    @else
                                                                        {{ 0 }} Days
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        </td>
                                                        <td><input type="text" list="list_proses" id="process" name="process[]" autocomplete="off"
                                                                class="form-control" value="{{ $trxmatdetail->id_proses }}" required />
                                                            <datalist id="list_proses">
                                                                 @foreach($data['process'] as $process)
                                                                <option>
                                                                    {{ $process->nama }}</option>
                                                                @endforeach
                                                            </datalist>
                                                        </td>
                                                        </td>
                                                        <td> <input type="date"  name="from[]" id="from_date{{ $no }}" value="{{ $trxmatdetail->from }}" onchange="handlerFromDate(event);" class="form-control"> </td>
                                                        <td> <input type="date"  name="to[]" id="to_date{{ $no }}" value="{{ $trxmatdetail->to }}"  onchange="handlerToDate(event);" class="form-control"> </td>
                                                        <td width="100"> <input type="text" name="datediff[]" id="datediff{{ $no }}" value="{{ $trxmatdetail->diff }}" readonly class="form-control amountdiff">
 <input type="hidden" value="{{ $diffproses }}" readonly class="form-control amountproses diffamount{{ $no }}"> 
                                                       </td>

                                                        <td><input type="text" list="list_pic" name="pic[]"  onchange="getsf1(this)" value="{{ $trxmatdetail->pic }}"  id="pic{{$no}}" autocomplete="off"
                                                               class="form-control" required />
                                                            <datalist id="list_pic">
                                                                @foreach($data['emp'] as $emp)
                                                                <option value="{{ $emp['nrp'] }}">{{ $emp['nama'] }}</option>
                                                                    @endforeach
                                                            </datalist><input type="hidden" name="pic_nrp[]" id="pic_nrp{{$no}}" value="{{ $trxmatdetail->pic_nrp }}"class="form-control" autocomplete="off"> <input type="hidden" name="pic_email[]" id="pic_email{{$no}}" value="{{ $trxmatdetail->pic_email }}" class="form-control" autocomplete="off"> </td>
                                                        
                                                        <td> <input type="text" name="remark[]" id="remark" value="{{ $trxmatdetail->remark }}"  class="form-control" autocomplete="off"> </td>
                                                        
                                                        
                                                        <td><a href="#" class="btn btn-danger remove"><i
                                                                    class="glyphicon glyphicon-remove"></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none">Total Lead Time </td>
                                                        <td style="border: none">: </td>
                                                        <td style="border: none"><span class="total"> {{ $totaldifproses+$totaldif }} Days</span></td>
                                                        
                                                        <td align="right"><input id="myBtn" type="submit" name=""
                                                                value="Submit" class="btn btn-success"></td>
                                                        <td>
                                                        <!--    <a href="#" id="addRow" class="btn btn-warning addRow"><i
                                                                    class="glyphicon glyphicon-plus"></i></a> -->
                                                                </td>
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
 <div id="my_modal" class="modal fade">
       
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Bom</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Component : *</label>
                            <input type="text" list="list_component" onchange="getcomponent(this)" name="edit_comp" id="edit_comp" class="form-control" autocomplete="off" required="">
                            <datalist id="list_component">@foreach($data['mstcomp'] as $mstcomp)<option value="{{ $mstcomp['material'] }}">{{ $mstcomp['material_desc'] }}</option> @endforeach </datalist>
                        </div>
                         <div class="form-group text-left">
                            <label class="form-control-label">Component Desc: *</label>
                            <input type="text"  name="comp_desc" id="comp_desc" class="form-control" autocomplete="off" required="">
                            <input type="hidden"  name="no" id="no" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Qty: *</label>
                            <input type="text" name="qty" id="edit_qty" class="form-control" autocomplete="off" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Oum: *</label>
                            <input type="text" name="oum" id="edit_oum" class="form-control" autocomplete="off" required="">
                        </div>
                        
                    </div>
                    <input type="hidden" name="id" value=""/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success" data-dismiss="modal" onclick="updateBom(this)">Yes</button>
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('myscript')

<script type="text/javascript">
    let indextr = 0;
    
    function getsf1(sel) {
        
        var lgtr = indextr;
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url: '{{ url('get-usersf') }}/' + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                console.log(lgtr);
                console.log(response['data']);
                document.getElementById("pic"+lgtr).value = response['data'][0].nama;
                document.getElementById("pic_nrp"+lgtr).value = response['data'][0].nrp;
                document.getElementById("pic_email"+lgtr).value = response['data'][0].email;
                // document.getElementById("val_pn_patria").value = response['data'].pn_patria;
                // document.getElementById("val_pn_vendor").value = response['data'].pn_vendor;
            }
        });
    }
    function myFunction(x) {
      indextr = x.rowIndex;
      console.log(indextr);
    }
    function handlerFromDate(e){
      console.log(indextr+" - from"+e.target.value);
      document.getElementById('to_date'+indextr).min = e.target.value;
      setup1(indextr);
      setupfrom(indextr);
      total();
    }
    function editBom(e) {
        console.log("editindex");
      editindex = e;
      console.log(editindex);
        var compdesc = document.getElementById('component_desc'+editindex).value;
        var comp = document.getElementById('component'+editindex).value;
        var qty = document.getElementById('qty'+editindex).value;
        var oum = document.getElementById('oum'+editindex).value;
      
        console.log(compdesc);
        document.getElementById('no').value= editindex;
        document.getElementById('comp_desc').value= compdesc;
        document.getElementById('edit_comp').value= comp;
        document.getElementById('edit_qty').value= qty;
        document.getElementById('edit_oum').value= oum;

    }
    function updateBom(e){
        var no          = document.getElementById('no').value;
        var compdesc    = document.getElementById('comp_desc').value;
        var comp        = document.getElementById('edit_comp').value;
        var qty         = document.getElementById('edit_qty').value;
        var oum         = document.getElementById('edit_oum').value;
        document.getElementById('component_desc'+no).value= compdesc;
        document.getElementById('component'+no).value= comp;
        document.getElementById('qty'+no).value= qty;
        document.getElementById('oum'+no).value= oum;
    }
    
    function getcomponent(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url: "{{ url('get-component') }}/" + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                document.getElementById('comp_desc').value= response['data'].material_desc;
                document.getElementById('edit_oum').value= response['data'].oum;
            }
        });
    }
    function getaddcomponent(sel) {
        var id = sel.value;
        var lgtr = $("#myBom tbody tr").length;
        console.log("comp tr");
        console.log(lgtr);
        // AJAX request 
        $.ajax({
            url: "{{ url('get-component') }}/" + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                document.getElementById('item'+lgtr).value= response['data'].item;
                document.getElementById('component'+lgtr).value= response['data'].component;
                document.getElementById('qty'+lgtr).value= response['data'].qty;
                document.getElementById('oum'+lgtr).value= response['data'].oum;
            }
        });
    }
     function unused(x) {
      // indextr = x.rowIndex;
      console.log(x);
      document.getElementById('flag'+x).value='0';
      document.getElementById('flag_val'+x).value='Unused';
    }
    function used(x) {
      // indextr = x.rowIndex;
      console.log(x);
      document.getElementById('flag'+x).value='1';
      document.getElementById('flag_val'+x).value='Used';
    }
    function handlerToDate(e){
      console.log(indextr+" -to "+e.target.value);
      document.getElementById('from_date'+indextr).max = e.target.value;
      setup1(indextr);
      setupto(indextr);
      total();
    }

    $('tbody').delegate('.quantity,.budget', 'keyup', function () {
        var tr = $(this).parent().parent();
        var quantity = tr.find('.quantity').val();
        var budget = tr.find('.budget').val();
        var amount = (quantity * budget);
        tr.find('.amount').val(amount);
        total();
    });
    function total() {
        var totaldiff = 0;
        var totalproses = 0;
        $('.amountproses').each(function (i, e) {
            var amount = $(this).val() - 0;
            totalproses += amount;
        });
        $('.amountdiff').each(function (i, e) {
            var amount = $(this).val() - 0;
            totaldiff += amount;
        });
        $('.total').html(totaldiff+totalproses + " Days");
    }

    function parseDate(input) {
      var parts = input.match(/(\d+)/g);
      return new Date(parts[0], parts[1]-1, parts[2]); 
    }
    
    
    function getsapmat(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url:  "{{ url('get-sapmat') }}/" + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                document.getElementById("val_num").value = response['data'].smt_name;
                // document.getElementById("val_pn_patria").value = response['data'].pn_patria;
                // document.getElementById("val_pn_vendor").value = response['data'].pn_vendor;
            }
        });
    }
    const setup1 = (tr) => {
        // console.log(tr);
      let d0 = $('#from_date'+tr).val();
      let d1 = $('#to_date'+tr).val();
      console.log(d0);
      console.log(d1);

     
      var holidays = ['2021-08-17','2021-08-11'];
      var startDate = parseDate(d0);
      var endDate = parseDate(d1);  

      var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
      startDate.setHours(0, 0, 0, 1);  // Start just after midnight
      endDate.setHours(23, 59, 59, 999);  // End just before midnight
      var diff = endDate - startDate;  // Milliseconds between datetime objects    
      var days = Math.ceil(diff / millisecondsPerDay);

      // Subtract two weekend days for every week in between
      var weeks = Math.floor(days / 7);
      days -= weeks * 2;

      // Handle special cases
      var startDay = startDate.getDay();
      var endDay = endDate.getDay();
        
      // Remove weekend not previously removed.   
      if (startDay - endDay > 1) {
        days -= 2;
      }
      // Remove start day if span starts on Sunday but ends before Saturday
      if (startDay == 0 && endDay != 6) {
        days--;  
      }
      // Remove end day if span ends on Saturday but starts after Sunday
      if (endDay == 6 && startDay != 0) {
        days--;
      }
      /* Here is the code */
      holidays.forEach(day => {
        if ((day >= d0) && (day <= d1)) {
          /* If it is not saturday (6) or sunday (0), substract it */
          if ((parseDate(day).getDay() % 6) != 0) {
            days--;
          }
        }
      });
      console.log(days-1);
      $("#datediff"+tr).val(days-1);

    }
    const setupfrom = (tr) => {
      console.log("setupfrom");
      console.log(tr);
      if(tr>1){
          
            console.log("setup2>1");
          var trr = tr-1;
          let d0 = $('#from_date'+tr).val();
          let d1 = $('#to_date'+trr).val();
            console.log(d1);

          var holidays = ['2021-08-17','2021-08-11'];
          var startDate = parseDate(d1);
          var endDate = parseDate(d0);  

          var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
          startDate.setHours(0, 0, 0, 1);  // Start just after midnight
          endDate.setHours(23, 59, 59, 999);  // End just before midnight
          var diff = endDate - startDate;  // Milliseconds between datetime objects    
          var days = Math.ceil(diff / millisecondsPerDay);

          // Subtract two weekend days for every week in between
          var weeks = Math.floor(days / 7);
          days -= weeks * 2;

          // Handle special cases
          var startDay = startDate.getDay();
          var endDay = endDate.getDay();
            
          // Remove weekend not previously removed.   
          if (startDay - endDay > 1) {
            days -= 2;
          }
          // Remove start day if span starts on Sunday but ends before Saturday
          if (startDay == 0 && endDay != 6) {
            days--;  
          }
          // Remove end day if span ends on Saturday but starts after Sunday
          if (endDay == 6 && startDay != 0) {
            days--;
          }
          /* Here is the code */
          holidays.forEach(day => {
            if ((day >= d0) && (day <= d1)) {
              /* If it is not saturday (6) or sunday (0), substract it */
              if ((parseDate(day).getDay() % 6) != 0) {
                days--;
              }
            }
          });
          console.log("diffproses");
          console.log(days-1);
          $('.diffproses'+tr).html(days-1+' Days');
          $('.diffamount'+tr).val(days-1);
      }
  }
  const setupto = (tr) => {
      console.log("setupto");
      console.log(tr);
          var trr = tr+1;
          if(typeof $('#from_date'+trr).val() !== 'undefined'){
           console.log("setup2>1");
          let d0 = $('#from_date'+trr).val();
          let d1 = $('#to_date'+tr).val();
            console.log(d1);

          var holidays = ['2021-08-17','2021-08-11'];
          var startDate = parseDate(d1);
          var endDate = parseDate(d0);  

          var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
          startDate.setHours(0, 0, 0, 1);  // Start just after midnight
          endDate.setHours(23, 59, 59, 999);  // End just before midnight
          var diff = endDate - startDate;  // Milliseconds between datetime objects    
          var days = Math.ceil(diff / millisecondsPerDay);

          // Subtract two weekend days for every week in between
          var weeks = Math.floor(days / 7);
          days -= weeks * 2;

          // Handle special cases
          var startDay = startDate.getDay();
          var endDay = endDate.getDay();
            
          // Remove weekend not previously removed.   
          if (startDay - endDay > 1) {
            days -= 2;
          }
          // Remove start day if span starts on Sunday but ends before Saturday
          if (startDay == 0 && endDay != 6) {
            days--;  
          }
          // Remove end day if span ends on Saturday but starts after Sunday
          if (endDay == 6 && startDay != 0) {
            days--;
          }
          /* Here is the code */
          holidays.forEach(day => {
            if ((day >= d0) && (day <= d1)) {
              /* If it is not saturday (6) or sunday (0), substract it */
              if ((parseDate(day).getDay() % 6) != 0) {
                days--;
              }
            }
          });
          console.log("diffproses");
          console.log(days-1);
          $('.diffproses'+trr).html(days-1+' Days');
          $('.diffamount'+trr).val(days-1);
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

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.2.3/jquery.min.js"></script>
@endsection