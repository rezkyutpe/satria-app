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
            <h1>Assist Management </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Group</th>
                            <th>Contact</th>
                            <th>Ticket Done</th>
                            <th>Ratings</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['assist'] as $assist)
                        <tr>
                            <td>{{ $assist->no }}</td>
                            <td>{{ $assist->name }}<br>
                                <span style="color: grey;font-size:12px">{{ $assist->email }}</span></td>
                            <td>{{ $assist->title }}</td>
                            <td>{{ $assist->group_name }}</td>
                            <td><span>{{ $assist->email_sf }}</span><br>
                                <span style="color: grey;font-size:12px">{{ $assist->phone }}</span>
                            </td>
                            <td>{{ $assist->flag }}</td>
                            <td>{{ $assist->flag }}</td>
                            <td>{{ $assist->flag }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                <a href="{{ url('assist-view/'.$assist->id) }}" ><i class="fa fa-eye fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->u==1)
                                <a href="#" data-toggle="modal" data-target="#modal-edit-assist-{{ $assist->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                <!-- <a href="#" data-toggle="modal" data-target="#modal-reset-assist-{{ $assist->id }}"><i class="fa fa-key fa-lg custom--1"></i></a> -->
                                @endif
                                @if($data['actionmenu']->d==1)
                                <a href="#" data-toggle="modal" data-target="#modal-delete-assist-{{ $assist->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
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

    <!-- Modal Edit assist -->
    
    <div id="modal-group-assist" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                    
                    <h2>Assist Group</h2>
                    <br>
                    <form method="post" action="{{url('insert-assist-group')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group text-left">
                       <label class="form-control-label">Name: *</label>
                    </div>
                    <div class="form-group text-left">
                        <button type="submit" class="btn btn-success pull-right">Add Group</button>
                        <input style="width:70%" type="text" class="form-control" name="group">
                    </div>
                                
                    </form>
                    <table id="sorting-table" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($no=0)
                            @foreach($data['group'] as $group)
                            @php($no=$no+1)

                            <tr>
                                <td class="text-left">{{ $no }}</td>
                                <td class="text-left">{{ $group->name }}</td>
                                <td class="text-right">
                                    @if($data['actionmenu']->u==1)
                                    <a href="#" data-toggle="modal" data-target="#modal-edit-group-{{ $group->id }}""><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                    <!-- <a href="#" data-toggle="modal" data-target="#modal-reset-group-{{ $group->id }}"><i class="fa fa-key fa-lg custom--1"></i></a> -->
                                    @endif
                                    @if($data['actionmenu']->d==1)
                                    <a href="#" data-toggle="modal" data-target="#modal-delete-group-{{ $group->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                                    @endif

                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    </div>
                    <input type="hidden" name="id" value=""/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
                        <!-- <button type="submit" class="btn btn-success">Yes</button> -->
                    </div>
                    @foreach($data['group'] as $group)
                    
                    <div id="modal-edit-group-{{ $group->id }}" class="modal fade">
                    <form method="post" action="{{url('update-assist-group')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <h2>Add Assist Group</h2>
                                    <br>
                                    <div class="form-group text-left">
                                        <label class="form-control-label">Name: *</label>
                                    <input type="text" class="form-control" value="{{ $group->name }}" name="group">
                                    </div>
                                </div>
                                <input type="hidden" name="id" value=""/>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                    @endforeach
                </div>
            </div>
    </div>
    <div id="modal-add-assist" class="modal fade">
        <form method="post" action="{{url('insert-assist')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Assist</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">User: *</label>
                         
                        
                            <select style="width: 100%" name="user" class="form-control js-example-basic-single" data-live-search="true" require="">
                            <option>Please Select User</option>
                                @foreach($data['user'] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                       
                        <div class="form-group text-left">
                            <label class="form-control-label">Group: *</label>
                            <select name="id_group" class="form-control" require="">
                            @foreach($data['group'] as $group)
                                    <option value="{{ $group['id'] }}">{{ $group['name'] }}</option> 
                                @endforeach 
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
    <!-- Modal Edit assist -->
    @foreach($data['assist'] as $assist)
    <div id="modal-edit-assist-{{ $assist->id }}" class="modal fade">
        <form method="post" action="{{url('update-assist')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Assist</h2>
                        <br>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $assist->id }}"/>
                            <label class="form-control-label">User: *</label>
                            <input type="text" name="name" class="form-control" value="{{ $assist->name }}" readonly required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Group: *</label>
                            <select name="id_group" class="form-control" require="">
                            @foreach($data['group'] as $group)
                                @if($group['id']==$assist->id_group)
                                    <option value="{{ $group['id'] }}" selected>{{ $group['name'] }}</option> 
                                @else
                                    <option value="{{ $group['id'] }}">{{ $group['name'] }}</option> 
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
    <!-- Modal Reset -->
    <div id="modal-reset-assist-{{ $assist->id }}" class="modal fade">
        <form method="post" action="{{url('reset-pass-assist')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Reset Password</h2>
                        <br>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $assist->id }}"/>
                            <label class="form-control-label">Password: *</label>
                            <input type="password" name="password" class="form-control" required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Re-type Password: *</label>
                            <input type="password" name="re_password" class="form-control" required="">
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
    <div id="modal-delete-assist-{{ $assist->id }}" class="modal fade">
        <form method="post" action="{{url('delete-assist')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $assist->id }}"/>
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
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": true,
            "info":     true,
            "paging":     true,
            "searching":     true,
        } );
        <?php if($data['actionmenu']->c==1){ ?>
        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-group-assist" href="#">Assist Group</a> | <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-assist" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });
    </script>
@endsection
