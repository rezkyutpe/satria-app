@extends('po-tracking.panel.master')
@section('content')

<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $header_title }}</h2>
                    <ul class="nav navbar-right panel_toolbox pl-5">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="x_content">


                {{-- Search --}}

                 {{-- Alert --}}
                @include('po-tracking.panel.cardmenu')

                <div>
                    @if(session()->has('err_message'))
                    <div class="alert alert-danger" role="alert" auto-close="5000">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="alert-heading">Warning !!</h4>
                          <hr>
                        <p class="mb-0">{{ session()->get('err_message') }}</p>

                    </div>
                    @endif
                    @if(session()->has('suc_message'))
                        <div class="alert alert-success alert-dismissible" role="alert" auto-close="5000">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session()->get('suc_message') }}
                        </div>
                    @endif
                </div>

                {{-- End Alert --}}
                {{-- End Search --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive" class="table table-bordered text-center table-hover" style="width:100%">

                                <thead>
                                    <tr>
                                        <th>Vendor Name</th>
                                        <th>PO Number</th>
                                        <th>PO Number OLD</th>
                                        <th>Item ID</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Delivery Date SAP</th>
                                        <th>Delivery Date Agreed</th>

                                        <th>Progress</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $k=> $item)
                                        @php

                                            $source         =  $item->Date ;
                                            $sources        =  $item->DeliveryDate ;
                                            $release_date   =  $item->ReleaseDate ;
                                            $release        = new DateTime($release_date);
                                            $date           = new DateTime($source);
                                            $dates          = new DateTime($sources);

                                            if ($item->DeliveryDate== null) {
                                                $datess = "-";
                                            }else{
                                                $datess = $dates->format('d/m/Y') ;
                                            }
                                            if ($item->ReleaseDate== null) {
                                               $releases = "-";
                                            }else{
                                                $releases = $release->format('d/m/Y') ;
                                            }
                                            if ($item->ConfirmedDate== null) {
                                                $confirmed_date = "-";
                                            }else{
                                                $confirmed_date = new DateTime($item->ConfirmedDate);
                                                $confirmed_date = $confirmed_date->format('d/m/Y');
                                            }

                                                $progress       = "Confirm PO";
                                                $color          = "secondary";
                                                $confirm_class  = "#";

                                            $data_id        = $item->ID;
                                            if ($item->ActiveStage == 1) {
                                                if ($item->IsClosed == 'L' || $item->IsClosed == 'C') {
                                                    $confirm_class = "cancelPOhide";
                                                    $color          = "danger";
                                                    $progress       = "Canceled";
                                                 }else {
                                                    $confirm_class = "#";
                                                    $color          = "secondary";
                                                    $progress       = "Negotiated";
                                                }
                                            }

                                        @endphp
                                        <tr class="baseBlock">
                                            <td>{{ $item->Vendor }}</td>
                                            <td class="po_number">
                                                <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor-code-new="<?= $item->VendorCode_new ?>" data-vendor="{{ $item->Vendor }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                                            </td>
                                            <td>{{ $item->Number_old == null ? '-' : $item->Number_old }}</td>
                                            <td>{{ $item->ItemNumber }}</td>
                                            <td class="text-left">{{ $item->Material }}</td>
                                            <td class="text-left">{{ $item->Description }}</td>
                                            <td>{{ $item->Quantity }}</td>

                                            <td data-order="{{$item->DeliveryDate}}">{{ $datess }}</td>
                                            <td data-order="{{$item->ConfirmedDate}}">{{ $confirmed_date }}</td>

                                            <td>
                                                <a href="#" class="{{ $confirm_class }} badge badge-{{ $color }}" data-toggle="modal"  data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}">
                                                    {{ $progress }}
                                                </a>
                                            </td>
                                            <td>
                                                      <a data-toggle="tooltip" title="Chat" class="btn btn-success btn-sm" onclick="getChat('{{$item->Number}}','{{$item->ItemNumber}}')" style="text-decoration:none;color:black">
                                                        <i class="fa fa-comment fa-lg custom--1"></i></a>
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



@section('top_javascript')

    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
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
                    'pageLength','colvis','copy','pdf', 'print',

                    {
                        text: 'Excel',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('po-open-download/polocalnewpo/2') }}";
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

    @include('po-tracking.panel.modaldetailPO')

@endsection

