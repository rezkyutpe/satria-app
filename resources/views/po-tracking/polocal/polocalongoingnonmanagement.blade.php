@extends('po-tracking.panel.master')
@section('content')


<style>
h6{
    font-size: 0.9rem;
}

</style>
<div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ $header_title }}</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
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

            @include('po-tracking.panel.cardmenu')


      @if(session()->has('err_message'))
            <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session()->get('err_message') }}
            </div>
        @endif
        @if(session()->has('suc_message'))
            <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000" >
                <button type="button"  class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session()->get('suc_message') }}
            </div>
        @endif
     <form method="post" action="{{url('skip-proforma')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
      <div class="row">
        <div class="col-sm-12">

             <div class="card-box table-responsive">
                <table id="datatable-responsive" class="table table-striped table-bordered" style="width:100%">

                    <thead>
                        <tr>
                            <th>Vendor Name</th>
                            <th>PO Number</th>
                            <th>PO Number OLD</th>
                            <th>PO Item</th>
                            <th>Material</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Delivery Date SAP</th>
                            <th>Delivery Date Agreed</th>
                            <th>Progress</th>
                            <th style="width: 60px;">Action</th>
                        </tr>
                  </thead>
                  <tbody>
                @foreach ($data as  $item)
                    <tr  class="baseBlock">
                    @php
                        $sisair = $item->totalgr - $item->totalir ;
                        $PoDate         = $item->Date ;
                        $confirmedDate  = $item->ConfirmedDate;
                        $releaseDate    = $item->ReleaseDate ;
                        $deliverydate    = $item->DeliveryDate ;
                        $release        = new DateTime($releaseDate);
                        $confirmed      = new DateTime($confirmedDate);
                        $delivery      = new DateTime($deliverydate);
                        $date           = new DateTime($PoDate);
                        if ( $item->ReleaseDate == null) {
                            $releases = "-";
                         }else{
                            $releases = $release->format('d/m/Y') ;
                        }

                        if ($item->ConfirmedDate == null) {
                            $confirmeds = "-" ;
                        }else{
                            $confirmeds = $confirmed->format('d/m/Y') ;
                        }
                        if ($item->DeliveryDate == null) {
                            $delivery_date      = "-";
                        }else{
                            $delivery_date      = new DateTime($deliverydate);
                            $delivery_date      = $delivery_date->format('d/m/Y');
                        }
                        $temp           = 0;

                    @endphp

                        <td>{{ $item->Vendor }}</td>
                        <td class="text-left po_number">
                            <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor-code-new="<?= $item->VendorCode_new ?>" data-vendor="{{ $item->Vendor }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                        </td>
                        <td>{{ $item->Number_old == null ? '-' : $item->Number_old }}</td>
                        <td>{{ $item->ItemNumber }}</td>
                        <td class="material">{{ $item->Material }}</td>
                        <td>{{ $item->Description }}</td>
                        <td><a href="javascript:void(0);" class="text-dark viewgr" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}" style="cursor: pointer;"> {{ $item->totalgr }} / {{ $item->ActualQuantity ?? $item->Quantity }} </a>
                        </td>
                        <td data-order="{{$item->DeliveryDate}}">{{$delivery_date}}</td>
                        <td data-order="{{$item->ConfirmedDate}}">{{$confirmeds}}</td>
                        <td>
                                @php
                                    $activeStage    = $item->ActiveStage;
                                    $total    = $item->totalticket;
                                    $qty    = $item->ActualQuantity;
                                    $openqty    = $item->OpenQuantity;

                                    if ( $activeStage == '2a' && $qty != $item->totalgr) {
                                            $class      = 'cekpo';
                                            $text       = 'Wait Vendor Proforma Invoice';
                                            $badge      = 'badge-secondary';
                                    }elseif ($activeStage == 2 && $qty != $item->totalgr) {
                                            $class      = 'cekpo';
                                            $text       = 'Wait Approve Procurement';
                                            $badge      = 'badge-secondary';
                                    }elseif ($activeStage == 3) {
                                            $class      = 'cekpo';
                                            $text       = 'Wait Procurement Approve Proforma';
                                            $badge      = 'badge-secondary';

                                    }elseif ($activeStage == 4 && $total == null && $qty == $item->totalgr ) {
                                            $text       = 'Ticket Full';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }elseif($activeStage == 4 && $total == null && $qty == $openqty ) {
                                            $text       = 'Wait Vendor Create Ticket ';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }elseif ($activeStage == 4 && $total == null && $qty > $item->openqty) {
                                            $text       = 'Wait Vendor Create Ticket ';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }elseif ($activeStage == 4 && $total < $qty ) {
                                            $text       = 'Vendor Ticket On';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }elseif ($activeStage == 4 && $total == $qty ) {
                                            $text       = 'Vendor Ticket Full';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }elseif ( $total == $openqty ) {
                                            $text       = 'Vendor Ticket Full';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }elseif ( $item->totalgr == $qty ) {
                                            $text       = 'Vendor Ticket Full';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }elseif ( $item->totalgr < $qty ) {
                                            $text       = 'Vendor Ticket ON';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }else{
                                            $text       = 'Kosong';
                                            $class      = 'cekpo';
                                            $badge      = 'badge-secondary';
                                    }
                                @endphp

                            <a href="#" class="{{ $class }} badge {{ $badge }}" data-toggle="modal" data-id="{{ $item->ID }}" data-number="{{ $item->Number }}">{{ $text }}</a>
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
       </form>
    </div>
    </div>
</div>

@section('top_javascript')

    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                dom             : 'Bfrtip',
                fixedHeader     : true,
                scrollCollapse  : true,
                columnDefs: [{
                    visible     : false
                }],
                buttons: [
                        'pageLength','colvis','copy','pdf', 'print',

                        {
                            text: 'Excel',
                            action: function ( e, dt, node, config ) {
                                window.location.href = "{{ url('po-open-download/polocalongoing/2') }}";
                            }
                        }

                    ],
                select                          : true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });
        });
    </script>
    @include('po-tracking.panel.modaldetailPO')
    {{-- Modal view_gr --}}
    @include('po-tracking.panel.view_detailgr')
@endsection


@endsection


