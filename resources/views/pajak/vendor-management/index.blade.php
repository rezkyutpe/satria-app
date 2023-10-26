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
            <h1>Vendor Management</h1>
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
                            <th>Kode Vendor</th>
                            <th>NPWP</th>
                            <th>Name</th>
                            <th>Alamat</th>
                            <th>PIC</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['vendor'] as $vendor)
                        <tr>
                            <td>{{ $vendor->no }}</td>
                            <td>{{ $vendor->kode_vendor }}</td>
                            <td>{{ $vendor->npwp }}</td>
                            <td>{{ $vendor->nama_vendor }}</td>
                            <td>{{ $vendor->alamat }}</td>
                            <td>{{ $vendor->pic }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-vendor-{{ $vendor->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-vendor-{{ $vendor->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit vendor -->
    <div id="modal-add-vendor" class="modal fade">
        <form method="post" action="{{url('insert-vendor')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Vendor</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Kode Vendor: *</label>
                            <input type="text" name="kode_vendor" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">NPWP: *</label>
                            <input type="text" name="npwp" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Nama Vendor: *</label>
                            <input type="text" name="nama_vendor" class="form-control" autocomplete="off" value="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Alamat: *</label>
                            <input type="text" name="alamat" class="form-control" autocomplete="off" value="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Kota: *</label>
                            <input type="text" name="kota" class="form-control" autocomplete="off" value="">
                        </div>
                         <div class="form-group text-left">
                            <label class="form-control-label">Pic: *</label>
                            <input type="text" name="pic" class="form-control" autocomplete="off" value="">
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
    <!-- Modal Edit vendor -->
    @foreach($data['vendor'] as $vendor)
    <div id="modal-edit-vendor-{{ $vendor->id }}" class="modal fade">
        <form method="post" action="{{url('update-vendor')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Vendor</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Kode Vendor: *</label>
                            <input type="hidden" name="id" class="form-control" value="{{ $vendor->id }}" required="" >
                            <input type="text" name="kode_vendor" class="form-control" autocomplete="off" value="{{ $vendor->kode_vendor }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">NPWP: *</label>
                            <input type="text" name="npwp" class="form-control" autocomplete="off" value="{{ $vendor->npwp }}" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Nama Vendor: *</label>
                            <input type="text" name="nama_vendor" class="form-control" autocomplete="off" value="{{ $vendor->nama_vendor }}" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Alamat: *</label>
                            <input type="text" name="alamat" class="form-control" autocomplete="off" value="{{ $vendor->alamat }}">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Kota: *</label>
                            <input type="text" name="kota" class="form-control" autocomplete="off" value="{{ $vendor->kota }}" required="">
                        </div>
                         <div class="form-group text-left">
                            <label class="form-control-label">Pic: *</label>
                            <input type="text" name="pic" class="form-control" autocomplete="off" value="{{ $vendor->pic }}" required="">
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
    <div id="modal-delete-vendor-{{ $vendor->id }}" class="modal fade">
        <form method="post" action="{{url('delete-vendor')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $vendor->id }}"/>
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
                window.location.href="vendor-management";
            } else {
                window.location.href="vendor-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-vendor" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
