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
                                <table id="myTable" class="table table-striped table-bordered nowrap" style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td class="text-center">Material Number</td>
                                            <td class="text-center">Description</td>
                                            <td>Material Type</td>
                                            <td>Material Group</td>
                                            <td>Base Unit</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['material'] as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-left">
                                                    <a href="{{ 'material-detail/'.$item->material_number }}" class="text-dark">{{ $item->material_number }}</a>
                                                </td>
                                                <td class="text-left">{{ $item->material_description }}</td>
                                                <td>{{ $item->material_type }}</td>
                                                <td>{{ $item->material_group }}</td>
                                                <td>{{ $item->base_unit }}</td>
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