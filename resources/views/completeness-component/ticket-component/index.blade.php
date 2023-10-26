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
                                <table id="myTable" class="table table-striped table-bordered nowrap"
                                    style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td>Ticket</td>
                                            <td>Production Order</td>
                                            <td class="text-center">Create Date</td>
                                            <td class="text-center">Request Date</td>
                                            <td class="text-center">Request By</td>
                                            <td class="text-center">Status</td>
                                        </tr>
                                    </thead>
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
                order: [
                    [6, 'ASC'],
                    [4, 'ASC'],
                    [2, 'ASC']
                ],
                // stateSave: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('ticket.index') }}",
                columns: [{
                        data: "id",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'ticket',
                        name: 'ticket',
                        render: function(data, type, row) {
                            return "<a href='list-component-ticket/" + row.id + "' class='text-dark'>" + row.ticket + "</a>"
                        },
                    },
                    {
                        data: 'production_order',
                        name: 'production_order',
                        render: function(data, type, row) {
                            return "<a href='production-order-planning/" + row.production_order + "' class='text-dark'>" + row.production_order + "</a>"
                        },
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(value) {
                            if (value === null) return "-";
                            return moment(value).format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'req_date',
                        name: 'req_date',
                        render: function(value) {
                            if (value === null) return "-";
                            return moment(value).format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(value) {
                            if (value === null) {
                                return "<span class='badge badge-danger'>OPEN</span>"
                            }else if(value === 2){
                                return "<span class='badge badge-warning'>PARTIAL</span>"
                            }else{
                                return "<span class='badge badge-success'>CLOSE</span>";
                            }                            
                        }
                    },
                ],
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> {
                        text: 'Download',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    data = $('<p>' + data + '</p>').text();
                                    return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                                }
                            }
                        },
                    }
                    <?php } ?>
                ],
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });
        });
    </script>
@endsection
