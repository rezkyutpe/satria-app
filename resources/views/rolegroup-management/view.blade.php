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
            <h1>List Permission Role Group : {{ $data['rolegroup']->name }} </h1>
        </div>
    </div>
    <div class="row">
        <div class="pull-right">
                <a class="float-right btn btn-success" onclick="getUserRole('{{ $data['rolegroup']->id }}')" data-toggle="modal" data-target="#modal-user-appsmenu" href="#">List User</a> | <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-appsmenu" href="#">Tambah</a>
            </div>
        <div class="col-md-12">
            
            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-center">Apps</th>
                            <th class="text-center">Menu</th>
                            <th class="text-center">Access</th>
                            <th class="text-center">Create</th>
                            <th class="text-center">Retrive</th>
                            <th class="text-center">Update</th>
                            <th class="text-center">Delete</th>
                            <th class="text-center">View</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['appsmenu'] as $appsmenu)
                        <tr>
                            <td>{{ $appsmenu->no }}</td>
                            <td class="text-center">{{ $appsmenu->app_name }}</td>
                            <td class="text-center">{{ $appsmenu->link }}</td>
                            <td class="text-center">{{ $appsmenu->access }}</td>
                            <td class="text-center">{{ $appsmenu->c }}</td>
                            <td class="text-center">{{ $appsmenu->r }}</td>
                            <td class="text-center">{{ $appsmenu->u }}</td>
                            <td class="text-center">{{ $appsmenu->d }}</td>
                            <td class="text-center">{{ $appsmenu->v }}</td>
                            <td class="text-right">
                                <a href="#" data-toggle="modal" data-target="#modal-edit-appsmenu-{{ $appsmenu->id }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                <a href="#" data-toggle="modal" data-target="#modal-delete-appsmenu-{{ $appsmenu->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
