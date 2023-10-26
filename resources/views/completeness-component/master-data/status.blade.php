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
                                        <td>No.</td>
                                        <td>System Status</td>
                                        <td class="text-center">Status</td>
                                        <td class="text-center">Description</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['status'] as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->ISTAT }}</td>
                                            <td>{{ $item->TXT04 }}</td>
                                            <td class="text-left">{{ $item->TXT30 }}</td>
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
                    }
                    <?php } ?>
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, ['All']]],
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });
        } );
    </script>
@endsection