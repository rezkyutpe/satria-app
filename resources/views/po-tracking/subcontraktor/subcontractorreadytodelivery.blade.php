@extends('po-tracking.panel.master')
@section('content')

<style>
h6{
    font-size: 0.9rem;
}
.contohPDF{
        display:inline-block;
        object-fit: cover;
        margin: 5px;
    }
    .contohPDF:hover {
        transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        -moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        -o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

</style>
        <div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ $header_title }}</h2>
        <ul class="nav navbar-right panel_toolbox">

          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>

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

                            <tr >
                                @if ($actionmenu->r==1 || $actionmenu->d==1 || $actionmenu->u==1 || $actionmenu->v==1)
                                <th>Vendor Name</th>
                                @endif
                                <th>PO Number</th>
                                <th>PO Item</th>
                                <th>Material</th>
                                <th>Description</th>
                                <th>PO Qty</th>
                                <th>ID Ticket</th>
                                <th>Ticket Qty</th>
                                <th>Delivery Date Agreed</th>
                                <th>Ticket Delivery Date</th>
                                <th>AcceptDate</th>
                                <th>SecurityDate</th>
                                <th>WarehouseDate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as  $item)
                            <tr class="baseBlock">
                                @php
                                    $PoDate         = $item->Date ;
                                    $DeliveryDateSAP  = $item->DeliveryDates;
                                    $confirmedDate  = $item->ConfirmDeliveryDate;
                                    $deliveryDate   = $item->DeliveryDate ;
                                    $releaseDate    = $item->ReleaseDate ;
                                    $acceptedate = $item->AcceptedDate ;
                                    $securitydate = $item->SecurityDate ;
                                    $warehousedate = $item->WarehouseDate ;
                                if ($item->SecurityDate == null) {
                                    $security = "" ;
                                }
                                else {
                                        $securitys =  new DateTime($securitydate) ;
                                        $security = $securitys->format('d/m/Y H:i:s');
                                }
                                if ($item->WarehouseDate == null) {
                                    $warehouse ="";
                                }
                                else {
                                        $warehouses =  new DateTime($warehousedate) ;
                                        $warehouse = $warehouses->format('d/m/Y H:i:s');
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
                                if ($item->POQuantity== 0) {
                                        $qtysap = $item->QtySAP;
                                }else{
                                        $qtysap = $item->POQuantity ;
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
                                $release        = new DateTime($releaseDate);
                                $confirmed      = new DateTime($confirmedDate);
                                $deliverydates      = new DateTime($DeliveryDateSAP);
                                $date           = new DateTime($PoDate);
                                $dates          = new DateTime($deliveryDate);

                                $accept          = new DateTime($acceptedate);
                                if ($item->DeliveryDates == null) {
                                    $confirmeddates = $confirmed->format('d/m/Y') ;
                                }else{
                                    $confirmeddates = $deliverydates->format('d/m/Y') ;
                                }
                                if ($item->ReleaseDate== null) {
                                    $releases = "-";
                                }else{
                                    $releases = $release->format('d/m/Y') ;
                                }
                            @endphp
                               @if ($actionmenu->r==1 || $actionmenu->d==1 || $actionmenu->u==1 || $actionmenu->v==1)
                               <td>{{ $item->Name }}</td>
                                @endif
                                <td class="text-left po_number">
                                    <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor="{{ $item->Name }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                                </td>
                                <td>{{ $item->ItemNumber }}</td>
                                <td class="text-left">{{ $item->Material }}</td>
                                <td class="text-left">{{ $item->Description }}</td>
                                <td>{{ $item->POQuantity }}</td>
                                <td><a href="javascript:void(0);" class="text-dark btn-edit view_ticket" data-toggle="modal" data-id="{{ $item->ID }}"  style="cursor: pointer;">{{ $item->TicketID }}</a></td>
                                <td>{{ $item->Quantity }}</td>
                                <td >{{ $confirmeddates }}</td>
                                <td >{{ $dates->format('d/m/Y H:i:s') }}</td>
                                <td >{{ $accept->format('d/m/Y H:i:s') }}</td>
                                <td >{{  $security }}</td>
                                <td >{{ $warehouse}}</td>
                                <td>
                                    @if ($item->status == 'A' || $item->status == 'D')
                                    @if ($actionmenu->c==1 || $actionmenu->r==1)
                                    <form method="post" action="{{ url('subcontractorcheckticket') }}" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <input type="hidden"  name="TicketID" class="checkItem" value="{{ $item->TicketID }}">
                                        <button type="submit" class="badge badge-primary" formtarget="_blank" >Cek Ticket</button>
                                    </form>
                                    @else
                                    <span class="badge badge-danger"> On Delivery</span>
                                    @endif
                                    @elseif ($item->status == 'S')
                                    <span class="badge badge-danger"> In Security</span>
                                    @elseif ($item->status == 'W')
                                    <span class="badge badge-success">  At Warehouse</span>
                                    @elseif ($item->status == 'R')
                                    <span class="badge badge-success">  On Progress Parking</span>
                                    @elseif ($item->status == 'X')
                                    <span class="badge badge-success">  Ticket Close</span>
                                    @elseif ($item->status =='Q' && ($actionmenu->c==1||$actionmenu->r==1) )
                                    <a href="#" class="badge badge-warning parkinginvoice" data-toggle="modal" data-id="{{ $item->ID }}">Parking Invoice</a>
                                    @elseif (($actionmenu->r== 1 || $actionmenu->u== 1 || $actionmenu->v== 1) && $item->status == 'Q')
                                   <span class="badge badge-success">  Waiting Parking Vendor</span>


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



@endsection

@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    {{-- detailpo --}}
    @include('po-tracking.panel.modaldetailPO')
    {{-- Modal proforma_invoicel
    @include('po-tracking.subcontraktor.modal.parking_invoice') --}}
     {{-- Modal View_ticket  --}}
     @include('po-tracking.subcontraktor.modal.view_ticket')
@endsection
