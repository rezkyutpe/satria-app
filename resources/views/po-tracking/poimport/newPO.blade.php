@extends('po-tracking.panel.master')
@section('content')


<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $header_title }}</h2>
                    <div class="clearfix"></div>
                </div>

                @include('po-tracking.panel.cardmenu_poimport')

                {{-- Alert --}}
                <div>
                    @if(session()->has('err_message'))
                    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('err_message') }}
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
                <div class="x_content">

                    {{-- Search --}}
                    @include('po-tracking.panel.search')
                    {{-- End Search --}}

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <table id="datatable-visibility" class="table nowrap table-bordered text-center table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Item ID</th>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            @if ($actionmenu->c== 1 || $actionmenu->u== 1 || $actionmenu->r== 1 )
                                            <th>Price&nbsp;&frasl;<sub>pcs</sub>&nbsp;</th>
                                            {{-- <th>Price/pcs (IDR)</th> --}}
                                            @endif
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

                                                $data_id        = $item->ID;
                                                $data_parent    = $item->ParentID;
                                                if ($actionmenu->c == 1 || $actionmenu->r == 1) {
                                                    $confirm_class  = "confirmpo";
                                                    $progress       = "Confirm PO";
                                                    $color          = "primary";
                                                    }else{
                                                    $progress       = "Confirm PO";
                                                    $color          = "secondary";
                                                    $confirm_class  = "#";
                                                    }
                                                    $data_id        = $item->ID;
                                                    if ($item->ActiveStage == 1) {
                                                        if ($item->IsClosed == 'L' || $item->IsClosed == 'C') {
                                                            $confirm_class = "cancelPOhide";
                                                            $color          = "danger";
                                                            $progress       = "Canceled";
                                                        }else if($actionmenu->v == 1 || $actionmenu->d == 1){
                                                            $confirm_class = "#";
                                                            $color          = "secondary";
                                                            $progress       = "Negotiated";

                                                        }else {
                                                            $confirm_class = "negosiasi";
                                                            $color          = "warning";
                                                            $progress       = "Negotiated";
                                                            $data_id        = $item->ParentID;
                                                        }
                                            }
                                            $price=number_format($item->NetPrice , 2, '.', '.');
                                            @endphp

                                            <tr class="baseBlock">
                                                <td class="po_number">
                                                    <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor="{{ $item->Vendor }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                                                </td>
                                                <td>{{ $item->ItemNumber }}</td>
                                                <td>{{ $item->Material }}</td>
                                                <td class="text-left">{{ $item->Description }}</td>
                                                <td>{{ $item->Quantity }}</td>
                                                @if ($actionmenu->c== 1 || $actionmenu->u== 1  || $actionmenu->r== 1)
                                                <td>
                                                    <div style="float:left;">{{$item->Currency}}</div>
                                                    <div style="float:right;">{{ $price }}</div>
                                                </td>
                                                @endif
                                                <td>{{ $datess   }}</td>
                                                <td>
                                                    <a href="#" class="{{ $confirm_class }} badge badge-{{ $color }}" data-toggle="modal"  data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}">
                                                        {{ $progress }}
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
    </div>
</div>

@endsection

@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

    @include('po-tracking.panel.modaldetailPO')
       {{-- Modal confirmpo --}}
    @include('po-tracking.panel.view_confirmpo')

    {{-- Modal Negosiasi PO --}}
    @include('po-tracking.panel.view_negosiasipo')

    {{-- Modal Cancel PO --}}
    @include('po-tracking.poimport.modal.cancel_newpo')

@endsection

