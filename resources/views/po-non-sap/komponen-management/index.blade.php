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
            <h1>Komponen Management</h1>
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
                            <th>PN Patria</th>
                            <th class="text-center">Description</th>
                            <th>PN Vendor</th>
                            <th>Price</th>
                            <th>Uom</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['komponen'] as $komponen)
                        <tr>
                            <td>{{ $komponen->no }}</td>
                            <td>{{ $komponen->pn_patria }}</td>
                            <td class="text-center">{{ $komponen->description }}</td>
                            <td>{{ $komponen->pn_vendor }}</td>
                            <td>{{ $komponen->price }}</td>
                            <td>{{ $komponen->uom }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-komponen-{{ $komponen->pn_patria }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-komponen-{{ $komponen->pn_patria }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit komponen -->
    <div id="modal-add-komponen" class="modal fade">
        <form method="post" action="{{url('insert-komponen')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add komponen</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">PN Patria: *</label>
                            <input type="text" name="pn_patria" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Description: *</label>
                            <input type="text" name="desc" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">PN Vendor: *</label>
                            <input type="text" name="pn_vendor" class="form-control" autocomplete="off"  >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Uom: *</label>
                            <input type="text" name="uom" class="form-control" autocomplete="off" >
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
    <!-- Modal Edit komponen -->
    @foreach($data['komponen'] as $komponen)
    <div id="modal-edit-komponen-{{ $komponen->pn_patria }}" class="modal fade">
        <form method="post" action="{{url('update-komponen')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit komponen</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">PN Patria: *</label>
                            <input type="hidden" name="pn_patria" class="form-control" value="{{ $komponen->pn_patria }}" required="" >
                            <input type="text" name="pn_patrian" class="form-control" autocomplete="off" value="{{ $komponen->pn_patria }}" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Description: *</label>
                            <input type="text" name="desc" class="form-control" autocomplete="off" value="{{ $komponen->description }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">PN Vendor: *</label>
                            <input type="text" name="pn_vendor" class="form-control" autocomplete="off" value="{{ $komponen->pn_vendor }}" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Uom: *</label>
                            <input type="text" name="uom" class="form-control" autocomplete="off"  value="{{ $komponen->uom }}" >
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
    <div id="modal-delete-komponen-{{ $komponen->pn_patria }}" class="modal fade">
        <form method="post" action="{{url('delete-komponen')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="pn_patria" value="{{ $komponen->pn_patria }}"/>
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
                window.location.href="komponen-management";
            } else {
                window.location.href="komponen-management?search="+search;
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
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-komponen" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
