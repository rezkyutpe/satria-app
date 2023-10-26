@extends('po-tracking.panel.master')
@section('content')

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Report PO Cancel

                </h2>
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
            <div class="x_content">
                <div class="well" style="overflow: auto;">
                    <form action="{{url('report-po-cancel')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>Month :</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="month" class="form-control select2">
                                        <option value=""></option>
                                        <?php
                                        for($i = 1; $i < 13; $i++){
                                        echo "<option >$i</option>" ;
                                        }
                                    ?>
                               </select> </div>
                                <div class="col-md-1">
                                    <strong>Years :</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="years" class="form-control select2">
                                        <option></option>
                                            <?php
                                            for($i = date('Y') ; $i >= 2019; $i--){
                                            echo "<option >$i</option>" ;
                                            }
                                        ?>
                                   </select>
                                 </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>PONumber:</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="ponumber[]"  class="form-control select2" multiple="multiple" style="width: 100%">
                                        <option value="" readonly></option>
                                        @foreach ($ponumber as $item)
                                        <option value="{{ $item->Number }}">{{ $item->Number }}</option>
                                        @endforeach
                                    </select>
                                 </div>
                                <div class="col-md-1">
                                    <strong>CancelBy :</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="cancelby" class="form-control select2"  style="width: 100%">
                                        <option ></option>
                                        <option >SAP</option>
                                        <option >PO Tracking</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>Search By:</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="searchby" class="form-control select2">
                                        <option >Delivery Date</option>
                                        <option >PO Date</option>
                                        <option >PO ReleaseDate</option>
                                    </select>
                                 </div>

                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url('report-po-cancel?reset=1') }}" class="btn btn-danger">Reset</a>
                                </div>
                            </div>
                            </div>

                     </form>
                    </div>
                <div class="row">
                    <div class="card-box table-responsive">
                        <table id="datatable-responsive" class="table text-center table-striped table-bordered dt-responsive" cellspacing="0" width="99%">
                            <thead>
                                <tr>
                                <th>PO Number</th>
                                <th>Item Number</th>
                                <th>Material</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                  @foreach ($getData as $k=>$item)
                                  @php
                                            $source         =  $item->Date ;
                                            $release_date   =  $item->ReleaseDate ;
                                            $release        = new DateTime($release_date);
                                            $date           = new DateTime($source);

                                            if ($item->ReleaseDate== null) {
                                               $releases = "-";
                                            }else{
                                                $releases = $release->format('d/m/Y') ;
                                            }
                                  @endphp
                                    <tr>
                                        <td class="po_number">
                                            <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor="{{ $item->Name }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->CreatedBy }}" style="cursor: pointer;">{{ $item->Number }}</a>
                                        </td>
                                        <td>{{ $item->ItemNumber }}</td>
                                        <td>{{ $item->Material }}</td>
                                        <td>{{ $item->Description }}</td>
                                        <td>{{ $item->Quantity }}</td>
                                        <td>{{date('d/m/Y', strtotime($item->DeliveryDate))}}</td>
                                        @if ($item->IsClosed == 'L')
                                        <td>Canceled By SAP</td>
                                        @else
                                        <td style="background-color:#f73a4c; color:#fff">Canceled By PO Tracking</td>
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
    <script src="{{ asset('public/assetss/datatable/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
     {{-- Modal DetailPo  --}}
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
                    'pageLength',
                    <?php if ($action_menu->r==1 || $action_menu->u==1|| $action_menu->d==1|| $action_menu->v==1){ ?>
                    {
                        text: 'Download',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('report-po-cancel-download') }}";
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
        });
     </script>
     @include('po-tracking.panel.modaldetailPO')

@endsection
