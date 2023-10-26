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
                {{-- Alert --}}
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- End Alert --}}

                    @include('po-tracking.panel.cardmenu_subcontractor')


                    <div>
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
                    </div>

                {{-- End Search --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive" class="table nowrap table-bordered text-center table-hover" style="width:100%">
                                @php
                                    $posisi= strpos (Auth::user()->title,"Department Head");
                                    if ($posisi){
                                        $status = 1 ;
                                    }
                                    else {
                                        $status = 2;
                                    }
                               @endphp
                                <thead>
                                    <tr >
                                        <th>PO Number</th>
                                        <th>PO Date</th>
                                        <th>PO Item</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Currency</th>
                                        <th>Price&nbsp;&frasl;<sub>pcs</sub>&nbsp;</th>
                                        <th>Delivery Date</th>
                                        <th>Progress</th>
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
                                            $confirm_class  = "#";
                                            $progress       = "Done";
                                            $color          = "success";
                                            $data_id        = $item->ID;

                                                if ($item->IsClosed == 'L') {
                                                    $color          = "danger";
                                                    $progress       = "Canceled By SAP";
                                                }else if($item->IsClosed == 'C'){
                                                    $confirm_class = "cancelPOhide";
                                                    $color          = "danger";
                                                    $progress       = "Canceled";
                                                    $type = "text";
                                                }
                                                if ($item->ConfirmedQuantity == null) {
                                                    $qty = $item->Quantity ;
                                                }else{
                                                    $qty = $item->ConfirmedQuantity ;
                                                }


                                            if ($item->ReleaseDate== null) {
                                                $releases = "-";
                                            }else{
                                                $releases = $release->format('d/m/Y') ;
                                            }
                                            $priceIDR=number_format(substr($item->NetPrice, 0),0,',','.');
                                        @endphp
                                        <tr class="baseBlock">
                                            {{-- <td>{{ $k+1 }}</td> --}}
                                            <td class="po_number">
                                                <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor-code-new="<?= $item->VendorCode_new ?>" data-vendor="{{ $item->Vendor }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                                            </td>
                                            <td class="po_date">{{ $date->format('d/m/Y') }}</td>
                                            <td>{{ $item->ItemNumber }}</td>
                                            <td class="text-left">{{ $item->Material }}</td>
                                            <td class="text-left">{{ $item->Description }}</td>
                                            @if ($item->IsClosed == 'C' && $item->ActiveStage == 1)
                                            <td><a href="javascript:void(0);" class="text-dark viewgr" data-toggle="modal"  data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}"  style="cursor: pointer;">  {{ $qty }} </a></td>
                                            @else
                                            <td><a href="javascript:void(0);" class="text-dark viewgr" data-toggle="modal"  data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}"  style="cursor: pointer;"> {{ $item->totalgr }} / {{ $item->Quantity }} </a></td>
                                            @endif
                                            <td>
                                                <div>{{$item->Currency}}</div>
                                            </td>
                                            <td>
                                                <div style="float:right;">{{ $priceIDR }}</div>
                                            </td>

                                            <td>{{ $dates->format('d/m/Y') }}</td>
                                            <td>
                                                @if ($actionmenu->u== 1 )
                                                <form method="post" action="{{url('poreturn-subcont')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="ID" value="{{ $item->ID }}">
                                                    <input type="hidden" name="POID" value="{{ $item->POID }}">
                                                    @if ($item->IsClosed == 'C')
                                                    <button type="submit" style="color: #fff; border:#fff" class="badge badge-{{ $color }}"  onclick="return confirm('Apakah anda Yakin Ingin Reverse PO ini ?');">{{ $progress }}</button>
                                                    @elseif ($item->IsClosed == 'L')
                                                    <span style="color: #fff; border:#fff" class="{{ $confirm_class }} badge badge-{{ $color }}">{{ $progress }}</span>
                                                    @else
                                                    <span style="color: #fff; border:#fff" class="{{ $confirm_class }} badge badge-{{ $color }}">{{ $progress }}</span>
                                                    @endif
                                                </form>
                                                @else
                                                <p type="submit" style="color: #fff; border:#fff" class="{{ $confirm_class }} badge badge-{{ $color }}"  >{{ $progress }}</p>

                                                @endif

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
                            window.location.href = "{{ url('po-open-download/subcontractorhistory/1') }}";
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
    @include('po-tracking.panel.view_detailgrhistory')
    {{-- Modal detailgr  --}}
@endsection


