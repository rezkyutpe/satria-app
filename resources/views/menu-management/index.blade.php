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
            <h1>Apps Menu Management</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <!-- <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" type="search" value="" autocomplete="off" placeholder="Search" class="form-control">
                    </div>
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div> -->
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Link</th>
                            <th>Logo</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['appsmenu'] as $appsmenu)
                        <tr>
                            <td>{{ $appsmenu->no }}</td>
                            <td>{{ $appsmenu->menu }}</td>
                            <td>{{ $appsmenu->link }}</td>
                            <td>{{ $appsmenu->icon }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-appsmenu-{{ $appsmenu->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-appsmenu-{{ $appsmenu->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit appsmenu -->
    <div id="modal-add-appsmenu" class="modal fade">
        <form method="post" action="{{url('insert-menu')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Menu</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Top Main Menu: *</label>
                            <input type="text" name="topmain" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Main Menu: *</label>
                            <input type="text" name="main" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Menu Name: *</label>
                            <input type="text" name="menu" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Apps: *</label>
                            <select name="app" class="form-control">
                                @foreach($data['apps'] as $apps)
                                    <option value="{{ $apps->id }}">{{ $apps->app_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Link: *</label>
                            <input type="text" name="link" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Icon: *</label>
                            <input type="text" name="icon" class="form-control" autocomplete="off" value="">
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
    <!-- Modal Edit appsmenu -->
    @foreach($data['appsmenu'] as $appsmenu)
    <div id="modal-edit-appsmenu-{{ $appsmenu->id }}" class="modal fade">
        <form method="post" action="{{url('update-menu')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit appsmenu</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Top Main Menu: *</label>
                            <input type="text" name="topmain" class="form-control" value="{{ $appsmenu->topmain }}" autocomplete="off">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Main Menu: *</label>
                            <input type="text" name="main" class="form-control" value="{{ $appsmenu->main }}" autocomplete="off">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Menu Name: *</label>
                            <input type="hidden" name="id" class="form-control" value="{{ $appsmenu->id }}" required="" >
                            <input type="text" name="menu" class="form-control" autocomplete="off" value="{{ $appsmenu->menu }}" required="" >
                        </div>
                        <!-- <div class="form-group text-left">
                            <label class="form-control-label">App: *</label>
                            <input type="text" name="app" class="form-control" autocomplete="off" value="{{ $appsmenu->app }}" required="" >
                        </div> -->
                        <div class="form-group text-left">
                            <label class="form-control-label">Link: *</label>
                            <input type="text" name="link" class="form-control" autocomplete="off" value="{{ $appsmenu->link }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Icon: *</label>
                            <input type="text" name="icon" class="form-control" autocomplete="off" value="{{ $appsmenu->icon }}" required="" >
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
    <div id="modal-delete-appsmenu-{{ $appsmenu->id }}" class="modal fade">
        <form method="post" action="{{url('delete-menu')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $appsmenu->id }}"/>
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
                window.location.href="menu-management";
            } else {
                window.location.href="menu-management?search="+search;
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
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-appsmenu" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
