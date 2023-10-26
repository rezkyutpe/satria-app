@extends('po-tracking.panel.master')
@section('content')
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Report Status Delivery
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="well" style="overflow: auto;">
            <form action="{{url('report-dl-gd')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <div class="col-md-2 mt-2">
                            <strong>Search By Vendor:</strong>
                        </div>
                        <div class="col-md-5">
                            <select name="searchbyvendor[]"  class="form-control select2" multiple="multiple">
                                <option></option>
                                @foreach ($VendorName as $item)
                                <option value="{{ $item->VendorName }}">{{ $item->VendorName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3">
                        <div class="col-md-2 mt-2">
                            <strong>Search By VendorType:</strong>
                        </div>
                        <div class="col-md-5">
                            <select name="searchbytype" class="form-control select2">
                                <option ></option>
                                <option >Vendor Local</option>
                                <option >Vendor SubCont</option>
                                <option >Vendor Import</option>
                            </select>
                         </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ url('report-dl-gd?reset=1') }}" class="btn btn-danger">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <div class="x_content">
                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#terbaik" role="tab" aria-controls="home" aria-selected="true">Delivery Terbaik</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#terburuk" role="tab" aria-controls="profile" aria-selected="false">Delivery Terburuk</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active " id="terbaik" role="tabpanel">
                                <table id="datatable-visibility-terbaik"class="table text-center table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th>Vendor Name</th>
                                        <th>Vendor Type</th>
                                        <th>PO Item</th>
                                        <th>Late</th>
                                        <th>Ontime</th>
                                        <th>Early</th>
                                        <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($terbaik as $k=>$item)
                                        <tr>
                                            <td>{{ $item->VendorName }}</td>
                                            <td>{{ $item->VendorType }}</td>
                                            <td>{{ $item->totalStatusDelivery }}</td>
                                            <td>{{ $item->countLate }}</td>
                                            <td>{{ $item->countOntime }}</td>
                                            <td>{{ $item->countEarly }}</td>
                                            <td>{{ number_format($item->performance , 2, '.', '.') }}% </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                    </div>

                    <div class="tab-pane fade" id="terburuk" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="tab-pane " id="terbaik" role="tabpanel">
                                <table id="datatable-visibility-terburuk" class="table text-center table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                     <thead>
                                        <tr>
                                            <th>Vendor Name</th>
                                            <th>Vendor Type</th>
                                            <th>PO Item</th>
                                            <th>Late</th>
                                            <th>Ontime</th>
                                            <th>Early</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($terburuk as $k=>$item)
                                        <tr>
                                            <td>{{ $item->VendorName }}</td>
                                            <td>{{ $item->VendorType }}</td>
                                            <td>{{ $item->totalStatusDelivery }}</td>
                                            <td>{{ $item->countLate }}</td>
                                            <td>{{ $item->countOntime }}</td>
                                            <td>{{ $item->countEarly }}</td>
                                            <td>{{ number_format($item->performance , 2, '.', '.') }}% </td>
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
        $(document).ready(function() {
            $('#datatable-visibility-terbaik').DataTable({
                dom: 'Bfrtip',
                fixedHeader                     : true,
                scrollCollapse                  : true,
                ordering                        : false,
                columnDefs: [{
                    visible                     : false
                }],
                buttons: [
                    'pageLength','colvis',
                    <?php if ($action_menu->r==1 || $action_menu->u==1|| $action_menu->d==1|| $action_menu->v==1 || $action_menu->c==1){ ?>
                    {
                        text: 'Download',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('report-dlgd-download?status=1') }}";
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
            $('#datatable-visibility-terburuk').DataTable({
                dom: 'Bfrtip',
                fixedHeader                     : true,
                scrollCollapse                  : true,
                ordering                        : false,
                columnDefs: [{
                    visible                     : false
                }],
                buttons: [
                    'pageLength','colvis',
                    <?php if ($action_menu->r==1 || $action_menu->u==1|| $action_menu->d==1|| $action_menu->v==1 || $action_menu->c==1){ ?>
                    {
                        text: 'Download',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('report-dlgd-download?status=2') }}";
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
    @include('po-tracking.master.modal.DetailPO')
    @include('po-tracking.master.modal.DetailGR')
@endsection
@endsection
