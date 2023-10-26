@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Report Delivery Date to GR Date
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="well" style="overflow: auto;">
                    <form action="{{ url('report-delivery-gr') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>Month :</strong>
                                </div>
                                <div class="col-md-5">

                                    <select name="month" class="form-control select2"
                                        value="{{ request()->session()->get('month') }}">
                                        @for ($i = 1; $i < 13; $i++)
                                            @if (request()->session()->get('month') == $i)
                                                <option selected>{{ $i }}</option>
                                            @else
                                                <option></option>
                                                <option>{{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <strong>Years :</strong>
                                </div>

                                <div class="col-md-5">
                                    <select name="years" class="form-control select2"
                                        value="{{ request()->session()->get('years') }}">
                                        @for ($i = date('Y'); $i >= 2019; $i--)
                                            @if (request()->session()->get('years') == $i)
                                                <option selected>{{ $i }}</option>
                                            @else
                                                <option value=""></option>
                                                <option>{{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>PONumber:</strong>
                                </div>
                                <div class="col-md-5">
                                    <input type="hidden" name="action" value="1">
                                    <input type="hidden" id="data_po"
                                        value="{{ json_encode(request()->session()->get('ponumber')) }}">
                                    <select name="ponumber[]" id="number" class="form-control select2"
                                        multiple="multiple">
                                        @foreach ($ponumber as $item)
                                            <option>{{ $item->Number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <strong>SearchDelv:</strong>
                                </div>
                                <div class="col-md-5">
                                    @php
                                        $opt_po = ['Fullfill-Ontime', 'Fullfill-Early', 'Fullfill-Late', 'Partial-Early', 'Partial-Late'];
                                    @endphp
                                    <select name="statusdelv" class="form-control select2"
                                        value="{{ request()->session()->get('statusdelv') }}">
                                        @foreach ($opt_po as $key => $opt)
                                            @if (request()->session()->get('statusdelv') == $opt)
                                                <option selected>{{ $opt }}</option>
                                            @else
                                                <option></option>
                                                <option>{{ $opt }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($action_menu->v == 1)
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-1 mt-2">
                                        <strong>Vendor Name:</strong>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="hidden" id="data_vendor"
                                            value="{{ json_encode(request()->session()->get('vendor')) }}">
                                        <select name="vendor[]" id="vendor" class="form-control select2"
                                            multiple="multiple">
                                            @foreach ($datavendor as $items)
                                                <option>{{ $items->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <strong>Vendor Type:</strong>
                                    </div>
                                    <div class="col-md-5">
                                        <select name="vendortype" class="form-control select2"
                                            value="{{ request()->session()->get('vendortype') }}">
                                            @foreach ($datavendortype as $itemss)
                                                @if (request()->session()->get('vendortype') == $itemss->VendorType)
                                                    <option selected>{{ $itemss->VendorType }}</option>
                                                @else
                                                    <option></option>
                                                    <option>{{ $itemss->VendorType }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            @else
                            @endif
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-5">

                                </div>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url('report-delivery-gr?reset=1') }}" class="btn btn-danger">Reset</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive"
                                class="table text-center table-striped table-bordered dt-responsive" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">PO Number</th>
                                        <th style="width: 5%">Item Number</th>
                                        <th style="width: 5%">Material</th>
                                        <th style="width: 5%">Description</th>
                                        <th style="width: 5%">Vendor</th>
                                        <th style="width: 5%">Vendor Type</th>
                                        <th style="width: 5%">Delivery Date</th>
                                        <th style="width: 5%">Security Date</th>
                                        <th style="width: 5%">GR Date</th>
                                        <th style="width: 5%">Status Delivery</th>
                                        <th style="width: 5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getData as $k => $item)
                                        @php
                                            if ($item->StatusDelivery == 'Ontime' && $item->StatusReceive == 'Fullfill') {
                                                $status = 'Fullfill-Ontime';
                                            } elseif ($item->StatusDelivery == 'Early' && $item->StatusReceive == 'Fullfill') {
                                                $status = 'Fullfill-Early';
                                            } elseif ($item->StatusDelivery == 'Late' && $item->StatusReceive == 'Fullfill') {
                                                $status = 'Fullfill-Late';
                                            } elseif ($item->StatusDelivery == 'Early' && $item->StatusReceive == 'Partial') {
                                                $status = 'Partial-Early';
                                            } elseif ($item->StatusDelivery == 'Late' && $item->StatusReceive == 'Partial') {
                                                $status = 'Partial-Late';
                                            }
                                        @endphp

                                        <tr>
                                            <td><a href="#" class="text-dark detailspo" data-toggle="modal"
                                                    data-number="{{ $item->Number }}">{{ $item->Number }}</a></td>
                                            <td>{{ $item->ItemNumber }}</td>
                                            <td>{{ $item->Material }}</td>
                                            <td>{{ $item->Description }}</td>
                                            <td>{{ $item->VendorName }}</td>
                                            <td>{{ $item->VendorType }}</td>
                                            <td>
                                                {{ date('d/m/Y', strtotime($item->DeliveryDate)) }}
                                            </td>
                                            <td>{{ $item->SecurityDate == null ? '-' : date('d/m/Y', strtotime($item->SecurityDate)) }}
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($item->GoodsReceiptDate)) }}</td>
                                            <td>{{ $status }}</td>
                                            <td>
                                                <a href="#" class="details badge badge-primary" data-toggle="modal"
                                                    data-number="{{ $item->Number }}" data-item="{{ $item->ItemNumber }}">
                                                    Details
                                                </a>
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


@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            <?php if ($action_menu->v==1 ){ ?> {
                var a = JSON.parse($('#data_vendor').val());
                $("#vendor").select2("val", [a]);
            }
            <?php } ?>
            var b = JSON.parse($('#data_po').val());
            if (b != null) {
                $("#number").select2("val", [b]);
            }
            $('#datatable-responsive').DataTable({
                dom: 'Bfrtip',
                fixedHeader: true,
                scrollCollapse: true,
                columnDefs: [{

                    visible: false
                }],
                buttons: [
                    'pageLength', 'colvis',
                    <?php if ($action_menu->r==1 ){ ?> {
                        text: 'Download',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ url('report-dsgr-open-download') }}";
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
    @include('po-tracking.master.modal.DetailPO')
    @include('po-tracking.master.modal.DetailGR')
@endsection
@endsection
