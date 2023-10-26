@extends('cogs.panel.master')
@section('content')


<div class="clearfix"></div>
<div class="row">

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Raw Material Price</h2>
                <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add-raw-material-price"
                    href="#">
                    <i class="fa fa-plus"></i> New
                </a>
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

                            <table id="datatable-visibility-rawmaterialprice"
                                class="table text-center table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category</th>
                                        <th>Price Exmill<br><sub> / un</sub></th>
                                        <th>Currency Exmill</th>
                                        <th>Price Exstock<br><sub> / un</sub></th>
                                        <th>Currency Exstock</th>
                                        <th>Un</th>
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
                                    <tr class="text-center">
                                        <td>{{ $i+1 }}</td>
                                        <td><b>{{ $item->Category }}</b></td>
                                        <td>{{ $item->PriceExmill }}</td>
                                        <td>{{ $item->CurrencyExmill }}</td>
                                        <td>{{ $item->PriceExstock }}</td>
                                        <td>{{ $item->CurrencyExstock }}</td>
                                        <td>{{ $item->Un }}</td>
                                        <td>{{ $item->CreatedBy }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->UpdatedBy }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>
                                            @if($actionmenu->u==1)
                                            <a href="#" class="edit" data-toggle="modal" data-id="{{ $item->ID }}"><i
                                                    class=" fa fa-pencil fa-lg"></i></a>
                                            @endif
                                            @if($actionmenu->d==1)
                                            <a href="#" class="delete" data-toggle="modal" data-id="{{ $item->ID }}"><i
                                                    class="fa fa-trash fa-lg"></i></a>
                                            @endif
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
<div id="add-raw-material-price" class="modal fade">
    <form method="post" action="{{url('cogs-add-rawmaterialprice')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add Raw Material Price</h2>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Category: *</label>
                                <select name="Category" id="Category" class="form-control" required>
                                    @foreach ($category as $item)
                                    <option value="{{ $item->Category }}">{{ $item->Category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Un: *</label>
                                <input type="text" name="Un" id="Price" class="form-control AlfabethInput"
                                    autocomplete="off" value="" min="0" required>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Price Exmill: *</label>
                                <input type="text" name="PriceExmill" id="PriceExmill"
                                    class="form-control NumberInputComma" autocomplete="off" value="" min="0" required>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Currency Exmill: *</label>
                                <select name="CurrencyExmill" id="CurrencyExmill" class="form-control" required>
                                    @foreach ($kurs as $item)
                                    <option value="{{ $item->MataUang }}">{{ $item->MataUang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Price Exstock: *</label>
                                <input type="text" name="PriceExstock" id="PriceExstock"
                                    class="form-control NumberInputComma" autocomplete="off" value="" min="0" required>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Currency Exstock: *</label>
                                <select name="CurrencyExstock" id="CurrencyExstock" class="form-control" required>
                                    @foreach ($kurs as $item)
                                    <option value="{{ $item->MataUang }}">{{ $item->MataUang }}</option>
                                    @endforeach
                                </select>
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
    <form method="post" action="{{url('cogs-delete-rawmaterialprice')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>Warning</h3>
                    <h2 id="message"></h2>
                </div>
                <input id="IDdelete" name="IDdelete" type="hidden" name="id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL UPDATE -->
<div id="edit-raw-material-price" class="modal fade">
    <form method="post" action="{{url('cogs-edit-rawmaterialprice')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit Raw Material Price</h2>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group text-left">
                                <label class="form-control-label"> Category: *</label>
                                <select name="editCategory" id="editCategory" class="form-control" required>
                                    @foreach ($category as $item)
                                    <option value="{{ $item->Category }}">{{ $item->Category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Un: *</label>
                                <input type="text" name="editUn" id="editUn" class="form-control AlfabethInput"
                                    autocomplete="off" value="" min="0" required>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Price Exmill: *</label>
                                <input type="text" name="editPriceExmill" id="editPriceExmill"
                                    class="form-control NumberInputComma" autocomplete="off" value="" min="0" required>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Currency Exmill: *</label>
                                <select name="editCurrencyExmill" id="editCurrencyExmill" class="form-control" required>
                                    @foreach ($kurs as $item)
                                    <option value="{{ $item->MataUang }}">{{ $item->MataUang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Price Exstock: *</label>
                                <input type="text" name="editPriceExstock" id="editPriceExstock"
                                    class="form-control NumberInputComma" autocomplete="off" value="" min="0" required>
                            </div>
                            <div class="form-group text-left">
                                <label class="form-control-label"> Currency Exstock: *</label>
                                <select name="editCurrencyExstock" id="editCurrencyExstock" class="form-control"
                                    required>
                                    @foreach ($kurs as $item)
                                    <option value="{{ $item->MataUang }}">{{ $item->MataUang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <input id="IDedit" name="IDedit" type="hidden" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Ok</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('myscript')
<script>
$(".NumberInput").on("keypress", function(evt) {
    console.log(evt.which);
    if (evt.which != 8 && evt.which != 0 && (evt.which < 48 || evt.which > 57)) {
        evt.preventDefault();
    }
});

$(".NumberInputComma").on("keypress", function(evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which > 57) {
        evt.preventDefault();
    };
});

$(".NumberInputComma").on("input", function(evt) {
    if ($(this).val().replace(/[^.]/g, "").length == 1) {
        var decimalPlace = $(this).val().split(".")[1];
        if (decimalPlace.length > 3) {
            original = $(this).val().slice(0, -1);
            console.log(original);
            $(this).val(original);
        }
    }
    if ($(this).val().replace(/[^.]/g, "").length > 1) {
        original = $(this).val().slice(0, -1);
        $(this).val(original);
    }
});


$(".AlfabethInput").on("keypress", function(evt) {
    if (evt.which >= 48 && evt.which <= 57) {
        evt.preventDefault();
    }
});

$('.delete').on("click", function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{url('cogs-search-rawmaterialprice')}}",
        data: {
            id: id
        },
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#IDdelete').val(data.ID);
            $('#message').html("Anda yakin ingin menghapus " + data.Category + " ?");
            $('#delete').modal('show');
        }
    });
});
$('.edit').on("click", function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{url('cogs-search-rawmaterialprice')}}",
        data: {
            id: id
        },
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            $('#IDedit').val(data.ID);
            $('#editCategory').val(data.Category);
            $('#editUn').val(data.Un);
            $('#editPriceExmill').val(data.PriceExmill);
            $('#editCurrencyExmill').val(data.CurrencyExmill);
            $('#editPriceExstock').val(data.PriceExstock);
            $('#editCurrencyExstock').val(data.CurrencyExstock);
            $('#edit-raw-material-price').modal('show');
        }
    });
});
$(document).ready(function() {
    $('#datatable-visibility-rawmaterialprice').DataTable({
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', 'pdf', 'print',
        ],
        columnDefs: [{
                targets: 2,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 4,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            }
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
