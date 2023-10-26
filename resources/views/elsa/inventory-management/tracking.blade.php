@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<style>

h3 {
    font-family: 'Josefin Sans', sans-serif
}

.box {
    padding: 0px 0px
}

.box-part {
    /* background: #F0FFFF; */
    padding: 40px 10px;
    margin: 30px 0px;
    /* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); */
    margin-bottom: 25px
}

.box-part:hover {
    /* background: #000000 */
}

.box-part:hover .fa,
.box-part:hover .title,
.box-part:hover .text,
.box-part:hover a {
    color: #FFF;
    -webkit-transition: all 1s ease-out;
    -moz-transition: all 1s ease-out;
    -o-transition: all 1s ease-out;
    transition: all 1s ease-out
}

.text {
    margin: 20px 0px
}

.fa {
    color: #00BFFF
}
</style>
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
            <h1>Inventory Tracking </h1>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-12">
                <div class="box">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="box-part bg-success text-center"> <i class="fa fa-archive fa-3x" aria-hidden="true"></i>
                                    <div class="title">
                                        <h3>{{ $data['inventory']->inventory_qty }} {{ $data['inventory']->inventory_satuan }}</h3>
                                    </div>
                                    <div class="text"> <span>Mouse Available</span> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="box-part bg-info text-center"> <i class="fa fa-cubes fa-3x" aria-hidden="true"></i>
                                    <div class="title">
                                        <h3>{{ $data['qtymonth'] }}</h3>
                                    </div>
                                    <div class="text"> <span>Inventory Out This Mounth</span> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="box-part bg-warning text-center"> <i class="fa fa-money fa-3x" aria-hidden="true"></i>
                                    <div class="title">
                                        <h3>{{ 'Rp '.number_format($data['lastprice']->price,0,',','.') }}</h3>
                                    </div>
                                    <div class="text"> <span>Last Purchase Price</span> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="box-part bg-default text-center"> <i class="fa fa-flag fa-3x" aria-hidden="true"></i>
                                    <div class="title">
                                        @if($data['inventory']->inventory_qty<=$data['inventory']->inventory_qty_min)
                                            @if($data['inventory']->inventory_qty<=0)
                                            <h3>Empty</h3>
                                            @else                                    
                                            <h3>Need Restock</h3>
                                            @endif
                                        @else
                                            <h3>Enough Stock</h3>
                                        @endif
                                    </div>
                                    <div class="text"> <span>Inventory Stock Status </span> </div>
                                </div>
                            </div>
                        </div>
                </div>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <td class="text-left">Name</td>
                        <td class="text-left">: {{ $data['inventory']->inventory_nama }} </td>
                        <td></td>
                        <td class="text-left">Stock</td>
                        <td class="text-left">: {{ $data['inventory']->inventory_qty }} {{ $data['inventory']->inventory_satuan }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Brand/Category</td>
                        <td class="text-left">: {{ $data['inventory']->brand_name }}/{{ $data['inventory']->cat_name }}</td>
                        <td></td>
                        <td class="text-left">Departement</td>
                        <td class="text-left">: {{ $data['inventory']->dept_name }}</td>
                    </tr>
                    <tr></tr>
                </table>
                <hr>
                <br>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Income</a></li>
                    <li><a data-toggle="tab" href="#menu1">Purchase Request</a></li>
                    <li><a data-toggle="tab" href="#menu2">Problem</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <br>
                        <div class="table-responsive custom--2">
                            <table id="sorting-table" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Income Qty</th>
                                        <th>Price</th>
                                        <th>Vendor</th>
                                        <th>Note</th>
                                        <th>At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no=0)
                                    @foreach($data['income'] as $income)
                                    @php($no=$no+1)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $income->qty }}</td>
                                        <td>{{ 'Rp '.number_format($income->price,0,',','.') }}</td>
                                        <td>{{ $income->vendor_id }}</td>
                                        <td>{{ $income->note }}</td>
                                        <td>{{ $income->created_at }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                    <br>
                        <div class="table-responsive custom--2">
                            <table id="sorting-table1" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Income Qty</th>
                                        <th>Price</th>
                                        <th>Vendor</th>
                                        <th>Note</th>
                                        <th>At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no=0)
                                    @foreach($data['income'] as $income)
                                    @php($no=$no+1)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $income->qty }}</td>
                                        <td>{{ $income->price }}</td>
                                        <td>{{ $income->vendor_id }}</td>
                                        <td>{{ $income->note }}</td>
                                        <td>{{ $income->created_at }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                    <br>
                        <div class="table-responsive custom--2">
                            <table id="sorting-table2" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Income Qty</th>
                                        <th>Price</th>
                                        <th>Vendor</th>
                                        <th>Note</th>
                                        <th>At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no=0)
                                    @foreach($data['income'] as $income)
                                    @php($no=$no+1)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $income->qty }}</td>
                                        <td>{{ $income->price }}</td>
                                        <td>{{ $income->vendor_id }}</td>
                                        <td>{{ $income->note }}</td>
                                        <td>{{ $income->created_at }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                
        </div>
    </div>

</div>


@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        $('#sorting-table1').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        $('#sorting-table2').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
    });
    </script>
@endsection
