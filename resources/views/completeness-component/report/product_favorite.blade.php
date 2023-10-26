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
                        <div class="col-sm-12 mb-3">
                            <div class="col-md-4">
                                <input type="text" name="datefilter" id="datefilter" class="form-control" autocomplete="off" value="" placeholder="Range Date" />
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="search-button" class="btn btn-primary">Search</button>
                                <a href="{{ url('report-product') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table nowrap table-striped table-bordered"
                                    style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td class="text-center">Product Number</td>
                                            <td class="text-center">Description</td>
                                            <td class="text-center">Group Product</td>
                                            <td>Quantity</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <input type="hidden" id="tanggal_product" value="{{ $data['tanggal'] }}">
                                        @foreach ($data['material'] as $material)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-left">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('planning-unit/' . $material->product_number) }}" class="text-dark">
                                                            {{ $material->product_number }}
                                                        </a>
                                                    @else
                                                        {{ $material->product_number }}
                                                    @endif
                                                </td>
                                                <td class="text-left">{{ $material->product_description }}</td>
                                                <td class="text-left">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('detail-product-ccr?groupProduct='.str_replace(' ', '_', str_replace('&', '^', $material->group_product))) }}" class="text-dark">
                                                            {{ $material->group_product }}
                                                        </a>
                                                    @else
                                                        {{ $material->group_product }}
                                                    @endif
                                                </td>
                                                <td data-order="{{ $material->quantity }}">
                                                    {{ str_replace('.', ',', $material->quantity) }}
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
                // ranges: {
                //     'Today': [moment(), moment()],
                //     'Next 7 Days': [moment(), moment().add(7, 'days')],
                //     'Next 30 Days': [moment(),moment().add(30, 'days')],
                //     'This Month': [moment().startOf('month'), moment().endOf('month')]
                // },
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

            var custom_date = $('#tanggal_product').val();
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
                window.location.href = "report-product";
            } else {
                window.location.href = "report-product?search=" + search;
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "fBrt",
                fixedHeader: true,
                order: [
                    [4, 'DESC']
                ],
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
