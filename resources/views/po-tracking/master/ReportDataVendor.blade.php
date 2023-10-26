@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Report Kelengkapan Data Vendor
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
                    <div class="well" >
                        <form method="get" action="kelengkapan-data-vendor">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-1 mt-2">
                                        <strong>SearchBy Type:</strong>
                                    </div>
                                    <div class="col-md-5">
                                        
                                        <select name="searchbytype" class="form-select">
                                            <option value="">All Data</option>
                                            <option value="Vendor Local">Vendor Local</option>
                                            <option value="Vendor Import">Vendor Import</option>
                                            <option value="Vendor SubCont">Vendor SubCont</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="col-md-5">
                                    </div>
                                    <div class="col-md-5">
                                        <button type="submit" class="btn btn-primary">Search</a>
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
                                <thead>
                                    <tr>
                                        <th>Vendor Code</th>
                                        <th>Vendor Code New</th>
                                        <th>Vendor Name</th>
                                        <th>Vendor Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendor as $k => $item)
                                        <tr>
                                            <td>{{ $item->VendorCode }}</td>
                                            <td>{{ $item->VendorCode_new }}</td>
                                            <td>{{$item->Name}}</td>
                                            <td>{{$item->VendorType}}</td>
                                            <td> 
                                                @php
                                                    $class='';
                                                @endphp
                                                @switch($item->status)
                                                    @case('Data Lengkap')
                                                        @php
                                                            $class='success';
                                                        @endphp
                                                    @break
                                                    @case('Data Belum Lengkap')
                                                        @php
                                                            $class='danger';
                                                        @endphp
                                                    @break
                                                @endswitch
                                                <a rel="tooltip" title="Detail Vendor" name="vendor"  href="{{url('detail-datavendor/'.$item->VendorCode)}}" style="font-size: 12px;" class="btn btn-{{$class}}">{{ $item->status}}</a>
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
@endsection

@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                dom: 'Bfrtip',
                fixedHeader: true,
                scrollCollapse: true,
                ordering: false,
                buttons: [
                    'pageLength', 'colvis',
                    <?php if ($action_menu->r==1 ){ ?> {
                        text: 'Download',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ url('report-vndata-download') }}";
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
@endsection

