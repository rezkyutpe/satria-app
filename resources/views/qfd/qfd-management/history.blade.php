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
table { border-collapse: collapse; empty-cells: show; }

td { position: relative; }

tr.strikeout td:before {
  content: " ";
  position: absolute;
  top: 50%;
  left: 0;
  border-bottom: 1px solid #111;
  width: 100%;
}

tr.strikeout td:after {
  content: "\00B7";
  font-size: 1px;
}

/* Extra styling */
/*td { width: 100px; }*/
/*th { text-align: left; }*/


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
        <div class="page-head">
            <div class="page-title">
                <h1>History Bom QFD</h1>
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
                                            <label class="form-control-label">Material Desc : </label>
                                            <input type="hidden" name="id" value="{{ $data['trxmat']->id }}" readonly autocomplete="off"
                                               class="form-control" readonly />
                                           
                                            <input type="text" list="list_pro" id="matdesc" name="matdesc" value="{{ $data['trxmat']->material_description }}" autocomplete="off"
                                                onchange="getsapmat(this)" class="form-control" readonly required />
                                            
                                        </div>
                                        
                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                    <div class="form-group">
                                            <label class="form-control-label">Material Number : </label>
                                            <input type="text" id="val_num" name="num" class="form-control"
                                             autocomplete="off" value="{{ $data['trxmat']->material_number }}" readonly required />
                                        </div>
                                        
                                    </div>
                                    <div class="col-xl-4 col-md-4 m-b-10px">
                                        <div class="form-group">
                                            <label class="form-control-label">Product Spec: </label>
                                            <input type="file" name="productspec" readonly class="form-control"
                                                autocomplete="off" />
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
                                        <div id="collapseOne" class="panel-collapse collapsein">
                                          <div class="panel-body">
                                             <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <!-- <th>Material QFD</th>
                                                            <th width="250">Material Desc</th> -->
                                                            <th width="80">Item Bom</th>
                                                            <th>Component Bom</th>
                                                            <th>Component Desc Bom</th>
                                                            <th width="80">Qty Bom</th>
                                                            <th width="80">OuM Bom</th>
                                                            <th width="80">Item Qfd</th>
                                                            <th>Component Qfd</th>
                                                            <th >Component Desc Qfd</th>
                                                            <th width="80">Qty Qfd</th>
                                                            <th width="80">OuM Qfd</th>
                                                            <th width="100">Flag</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                        @php($no=0)
                                                        @foreach($data['trxbomhistory'] as $bom)
                                                        @php($no=$no+1)
                                                        <tbody class="tbom">
                                                        <tr onclick="myFunctionBom(this)" @if($bom->flag==0) class="strikeout" @endif  @if($bom->item_bom=='') style="background-color:#c6f79e" @endif)>
                                                            <td>{{ $no }}</td>
                                                           <!--  <td><input type="text" name="material_qfd[]" id="material_qfd" class="form-control" value="{{ $bom->material_qfd }}" readonly autocomplete="off"></td>
                                                            <td><input type="text" name="material_desc[]" id="material_desc" class="form-control" value="{{ $bom->material_desc }}" readonly autocomplete="off"></td> -->
                                                            <td><input type="text" name="item_bom[]" id="item_bom" class="form-control" value="{{ $bom->item_bom }}" readonly autocomplete="off"></td>
                                                            <td><input type="text" list="list_component" id="component_bom" name="component_bom[]" value="{{ $bom->component_bom }}" autocomplete="off" readonly class="form-control" /></td>
                                                            <td><textarea readonly class="form-control">{{ $bom->component_desc_bom }}</textarea>
                                                             <!--    <input type="text" id="component_desc_bom" name="component_desc_bom[]" value="{{ $bom->component_desc_bom }}" autocomplete="off" readonly class="form-control" /> -->
                                                            </td>
                                                            <td><input type="text" name="qty_bom[]" id="qty_bom" class="form-control" value="{{ $bom->qty_bom }}" readonly autocomplete="off"></td>
                                                            <td><input type="text" name="oum_bom[]" id="oum_bom" class="form-control" value="{{ $bom->oum_bom }}" readonly autocomplete="off">


                                                            <td @if($bom->item_bom!='' && $bom->item!=$bom->item_bom) style="background-color:#e2e677" @endif><input type="text" name="item[]" id="item" class="form-control" value="{{ $bom->item }}" readonly autocomplete="off"></td>
                                                            <td @if($bom->item_bom!='' && $bom->component!=$bom->component_bom) style="background-color:#e2e677" @endif><input type="text" list="list_component" id="component" name="component[]" value="{{ $bom->component }}" autocomplete="off" readonly class="form-control" /></td>
                                                            <td @if($bom->item_bom!='' && $bom->component_desc!=$bom->component_desc_bom) style="background-color:#e2e677" @endif><textarea readonly class="form-control">{{ $bom->component_desc }}</textarea>
                                                               <!--  <input type="text" id="component_desc" name="component_desc[]" value="" autocomplete="off" readonly class="form-control" /> -->
                                                            </td>
                                                            <td  @if($bom->item_bom!='' && $bom->qty!=$bom->qty_bom) style="background-color:#e2e677" @endif ><input type="text" name="qty[]" id="qty" class="form-control" value="{{ $bom->qty }}" readonly autocomplete="off"></td>
                                                            <td @if($bom->item_bom!='' && $bom->oum!=$bom->oum_bom) style="background-color:#e2e677" @endif ><input type="text" name="oum[]" id="oum" class="form-control" value="{{ $bom->oum }}" readonly autocomplete="off">
                                                               </td>
                                                            <td>@if($bom->flag==0) <a href="#" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a> @else <a href="#" class="btn btn-info"><i class="glyphicon glyphicon-check"></i></a>@endif</td>
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
                                            <legend>Detail Process</legend>
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
                                                        <th>Diff</th>
                                                        <th>PIC</th>
                                                        <th>Remark</th>
                                                        <!-- <th>#</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php($no=0)
                                                    @php($totaldifproses=0)
                       								@php($totaldif=0)
                                                    @foreach($data['trxmatdetail'] as $key => $trxmatdetail)
                                                    @php($no=$no+1)
                                                    @php($totaldif=$totaldif+($trxmatdetail->diff))
                                                    <tr onclick="myFunction(this)">
                                                        <td>
			                                            <input type="hidden" name="id_detail[]" value="{{ $trxmatdetail->id }}" autocomplete="off"
			                                               class="form-control" readonly />
                                                            <article class="panel panel-info panel-outline">
                                                                <div class="panel-heading icon">
                                                                    <i class="glyphicon glyphicon-info-sign"></i>
                                                                </div>
                                                                <div class="panel-body diffproses{{ $no }}">
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
                                                                class="form-control" value="{{ $trxmatdetail->id_proses }}" readonly required />
                                                            
                                                        </td>
                                                        </td>
                                                        <td> <input type="date"  name="from[]" id="from_date{{ $no }}" value="{{ $trxmatdetail->from }}" onchange="handlerFromDate(event);" class="form-control" readonly> </td>
                                                        <td> <input type="date"  name="to[]" id="to_date{{ $no }}" value="{{ $trxmatdetail->to }}"  onchange="handlerToDate(event);" class="form-control" readonly> </td>
                                                        <td width="100"> <input type="text" name="datediff[]" id="datediff{{ $no }}"" value="{{ $trxmatdetail->diff }}" readonly class="form-control" readonly> </td>

                                                        <td><input type="text" list="list_pic" name="pic[]" value="{{ $trxmatdetail->pic }}"  id="pic" autocomplete="off"
                                                               class="form-control" required readonly/>
                                                            </td>
                                                        <td> <input type="text" name="remark[]" id="remark" value="{{ $trxmatdetail->remark }}"  class="form-control" autocomplete="off" readonly> </td>
                                                        
                                                        <!-- 
                                                        <td><a href="#" class="btn btn-danger remove"><i
                                                                    class="glyphicon glyphicon-remove"></i></a></td> -->
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none"></td>
                                                        <td style="border: none">Total : {{ $totaldifproses+$totaldif }} Days</td>
                                                        
                                                        <td align="right">
                                                            @if($data['trxmat']->status==0 || $data['trxmat']->status==3)
                                                           <!--  <input id="myBtn" type="submit" name=""
                                                                value="Fully Approve" data-toggle="modal" data-target="#modal-approve-qfd"  class="btn btn-success">  <input id="myBtn" type="submit" name=""
                                                                value="Escalate" data-toggle="modal" data-target="#modal-escalate-qfd"  class="btn btn-warning"> -->
                                                            @elseif($data['trxmat']->status==1)
                                                               <article class="panel panel-info panel-outline">
                                                                    <div class="panel-heading icon">
                                                                        <i class="fa fa-question-circle fa-lg custom--1"></i>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        Partial Approved at : {{ $data['trxmat']->accepted_date  }}
                                                                    </div>
                                                                </article>
                                                            @elseif($data['trxmat']->status==5)
                                                               <article class="panel panel-warning panel-outline">
                                                                    <div class="panel-heading icon">
                                                                        <i class="fa fa-question-circle fa-lg custom--1"></i>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        Escalated at : {{ $data['trxmat']->updated_at  }}
                                                                    </div>
                                                                </article>
                                                            @elseif($data['trxmat']->status==4)
                                                               <article class="panel panel-danger panel-outline">
                                                                    <div class="panel-heading icon">
                                                                        <i class="fa fa-times-circle fa-lg custom--1"></i>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        Rejected at : {{ $data['trxmat']->accepted_date  }}
                                                                    </div>
                                                                </article>
                                                            @elseif($data['trxmat']->status==2)
                                                                <article class="panel panel-success panel-outline">
                                                                    <div class="panel-heading icon">
                                                                        <i class="fa fa-check fa-lg custom--1"></i>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        Fully Approved at : {{ $data['trxmat']->approved_date  }}
                                                                    </div>
                                                                </article>
                                                            @endif
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
</div>


