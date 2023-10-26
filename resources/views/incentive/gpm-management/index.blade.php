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
            <h1>GPM Management</h1>
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
                            <th>GPM</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Percentage</th>
                            <th>Category</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['gpm'] as $gpm)
                        <tr>
                            <td>{{ $gpm->no }}</td>
                            <td>{{ $gpm->gpm }}</td>
                            <td>{{ $gpm->min }}</td>
                            <td>{{ $gpm->max }}</td>
                            <td>{{ $gpm->percentage }}</td>
                            <td>{{ $gpm->cat }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-gpm-{{ $gpm->gpm }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-gpm-{{ $gpm->gpm }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit gpm -->
    <div id="modal-add-gpm" class="modal fade">
        <form method="post" action="{{url('insert-gpm')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add GPM</h2>
                        <br>
                         <div class="form-group text-left">
                            <label class="form-control-label">GPM Code: *</label>
                            <input type="text" name="gpm" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Min: *</label>
                            <input type="number" name="min" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Max: *</label>
                            <input type="number" name="max" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        
                        <div class="form-group text-left">
                            <label class="form-control-label">Percentage: *</label>
                            <input type="text" name="percentage" class="form-control" autocomplete="off" value="">
                        </div>
                      
                         <div class="form-group text-left">
                            <label class="form-control-label">Category: *</label>
                            <select name="cat" class="form-control">
                            	<option value="Value Added">Value Added</option>
                            	<option value="Whole Round">Whole Round</option>
                            </select>
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
    <!-- Modal Edit gpm -->
    @foreach($data['gpm'] as $gpm)
    <div id="modal-edit-gpm-{{ $gpm->gpm }}" class="modal fade">
        <form method="post" action="{{url('update-gpm')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit GPM</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Min: *</label>
                            <input type="text" name="gpm" class="form-control" value="{{ $gpm->gpm }}" readonly="" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Min: *</label>
                            <input type="number" name="min" class="form-control" autocomplete="off" value="{{ $gpm->min }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Max: *</label>
                            <input type="number" name="max" class="form-control" autocomplete="off" value="{{ $gpm->max }}" required="" >
                        </div>
                       
                        <div class="form-group text-left">
                            <label class="form-control-label">Percentage: *</label>
                            <input type="text" name="percentage" class="form-control" autocomplete="off" value="{{ $gpm->percentage }}" required="" >
                        </div>
                      
                         <div class="form-group text-left">
                            <label class="form-control-label">Category: *</label>
                            <select name="cat" class="form-control">
                            	@if($gpm->cat=='Value Added')
                            	<option value="Value Added" selected>Value Added</option>
                            	<option value="Whole Round">Whole Round</option>
                            	@else
                            	<option value="Value Added" >Value Added</option>
                            	<option value="Whole Round" selected>Whole Round</option>
                            	@endif
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
    <div id="modal-delete-gpm-{{ $gpm->gpm }}" class="modal fade">
        <form method="post" action="{{url('delete-gpm')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $gpm->gpm }}"/>
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
                window.location.href="gpm-management";
            } else {
                window.location.href="gpm-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-gpm" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
