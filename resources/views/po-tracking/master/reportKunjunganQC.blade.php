@extends('po-tracking.panel.master')
@section('content')
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Report Kunjungan QC
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="well" style="overflow: auto;">
            <form action="{{url('report-kunjungan-qc')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <div class="col-md-1 mt-2">
                            <strong>Date :</strong>
                        </div>
                            <div class="col-md-5">
                                <input type="text" name="datefilter" id="datefilter" class="form-control" autocomplete="off"
                                value="" placeholder="Range Date" />
                                <input type="hidden" name="searchby" value="ReleaseDate" class="form-control" autocomplete="off">
                            </div>
                        <div class="col-md-1">
                            <strong>PO NO :</strong>
                        </div>
                            <div class="col-md-5">
                                <select name="number[]"  class="form-control select2" multiple="multiple">
                                    <option></option>
                                    @foreach ($number as $item)
                                    <option value="{{ $item->Number }}">{{ $item->Number }}</option>
                                    @endforeach
                                </select>
                             </div>
                    </div>
                    <div class="col-sm-12 mb-3">
                        <div class="col-md-1 mt-2">
                            <strong>Vendor :</strong>
                        </div>
                            <div class="col-md-5">
                                <select name="vendor[]"  class="form-control select2" multiple="multiple">
                                    <option></option>
                                    @foreach ($vendor as $item)
                                    <option value="{{ $item->Vendor }}">{{ $item->Vendor }}</option>
                                    @endforeach
                                </select>
                         </div>
                        <div class="col-md-1">
                            <strong>Material :</strong>
                        </div>
                        <div class="col-md-5">
                            <select name="material[]"  class="form-control select2" multiple="multiple">
                                <option></option>
                                @foreach ($material as $item)
                                <option value="{{ $item->Material }}">{{ $item->Material }}</option>
                                @endforeach
                            </select>
                         </div>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div class="col-md-1 mt-2">
                            <strong>Search By Date:</strong>
                        </div>
                        <div class="col-md-5">
                            <select name="searchby" class="form-control select2">
                                <option >Planning QC</option>
                                <option >Delivery Date</option>
                                <option >Req Date CCR</option>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ url('report-kunjungan-qc?reset=1') }}" class="btn btn-danger">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="card-box table-responsive">
                        <table id="datatable-responsive" class="table text-center table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th style="width: 5%">Vendor</th>
                                <th style="width: 5%">PO Number</th>
                                <th style="width: 5%">Item Number</th>
                                <th style="width: 5%">Material</th>
                                <th style="width: 5%">Description</th>
                                <th style="width: 5%">DeliveryDateAgreed</th>
                                <th style="width: 5%">ConfirmDate</th>
                                <th style="width: 5%">Qty</th>
                                <th style="width: 5%">ConfirmedQty</th>
                                <th style="width: 5%">PlanningQC</th>
                                <th style="width: 5%">ReqDateCCR</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $k=>$item)

                                @php
                                    if($item->req_date == null){
                                        $reqccr = "-";
                                    }else {
                                        $reqccr = date('d/m/Y', strtotime($item->req_date)) ;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $item->Vendor }}</td>
                                    <td>{{ $item->Number }}</a></td>
                                    <td>{{ $item->ItemNumber }}</td>
                                    <td>{{ $item->Material }}</td>
                                    <td>{{ $item->Description }}</td>
                                    <td data-order="{{ $item->DeliveryDate }}">
                                        {{date('d/m/Y', strtotime($item->DeliveryDate))}}
                                    </td data-order="{{ $item->ConfirmedDate }}">
                                    <td>{{ date('d/m/Y', strtotime($item->ConfirmedDate)) }}</td>
                                    <td>{{ $item->Quantity }}</td>
                                    <td>{{ $item->ConfirmedQuantity }}</td>
                                    <td data-order="{{ $item->PlanningQCDate }}">{{ date('d/m/Y', strtotime($item->PlanningQCDate)) }}</td>
                                    <td>{{ $reqccr }}</td>
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


@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    {{-- <script src="{{ asset('public/assetss/datatable/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/jszip.min.js') }}"></script> --}}
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
            $('#datatable-responsive').DataTable({
                dom: 'Bfrtip',
                fixedHeader                     : true,
                scrollCollapse                  : true,
                columnDefs: [{

                    visible                     : false
                }],
                buttons: [
                    'pageLength','colvis',
                    <?php if ($action_menu->r==1 || $action_menu->u==1|| $action_menu->d==1|| $action_menu->v==1 || $action_menu->c==1){ ?>
                    {
                        text: 'Download',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('report-kqc-download') }}";
                        }
                    }
                    <?php } ?>
                ],
                select                          : true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });


            $("#month").change(function() {
                $('#years').val('2022').change();
            });

        });


     </script>

@endsection
@endsection
