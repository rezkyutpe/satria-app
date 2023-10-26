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
            <h1>Manage Contract </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <div class="form-group">
                            <select class="form-control" name="status" id="status">
                            @if(!isset($_GET['status']))
                                <option value="">Filter Status</option>
                                <option value="last">Last Renew</option>
                                <option value="first" selected>First Renew</option>
                            @elseif($_GET['status'] == 'first')
                                <option value="last">Last Renew</option>
                                <option value="first" selected>First Renew</option>
                            @else
                                <option value="last" selected>Last Renew</option>
                                <option value="first">First Renew</option>
                            @endif
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-12 col-md-3 m-b-10px">
                        <div class="form-group">
                            <select class="form-control" name="cat" id="cat-value">
                            @if(!isset($_GET['cat']))
                                <option value="">Filter Category</option>
                                <option value="1">Contract</option>
                                <option value="0">License</option>
                            @elseif($_GET['cat'] == '0')
                                <option value="">No Filter</option>
                                <option value="1">Contract</option>
                                <option value="0" selected>License</option>
                            @else
                                <option value="">No Filter</option>
                                <option value="1" selected>Contract</option>
                                <option value="0">License</option>
                            @endif
                            </select>
                        </div>
                    </div>
                    
                   
                    
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Filter</button>
                    </div>
                </div>
                <br>
                <br>
            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>DueDate</th>
                            <th>Priority</th>
                            <!-- <th>Contract Num</th> -->
                            <th>Contract Desc</th>
                            <th>Company</th>
                            <th>Category</th>
                            <th>Deal Date</th>
                            <th>Renew DueDate</th>
                            <th colspan="2">Days Remaining</th>
                            <th>Start Alert</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['renew'] as $kontrak)
                        @if(isset($_GET['status']))
                            @if($_GET['status'] == 'first')
                                @php($date=date_create($kontrak['kontrak_date']))
                            @else 
                                @php($date=date_create($kontrak['detail_renew_date_renew']))
                            @endif
                        @else 
                            @php($date=date_create($kontrak['kontrak_date']))
                        @endif
                        @php($daysadd = $kontrak['durasi'])
                        @php($temp = $daysadd." days")
                        @php($dateecho = date_add($date,date_interval_create_from_date_string($temp)))

                        @php($now = new DateTime())
                        @php($date1=date_create(date_format($dateecho,"d-m-Y")))
                        @php($date2=date_create($now->format('Y-m-d')))
                        @php($diff=date_diff($date2,$date1))
                        @php($temp = $diff->format("%R%a")+0)
                        
                        <tr>
                            <td>{{ $kontrak->no }}</td>
                            <td>@if($temp < 0) 
                                <i style="color: red; font-size: 20px;" class="fa fa-flag" data-toggle="tooltip" title="" data-original-title="warning"  ><span style="display: none;">{{ $temp }}</span></i>
                               @else
                                    @if($temp > $kontrak['start_alert'])
                                    <i style="color: green; font-size: 20px;" class="fa fa-flag" data-toggle="tooltip" title="" data-original-title="info" ><span style="display: none;">{{ $temp }}</span></i>
                                    @elseif($temp <= $kontrak['start_alert'] )
                                    <i style="color: orange; font-size: 20px;" class="fa fa-flag" data-toggle="tooltip" title="" data-original-title="warning"><span style="display: none;">{{ $temp }}</span></i>
                                    @else
                                    <i style="color: orange; font-size: 20px;" class="fa fa-flag" data-toggle="tooltip" title="" data-original-title="warning"><span style="display: none;">{{ $temp }}</span></i>
                                    @endif
                                @endif
                            </td>
                            <td> @if($kontrak['kontrak_priority'] == 1)
                                <i style="color: green; font-size: 20px;" class="fa fa-warning" data-toggle="tooltip" title="" data-original-title="warning"  ><span style="display: none;">{{ $kontrak['kontrak_priority'] }}</span></i>
                                 @elseif( $kontrak['kontrak_priority'] ==2)
                                <i style="color: orange; font-size: 20px;" class="fa fa-warning" data-toggle="tooltip" title="" data-original-title="info" ><span style="display: none;">{{ $kontrak['kontrak_priority'] }}</span></i>
                                @elseif($kontrak['kontrak_priority'] ==3)
                                <i style="color: red; font-size: 20px;" class="fa fa-warning" data-toggle="tooltip" title="" data-original-title="warning"><span style="display: none;">{{ $kontrak['kontrak_priority'] }}</span></i>
                                @endif
                            </td>
                            <!-- <td>@if(isset($_GET['status']))
                                    @if($_GET['status'] == 'first')
                                        {{ $kontrak->kontrak_no_kontrak }}
                                    @else 
                                        {{ $kontrak->detail_renew_no_kontrak }} 
                                    @endif
                                @else 
                                    {{ $kontrak->kontrak_no_kontrak }}
                                @endif</td> -->
                            <td>{{ $kontrak->kontrak_desc }}</td>
                            <td>{{ $kontrak->kontrak_perusahaan }}</td>
                            <td>@if($kontrak->kontrak_category==1)
                                {{ "Contract" }}
                                @else
                                {{ "License" }}
                                @endif
                            </td>
                            <td>@if(isset($_GET['status']))
                                    @if($_GET['status'] == 'first')
                                        {{ date('Y-m-d',strtotime($kontrak->kontrak_date)) }}
                                    @else 
                                        {{ date('Y-m-d',strtotime($kontrak->detail_renew_date_renew)) }} 
                                    @endif
                                @else 
                                    {{ date('Y-m-d',strtotime($kontrak->kontrak_date)) }} 
                                @endif</td>
                            <td>{{ date_format($dateecho ,"Y-m-d") }}</td>
                            <td>
                                @if($temp > 0  )
                                <span style="font-size: 10px; font-color:grey">{{ $diff->format("%R%a") }}</span><br>
                                <span>{{ Helper::timeElapsedStringBefore($diff->format("%R%a days")) }}</span> 
                                @elseif($temp < 0 )
                                <span style="font-size: 10px; font-color:grey">{{ $diff->format("%R%a days") }}</span><br>
                                <span>{{ Helper::timeElapsedString($diff->format("%R%a days")) }}</span> 
                                @endif
                            </td>
                            <td></td>
                            <td>{{ $kontrak->start_alert }} Days Before</td>
                            <td class="text-right">
                                @if($data['actionmenu']->c==1)
                                <a href="#" data-toggle="modal" data-target="#modal-renew-contract-{{ $kontrak->kontrak_id }}"><i class="fa fa-plus fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->v==1)
                                <a href="#" onclick="getCounterpart('{{ $kontrak->kontrak_id }}')" data-toggle="modal" data-target="#modal-detail-kontrak-{{ $kontrak->kontrak_id }}"><i class="fa fa-eye fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-kontrak-{{ $kontrak->kontrak_id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

