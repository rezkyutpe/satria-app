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
            <h1>Sales Incentive Review </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

        <div class="table-responsive custom--2">
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>: </th>
                            <th>{{ $data['request']['id_req']}} </th>
                            <th>Claim Month</th>
                            <th>: </th>
                            <th>{{ date('Y-m',strtotime($data['request']['req_month'])) }}  </th>
                        </tr>
                        <tr>
                            <th>NRP</th>
                            <th>: </th>
                            <th>{{ $data['request']['sales']}}  </th>
                            <th>Status </th>
                            <th>: </th>
                            <th>@if($data['request']['status']=='0')
                                {{ 'New'}}
                                @elseif($data['request']['status']=='1')
                                {{ 'Partial Approved'}}
                                @elseif($data['request']['status']=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif  </th>
                        </tr>
                        <tr>
                            <th>Sales Name</th>
                            <th>: </th>
                            <th>{{ $data['request']['sales_name']}}  </th>
                            
                            <th></th>
                            <th> </th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                <br>
                <hr>
                <div class="page-head">
                    <div class="page-title">
                        <h1>List Incentive </h1>
                    </div>
                    <div class="page-title pull-right">
                        @if($data['request']['status']=='0')
                            @if($data['actionmenu']->c==1)
                            @else
	                            <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-accept-incentive" href="#">Accept</a>
	                        @endif
                        @elseif($data['request']['status']=='1')
                            @if($data['actionmenu']->c==1)
                            <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-approve-incentive" href="#">Approve</a>
                        	@endif
                        
                        @else

                        @endif
                    </div>
                </div>
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <!-- <th>Sales</th> -->
                            <th>InvNo</th>
                            <th>InvDate</th>
                            <th>CashDate</th>
                            <!-- <th>CustName </th>
                            <th>CustProfile </th>
                            <th>Product </th>
                            <th>Segmen </th> -->
                            <th>Berat</th>
                            <!-- <th class="text-center">Cost</th> -->
                            <th class="text-center">Cash In</th>
                            <th>GRADING</th>
                            <th>AGING</th>
                            <th>GPM</th>
                            <th>TARGET</th>
                            <th>INC_EF</th>
                            <th>TOTAL</th>
                            <th>New Cust</th>
                            <th>Incentive</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total=0)
                        @php($total_inc=0)
                        @foreach($data['incentive'] as $incentive)
                        @php($total_factor = Helper::Total($incentive->grading,$incentive->aging,$incentive->gpm,$incentive->target,$incentive->inc_ef))
                        @php($inc = $incentive->cash_in*($total_factor/100)*($incentive->cust_type/100))
                        <tr>
                            <td>{{ $incentive->no }}</td>
                            <!-- <td>{{ $incentive->sales_name }}</td> -->
                            <td>{{ $incentive->inv }}</td>
                            <td>{{ $incentive->inv_date }}</td>
                            <td>{{ $incentive->cash_date }}</td>
                            <!-- <td>{{ $incentive->customer }}</td>
                            <td>{{ $incentive->cust_profile }}</td>
                            <td>{{ $incentive->product }}</td>
                            <td>{{ $incentive->segment }}</td> -->
                            <td>{{ $incentive->qty }}</td>
                            <!-- <td class="text-right">{{ number_format($incentive->tot_cost,0,',','.') }}</td> -->
                            <td class="text-right">{{ number_format($incentive->cash_in,0,',','.') }}</td>
                            <td>{{ $incentive->grading }}%</td>
                            <td>{{ $incentive->aging }}%</td>
                            <td>{{ $incentive->gpm }}%</td>
                            <td>{{ $incentive->target }}%</td>
                            <td>{{ $incentive->inc_ef }}%</td>
                            <td>{{ $total_factor }}%</td>
                            <td>{{ $incentive->cust_type }}%</td>
                            <td class="text-right">{{ number_format($inc,0,',','.') }}</td>
                            
                           
                            <td class="text-right">
                            @if($data['actionmenu']->u==1)
                                @if($data['request']['status']=='0')
                                    <a href="#" data-toggle="modal" data-target="#modal-edit-incentive-{{ $incentive->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                            @endif

                            </td>
                        </tr>
                        
                        @php ($total = $total+$incentive->tot_price)
                        @php ($total_inc = $total_inc+$inc)
                        @endforeach
                        

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"></td>
                            <td>{{  number_format($total,0,',','.') }}</td>
                            <td colspan="6"></td>
                            <td>{{  number_format($total_inc,0,',','.')  }}</td>
                        </tr>
                        </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<div id="modal-accept-incentive" class="modal fade">
        <form method="post" action="{{url('accept-request')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to Accept incentive  ?</p>
                        <input type="hidden" name="id" value="{{ $data['request']['id_req'] }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                       
                        <button type="submit" class="btn btn-success">Yes</button>
                       
                    </div>
                </div>
            </div>
        </form>
    </div>
    
<div id="modal-approve-incentive" class="modal fade">
        <form method="post" action="{{url('approve-request')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to Approve incentive  ?</p>
                        <input type="hidden" name="id" value="{{ $data['request']['id_req'] }}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                       
                        <button type="submit" class="btn btn-success">Yes</button>
                    
                    </div>
                </div>
            </div>
        </form>
    </div>
    @foreach($data['incentive'] as $incentive)
    <div id="modal-edit-incentive-{{ $incentive->id }}" class="modal fade">
        <form method="post" action="{{url('adjustment-incentive')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit incentive</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Inc: *</label>
                            <input type="text" name="id" class="form-control" autocomplete="off" value="{{ $incentive->id }}" required="" readonly>
                              </div>
                        <div class="form-group text-left">
                            <!-- <label class="form-control-label">Inv Date: *</label> -->
                            <input type="hidden" name="inc_efold" class="form-control" autocomplete="off" value="{{ $incentive->inc_ef }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Inc E Faktor: *</label>
                            <select name="inc_ef" class="form-control" >
                                @for($i=10;$i>=0;$i--)
                                <option value="{{ $i/10 }}">{{ $i/10 }}</option>
                                @endfor
                            </select>
                            <!-- <input type="number" name="inc_ef" class="form-control" autocomplete="off" value="{{ $incentive->inc_ef }}" required="" > -->
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

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            if (search == null || search == ""){
                window.location.href="sales-management";
            } else {
                window.location.href="sales-management?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     false,
            "searching":     false,
        } );
        
       
    });
    
    </script>
@endsection
