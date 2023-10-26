@extends('panel.master')

@section('css')

<get_link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

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
            <h1>Approval Apps Management</h1>
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
                            <th>Get Link Apps</th>
                            <th>Ket</th>
                            <th>GET</th>
                            <th>POST</th>
                            <th>To</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['approval'] as $approval)
                        <tr>
                            <td>{{ $approval->no }}</td>
                            <td>{{ $approval->approval_name }}</td>
                            <td>{{ $approval->get_link_apps }}</td>
                            <td>{{ $approval->ket }}</td>
                            <td>{{ $approval->get_link }}</td>
                            <td>{{ $approval->post_link }}</td>
                            <td>{{ $approval->name }}</td>
                            <td>{{ $approval->approval_level }}</td>
                            <td>@if($approval->status == 0)
                                {{ "Inactive" }}
                                @else
                                {{ "Active" }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-approval-{{ $approval->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-approval-{{ $approval->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit approval -->
    <div id="modal-add-approval" class="modal fade">
        <form method="post" action="{{url('insert-approval-detail')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add approval</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Approval Apps: *</label>
                            <select name="approval" class="form-control">
                                @foreach($data['approvalapps'] as $approvalapps)
                                    <option value="{{ $approvalapps->id }}">{{ $approvalapps->approval_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Keterangan: *</label>
                            <input type="text" name="ket" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Get Link: *</label>
                            <input type="text" name="get_link" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Post Link: *</label>
                            <input type="text" name="post_link" class="form-control" autocomplete="off" value="" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Approval To: *</label>
                            <select name="approval_to" class="form-control">
                                @foreach($data['user'] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Level: *</label>
                            <select name="approval_level" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
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
    <!-- Modal Edit approval -->
    @foreach($data['approval'] as $approval)
    <div id="modal-edit-approval-{{ $approval->id }}" class="modal fade">
        <form method="post" action="{{url('update-approval-detail')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit approval</h2>
                        <br>
                       
                        <div class="form-group text-left">
                            <label class="form-control-label">Approval Apps: *</label>
                            <select name="approval" class="form-control">
                                @foreach($data['approvalapps'] as $approvalapps)
                                @if($approvalapps->id == $approval->approval)
                                    <option selected value="{{ $approvalapps->id }}">{{ $approvalapps->approval_name }}</option>
                                @else
                                    <option  value="{{ $approvalapps->id }}">{{ $approvalapps->approval_name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Keterangan: *</label>
                            <input type="hidden" name="id" class="form-control" value="{{ $approval->id }}" required="" >
                            <input type="text" name="ket" class="form-control" autocomplete="off" value="{{ $approval->ket }}" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Get Link: *</label>
                            <input type="text" name="get_link" class="form-control" autocomplete="off" value="{{ $approval->get_link }}" required="" >
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Post Link: *</label>
                            <input type="text" name="post_link" class="form-control" autocomplete="off" value="{{ $approval->post_link }}" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Approval To: *</label>
                            <select name="approval_to" class="form-control">
                                @foreach($data['user'] as $user)
                                    @if($user->id == $approval->approval_to)
                                        <option selected value="{{ $user->id }}">{{ $user->name }}</option>
                                    @else
                                        <option  value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Level: *</label>
                            <select name="approval_level" class="form-control">
                                @if($approval->approval_to=='1')
                                    <option selected value="1">1</option>
                                    <option value="2">2</option>
                                @else
                                    <option value="1">1</option>
                                    <option selected value="2">2</option>
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
    <div id="modal-delete-approval-{{ $approval->id }}" class="modal fade">
        <form method="post" action="{{url('delete-approval-detail')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $approval->id }}"/>
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
                window.location.href="approval-management";
            } else {
                window.location.href="approval-management?search="+search;
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

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-approval" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
