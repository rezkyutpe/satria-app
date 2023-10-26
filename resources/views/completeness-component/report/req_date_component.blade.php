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
                            <form action="{{ url('report-date-component') }}" method="POST">
                                @csrf
                                <div class="col-md-4">
                                    <input type="text" name="datefilter" id="datefilter" class="form-control" autocomplete="off" value="" placeholder="Range Date" />
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url('report-date-component?reset=1') }}" class="btn btn-danger">Reset</a>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table nowrap table-striped table-bordered"
                                    style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td>Rsv. No.</td>
                                            <td>Production Order</td>
                                            <td>Material Number</td>
                                            <td>Description</td>
                                            <td>Material Type</td>
                                            <td>Material Group</td>
                                            <td>Req. Date</td>
                                            <td>Req. Qty</td>
                                            <td>GI</td>
                                            <td>Allc. Stock</td>
                                            <td>Short Qty</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = $data['component']->firstItem();
                                        @endphp
                                        <input type="hidden" id="tanggal" value="{{ $data['tanggal'] }}">
                                        @foreach ($data['component'] as $component)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $component->reservation_number }}</td>
                                                <td>{{ $component->production_order }}</td>
                                                <td class="text-left" data-toggle="tooltip" data-placement="right" title="{{ $component->material_description }}">{{ $component->material_number }}</td>
                                                <td class="text-left">{{ $component->material_description }}</td>
                                                <td>{{ $component->material_type }}</td>
                                                <td>{{ $component->material_group }}</td>
                                                <td>{{ date('d-m-Y', strtotime($component->requirement_date)) }}</td>
                                                <td data-order="{{ round($component->requirement_quantity, 3) }}">
                                                    {{ str_replace('.', ',', round($component->requirement_quantity, 3)) }}</td>
                                                <td data-order="{{ round($component->good_issue, 3) }}">
                                                    {{ str_replace('.', ',', round($component->good_issue, 3)) }}</td>
                                                <td data-order="{{ round($component->alokasi_stock, 3) }}">
                                                    {{ str_replace('.', ',', round($component->alokasi_stock, 3)) }}</td>
                                                <td data-order="{{ round($component->kekurangan_stock, 3) }}">
                                                    @if ($component->kekurangan_stock < 0)
                                                        <a href="{{ url('material-detail/'.$component->material_number) }}" class="text-dark">
                                                            {{ str_replace('.', ',', round($component->kekurangan_stock, 3)) }}
                                                        </a>
                                                    @else                                                      
                                                        {{ str_replace('.', ',', round($component->kekurangan_stock, 3)) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($data['component']->total() > 0)
                                <div>
                                    <div class="pull-left text-dark">
                                        @php($number = $no-1)
                                        {{ "Showing ".$data['component']->firstItem()." to ".$number." of ".$data['component']->total()." entries" }}
                                    </div>
                                    <div class="pull-right">
                                        {{ $data['component']->links() }}
                                    </div>
                                </div>
                            @endif
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
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
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
    </script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "fBrt",
                fixedHeader: true,
                scrollCollapse: true,
                paging: true,
                searching : false,
                ordering : false,
                columnDefs: [{
                    targets : [2, 4],
                    visible : false
                }],
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu']->v==1){ ?> 
                    {
                        text: 'Download',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('report-date-component-download') }}";
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
