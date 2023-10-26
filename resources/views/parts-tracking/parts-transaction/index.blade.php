@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
            <h1>Parts Transactions </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
                <div class="row custom-position-header">
                
                
                <form method="post" action="{{ url('parts-transaction-export') }}"  enctype="multipart/form-data">
                {{csrf_field()}}
                    <table class="table" style="width:100%" border="0">
                    <tr>
                        <td>
                        <label>Filter Date Range : </label></td>
                        <td style="width:30%">
                            <div class="demo__input input-group pull-right" id="demo1-input-parent">
                            <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="date" value="" class="form-control" autocomplete="off">
                        </div>
                        </td>
                        <td><label>SN Unit : </label></td>
                        <td><select name="sn" id="search-value" class="form-control selectpicker" data-live-search="true">
                            <option value="">Select SN Unit</option>
                                @foreach ($data['snunit'] as $row)
                                    <option value="{{ $row->id_unit }}">{{ $row->sn_unit }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td  style="width:25%">
                        <center>
                        <input type="submit" class="btn btn-success" value="Export">
                        <a href="{{ url('parts-transaction-export') }}" class="btn btn-danger">Export All</a>
                        </center>
                        </td>
                    </tr>
                    </table>
                </form>    
                </div>
                <br><br>
                <hr>
            <div class="table-responsive custom--2">
                
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lot ID</th>
                            <th>ID</th>
                            <th>Tanggal Transaksi</th>
                            <th>SN_Unit</th>
                            <th>Aplikasi</th>
                            <th>PN_Assy</th>
                            <th>Lokasi</th>
                            <th>Kondisi</th>
                            <th>Lifetime</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['parts'] as $parts)
                        <tr>
                            <td>{{ $parts->no }}</td>
                            @if ($parts->status == 1) 
                            <td>{{ $parts->id }} </td>
                            @else
                            <td> - </td>
                            @endif
                            <td>{{ $parts->id_transaksi }} </td>
                            <td>{{ date('Y-m-d', strtotime($parts['tgl_transaksi'])) }}</td>
                            <td>{{ $parts->sn_unit }} </td>
                            <td>{{ $parts->aplikasi }} </td>
                            <td>{{ $parts->pn_assy }} </td>
                            <td>{{ $parts->lokasi }} </td>
                            <td>{{ $parts->kondisi_transaksi }} </td>

                            @php($now  = new DateTime(date('Y-m-d')))
                            @php($life = new DateTime(date('Y-m-d', strtotime($parts['tgl_lifetime']))))
                            @php($sisa = $life->diff($now)->days)
                            @if($sisa <= 30) 
                                <td><strong><a class="btn btn-danger btn-circle btn-sm">{{ $life->diff($now)->days }} Days</a></strong></td>
                            @else
                            <td> - </td>
                            @endif
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                <a href="{{ url('parts-transaction-view/'.$parts->id_transaksi) }}" ><i class="fa fa-eye fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-transaction-{{ $parts->id_transaksi }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-transaction-{{ $parts->id_transaksi }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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


@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(function() {
  $('input[name="date"]').daterangepicker({
    opens: 'right',
    locale: {
            format: 'Y-MM-DD'
        }
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            if (search == null || search == ""){
                window.location.href="parts-transaction";
            } else {
                window.location.href="parts-transaction?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     true,
        } );
        <?php if($data['actionmenu']->c==1){ ?>
        $("div.toolbar").html('<a class=" btn btn-success" href="{{ url('parts-transaction-add') }}">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
