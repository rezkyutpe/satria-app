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
            <h1>Jenis Hose Management</h1>
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
                            <th>Jhose</th>
                            <th>Diameter</th>
                            <th>MWP</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['mwp'] as $mwp)
                        <tr>
                            <td>{{ $mwp->no }}</td>
                            <td>{{ $mwp->nama_hose }}</td>
                            <td>{{ $mwp->diameter }}</td>
                            <td>{{ $mwp->mwp }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-mwp-{{ $mwp->id_mwp }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-mwp-{{ $mwp->id_mwp }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit mwp -->
    <div id="modal-add-mwp" class="modal fade">
        <form method="post" action="{{url('insert-mwp')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Mwp</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Jenis Hose: *</label>
                            <select name="jhose" class="form-control">
                                @foreach($data['jhose'] as $jhose)
                                <option value="{{ $jhose->id_jhose }}">{{ $jhose->nama_hose }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Diameter: *</label>
                            <select name="diameter" class="form-control">
                                @foreach($data['diameter'] as $diameter)
                                <option value="{{ $diameter->id_diameter }}">{{ $diameter->ukuran_diameter }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">MWP: *</label>
                            <input type="text" name="mwp" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">MBP: *</label>
                            <input type="text" name="mbp" class="form-control" autocomplete="off" value="" required="">
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
    <!-- Modal Edit mwp -->
    @foreach($data['mwp'] as $mwp)
    <div id="modal-edit-mwp-{{ $mwp->id_mwp }}" class="modal fade">
        <form method="post" action="{{url('update-mwp')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit mwp</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Jenis Hose: *</label>
                            <select name="jhose" class="form-control">
                                @foreach($data['jhose'] as $jhose)
                                    @if($mwp->jhose==$jhose->id_jhose)
                                        <option selected value="{{ $jhose->id_jhose }}">{{ $jhose->nama_hose }}</option>
                                    @else
                                        <option  value="{{ $jhose->id_jhose }}">{{ $jhose->nama_hose }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Diameter: *</label>
                            <select name="diameter" class="form-control">
                                @foreach($data['diameter'] as $diameter)
                                    @if($mwp->diameter==$diameter->id_diameter)
                                        <option selected value="{{ $diameter->id_diameter }}">{{ $diameter->ukuran_diameter }}</option>
                                    @else
                                        <option value="{{ $diameter->id_diameter }}">{{ $diameter->ukuran_diameter }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">MWP: *</label>
                            <input type="hidden" name="id_mwp" class="form-control" value="{{ $mwp->id_mwp }}" required="" >
                            <input type="text" name="mwp" class="form-control" autocomplete="off" value="{{ $mwp->mwp }}" required="" >
                        </div>
                      
                        <div class="form-group text-left">
                            <label class="form-control-label">MBP: *</label>
                            <input type="text" name="mbp" class="form-control" autocomplete="off" value="{{ $mwp->mbp }}" required="">
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
    <div id="modal-delete-mwp-{{ $mwp->id_mwp }}" class="modal fade">
        <form method="post" action="{{url('delete-mwp')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id_mwp" value="{{ $mwp->id_mwp }}"/>
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
                window.location.href="mwp-management";
            } else {
                window.location.href="mwp-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-mwp" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
