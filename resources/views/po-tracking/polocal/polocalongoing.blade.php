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
     <form method="post" action="{{url('skip-proformalocal')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
      <div class="row">
        <div class="col-sm-12">
            @if ($actionmenu->u==1)
            <input type="submit" class="btn btn-danger" value="Skip Proforma" >
            @endif
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
                        <th>Currency</th>
                        <th>Price&nbsp;&frasl;<sub>pcs</sub>&nbsp;</th>
                        <th>Delivery Date SAP</th>
                        <th>Delivery Date Agreed</th>
                        <th>Progress</th>
                        <th style="width: 60px;">Action</th>
                        @if ( $actionmenu->u== 1 )
                        <th class="no-sort"><input type="checkbox" name="ID" class="checkItems" id="allpayments"></th>
                        @endif

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
                        $priceIDR=number_format(substr($item->NetPrice, 0),0,',','.');
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
                        <td>
                            <div>{{$item->Currency}}</div>
                        </td>
                        <td>
                            <div style="float:right;">{{ $priceIDR }}</div>
                        </td>
                        <td data-order="{{$item->DeliveryDate}}">{{$delivery_date}}</td>
                        <td data-order="{{$item->ConfirmedDate}}">{{$confirmeds}}</td>

                        <td>
                                @php
                                    $activeStage    = $item->ActiveStage;

                                if ($actionmenu->v== 1 && $activeStage == '2a' && $item->Quantity != $item->totalgr)
                                {
                                        $text       = 'Proforma Invoice';
                                        $class      = 'proformaInvoice';
                                        $badge      = 'badge-danger ';
                                }elseif ( $activeStage == '2a' && $item->Quantity != $item->totalgr) {
                                        $class      = 'cekpo';
                                        $text       = 'Wait Vendor Proforma Invoice';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->u== 1 && $activeStage == 2 && $item->Quantity != $item->totalgr) {
                                        $class      = 'proformaInvoice';
                                        $text       = 'Proforma';
                                        $badge      = 'badge-danger';
                                }elseif ($activeStage == 2 && $item->Quantity != $item->totalgr) {
                                        $class      = 'cekpo';
                                        $text       = 'Wait Approve Procurement';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->u== 1 && $activeStage == 3 && $item->Quantity != $item->totalgr) {
                                        $class      = 'proformaInvoice';
                                        $text       = 'Confirm Payment';
                                        $badge      = 'badge-primary';
                                }elseif ($activeStage == 3 && $item->Quantity != $item->totalgr) {
                                        $class      = 'cekpo';
                                        $text       = 'Wait Procurement Approve Proforma';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $activeStage == 4 && $item->totalticket == null && $item->Quantity == $item->totalgr ) {
                                        $text       = 'Ticket Full';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-danger';
                                }elseif ($activeStage == 4 && $item->totalticket == null && $item->Quantity == $item->totalgr ) {
                                        $text       = 'Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $activeStage == 4 && $item->totalticket == null && $item->Quantity ==  $item->OpenQuantity ) {
                                        $text       = 'Create Ticket';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-success';
                                }elseif($activeStage == 4 && $item->totalticket == null && $item->Quantity ==  $item->OpenQuantity ) {
                                        $text       = 'Wait Vendor Create Ticket ';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $activeStage == 4 && $item->totalticket == null && $item->Quantity > $item->openqty ) {
                                        $text       = 'Ticket ON';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-warning';
                                }elseif ($activeStage == 4 && $item->totalticket == null && $item->Quantity > $item->openqty) {
                                        $text       = 'Wait Vendor Ticket On ';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $activeStage == 4 &&  $item->totalticket < $item->Quantity ) {
                                        $text       = 'Ticket On';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-warning';
                                }elseif ($activeStage == 4 && $item->totalticket < $item->Quantity ) {
                                        $text       = 'Wait Vendor Ticket On';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $activeStage == 4 && $item->totalticket ==  $item->OpenQuantity ) {
                                        $text       = 'Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-danger';
                                }elseif ($activeStage == 4 && $item->totalticket == $item->Quantity ) {
                                        $text       = 'Vendor Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $item->totalgr == $item->Quantity ) {
                                        $text       = 'Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-danger';
                               }elseif ($item->totalgr == $item->Quantity ) {
                                        $text       = 'Vendor Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $item->totalticket == $item->Quantity ) {
                                        $text       = 'Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-danger';
                                }elseif ($item->totalticket == $item->Quantity ) {
                                        $text       = 'Vendor Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $item->totalticket ==  $item->OpenQuantity ) {
                                        $text       = 'Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-danger';
                                }elseif ($item->totalticket ==  $item->OpenQuantity ) {
                                        $text       = 'Vendor Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $item->totalgr == $item->Quantity ) {
                                        $text       = 'Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-danger';
                                }elseif ($item->totalgr == $item->Quantity ) {
                                        $text       = 'Vendor Ticket Full';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $item->totalgr == 0) {
                                        $text       = 'Create Ticket';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-success';
                                }elseif ($item->totalgr == 0 ) {
                                        $text       = 'Vendor Create Ticket';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-secondary';
                                }elseif ($actionmenu->v== 1 && $item->totalgr < $item->Quantity ) {
                                        $text       = 'Ticket ON';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-warning';
                                }elseif ($item->totalgr < $item->Quantity ) {
                                        $text       = 'Vendor Ticket ON';
                                        $class      = 'Ticketing';
                                        $badge      = 'badge-secondary';
                                }else{
                                       $text       = 'Kosong';
                                        $class      = 'cekpo';
                                        $badge      = 'badge-secondary';
                                }
                            @endphp

                            <a href="#" class="{{ $class }} badge {{ $badge }}" data-toggle="modal" data-id="{{ $item->ID }}" data-number="{{ $item->Number }}">{{ $text }}</a>
                            {{-- <a href="#"  class="invoicepayment badge badge-warning" data-toggle="modal" data-id="{{ $item->ID }}">Cek Po </a> --}}
                        </td>
                            <td>
                                <a href="{{ url("po-pdf/$item->Number/ongoing") }}" class="btn btn-danger btn-sm" formtarget="_blank" target="_blank" data-toggle="tooltip" title="PO File"><i class="fa fa-download"></i></a>

                                @if (!empty($item->ProformaInvoiceDocument))
                                    <a class="btn btn-info btn-sm" href="{{ url("downloadproforma/$item->ProformaInvoiceDocument") }}" target="_blank" data-toggle="tooltip" title="Proforma Invoice File"> <i class="fa fa-arrow-circle-down"></i></a>
                                @else

                                @endif
                                @if (!empty($item->ApproveProformaInvoiceDocument))
                                    <a class="btn btn-info btn-sm" href="{{ url("downloadproforma/$item->ApproveProformaInvoiceDocument") }}" target="_blank" data-toggle="tooltip" title="Payment Invoice File"> <i class="fa fa-arrow-circle-down"></i></a>
                                @else

                                @endif
                                @if ($actionmenu->u==1)
                                    <button type="button" name="action" class="btn btn-warning btn-sm exampleModalUpdate" data-id="{{ $item->ID }}" data-number="{{ $item->Number }}" data-itemnumber="{{ $item->ItemNumber }}" data-status="Update" data-qty="{{ $item->ActualQuantity ?? $item->Quantity }}" data-toggle="modal" ><i class="fa fa-edit"></i></button>
                                @endif
                                    @if($actionmenu->v== 1)
                                        @if($item->totalparking == $sisair && $item->totalparking != null &&  $item->Status == "Approve Parking")
                                        <a href="#" class="btn btn-success btn-sm parkinginvoice"  data-toggle="tooltip" title="Invoice Parking Approve"  data-id="{{ $item->Number }}" data-item="{{ $item->ItemNumber }}"><i class="fa fa-check-square"></i></a>
                                        @elseif ($item->totalparking == $sisair && $item->totalparking != null)
                                        <a href="#" class="btn btn-primary btn-sm parkinginvoice"  data-toggle="tooltip" title="Wait Approve Invoice"  data-id="{{ $item->Number }}" data-item="{{ $item->ItemNumber }}"><i class="fa fa-file-text-o"></i></a>
                                        @elseif ($item->totalgr > $item->totalir)
                                        <a href="#" class="btn btn-warning btn-sm parkinginvoice"  data-toggle="tooltip" title="Parking Invoice"  data-id="{{ $item->Number }}" data-item="{{ $item->ItemNumber }}"><i class="fa fa-file-text-o"></i></a>
                                        @endif
                                    @endif
                                <a data-toggle="tooltip" title="Chat" class="btn btn-success btn-sm" onclick="getChat('{{$item->Number}}','{{$item->ItemNumber}}')" style="text-decoration:none;color:black">
                                                            <i class="fa fa-comment fa-lg custom--1"></i></a>
                            </td>

                        @if ( $actionmenu->u== 1 )
                            @if ($item->ActiveStage==2)
                                <td>
                                <input type="checkbox" id="id"  name="Number[]" class="checkItems" value="{{ $item->Number }}">
                                <input type="hidden"  name="vendor" value="Local" >
                                </td>
                            @else
                                <td>
                                </td>
                            @endif
                        @endif
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
<div class="modal fade" id="Update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <span id="headers"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="{{ url('updatedata-local') }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <label for="">Qty</label> <input class="form-control" type="text" id="QTY" name="qty" autocomplete="off">
                    <input type="hidden" name="ID" id="ID">
                </div>
            </div>

            </div>
            <div class="modal-footer">
                <input type="submit"  class="btn btn-primary" value="Action">
            </div>
        </form>
      </div>
    </div>
</div>
{{-- <div class="modal fade" id="invoicepayments" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">

                <h6 text-center>Progress PO</h6>
              </div>
            <div class="modal-body text-center">
                    <div id="modalproforma">

                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Back </button>
           </div>
        </div>
    </div>
</div> --}}
<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        //edit data
        $('.exampleModalUpdate').on("click" ,function() {
        $('#headers').empty();
        var id = $(this).attr('data-id');
        var number = $(this).attr('data-number');
        var itemnumber = $(this).attr('data-itemnumber');
        var qty = $(this).attr('data-qty');
            $("#headers").append
                        (`
                        <h5 class="modal-title" id="exampleModalCenterTitle">Update Data PO `+number+` Item `+itemnumber+`</h5>
                       `);
            $('#ID').val(id);
            $('#QTY').val(qty);
            $('#Update').modal('show');
        });
        });
</script>
<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom             : 'Bfrtip',
            fixedHeader     : true,
            scrollCollapse  : true,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }],
            buttons: [
                    'pageLength','colvis','copy','pdf', 'print',

                    {
                        text: 'Excel',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('po-open-download/polocalongoing/1') }}";
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
<script>
    $(document).ready(function() {
        $('#allpayments').click(function(){

        if ($(this).is(':checked') ==  true)
        {
            $('.checkItems').prop('checked', true);
        }
        else
        {
            $('.checkItems').prop('checked', false);
        }
      })
    });
</script>

@endsection
@section('top_javascript')

    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

    @include('po-tracking.panel.modaldetailPO')
    {{-- Modal New PO --}}
    @include('po-tracking.polocal.modal.cek_po')
     {{-- Modal Ticket po --}}
     @include('po-tracking.polocal.modal.ticket_po')
    {{-- Modal view_gr --}}
    @include('po-tracking.panel.view_detailgr')
    {{-- Modal proforma_invoicel  --}}
    @include('po-tracking.polocal.modal.proforma_invoice').
    {{-- Modal parking invoice --}}
    @include('po-tracking.panel.parking_invoice')


@endsection