@foreach($data['renew'] as $kontrak)
                        @if(isset($_GET['status']))
                            @if($_GET['status'] == 'first')
                                @php($date=date_create($kontrak['kontrak_date']))
                            @else 
                                @php($date=date_create($kontrak['detail_renew_date_renew']))
                            @endif
                        @else 
                            @php($date=date_create($kontrak['kontrak_date']))
                        @endif
                        @php($daysadd = $kontrak['durasi'])
                        @php($temp = $daysadd." days")
                        @php($dateecho = date_add($date,date_interval_create_from_date_string($temp)))

                        @php($now = new DateTime())
                        @php($date1=date_create(date_format($dateecho,"d-m-Y")))
                        @php($date2=date_create($now->format('Y-m-d')))
                        @php($diff=date_diff($date2,$date1))
                        @php($temp = $diff->format("%R%a")+0)

    <div id="modal-renew-contract-{{ $kontrak->kontrak_id }}" class="modal fade">
        <form method="post" action="{{url('renew-contract')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Renew Contract</h2>
                        <br>
                         <table class="table" >
                            	<thead>
                            		<tr>    
                                        <th style="font-size: 10px;">Company</th>
                                        <th style="font-size: 10px;">:</th>
                                        <th style="font-size: 10px;">{{ $kontrak['kontrak_perusahaan']}}</th>
                                        <th style="font-size: 10px;">Contract Num</th>
                                        <th style="font-size: 10px;">:</th>
                                        <th style="font-size: 10px;">{{ $kontrak['kontrak_no_kontrak']}}</th>
                                    </tr>
                            		<tr>    
                                        <th style="font-size: 10px;">Deal Date</th>
                                        <th style="font-size: 10px;">:</th>
                                        <th style="font-size: 10px;">@if(isset($_GET['status']))
				                                @if($_GET['status'] == 'first')
				                                    {{ date('Y-m-d',strtotime($kontrak->kontrak_date)) }}
				                                @else 
				                                    {{ date('Y-m-d',strtotime($kontrak->detail_renew_date_renew)) }} 
				                                @endif
				                            @else 
				                                {{ date('Y-m-d',strtotime($kontrak->kontrak_date)) }} 
				                            @endif</th>
                                        <th style="font-size: 10px;">Renew Date</th>
                                        <th style="font-size: 10px;">:</th>
                                        <th style="font-size: 10px;">{{ date_format($dateecho ,"Y-m-d") }}</th>
                                    </tr>
                                    <tr>    
                                        <th style="font-size: 10px;">Contract Note</th>
                                        <th style="font-size: 10px;">:</th>
                                        <th style="font-size: 10px;">{{ $kontrak['kontrak_desc']}}</th>
                                        <th style="font-size: 10px;">Days Remaining</th>
                                        <th style="font-size: 10px;">:</th>
                                        <th style="font-size: 10px;">{{  Helper::timeElapsedStringBefore($diff->format("%R%a days")) }} | {{ $kontrak->start_alert }} Days Before</th>
                                    </tr>
                            	</thead>
                            </table>
                            <hr>
                        <div class="form-group text-left">
                          <input type="hidden" name="kontrak_no_kontrak" value="{{ $kontrak->kontrak_no_kontrak }}"/>
                          <input type="hidden" name="kontrak_id" value="{{ $kontrak->kontrak_id }}"/>
                            <label class="form-control-label">New Contract Number: *</label>
                            <input type="text" name="contract_renew_num" class="form-control" autocomplete="off"  required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Contract Renew Note : *</label>
                            <textarea name="contract_renew_note" class="form-control" cols="10" rows="10"></textarea>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Contract Duration : </label>
                            <select name="duration" class="form-control">
                                @foreach($data['cat'] as $cat)
                                    <option value="{{ $cat['id'] }}">{{ $cat['note'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Contract Deal Date : *</label>
                            <input type="date" name="contract_deal_date" class="form-control"  autocomplete="off" required="">
                        </div>
                        
                        <div class="form-group text-left">
                            <label class="form-control-label">Contract File : *</label>
                            <input type="file" name="file_contract" class="form-control"  autocomplete="off" required="">
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
    <div id="modal-detail-kontrak-{{ $kontrak->kontrak_id }}" class="modal fade">
        <form method="post" action="{{url('delete-contract')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Detail Contract</h2>
                        <br>
                                <table class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>: </th>
                                        <th>{{ $kontrak['kontrak_perusahaan']}} </th>
                                    </tr>
                                    <tr>
                                        <th>Contract/SN Num</th>
                                        <th>: </th>
                                        <th>{{ $kontrak['kontrak_no_kontrak']}}  </th>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <th>: </th>
                                        <th>@if($kontrak->kontrak_category=='1') {{ 'Contract' }} @else {{'License'}} @endif </th>
                                    </tr>
                                    <tr>
                                        <th>PIC</th>
                                        <th>: </th>
                                        <th>{{ $kontrak['kontrak_pic_name'].' | '.$kontrak['kontrak_pic_email']}}  </th>
                                    </tr>
                                    <tr>
                                        <th>Deal Date </th>
                                        <th>: </th>
                                        <th>@if(isset($_GET['status']))
				                                @if($_GET['status'] == 'first')
				                                    {{ date('Y-m-d',strtotime($kontrak->kontrak_date)) }}
				                                @else 
				                                    {{ date('Y-m-d',strtotime($kontrak->detail_renew_date_renew)) }} 
				                                @endif
				                            @else 
				                                {{ date('Y-m-d',strtotime($kontrak->kontrak_date)) }} 
				                            @endif </th>
                                    </tr>
                                    <tr>
                                        <th>Duration</th>
                                        <th>: </th>
                                        <th>{{ $kontrak['durasi']}} Days </th>
                                    </tr>
                                    <tr>    
                                        <th>Contract Note</th>
                                        <th>: </th>
                                        <th>{{ $kontrak->kontrak_desc }} </th>
                                    </tr>
                                    <tr>    
                                        <th>Contract File</th>
                                        <th>: </th>
                                        <th><a href="{{ asset('public/contract/'.$kontrak->kontrak_file) }}" target="_BLANK">{{ $kontrak->kontrak_file }}</a> </th>
                                    </tr>

                                </thead>

                            </table>
                            <table class="table">
                            	<thead>
                            		<tr>    
                                        <th>No</th>
                                        <th>Counterpart Company</th>
                                        <th>Counterpart Contact</th>
                                    </tr>
                            	</thead>
                            	<tbody>
                            	</tbody>
                            </table>
                    </div>
                    <input type="hidden" name="id" value="{{ $kontrak->kontrak_id }}"/>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button> -->
                        <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="modal-delete-kontrak-{{ $kontrak->kontrak_id }}" class="modal fade">
        <form method="post" action="{{url('delete-contract')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $kontrak->kontrak_id }}"/>
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
        $('#table').DataTable( {
"columnDefs": [
    { "orderable": false, "targets": 2 }
  ]
  });
    $(function () {
        $('#search-button').click(function(){
            var cat = $('#cat-value').val();
            var status = $('#status').val();
            if(cat == "" && status == ""){ //jika status saja
                window.location.href="manage-contract";
            }else if(cat == null || cat == ""){ //jika status saja
                window.location.href="manage-contract?status="+status;
            }else if(status == null || status == ""){ //jika cat saja
                window.location.href="manage-contract?cat="+cat;
            }else {
                window.location.href="manage-contract?cat="+cat+"&status="+status;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        <?php if($data['actionmenu']->c==1){ ?>
        $("div.toolbar").html('<a class="float-right btn btn-success" href="{{url('add-contract')}}">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    function getCounterpart(sel) {
        APP_URL = '{{url('/')}}' ;
        $('#tbody').empty(); 
        // var datas = $('#counterpart');
        $.ajax({
            url: APP_URL+'/get-counterpart/' + sel,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log(response); 
                console.log(response.length); 
                var total = response.length;
                for (var i = 0; i < total; i++) {
                    var no = i+1;
                    console.log(response[i]['counterpart_company']);
                    // var tr = `<tr><td class="text-left">`+response[i]['counterpart_company']+`</td><td class="text-left">`+response[i]['counterpart_contact']+`</td></tr>`;
                    var tr = '<tr>' +
                        '<td class='+'text-left'+'>' + no + '</td>'+
                        '<td class='+'text-left'+'>'+ response[i]['counterpart_company'] +'</td>' +
                        '<td class='+'text-left'+'>'+ response[i]['counterpart_contact'] +'</td>' +
                        '</tr>';
                    console.log(tr);
                    $('tbody').append(tr);
                }
            }
        });
    }
    </script>
@endsection
