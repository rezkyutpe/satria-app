@extends('po-tracking.panel.master')
@section('content')
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>User Plant Management - PO Tracking</h2>

                <div class="clearfix"></div>
            </div>
            @if (session()->has('err_message'))
                <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('err_message') }}
                </div>
            @endif
            @if (session()->has('suc_message'))
                <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('suc_message') }}
                </div>
            @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <div class="x_content">
                            <div class="container-fluid">
                                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#internal"
                                            role="tab" aria-controls="home" aria-selected="true">User
                                            Plant 1</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#external"
                                            role="tab" aria-controls="profile" aria-selected="false">User
                                            Plant 2</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active " id="internal" role="tabpanel">
                                        <table
                                            id="datatable-visibility"class="table text-center table-striped table-bordered dt-responsive"
                                            cellspacing="0" width="100%">
                                            <div class="panel_toolbox">
                                                <button class="btn btn-primary add">Add User</button>
                                            </div>
                                            <thead class="text-center align-middle">
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Name</th>
                                                    <th>Assign Plant</th>
                                                    <th width="20%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($plant1 as $item)
                                                    <tr>
                                                        <td class="align-middle">{{ $item['email'] }}</td>
                                                        <td class="align-middle">{{ $item['name'] }}</td>
                                                        <td class="align-middle">{{ $item['assign_plant'] }}</td>
                                                        <td width="30%" class="text-center align-middle">
                                                            <button class="edit btn btn-sm btn-info"
                                                                data-id="{{ $item->id }}"><i
                                                                    class="fa fa-pencil fa-lg custom--1"></i> Edit</button>
                                                            <form method="POST"
                                                                action="{{ url('delete-user-plant-potracking') }}">
                                                                @csrf
                                                                <input name="id_user" type="hidden"
                                                                    value="{{ $item['id_user'] }}">
                                                                <button class="deleteUser btn btn-sm btn-danger"
                                                                    data-name="{{ $item->name }}"><i
                                                                        class="fa fa-trash fa-lg custom--1"></i>
                                                                    Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="external" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="tab-pane " id="external" role="tabpanel">
                                            <table id="datatable-responsive"
                                                class="table text-center table-striped table-bordered dt-responsive"
                                                cellspacing="0" width="100%">
                                                <div class="panel_toolbox">
                                                    <button class="btn btn-primary add2">Add User</button>
                                                </div>
                                                <thead class="text-center align-middle">
                                                    <tr>
                                                        <th>Email</th>
                                                        <th>Name</th>
                                                        <th>Assign Plant</th>
                                                        <th width="20%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($plant2 as $item)
                                                        <tr>
                                                            <td class="align-middle">{{ $item['email'] }}</td>
                                                            <td class="align-middle">{{ $item['name'] }}</td>
                                                            <td class="align-middle">{{ $item['assign_plant'] }}</td>
                                                            <td width="30%" class="text-center align-middle">
                                                                <button class="edit2 btn btn-sm btn-info"
                                                                    data-id="{{ $item->id }}"><i
                                                                        class="fa fa-pencil fa-lg custom--1"></i>
                                                                    Edit</button>
                                                                <form method="POST"
                                                                    action="{{ url('delete-user-plant-potracking') }}">
                                                                    @csrf
                                                                    <input name="id_user" type="hidden"
                                                                        value="{{ $item['id_user'] }}">
                                                                    <button class="deleteUser btn btn-sm btn-danger"
                                                                        data-name="{{ $item->name }}"><i
                                                                            class="fa fa-trash fa-lg custom--1"></i>
                                                                        Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-user-add" aria-hidden="true">
        <form method="post" action="{{ url('add-user-plant-potracking') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h2>Add User PO Tracking</h2>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> User : </label><br>
                                        <select class="select2" id="ddlUser" style="width:100%" name="user"
                                            required>
                                            @foreach ($get_user_potracking as $item)
                                                <option value="{{ $item['user'] }}" data-name="{{ $item['username'] }}"
                                                    data-email="{{ $item['email'] }}">
                                                    {{ $item['username'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Name : </label><br>
                                        <input type="text" class="form-control" id="add_name" name="name"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Email : </label><br>
                                        <input type="text" class="form-control" id="add_email" name="email"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Plant : </label><br>
                                        <select class="select2" multiple="multiple" id="ddlPlant" style="width:100%"
                                            name="plant[]" required>
                                            @foreach ($plant['plant'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="action"
                                value="adduserplant">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="modal-user-add2" aria-hidden="true">
        <form method="post" action="{{ url('add-user-plant-potracking') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h2>Add User PO Tracking</h2>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> User : </label><br>
                                        <select class="select2" id="ddlUsers" style="width:100%" name="user"
                                            required>
                                            @foreach ($get_user_potracking as $item)
                                                <option value="{{ $item['user'] }}" data-name="{{ $item['username'] }}"
                                                    data-email="{{ $item['email'] }}">
                                                    {{ $item['username'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Name : </label><br>
                                        <input type="text" class="form-control" id="add_names" name="name"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Email : </label><br>
                                        <input type="text" class="form-control" id="add_emails" name="email"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Plant : </label><br>
                                        <select class="select2" multiple="multiple" id="ddlPlants" style="width:100%"
                                            name="plant[]" required>
                                            @foreach ($plant['plant2'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="action"
                                value="adduserplant">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modal-user-edit" aria-hidden="true">
        <form method="post" action="{{ url('edit-user-plant-potracking') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h2>Edit User PO Tracking</h2>
                            <br>
                            <input type="hidden" id="id_user" name="id_user" hidden>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Name : </label><br>
                                        <input type="text" class="form-control"id="edit_name" name="name"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Email : </label><br>
                                        <input type="text" class="form-control" id="edit_email" name="email"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Plant : </label><br>

                                        <select class="select2" id="edit_ddlPlant" style="width:100%" name="plant[]"
                                            multiple="multiple" required>
                                            @foreach ($plant['plant'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="action"
                                value="edituserplant">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="modal-user-edit2" aria-hidden="true">
        <form method="post" action="{{ url('edit-user-plant-potracking') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h2>Edit User PO Tracking</h2>
                            <br>
                            <input type="hidden" id="id_user2" name="id_user" hidden>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Name : </label><br>
                                        <input type="text" class="form-control"id="edit_name2" name="name"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Email : </label><br>
                                        <input type="text" class="form-control" id="edit_email2" name="email"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Plant : </label><br>

                                        <select class="select2" id="edit_ddlPlant2" style="width:100%" name="plant[]"
                                            multiple="multiple" required>
                                            @foreach ($plant['plant2'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="action"
                                value="edituserplant">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {

        //add data user
        $("#ddlUser").change(function() {
            var username = $(this).find('option:selected').attr('data-name');
            var email = $(this).find('option:selected').attr('data-email');

            $('#add_name').val(username);
            $('#add_email').val(email);
        });
        $("#ddlUsers").change(function() {
            var username = $(this).find('option:selected').attr('data-name');
            var email = $(this).find('option:selected').attr('data-email');

            $('#add_names').val(username);
            $('#add_emails').val(email);
        });

        $('.add').on("click", function() {
            $('#modal-user-add').modal('show');
        });
        $('.add2').on("click", function() {
            $('#modal-user-add2').modal('show');
        });

        $('#modal-user-add').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
            $("#ddlUser").val('').trigger('change');
            $("#ddlPlant").val('').trigger('change');
        });
        $('#modal-user-add2').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
            $("#ddlUsers").val('').trigger('change');
            $("#ddlPlants").val('').trigger('change');
        });
        // --------

        //delete data user
        $(document).on('click', '.deleteUser', function() {
            event.preventDefault();
            const id = $(this).attr("data-id");
            const name = $(this).attr("data-name");
            Swal.fire({
                title: 'Delete Confirmation',
                text: "Delete [" + name + "] from record?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete',
                closeOnConfirm: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).parent('form').submit();
                } else {
                    return false;
                }
            });
        });
        // ------

        //edit data
        $('.edit').on("click", function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('view-user-plant-potracking') }}?id=" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#id_user').val(data.user.id_user);
                    $('#edit_name').val(data.user.name);
                    $('#edit_email').val(data.user.email);
                    $("#edit_ddlPlant").select2().val(data.assign_plant).trigger(
                        'change.select2');
                    $('#modal-user-edit').modal('show');
                }
            });
        });
        $('.edit2').on("click", function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('view-user-plant-potracking') }}?id=" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#id_user2').val(data.user.id_user);
                    $('#edit_name2').val(data.user.name);
                    $('#edit_email2').val(data.user.email);
                    $("#edit_ddlPlant2").select2().val(data.assign_plant).trigger(
                        'change.select2');
                    $('#modal-user-edit2').modal('show');
                }
            });
        });

    });
</script>
