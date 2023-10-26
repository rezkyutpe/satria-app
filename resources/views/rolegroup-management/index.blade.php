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
            <h1>Role Group Management</h1>
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
                            <th>Name</th>
                            <th>Apps</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['rolegroup'] as $rolegroup)
                        <tr>
                            <td>{{ $rolegroup->no }}</td>
                            <td>{{ $rolegroup->name }}</td>
                            <td>{{ $rolegroup->app_name }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                    <a href="{{ url('rolegroup-view/'.$rolegroup->id) }}" ><i class="fa fa-eye fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-rolegroup-{{ $rolegroup->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-rolegroup-{{ $rolegroup->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit rolegroup -->
    <div id="modal-add-rolegroup" class="modal fade">
        <form method="post" action="{{url('insert-rolegroup')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Role Group</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Name: *</label>
                            <input type="text" name="name" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Apps: *</label>
                            <select name="apps" class="form-control">
                            <option>Please choose Apps first</option>
                                @foreach($data['apps'] as $apps)
                                    <option value="{{ $apps->id }}">{{ $apps->app_name }}</option>
                                @endforeach
                            </select>
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
    <!-- Modal Edit rolegroup -->
    @foreach($data['rolegroup'] as $rolegroup)
    <div id="modal-edit-rolegroup-{{ $rolegroup->id }}" class="modal fade">
        <form method="post" action="{{url('update-rolegroup')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Role Group</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Name: *</label>
                            <input type="hidden" name="id" class="form-control" value="{{ $rolegroup->id }}" required="" >
                            <input type="text" name="name" class="form-control" autocomplete="off" value="{{ $rolegroup->name }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Apps: *</label>
                            <select name="apps" class="form-control">
                            <option>Please choose Apps first</option>
                                @foreach($data['apps'] as $apps)
                                    @if($apps->id==$rolegroup->apps)
                                        <option value="{{ $apps->id }}" selected>{{ $apps->app_name }}</option>
                                    @else
                                        <option value="{{ $apps->id }}">{{ $apps->app_name }}</option>
                                    @endif
                                @endforeach
                            </select>
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
    <div id="modal-delete-rolegroup-{{ $rolegroup->id }}" class="modal fade">
        <form method="post" action="{{url('delete-rolegroup')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $rolegroup->id }}"/>
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
                window.location.href="rolegroup-management";
            } else {
                window.location.href="rolegroup-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-rolegroup" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
