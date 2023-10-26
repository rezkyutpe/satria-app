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
            <h1>Package Management</h1>
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
                            <th>Package</th>
                            <th>Total Kompenen</th>
                            <th class="text-center">Is Updated</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['package'] as $package)
                        <tr>
                            <td>{{ $package->no }}</td>
                            <td>{{ $package->package }}</td>
                            <td>{{ $package->total }}</td>
                            <td class="text-center">@if($package->as_updated==0)
                                    {{"Unupdated"}}
                                  @else
                                    {{"Updated"}}
                                  @endif</td>
                           
                            <td class="text-right">
                            @if($data['actionmenu']->v==1)

                                <a href="{{ url('view-package/'.$package->package) }}" ><i class="fa fa-pencil fa-lg custom--1"></i></a>                                
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

    <!-- Modal Edit package -->
    <div id="modal-add-package" class="modal fade">
        <form method="post" action="{{url('upload-package')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add package</h2>
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
    <!-- Modal Edit package -->
    @foreach($data['package'] as $package)
    <div id="modal-edit-package-{{ $package->id }}" class="modal fade">
        <form method="post" action="{{url('update-package')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit package</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">PN Patria: *</label>
                            <input type="hidden" name="id" class="form-control" value="{{ $package->id }}" required="" >
                            <input type="text" name="idn" class="form-control" autocomplete="off" value="{{ $package->id }}" required="" readonly>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Description: *</label>
                            <input type="text" name="desc" class="form-control" autocomplete="off" value="{{ $package->description }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">PN Vendor: *</label>
                            <input type="text" name="pn_vendor" class="form-control" autocomplete="off" value="{{ $package->pn_vendor }}" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Type: *</label>
                            <input type="text" name="type" class="form-control" autocomplete="off" value="{{ $package->type }}" required="" >
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
    <div id="modal-delete-package-{{ $package->id }}" class="modal fade">
        <form method="post" action="{{url('delete-package')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $package->id }}"/>
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
                window.location.href="package-management";
            } else {
                window.location.href="package-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-package" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
