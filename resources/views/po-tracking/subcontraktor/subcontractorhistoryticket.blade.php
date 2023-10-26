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

          <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">
                   <table id="datatable-visibility" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                        <th>PO Number</th>
                        <th>PO Item</th>
                        <th>Material</th>

                        <th>Qty</th>
                        <th>ID Ticket</th>
                        <th>DeliveryDate</th>
                        <th>AcceptDate</th>
                        <th>SecurityDate</th>
                        <th>WarehouseDate</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as  $item)
                        <tr class="baseBlock">
                            @php
                            $PoDate         = $item->Date ;
                            $confirmedDate  = $item->ConfirmDeliveryDate;
                            $deliveryDate   = $item->DeliveryDate ;
                            $releaseDate    = $item->ReleaseDate ;
                            $acceptedate = $item->AcceptedDate ;
                            $securitydate = $item->SecurityDate ;
                            $warehousedate = $item->WarehouseDate ;


                            $warehouse = $item->WarehouseDate ;
                            $release        = new DateTime($releaseDate);
                            $confirmed      = new DateTime($confirmedDate);
                            $date           = new DateTime($PoDate);
                            $dates          = new DateTime($deliveryDate);
                            $accept          = new DateTime($acceptedate);
                            $security =  new DateTime($securitydate);
                            $warehouse =  new DateTime($warehousedate);
                            if ($item->ReleaseDate== null) {
                                    $releases = "-";
                            }else{
                                    $releases = $release->format('d/m/Y') ;
                            }
                            if ($item->Number== null) {
                                    $number = $item->Numbers;
                            }else{
                                    $number =$item->Number;
                            }
                            if ($item->ItemNumber== null) {
                                    $itemnumber = $item->ItemNumbers;
                            }else{
                                    $itemnumber = $item->ItemNumber ;
                            }
                            if ($item->Material== null) {
                                    $material = $item->Materials;
                            }else{
                                    $material = $item->Material ;
                            }
                            if ($item->Description== null) {
                                    $description = $item->Descriptions;
                            }else{
                                    $description = $item->Description ;
                            }

                            if ($item->Quantity== null) {
                                    $releases = "-";
                            }else{
                                    $releases = $release->format('d/m/Y') ;
                            }
                            if ($item->Description== null) {
                                    $releases = "-";
                            }else{
                                    $releases = $release->format('d/m/Y') ;
                            }
                            if ($item->SecurityDate == null) {
                                $securitys = "-" ;
                            }else{
                                $securitys = $security->format('d/m/Y') ;
                            }
                            if ($item->WarehouseDate == null) {
                                $warehouses = "-" ;
                            }else{
                                $warehouses = $warehouse->format('d/m/Y') ;
                            }
                        @endphp
                            <td class="text-left po_number">
                              <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor="{{ $item->Name }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}" style="cursor: pointer;">{{ $number }}</a>
                            </td>
                            <td>{{ $itemnumber }}</td>
                            <td class="text-left" data-toggle="tooltip" title="{{ $description }}">{{ $material }}</td>
                            <td>{{ $item->Quantity }}</td>
                            <td><a href="javascript:void(0);" class="text-dark btn-edit view_ticket" data-toggle="modal" data-id="{{ $item->ID }}"  style="cursor: pointer;">{{ $item->TicketID }}</a></td>
                            <td class="po_date">{{ $dates->format('d/m/Y') }}</td>
                            <td class="po_date">{{ $accept->format('d/m/Y h:i:s') }}</td>
                            <td class="text-center">{{ $securitys }}</td>
                            <td class="text-center">{{ $warehouses }}</td>
                            <td>
                                @if ($item->status == 'A')
                                <span class="badge badge-success">Delivery Ready</span>
                                @elseif ($item->status == 'C')
                                <span class="badge badge-danger"  data-toggle="tooltip" title="{{ $item->remarks }}">Ticket Cancel </a> </span>
                                @elseif ($item->status == 'D')
                                <span class="badge badge-warning">On Delivery</span>
                                @elseif ($item->status == 'S')
                                <span class="badge badge-dark">Security</span>
                                @elseif ($item->status == 'R')
                                <span class="badge badge-primary">Parking</span>
                                @elseif ($item->status == 'X')
                                <span class="badge badge-secondary">Ticket Close</span>
                                @elseif ($item->status == 'W')
                                <span class="badge badge-primary">Warehouse</span>
                                @elseif ($item->status == 'Q')
                                <span class="badge badge-info">QC</span>
                                @elseif ($item->status == 'E')
                                <span class="badge badge-outline-danger">Ticket Expired</span>
                                @endif
                            </td>
                            <td>
                                {{ $item->remarks }}
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
    {{-- detailpo --}}
    @include('po-tracking.panel.modaldetailPO')
    {{-- Modal proforma_invoicel  --}}
    {{-- @include('po-tracking.polocal.modal.parking_invoice') --}}
    {{-- Modal View_ticket  --}}
    @include('po-tracking.subcontraktor.modal.view_ticket')
@endsection
