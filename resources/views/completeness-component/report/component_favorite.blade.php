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
                        <div class="col-sm-12 mb-3">
                            <div class="col-md-4">
                                <input type="text" name="datefilter" id="datefilter" class="form-control" autocomplete="off" value="" placeholder="Range Date" />
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="search-button" class="btn btn-primary">Search</button>
                                <a href="{{ url('report-component-favorite') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table nowrap table-striped table-bordered"
                                    style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td class="text-center">Material Number</td>
                                            <td class="text-center">Description</td>
                                            <td class="text-center">Good Issue</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['material'] as $material)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-left">
                                                    <input type="hidden" id="tanggal" value="{{ $data['tanggal'] }}">
                                                    <a href="{{ url('material-detail/' . $material->material_number) }}" class="text-dark">
                                                        {{ $material->material_number }}
                                                    </a>
                                                </td>
                                                <td class="text-left">{{ $material->material_description }}</td>
                                                <td data-order="{{ $material->good_issue }}">
                                                    {{ str_replace('.', ',', $material->good_issue) . ' ' . $material->material_type }}
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
@endsection

@section('myscript')
    <script type="text/javascript">
        $(function() {
            $('input[name="datefilter"]').daterangepicker({
                alwaysShowCalendars: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            var custom_date = $('#tanggal').val();
            if(custom_date != ''){
                var tanggal = custom_date.split(" - ");
                $('input[name="datefilter"]').daterangepicker({                 
                    alwaysShowCalendars: true,
                    autoApply: true,
                    locale: {
                        format          : 'DD/MM/YYYY'
                    },
                    startDate: tanggal[0], 
                    endDate: tanggal[1] 
                });
            }

        });

        $('#search-button').click(function() {
            var search = $('#datefilter').val();
            if (search == null || search == "") {
                window.location.href = "report-component-favorite";
            } else {
                window.location.href = "report-component-favorite?search=" + search;
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "fBrt",
                fixedHeader: true,
                scrollCollapse: true,
                paging: true,
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
                select: true,
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
