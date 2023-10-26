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
            <h1>Ticket Management </h1>
        </div>
        <div class="pull-right">
        <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-ticket" href="#">Tambah</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" type="search" autocomplete="off" @if(isset($_GET['search'])) value="{{ $_GET['search'] }}" @endif placeholder="Search" class="form-control">
                    </div>
                    
                    <div class="col-xl-12 col-md-3 m-b-10px">
                        <div class="form-group">
                            <select class="form-control" name="status" id="status">
                            @if(!isset($_GET['status']))
                                <option value="">Filter Status</option>
                                <option value="O">Open</option>
                                <option value="P">Proccess</option>
                                <option value="C">Close</option>
                            @elseif($_GET['status'] == 'P')
                                <option value="">No Filter</option>
                                <option value="O">Open</option>
                                <option value="P" selected>Proccess</option>
                                <option value="C">Close</option>
                            @elseif($_GET['status'] == 'C')
                                <option value="">No Filter</option>
                                <option value="O">Open</option>
                                <option value="P">Proccess</option>
                                <option value="C" selected>Close</option>
                            @else
                                <option value="">No Filter</option>
                                <option value="O" selected>Open</option>
                                <option value="P">Proccess</option>
                                <option value="C">Close</option>
                            @endif
                            </select>
                        </div>
                    </div>
                    
                   
                    
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div>
                <br>
                <br>
            <div class="table-responsive custom--2">
                <table class="table" style="width: 99%">
                @foreach($data['ticket'] as $ticket)
                    <tbody>
                        <tr>
                            <td style="vertical-align:top;width: 65%;" colspan="3"><strong># <span style="background-color: {{ $ticket->bg_color }}; color: {{ $ticket->fg_color }};" class="badge badge-secondary">Ticket {{ $ticket->flow_name}}</span> / </strong> 
                                <strong style="color: #0f0;">{{ $ticket->ticket_id }} </strong>  
                                <strong>/ </strong><i class="fa fa-user fa-lg custom--1"></i> {{ $ticket->reporter_nrp }} <strong>/ </strong> <i class="fa fa-clock-o fa-lg custom--1"></i> {{ date('d F Y, H:i',strtotime($ticket->created_at))}}</td>
                                <td style="vertical-align:top;width: 25%;" ><span class="pull-right"><i data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Empty" class="fa fa-flag fa-lg custom--1" style="color: {{ $ticket->color }}" ></i>{{ $ticket->priority_name }}</span></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top;width: 40%;"><strong>{{ $ticket->reporter_name }}<br>
                                    <span style="color: grey;font-size:12px">{{ $ticket->reporter_nrp }}</span><br>
                                    <strong>Asset :</strong><br><span style="color: grey;font-size:12px">{{ $ticket->asset_name }}</span></td>
                            <td style="vertical-align:top;width: 25%;"><strong>Subject</strong><br><span style="color: grey;font-size:12px">{{ $ticket->subject }}</span><br><strong>Message</strong><br><span style="color: grey;font-size:12px">{{ $ticket->message }}</span></td>
                            <td></td>
                            <td style="vertical-align:top;width: 25%;"><strong>Assister</strong><br>
                                @if($ticket->assist_id=='')
                                <a href="#" data-toggle="modal"  data-target="#modal-asign-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span  class="badge badge-info">Asign To</span></a>
                                <a href="#" data-toggle="modal"  data-target="#modal-move-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span  class="badge badge-warning">Move To</span></a>
                                @else
                                {{ $ticket->assist_name }}<br>
                                    <span style="color: grey;font-size:12px">{{ $ticket->dept_assist }}</span>
                                @endif<br>
                                    <strong>SLA - </strong>
                                    @if($ticket->resolve_status==1)
                                    <a href="#" style="text-decoration:none;color:black" ><span style="background-color: green" class="badge badge-secondary">Achieved</span></a>
                                    @elseif($ticket->resolve_status==0 && $ticket->resolve_time!='')
                                    <a href="#" style="text-decoration:none;color:black" ><span style="background-color: red" class="badge badge-secondary">Not Achieved</span></a>
                                    @endif
                                    <br>
                                    <span style="color: grey;font-size:12px">{{ $ticket->sla_name }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top;width: 35%;" colspan="2"><a @if($ticket->assist_nrp==Auth::user()->email)onclick="getChat({{$ticket->id }})"  @endif style="text-decoration:none;color:black" ><i class="fa fa-comment fa-lg custom--1"></i> Chat User</a> | 
                            <a onclick="getHistory({{$ticket->id }});"   style="text-decoration:none;color:black" data-toggle="modal"  data-target="#modal-history-ticket-{{ $ticket->id }}"><i class="fa fa-clock-o fa-lg custom--1"></i>History Ticket</a>
                            | Last Modified  {{ $ticket->updated_at }}</td>
                            <td></td>
                            <td style="vertical-align:top;width: 50%;">
                            @if($ticket->flag==1)
                                <a href="#" data-toggle="modal"  data-target="#modal-proccess-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span style="background-color: green" class="badge badge-secondary">Proccess</span></a>
                                <a href="#" data-toggle="modal"  data-target="#modal-cancel-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span style="background-color: red" class="badge badge-secondary">Cancel</span></a>
                            @elseif($ticket->flag==2 || $ticket->flag==3 ||$ticket->flag==9)
                                <a href="#" data-toggle="modal"  data-target="#modal-escalate-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span style="background-color: #997314" class="badge badge-secondary">Escalate</span></a>
                                @if($ticket->flag!=3)
                                    <a href="#" data-toggle="modal"  data-target="#modal-resolve-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span style="background-color: #0d3da3" class="badge badge-secondary">Resolve</span></a>
                                @else
                                <a href="#" data-toggle="modal"  data-target="#modal-close-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span style="background-color: grey" class="badge badge-secondary">Close</span></a>
                                @endif
                            @elseif($ticket->flag!=5 && $ticket->flag!=6)
                                <a href="#" data-toggle="modal"  data-target="#modal-cancel-ticket-{{ $ticket->id }}" style="text-decoration:none;color:black" ><span style="background-color: red" class="badge badge-secondary">Cancel</span></a>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            <div class="progress">
                                <div class="progress-bar @if($ticket->resolve_status==0)progress-bar-danger @endif" role="progressbar" aria-valuenow="{{ $ticket->resolve_percent }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $ticket->resolve_percent }}%; @if($ticket->resolve_percent<=5)color:black;@endif">
                                @if($ticket->resolve_time!='')
                                <strong>{{ $ticket->resolve_percent }}%</strong>
                                @endif
                                </div>
                            </div>
                        <!-- <button type="button" class="btn btn btn-block btn-outline-primary"></button></td> -->
                        </tr>
                    </tbody>
                @endforeach
                </table>
                
            </div>
        </div>
    </div>

</div>

    
    <div id="modal-add-ticket" class="modal fade">
        <form method="post" action="{{url('insert-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Ticket</h2>
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
                            <label class="form-control-label">Subject: *</label>
                            <input type="text" name="subject"  maxlength="30"  required="" class="form-control" autocomplete="off">  
                        </div>
                        
                        <div class="form-group text-left">
                            <label class="form-control-label">To Departement: *</label>
                            <select style="width: 100%;"  name="dept" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option value="">Choose Departement</option>
                            @foreach($data['dept'] as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->as_nama }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Message: </label>
                            <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                        </div>
                         <div class="form-group text-left">
                            <label class="form-control-label">Capture: </label>
                        <input type="file" name="media" id="media" class="form-control" autocomplete="off" accept=".png, .jpg, .jpeg"> 
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
    <!-- Modal proccess ticket -->
    @foreach($data['ticket'] as $ticket)
    <div id="modal-history-ticket-{{ $ticket->id }}" class="modal fade">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2 class="text-center">History Ticket</h2>
                        <div class="tracking-list" id="historyTable">
                            <article>
                            
                            </article>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
                    </div>
                </div>
            </div>
    </div>
    <div id="modal-move-ticket-{{ $ticket->id }}" class="modal fade">
        <form method="post" action="{{url('move-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2 class="text-center">Move Ticket</h2>
                        <br>
                         <div class="form-group text-left">
                            <label class="form-control-label">Move To Departement: *</label>
                          <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            <select style="width: 100%;"  name="dept" class="form-control js-example-basic-single" data-live-search="true" required="">
                            <option value="">Choose Departement</option>
                            @foreach($data['dept'] as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="modal-asign-ticket-{{ $ticket->id }}" class="modal fade">
        <form method="post" action="{{url('asign-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Assign Ticket</h2>
                        <div class="form-group text-left">
                            <label class="form-control-label">Assign To: *</label>
                            <select name="assist" class="form-control">
                                @foreach($data['assist'] as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            <label class="form-control-label">Note: *</label>
                            <textarea name="note"  class="form-control" cols="30" rows="5"></textarea>
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
    <div id="modal-proccess-ticket-{{ $ticket->id }}" class="modal fade">
        <form method="post" action="{{url('proccess-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to Proccess Ticket?</p>
                        <div class="form-group text-left">
                            <label class="form-control-label">Sla: *</label>
                            <select name="sla" class="form-control">
                                @foreach($data['sla'] as $sla)
                                    <option value="{{ $sla->id }}">{{ $sla->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            <label class="form-control-label">Note: *</label>
                            <textarea name="note"  class="form-control" cols="30" rows="5"></textarea>
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
    <div id="modal-resolve-ticket-{{ $ticket->id }}" class="modal fade">
        <form method="post" action="{{url('resolve-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure the Ticket has Resolved?</p>
                        <div class="form-group text-left">
                            <label class="form-control-label">Reduce Inventory: </label>
                            <select style="width: 100%;"  name="inventory" onchange="getinventory(this)"  class="form-control js-example-basic-single" data-live-search="true">
                                <option value="">- Select Inventory -</option>
                                @foreach($data['inventory'] as $inventory)
                                    <option value="{{ $inventory->inventory_id }}" >{{ $inventory->inventory_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Qty: </label>
                            <input type="number" name="reduce"  id="reduce" class="form-control"  autocomplete="off">  
                        </div>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            <label class="form-control-label">Note: </label>
                            <textarea name="note"  class="form-control" cols="30" rows="5"></textarea>
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
    
    <div id="modal-escalate-ticket-{{ $ticket->id }}" class="modal fade">
        <form method="post" action="{{url('escalate-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure the to Escalate Ticket?</p>
                        <div class="form-group text-left">
                            <label class="form-control-label">Asign To: *</label>
                            <select name="assist" class="form-control">
                                @foreach($data['assist'] as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            <label class="form-control-label">Note: *</label>
                            <textarea name="note"  class="form-control" cols="30" rows="5"></textarea>
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
    <!-- Modal Close -->
    <div id="modal-close-ticket-{{ $ticket->id }}" class="modal fade">
        <form method="post" action="{{url('close-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to Close Ticket?</p>
                        <div class="form-group text-left">
                            <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            <label class="form-control-label">Note: *</label>
                            <textarea name="note"  class="form-control" cols="30" rows="5"></textarea>
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
    <!-- Modal Cancel -->
    <div id="modal-cancel-ticket-{{ $ticket->id }}" class="modal fade">
        <form method="post" action="{{url('cancel-ticket')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to Close Ticket?</p>
                        <div class="form-group text-left">
                            <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            <label class="form-control-label">Note: *</label>
                            <textarea name="note"  class="form-control" cols="30" rows="5"></textarea>
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
    @endforeach


@endsection

@section('myscript')

  
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            var status = $('#status').val();
            if(search == "" && status == ""){ //jika status saja
                window.location.href="ticket-management";
            }else if(search == null || search == ""){ //jika status saja
                window.location.href="ticket-management?status="+status;
            }else if(status == null || status == ""){ //jika search saja
                window.location.href="ticket-management?search="+search;
            }else {
                window.location.href="ticket-management?search="+search+"&status="+status;
            }
        });
    });
    
    function getHistory(val){
        APP_URL = '{{url('/')}}' ;
       $.ajax({
                    url: APP_URL+'/ticket-history/' + val,
         type: 'get',
         dataType: 'json',
         success: function(response){

           var len = 0;
           $('#historyTable article').empty(); // Empty <article>
           if(response['data'] != null){
              len = response['data'].length;
           }

           if(len > 0){
              for(var i=0; i<len; i++){
                var tr_str ="<div class='tracking-item'>"+
                                "<div class='tracking-icon status-intransit'>"+
                                "<i class='fa fa-spinner'></i>"+
                                "</div>"+
                                "<div class='tracking-date'>"+response['data'][i]['timestamp']+"</span></div>"+
                                "<div class='tracking-content'>"+response['data'][i]['title']+"<span>"+response['data'][i]['description']+"</span></div>"+
                            "</div>"
                 

                 $("#historyTable article").append(tr_str);
              }
           }else{
              var tr_str = "<div class='panel-heading icon'>" +
                                "<i class='fa fa-spinner'></i>" +
                            "</div>"+
                            "<div class='panel-body'>Not Found</div>";

              $("#historyTable article").append(tr_str);
           }

         }
       });
     }
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
                // document.getElementById("dept").value = response['data'].department;
                // document.getElementById("val_pn_patria").value = response['data'].pn_patria;
                // document.getElementById("val_pn_vendor").value = response['data'].pn_vendor;
            }
        });
    }
    
    function getinventory(sel) {
        var id = sel.value;
       
        APP_URL = '{{url('/')}}' ;
        $.ajax({
            url: APP_URL+'/get-inventory/' + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                console.log(response['data']);
                $("input[name=reduce]").val(response['data'].inventory_qty);
                $("input[name=reduce]").attr({
                    "max" : response['data'].inventory_qty,
                    "min" : 1
                });
            }
        });
    }
    </script>
    
@endsection
