@extends('po-tracking.panel.master')
@section('content')
<div class="clearfix"></div>
<div class="row">
    <style>
        h6{
            font-size: 14px;
            margin-left: 10px;
        }
    </style>
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Log History User</h2>

        <div class="clearfix"></div>
      </div>
      @if(session()->has('err_message'))
            <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session()->get('err_message') }}
            </div>
        @endif
        @if(session()->has('suc_message'))
            <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session()->get('suc_message') }}
            </div>
        @endif
          <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <div class="x_content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <div class="card baseBlock h-75" style="cursor:pointer; background: rgb(139, 7, 2);">
                                        <div class="card-body text-white">
                                            <div class="media d-flex" style="position: relative">
                                                <div class="media-body text-left">
                                                    <h5 style="font-weight: bold;">User Internal</h5>
                                                    <h3><div style="display: inline-block; font-size:1rem ;margin-left: 3px;">User Active / Days</div>  {{ $datainternalactive }} <div style="display: inline-block; font-size:1rem ;margin-left: 3px;">User Active / Month</div> {{ $datainternalmonth }} </h3>
                                                </div>
                                                <div class="cardicons">
                                                    <i class="fa fa-user fa-4x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card baseBlock h-75" style="background: #01116b;cursor:pointer">
                                        <div class="card-body text-white">
                                            <div class="media d-flex" style="position: relative">
                                                <div class="media-body text-left">
                                                    <h5 style="font-weight: bold;">User Vendor Local</h5>
                                                    <h3> <div style="display: inline-block; font-size:1rem;margin-left: 3px; ">User Active / Days</div> {{ $datalocalactive }}<div style="display: inline-block; font-size:1rem;margin-left: 3px; ">User Active / Month</div> {{ $datalocalmonth }} </h3>

                                                </div>
                                                <div class="cardicons">
                                                <i class="fa fa-users fa-4x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card baseBlock h-75" style="background: rgb(10, 110, 1);cursor:pointer">
                                        <div class="card-body text-white">
                                            <div class="media d-flex" style="position: relative">
                                                <div class="media-body text-left">
                                                    <h5 style="font-weight: bold;">User Vendor Subcont</h5>
                                                    <h3><div style="display: inline-block; font-size:1rem;margin-left: 3px; ">User Active / Days</div> {{ $datasubcontactive }} <div style="display: inline-block; font-size:1rem;margin-left: 3px; ">User Active / Month</div> {{ $datasubcontmonth }} </h3>

                                                </div>
                                                <div class="cardicons">
                                                    <i class="fa fa-users fa-5x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#internal" role="tab" aria-controls="home" aria-selected="true">History User Internal</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#external" role="tab" aria-controls="profile" aria-selected="false">History User External</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active " id="internal" role="tabpanel">
                                        <table id="datatable-responsive-internal"class="table text-center table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>User Code</th>
                                                    <th>User Name</th>
                                                    <th>User Login Type</th>
                                                    <th>Menu</th>
                                                    <th>Access Name</th>
                                                    <th>PO NO</th>
                                                    <th>PO Item</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($datainternal as $k=>$item)
                                                    <tr>
                                                        <td>{{ $k+1 }}</td>
                                                        <td>{{ $item->user }}</td>
                                                        <td>{{ $item->CreatedBy }}</td>
                                                        <td>{{ $item->userlogintype }}</td>
                                                        <td>{{ $item->menu }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td>{{ $item->ponumber }}</td>
                                                        <td>{{ $item->poitem }}</td>
                                                        <td>{{ $item->date }}</td>
                                                        <td>{{ $item->time }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                        </table>
                                </div>
                                <div class="tab-pane fade" id="external" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="tab-pane " id="external" role="tabpanel">
                                        <table id="datatable-responsive-external" class="table text-center table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>User Code</th>
                                                    <th>User Name</th>
                                                    <th>Vendor Type</th>
                                                    <th>Menu</th>
                                                    <th>Access Name</th>
                                                    <th>PO NO</th>
                                                    <th>PO Item</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataexternal as $k=>$item)
                                                    <tr>
                                                        <td>{{ $k+1 }}</td>
                                                        <td>{{ $item->user }}</td>
                                                        <td>{{ $item->CreatedBy }}</td>
                                                        <td>{{ $item->vendortype }}</td>
                                                        <td>{{ $item->menu }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td>{{ $item->ponumber }}</td>
                                                        <td>{{ $item->poitem }}</td>
                                                        <td>{{ $item->date }}</td>
                                                        <td>{{ $item->time }}</td>
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
    </div>
</div>
</div>
@endsection
@section('top_javascript')
<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable-responsive-internal').DataTable({
            dom: 'Bfrtip',
            fixedHeader                     : true,
            scrollCollapse                  : true,
            ordering                        : false,
            columnDefs: [{
                visible                     : false
            }],
            buttons: [
                'pageLength','colvis',
                {
                    text: 'Download',
                    action: function ( e, dt, node, config ) {

                        window.location.href = "{{ url('report-loghistory-download/1') }}";
                    }
                }
            ],
            select                          : true,
            stateSave: true,
            drawCallback: function(settings) {
                $(".right_col").css("min-height", "");
                $(".child_menu").css("display", "none");
                $("#sidebar-menu li").removeClass("active");
            },
        });
        $('#datatable-responsive-external').DataTable({
            dom: 'Bfrtip',
            fixedHeader                     : true,
            scrollCollapse                  : true,
            ordering                        : false,
            columnDefs: [{
                visible                     : false
            }],
            buttons: [
                'pageLength','colvis',
                {
                    text: 'Download',
                    action: function ( e, dt, node, config ) {
                        window.location.href = "{{ url('report-loghistory-download/2') }}";
                    }
                }
            ],
            select                          : true,
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