</div>

    <!-- Modal Edit User -->
    <div id="modal-add-appsmenu" class="modal fade">
        <form method="post" action="{{url('insert-menu-rolegroup')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add Permission</h2>
                        <br>
                        <div class="form-group text-left">
                            <label class="form-control-label">Role Name: *</label>
                            <input type="text" name="full_name" class="form-control" value="{{ $data['rolegroup']->name }}" readonly required="">
                            <input type="hidden" name="group" class="form-control" value="{{ $data['rolegroup']->id }}" readonly required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Apps: *</label>
                            <select name="app" class="form-control"  onchange="getMenu(this);">
                            <option>Please choose Apps first</option>
                                @foreach($data['apps'] as $apps)
                                    <option value="{{ $apps->id }}">{{ $apps->app_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Menu: *</label>
                            <select id="child" name="menu" class="form-control">
                               <option>Choose Menu</option>
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Permission: *</label>
                        </div>
                        <div class="form-group text-left">
                            <table class="table">
                                <tr>
                                    <td class="text-center">C</td>
                                    <td class="text-center">R</td>
                                    <td class="text-center">U</td>
                                    <td class="text-center">D</td>
                                    <td class="text-center">V</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="c" value="1"></td>
                                    <td class="text-center"><input type="checkbox" name="r" value="1"></td>
                                    <td class="text-center"><input type="checkbox" name="u" value="1"></td>
                                    <td class="text-center"><input type="checkbox" name="d" value="1"></td>
                                    <td class="text-center"><input type="checkbox" name="v" value="1"></td>
                                </tr>
                            </table>
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
    
    <!-- Modal Edit User -->
    <div id="modal-user-appsmenu" class="modal fade">
        
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2>List User</h2>
                        <br>
                            <label class="form-control-label">Add User: *</label>
                        <br>
                        <div class="form-group text-left col-md-8" >
                            <input type="hidden" id="group" name="group" class="form-control" value="{{ $data['rolegroup']->id }}" readonly required="">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <select name="user" id="userlist" style="width: 100%"  class="form-control js-example-basic-single" data-live-search="true" required="">
                                <option value="">Please Select User</option>
                            </select>
                            
                        </div>
                        <div class="form-group text-left col-md-4" >
                        <span  style="display: none;" id="LoadingAddUser"  class="spinner-border spinner-border-sm align-middle ms-2">Loading...</span>
                                            <button class="btn btn-lg btn-success btn-submit-prout" id="add_user_sbmt" type="button"  onclick="AddUserRole()">Add</button>
                            
                        </div>
                        <table id="sorting-tables" class="table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>User</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="userrole">
                        </tbody>
                </table>
                    </div>
                    <input type="hidden" name="id" value=""/>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button> -->
                        <button type="button" class="btn btn-success"  data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
    </div>
    
    <!-- Modal Edit appsmenu -->
    @foreach($data['appsmenu'] as $appsmenu)
    <div id="modal-edit-appsmenu-{{ $appsmenu->id }}" class="modal fade">
        <form method="post" action="{{url('update-menu-rolegroup')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit Permission</h2>
                        <br>
                        <div class="form-group text-left">
                          <input type="hidden" name="id" value="{{ $appsmenu->id }}"/>
                            <label class="form-control-label">Role Name: *</label>
                            <input type="text" name="full_name" class="form-control" value="{{ $data['rolegroup']->name }}" required="">
                            <input type="hidden" name="id" class="form-control" value="{{ $appsmenu->id }}" readonly required="">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Apps: *</label>
                            <select name="app" class="form-control" >
                                @foreach($data['apps'] as $apps)
                                    @if($appsmenu->app == $apps->id)
                                    <option value="{{ $apps->id }}" selected>{{ $apps->app_name  }}</option>
                                    @else
                                    <option value="{{ $apps->id }}">{{ $apps->app_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Menu: *</label>
                            <select name="menu" class="form-control">
                                @foreach($data['menu'] as $menu)
                                    @if($appsmenu->menu == $menu->id)
                                    <option value="{{ $menu->id }}" selected>{{ $menu->menu.' - '.$menu->link }}</option>
                                    @else
                                    <option value="{{ $menu->id }}">{{ $menu->menu.' - '.$menu->link }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Permission: *</label>
                        </div>
                        <div class="form-group text-left">
                            <table class="table">
                                <tr>
                                    <td class="text-center">C</td>
                                    <td class="text-center">R</td>
                                    <td class="text-center">U</td>
                                    <td class="text-center">D</td>
                                    <td class="text-center">V</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="c" value="1" @if($appsmenu->c==1) checked @endif></td>
                                    <td class="text-center"><input type="checkbox" name="r" value="1"  @if($appsmenu->r==1) checked @endif></td>
                                    <td class="text-center"><input type="checkbox" name="u" value="1"  @if($appsmenu->u==1) checked @endif></td>
                                    <td class="text-center"><input type="checkbox" name="d" value="1"  @if($appsmenu->d==1) checked @endif></td>
                                    <td class="text-center"><input type="checkbox" name="v" value="1"  @if($appsmenu->v==1) checked @endif></td>
                                </tr>
                            </table>
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
    <!-- Modal Delete -->
    <div id="modal-delete-appsmenu-{{ $appsmenu->id }}" class="modal fade">
        <form method="post" action="{{url('delete-menu-rolegroup')}}" enctype="multipart/form-data">
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
                window.location.href="user-management";
            } else {
                window.location.href="user-management?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     true,
        } );

         
        $("div.toolbar").html('<br>');
    });
    
    function getMenu(sel) {
        APP_URL = '{{url('/')}}' ;
        var select1 = $('#child');
        select1.children().remove().end()
                var app = sel.value;
                $.ajax({
                    url: APP_URL+'/get-menu/' + app,
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {
                        // console.log(response['data']); 
                        var total = response['data'].length;
                        for (var i = 0; i < total; i++) {
                            var added = document.createElement('option');
                            added.value = response['data'][i]['id'];
                            added.innerHTML = response['data'][i]['menu']+' - '+response['data'][i]['link'];
                            select1.append(added);
                        }
                    }
            });
    }
    function getUserRole(sel) {
        var table = $('#sorting-tables').DataTable({
                        "dom": '<"toolbar">frtip',
                        "ordering": true,
                        "info":     true,
                        "paging":     true,
                        "searching":     true,
                    } );
        $("div.toolbar").html('<br>');
        table.destroy();
        APP_URL = '{{url('/')}}' ;
        var select1 = $('#userlist');
        var userrole = $('#userrole');
        // $('#sorting-tables').empty(); 
        $('#userrole').empty(); 
        select1.children().remove().end()
                        // console.log(sel); 
                $.ajax({
                    url: APP_URL+'/get-rolegroup-view/' + sel,
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {
                        // console.log(response['user']); 
                        for (var i = 0; i < response['user'].length; i++) {
                            var added = document.createElement('option');
                            added.value = response['user'][i]['id'];
                            added.innerHTML = response['user'][i]['name']+' | '+response['user'][i]['email'];
                            select1.append(added);
                        }
                        // console.log(response['userrole']); 
                        for (var j = 0; j < response['userrole'].length; j++) {
                            var tr = `<tr>
                            <td>`+(j+1)+`</td>
                            <td>`+response['userrole'][j]['email']+`</td>
                            <td>`+response['userrole'][j]['name']+`</td>
                            <td class="text-right">
                                <a  onclick="DeleteUserRole(`+response['userrole'][j]['id']+`,`+sel+`)" onclick="return confirm('Are you sure you want to delete role for this user?');"><i class="fa fa-trash fa-lg custom--1"></i></a>
                            </td>
                        </tr>`;
                        userrole.append(tr);
                        }
                        
                        var table = $('#sorting-tables').DataTable({
                                        "dom": '<"toolbar">frtip',
                                        "ordering": true,
                                        "info":     true,
                                        "paging":     true,
                                        "searching":     true,
                                    } );
                        $("div.toolbar").html('<br>');
                    }
            });
    }
    
function AddUserRole(){
    var userid =  document.getElementById("userlist").value;
    var group =  document.getElementById("group").value;
	var token  = $('#token').val()
    document.getElementById("add_user_sbmt").style.display = "none";
    document.getElementById("LoadingAddUser").style.display = "block";

    APP_URL = '{{url('/')}}' ;
    console.log(APP_URL);
    $.ajax({
        type:"POST",
        url: "{{url('insert-user-rolegroup')}}",
        data:{group:group,user:userid, "_token": token},
		dataType: "json",                  
        success:function(data){
            console.log("success-add-user");
            getUserRole(group);
            document.getElementById("add_user_sbmt").style.display = "block";
            document.getElementById("LoadingAddUser").style.display = "none";
        }
        });
};

function DeleteUserRole(sel,group){
	var token  = $('#token_delete').val()
    // document.getElementById("BtnDeletePrOutInventory"+val).style.display = "none";
    // document.getElementById("LoadingDeletePrOutInventory"+val).style.display = "block";
    APP_URL = '{{url('/')}}' ;
    $.ajax({
        type:"GET",
        url: "{{url('delete-user-rolegroup')}}/"+ sel,
		dataType: "json",                  
        success:function(data){
            console.log(data);
            getUserRole(group);        
        }
        });
};
</script>
@endsection
