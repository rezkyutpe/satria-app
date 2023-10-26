@extends('completeness-component.panel.master')

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data['title'] }}<small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped table-bordered" style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td style="width: 5%">No.</td>
                                            <td>Comment</td>
                                            <td>Status</td>
                                            @if ($data['actionmenu']->c==1 && $data['actionmenu']->d==1)
                                                <td style="width: 20%">Action</td>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['komentar'] as $item)
                                            <tr>
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="text-left align-middle">{{ $item->komentar }}</td>
                                                <td class="align-middle">
                                                    @if ($item->status == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Non Active</span>    
                                                    @endif
                                                </td>
                                                @if ($data['actionmenu']->c==1 && $data['actionmenu']->d==1)
                                                    <td>
                                                        @if ($data['actionmenu']->d==1)
                                                            <a href="javascript:void(0)" data-id="{{ $item->id }}" data-komentar="{{ $item->komentar }}" data-status="{{ $item->status }}" class= "edit-komentar btn btn-sm btn-primary" >
                                                                <i class="fa fa-pencil-square-o" aria-hidden="true"> Edit</i>
                                                            </a>
                                                            <a href="javascript:void(0)" data-id="{{ $item->id }}" class= "delete-komentar btn btn-sm btn-danger" >
                                                                <i class="fa fa-trash" aria-hidden="true"> Delete</i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
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
@endsection

@section('modal')
    {{-- Modal Create --}}
    <div class="modal fade" id="createKomentar" tabindex="-1" role="dialog" aria-labelledby="createKomentarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createKomentarLabel">Create Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('ccr-create-komentar') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <span id="dataCreateComment"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="editKomentar" tabindex="-1" role="dialog" aria-labelledby="editKomentarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKomentarLabel">Edit Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('ccr-edit-komentar') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <span id="dataEditComment"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="deleteKomentar" tabindex="-1" role="dialog" aria-labelledby="deleteKomentarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteKomentarLabel">Delete Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('ccr-delete-komentar') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <span id="dataDeleteComment"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('myscript')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "flBrtip",
                fixedHeader: true,
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?>
                    {
                        text: 'Download',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    <?php } ?>
                    <?php if ($data['actionmenu']->c==1 && $data['actionmenu']->d==1){ ?> 
                        {
                            text: 'Create New Comment',
                            className: 'create-komentar'
                        }
                    <?php } ?>
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, ['All']]],
                select:true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });

            $('#myTable tbody').on('click', '.edit-komentar', function() {
                var id          = $(this).attr('data-id');
                var komentar    = $(this).attr('data-komentar');
                var status      = $(this).attr('data-status');

                $('#dataEditComment').empty();
                if (status == 1) {
                    html = `
                        <div class="form-group">
                            <label for="komentar" class="col-form-label">Komentar</label>
                            <input type="hidden" class="form-control" name="id" value="` + id + `">
                            <input type="text" class="form-control" name="komentar" value="` + komentar + `">
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="1" SELECTED>Active</option>
                                <option value="2">Non Active</option>
                            </select>
                        </div>
                    `;
                }else{
                    html = `
                        <div class="form-group">
                            <label for="komentar" class="col-form-label">Komentar</label>
                            <input type="hidden" class="form-control" name="id" value="` + id + `">
                            <input type="text" class="form-control" name="komentar" value="` + komentar + `">
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="2" SELECTED>Non Active</option>
                            </select>
                        </div>
                    `;
                }
                
                $('#dataEditComment').append(html);
                $('#editKomentar').modal('show');
            });

            $('#myTable tbody').on('click', '.delete-komentar', function() {
                var id          = $(this).attr('data-id');
                var komentar    = $(this).attr('data-komentar');

                $('#dataDeleteComment').empty();
                html = `
                    <input type="hidden" class="form-control" name="id" value="` + id + `">
                    <span>Are you sure delete this comment ?</span>
                `;
                
                $('#dataDeleteComment').append(html);
                $('#deleteKomentar').modal('show');
            });

            
            $(".create-komentar").click(function(){
                $('#dataCreateComment').empty();
                html = `
                    <div class="form-group">
                        <label for="komentar" class="col-form-label">Komentar</label>
                        <input type="text" class="form-control" name="komentar">
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="2">Non Active</option>
                        </select>
                    </div>
                `;
                
                $('#dataCreateComment').append(html);
                $('#createKomentar').modal('show');
            });
        } );
    </script>
@endsection