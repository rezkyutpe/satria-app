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
            <h1>Sales Incentive Management {{round(2397984475/2240000000*100,0) }} </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <div class="row custom-position-header">
                    <div class="col-xl-12 col-md-3 m-b-10px">
                        <div class="form-group">
                            <input id="search-value" type="month" @if(isset($_GET['search'])) value="{{ $_GET['search'] }}" @endif name="date" class="form-control date" min="" max="" required/>
                        </div>
                    </div>
                   
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                        @if(isset($_GET['search']))
                        <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-submit-incentive" href="#">Submit</a>
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
                            <th>Cost</th>
                            <th>Cash In</th>
                            <th>Penjualan</th>
                            <th>GRADING</th>
                            <th>AGING</th>
                            <th>GPM</th>
                            <th>TARGET</th>
                            <th>INC_EF</th>
                            <th>TOTAL</th>
                            <th>New Cust</th>
                            <th>Incentive</th>
                            <!-- <th class="text-right">Action</th> -->
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
                            <td class="text-right">{{ number_format($incentive->tot_cost,0,',','.') }}</td>
                            <td class="text-right">{{ number_format($incentive->cash_in,0,',','.') }}</td>
                            <td class="text-right">{{ number_format($incentive->tot_price,0,',','.') }}</td>
                            <td>{{ $incentive->grading }}%</td>
                            <td>{{ $incentive->aging }}%</td>
                            <td>{{ $incentive->gpm }}%</td>
                            <td>{{ $incentive->target }}%</td>
                            <td>{{ $incentive->inc_ef }}%</td>
                            <td>{{ $total_factor }}%</td>
                            <td>{{ $incentive->cust_type }}%</td>
                            <td class="text-right">{{ number_format($inc,0,',','.') }}</td>
                            
                        
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

<div id="modal-submit-incentive" class="modal fade">
        <form method="post" action="{{url('submit-incentive')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure to submit incentive  @if(isset($_GET['search'])){{ $_GET['search'] }} @endif?</p>
                        <span style="color: #f00;" >@if($total==0) No Data Found, Cannot Submit @endif</span>
                    </div>
                    <input type="hidden" name="date" @if(isset($_GET['search'])) value="{{ $_GET['search'] }}" @endif required/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        @if($total!=0)
                        <button type="submit" class="btn btn-success">Yes</button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

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
        <?php if($data['actionmenu']->c==1){ ?>

        
            $("div.toolbar").html('<br>');
            <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    
    </script>
@endsection
