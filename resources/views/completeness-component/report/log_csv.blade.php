@extends('completeness-component.panel.master')

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data['title'] }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped table-bordered nowrap" style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td class="align-middle">No.</td>
                                            <td class="align-middle">Diupload Oleh</td>
                                            <td class="align-middle">Proses</td>
                                            <td class="align-middle">Nama File</td>
                                            <td class="align-middle">Total Baris</td>
                                            <td class="align-middle">Start</td>
                                            <td class="align-middle">Finish</td>
                                            <td class="align-middle">Status</td>
                                            <td class="align-middle">Pesan Message</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['csv'] as $log)
                                            <tr @if ($log->status == 'Error') style="background-color: #fb8496;" @else  style="background-color: #b2e0aa;" @endif >
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle text-left">{{ $log->upload_by }}</td>
                                                <td class="align-middle text-left">{{ $log->proses }}</td>
                                                @if ($log->filename == null)
                                                    <td class="text-center align-middle">-</td>
                                                @else
                                                    <td class="text-left align-middle">{{ $log->filename }}</td>
                                                @endif
                                                <td class="align-middle">{{ $log->total_row == null ? '-' : $log->total_row }}</td>
                                                <td class="align-middle">{{ date('d-m-Y h:i:s A', strtotime($log->start)) }}</td>
                                                <td class="align-middle">{{ date('d-m-Y h:i:s A', strtotime($log->stop)) }}</td>
                                                <td class="align-middle">{{ $log->status }}</td>
                                                <td class="align-middle">{{ $log->message }}</td>
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
                scrollCollapse: true,
                scrollX: true,
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> {
                        text: 'Download',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                    <?php } ?>
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
                columnDefs: [{
                    targets: [4,8],
                    visible: false
                }],
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });
        });
    </script>
@endsection
