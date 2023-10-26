@extends('cogs.panel.master')
@section('content')


<div class="clearfix"></div>
<div class="row">

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Product Category</h2>
                @if($actionmenu->u==1)
                <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add-category" href="#">
                    <i class="fa fa-plus"></i> New
                </a>
                @endif
                <div class="clearfix"></div>
            </div>
            @if(session()->has('err_message'))
            <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('err_message') }}
            </div>
            @endif
            @if(session()->has('suc_message'))
            <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('suc_message') }}
            </div>
            @endif
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table id="datatable-visibility-productcategory"
                                class="table text-center table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category Name</th>
                                        <th>Created By</th>
                                        <th>Created On</th>
                                        <th>Updated By</th>
                                        <th>Updated On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data))
                                    @foreach ($data as $i => $item)
                                    @php
                                    @endphp
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td><b>{{ $item->CategoryName }}</b></td>
                                        <td>{{ $item->CreatedBy }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->UpdatedBy }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>
                                            <a href="#" class="edit" data-toggle="modal" data-id="{{ $item->ID }}"><i
                                                    class=" fa fa-pencil fa-lg"></i></a>
                                            <a href="#" class="delete" data-toggle="modal" data-id="{{ $item->ID }}"><i
                                                    class="fa fa-trash fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL INSERT -->
<div id="add-category" class="modal fade">
    <form method="post" action="{{url('cogs-insert-productcategory')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add Product Category</h2>
                    <br>
                    <div class="row">

                        <div class="col-12">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Category Name: *</label>
                                <input type="text" name="CategoryName" class="form-control" autocomplete="off" value=""
                                    required>
                            </div>
                        </div>

                    </div>
                </div>
                <input type="hidden" name="id" value="" />
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Ok</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL DELETE -->
<div class="modal fade" id="delete">
    <form method="post" action="{{url('cogs-delete-productcategory')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>Warning</h3>
                    <h2 id="message"></h2>
                </div>
                <input id="IDdelete" type="hidden" name="id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit">
    <form method="post" action="{{url('cogs-edit-productcategory')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <form id="data">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h2>Edit Product Category</h2>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group text-left">
                                        <label class="form-control-label"> Category Name: *</label>
                                        <input id="name" type="text" name="CategoryName" class="form-control"
                                            autocomplete="off" value="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input id="IDedit" type="hidden" name="id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-success">Yes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</div>
@endsection

@section('myscript')
<script>
$('.delete').on("click", function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{url('cogs-search-productcategory')}}?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#IDdelete').val(data.ID);
            $('#message').html("Anda yakin ingin menghapus " + data.CategoryName + " ?");
            $('#delete').modal('show');
        }
    });
});
$('.edit').on("click", function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{url('cogs-search-productcategory')}}?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#IDedit').val(data.ID);
            $('#name').val(data.CategoryName);
            $('#edit').modal('show');
        }
    });
});
$(document).ready(function() {
    $('#datatable-visibility-productcategory').DataTable({
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', 'pdf', 'print',
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('table').find('thead').find(
                'th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
        },
    });
});
</script>
@endsection
