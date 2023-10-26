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
            <h1>Grade Management</h1>
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
                            <th>Description</th>
                            <th>Percentage</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['grade'] as $grade)
                        <tr>
                            <td>{{ $grade->no }}</td>
                            <td>{{ $grade->description }}</td>
                            <td>{{ $grade->percentage }}</td>
                            <td>{{ $grade->month }}</td>
                            <td>{{ $grade->year }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-grade-{{ $grade->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-grade-{{ $grade->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit grade -->
    <div id="modal-add-grade" class="modal fade">
        <form method="post" action="{{url('insert-grade')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Grade</h2>
                        <br>
                         <div class="form-group text-left">
                            <label class="form-control-label">Description: *</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Percentage: *</label>
                            <input type="text" name="percentage" class="form-control" autocomplete="off" value="">
                        </div>
                         <div class="form-group text-left">
                            <label class="form-control-label">Month: *</label>
                            <input type="number" name="month" class="form-control" autocomplete="off"  required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Year: *</label>
                            <input type="number" name="year" class="form-control" autocomplete="off"  required="" >
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
    <!-- Modal Edit grade -->
    @foreach($data['grade'] as $grade)
    <div id="modal-edit-grade-{{ $grade->id }}" class="modal fade">
        <form method="post" action="{{url('update-grade')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Grade</h2>
                        <br>
                       
                        <div class="form-group text-left">
                            <label class="form-control-label">Description: *</label>
                            <input type="hidden" name="id" class="form-control" value="{{ $grade->id }}" required="" >
                            <textarea class="form-control" name="description">{{ $grade->description }}</textarea>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Percentage: *</label>
                            <input type="text" name="percentage" class="form-control" autocomplete="off" value="{{ $grade->percentage }}" required="" >
                        </div>
                      	 <div class="form-group text-left">
                            <label class="form-control-label">Month: *</label>
                            <input type="number" name="month" class="form-control" autocomplete="off" value="{{ $grade->month }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Year: *</label>
                            <input type="number" name="year" class="form-control" autocomplete="off" value="{{ $grade->year }}" required="" >
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
    <div id="modal-delete-grade-{{ $grade->id }}" class="modal fade">
        <form method="post" action="{{url('delete-grade')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $grade->id }}"/>
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
                window.location.href="grade-management";
            } else {
                window.location.href="grade-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-grade" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
