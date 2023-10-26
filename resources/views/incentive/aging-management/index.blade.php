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
            <h1>Aging Management</h1>
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
                            <th>Min</th>
                            <th>Max</th>
                            <th>Description</th>
                            <th>Percentage</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['aging'] as $aging)
                        <tr>
                            <td>{{ $aging->no }}</td>
                            <td>{{ $aging->min }}</td>
                            <td>{{ $aging->max }}</td>
                            <td>{{ $aging->descrip }}</td>
                            <td>{{ $aging->percentage }}</td>
                            <td>{{ $aging->type }}</td>
                            <td>{{ $aging->cat }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-aging-{{ $aging->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-aging-{{ $aging->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit aging -->
    <div id="modal-add-aging" class="modal fade">
        <form method="post" action="{{url('insert-aging')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Aging</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Min: *</label>
                            <input type="number" name="min" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Max: *</label>
                            <input type="number" name="max" class="form-control" autocomplete="off" value="" required="">
                        </div>
                         <div class="form-group text-left">
                            <label class="form-control-label">Description: *</label>
                            <textarea class="form-control" name="descrip"></textarea>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Percentage: *</label>
                            <input type="text" name="percentage" class="form-control" autocomplete="off" value="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Type: *</label>
                            <select name="type" class="form-control">
                            	<option value="Canning">Canning</option>
                            	<option value="Retail">Retail</option>
                            	<option value="PINDANGAN">PINDANGAN</option>
                            	<option value="Export">Export</option>
                            </select>
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
    <!-- Modal Edit aging -->
    @foreach($data['aging'] as $aging)
    <div id="modal-edit-aging-{{ $aging->id }}" class="modal fade">
        <form method="post" action="{{url('update-aging')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Aging</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Min: *</label>
                            <input type="hidden" name="id" class="form-control" value="{{ $aging->id }}" required="" >
                            <input type="number" name="min" class="form-control" autocomplete="off" value="{{ $aging->min }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Max: *</label>
                            <input type="number" name="max" class="form-control" autocomplete="off" value="{{ $aging->max }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Description: *</label>
                            <textarea class="form-control" name="descrip">{{ $aging->descrip }}</textarea>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Percentage: *</label>
                            <input type="text" name="percentage" class="form-control" autocomplete="off" value="{{ $aging->percentage }}" required="" >
                        </div>
                      	<div class="form-group text-left">
                            <label class="form-control-label">Type: *</label>
                            <select name="type" class="form-control">
                            	@if($aging->type=='Canning')
                            	<option value="Canning" selected>Canning</option>
                            	<option value="Retail">Retail</option>
                            	<option value="PINDANGAN">PINDANGAN</option>
                            	<option value="Export">Export</option>
                            	@elseif($aging->type=='Retail')
                            	<option value="Canning" >Canning</option>
                            	<option value="Retail" selected>Retail</option>
                            	<option value="PINDANGAN">PINDANGAN</option>
                            	<option value="Export">Export</option>
                            	@elseif($aging->type=='PINDANGAN')
                            	<option value="Canning" >Canning</option>
                            	<option value="Retail" >Retail</option>
                            	<option value="PINDANGAN" selected>PINDANGAN</option>
                            	<option value="Export">Export</option>
                            	@else
                            	<option value="Canning" >Canning</option>
                            	<option value="Retail" >Retail</option>
                            	<option value="PINDANGAN" >PINDANGAN</option>
                            	<option value="Export" selected>Export</option>
                            	@endif
                            </select>
                        </div>
                         <div class="form-group text-left">
                            <label class="form-control-label">Category: *</label>
                            <select name="cat" class="form-control">
                            	@if($aging->cat=='Value Added')
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
    <div id="modal-delete-aging-{{ $aging->id }}" class="modal fade">
        <form method="post" action="{{url('delete-aging')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $aging->id }}"/>
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
                window.location.href="aging-management";
            } else {
                window.location.href="aging-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-aging" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
