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
            <h1>incentive Management</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" type="search" value="" autocomplete="off" placeholder="Search" class="form-control">
                    </div>
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div>
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>InvNo</th>
                            <th>Sales</th>
                            <th>InvDate</th>
                            <th>CashDate</th>
                            <th>CustName </th>
                            <th>CustProfile </th>
                            <th>Product </th>
                            <th>Segmen </th>
                            <th>Berat</th>
                            <th>Price</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['incentive'] as $incentive)
                        <tr>
                            <td>{{ $incentive->no }}</td>
                            <td>{{ $incentive->inv }}</td>
                            <td>{{ $incentive->sales }}</td>
                            <td>{{ $incentive->inv_date }}</td>
                            <td>{{ $incentive->cash_date }}</td>
                            <td>{{ $incentive->customer }}</td>
                            <td>{{ $incentive->cust_profile }}</td>
                            <td>{{ $incentive->product }}</td>
                            <td>{{ $incentive->segment }}</td>
                            <td>{{ $incentive->qty }}</td>
                            <td>{{ $incentive->tot_price }}</td>
                            
                           
                            <td class="text-center">
                            @if($data['actionmenu']->u==1)
                            <a href="#" data-toggle="modal" data-target="#modal-edit-incentive-{{ $incentive->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
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

    <!-- Modal Edit incentive -->
    <div id="modal-add-incentive" class="modal fade">
        <form method="post" action="{{url('upload-incentive')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add incentive</h2>
                        <br>
                        <!-- <div class="form-group text-left">
                            <label class="form-control-label">Nama File: *</label>
                            <input type="text" name="id" class="form-control" autocomplete="off" value="" required="">
                        </div> -->
                        <div class="form-group text-left">
                            <label class="form-control-label">File: *</label>
                            <input type="file" name="file" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        
                    </div>
                    <input type="hidden" name="id" value=""/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal Edit incentive -->
    @foreach($data['incentive'] as $incentive)
    <div id="modal-edit-incentive-{{ $incentive->id }}" class="modal fade">
        <form method="post" action="{{url('update-incentive')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit incentive</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Inc: *</label>
                            <input type="text" name="id" class="form-control" autocomplete="off" value="{{ $incentive->id }}" required="" readonly>
                            <input type="hidden" name="cust_profile" class="form-control" autocomplete="off" value="{{ $incentive->cust_profile }}" required="" readonly>
                            <input type="hidden" name="segment" class="form-control" autocomplete="off" value="{{ $incentive->segment }}" required="" readonly>
                        </div>
                        <div class="form-group text-left">
                            <!-- <label class="form-control-label">Inv Date: *</label> -->
                            <input type="hidden" name="inv_date" class="form-control" autocomplete="off" value="{{ $incentive->inv_date }}" required="" >
                            <input type="hidden" name="cash_date_old" class="form-control" autocomplete="off" value="{{ $incentive->cash_date }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Cash Date: *</label>
                            <input type="date" name="cash_date" class="form-control" autocomplete="off" value="{{ $incentive->cash_date }}" required="" >
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
    <div id="modal-delete-incentive-{{ $incentive->id }}" class="modal fade">
        <form method="post" action="{{url('delete-incentive')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $incentive->id }}"/>
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
                window.location.href="incentive-management";
            } else {
                window.location.href="incentive-management?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     false,
        } );
        <?php if($data['actionmenu']->c==1){ ?>

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-incentive" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
