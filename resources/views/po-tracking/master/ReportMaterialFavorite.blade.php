@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Report Material Favorite
                    </h2>
                    <div class="clearfix"></div>
                </div>

                @if (session()->has('err_message'))
                    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        {{ session()->get('err_message') }}
                    </div>
                @endif
                @if (session()->has('suc_message'))
                    <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        {{ session()->get('suc_message') }}
                    </div>
                @endif
                <div class="x_content">
                    <div class="well" style="overflow: auto;">
                        <form action="{{ url('report-mt-fv') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-1 mt-2">
                                        <strong>Date :</strong>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="datefilter" id="datefilter"
                                            value="{{ request()->session()->get('datefilter') }}" class="form-control"
                                            autocomplete="off" value="" placeholder="Range Date" />
                                        <input type="hidden" name="searchby" value="ReleaseDate" class="form-control"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-md-1">
                                        <strong>SearchBy Material:</strong>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="hidden" id="data_material"
                                            value="{{ json_encode(request()->session()->get('searchbymaterial')) }}">
                                        <select name="searchbymaterial[]" id="material" class="form-control select2"
                                            multiple="multiple">
                                            @foreach ($Material as $item)
                                                <option>{{ $item->Material }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-1">
                                        <strong>SearchBy Status:</strong>
                                    </div>
                                    <div class="col-md-5">
                                        @php
                                            $opt_po = ['New PO', 'Ongoing', 'History'];
                                        @endphp
                                        <select name="searchbystatus" class="form-control select2"
                                            value="{{ request()->session()->get('searchbystatus') }}">
                                            @foreach ($opt_po as $key => $opt)
                                                @if (request()->session()->get('searchbystatus') == $opt)
                                                    <option selected>{{ $opt }}</option>
                                                @else
                                                    <option></option>
                                                    <option>{{ $opt }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($action_menu->v == 1)
                                        <div class="col-md-1 mt-2">
                                            <strong>SearchBy Type:</strong>
                                        </div>
                                        <div class="col-md-5">
                                            @php
                                                $opt_type = ['Vendor Local', 'Vendor SubCont', 'Vendor Import'];
                                            @endphp
                                            <select name="searchbytype" class="form-control select2"
                                                value="{{ request()->session()->get('searchbytype') }}">
                                                @foreach ($opt_type as $key => $opttype)
                                                    @if (request()->session()->get('searchbytype') == $opttype)
                                                        <option selected>{{ $opttype }}</option>
                                                    @else
                                                        <option></option>
                                                        <option>{{ $opttype }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                    @endif
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-5">

                                    </div>
                                    <div class="col-md-5">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('report-mt-fv?reset=1') }}" class="btn btn-danger">Reset</a>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="row">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive"
                                class="table text-center table-striped table-bordered dt-responsive" cellspacing="0"
                                width="99%">
                                @php
                                    $posisi = strpos(Auth::user()->title, 'Department Head');
                                    if ($posisi) {
                                        $status = 1;
                                    } else {
                                        $status = 2;
                                    }
                                @endphp
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Total Quantity</th>
                                        @if ($action_menu->r == 1 || $action_menu->u == 1 || $action_menu->c == 1 || $status == 1)
                                            <th>Currency</th>
                                            <th>Nett Price</th>
                                            <th>Total Amount Foreign</th>
                                            <th>Total Amount IDR</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getData as $k => $item)
                                        @php
                                            if ($item->Currency == 'IDR') {
                                                // $amount =number_format($item->TotalAmount , 0, '.', '.');
                                                $amount = '';
                                                $Price = number_format($item->NetPrice, 0, '.', '.');
                                            } else {
                                                $Price = $item->NetPrice;
                                                $amount = number_format($item->TotalAmount, 2, '.', '.');
                                            }
                                        @endphp
                                        <tr>
                                            <td><a href="#" class="text-dark detailmaterial" data-toggle="modal"
                                                    data-id="{{ $item->Material }}"
                                                    data-currency="{{ $item->Currency }}">{{ $item->Material }}</a></td>
                                            <td>{{ $item->Description }}</td>
                                            <td>{{ $item->TotalQuantity }}</td>
                                            @if ($action_menu->r == 1 || $action_menu->u == 1 || $action_menu->c == 1 || $status == 1)
                                                <td>{{ $item->Currency }}</td>
                                                <td>{{ $Price }}</td>
                                                <td>{{ $amount }}</td>
                                                @foreach ($Kurs as $k => $items)
                                                    @php
                                                        if ($item->Currency == $items->MataUang && $item->Currency == 'IDR') {
                                                            $amountidr = number_format($item->TotalAmount, 0, '.', '.');
                                                        } elseif ($item->Currency == $items->MataUang) {
                                                            $foreign = $item->TotalAmount * $items->KursTengah;
                                                            $amountidr = number_format($foreign, 0, '.', '.');
                                                        }
                                                    @endphp
                                                @endforeach
                                                <td>{{ $amountidr }}</td>
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
@endsection
@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    {{-- Modal DetailPo  --}}
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



        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var a = JSON.parse($('#data_material').val());
            if (a != null) {
                $("#material").select2("val", [a]);
            }
            $('#datatable-responsive').DataTable({
                dom: 'Bfrtip',
                fixedHeader: true,
                scrollCollapse: true,
                ordering: false,
                columnDefs: [{
                    visible: false
                }],
                buttons: [
                    'pageLength', 'colvis',
                    <?php if ($action_menu->r==1 || $action_menu->u==1 || $action_menu->c==1 || $status == 1){ ?> {
                        text: 'Download',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ url('report-mtfv-download') }}";
                        }
                    }
                    <?php } ?>
                ],
                select: true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });
        });
    </script>
    @include('po-tracking.master.modal.DetailMaterial')
@endsection