<div id="modal-escalate-qfd" class="modal fade">
        <form method="post" action="{{url('escalate-qfd')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to Escalate Qfd  ?</p>
                        <input type="hidden" name="id" value="{{ $data['trxmat']->id  }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                       
                        <button type="submit" class="btn btn-success">Yes</button>
                       
                    </div>
                </div>
            </div>
        </form>
    </div>
    
<div id="modal-approve-qfd" class="modal fade">
        <form method="post" action="{{url('approve-qfd')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to Fully Approve Qfd  ?</p>
                        <input type="hidden" name="id" value="{{ $data['trxmat']->id  }}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                       
                        <button type="submit" class="btn btn-success">Yes</button>
                    
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('myscript')

<script type="text/javascript">
	let indextr = 0;
	function myFunction(x) {
	  indextr = x.rowIndex;
	  console.log(indextr);
	}
   	function handlerFromDate(e){
	  console.log(indextr+" - from"+e.target.value);
	  document.getElementById('to_date'+indextr).min = e.target.value;
	  setup1(indextr);
	  setupfrom(indextr);
	}
	function handlerToDate(e){
	  console.log(indextr+" -to "+e.target.value);
	  document.getElementById('from_date'+indextr).max = e.target.value;
	  setup1(indextr);
	  setupto(indextr);
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
      }
  }
  const setupto = (tr) => {
      console.log("setupto");
      console.log(tr);
          
            console.log("setup2>1");
          var trr = tr+1;
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