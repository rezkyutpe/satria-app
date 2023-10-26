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

      <form method="post" action="{{url('confirmticketsubcontractor')}}" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <a href="{{url("$menu_history")}}" style="float: right;" class="btn btn-success" >History Ticket</a>
                    <table id="datatable-visibility" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>

                            <th>PO Number</th>
                            <th>PO Number OLD</th>
                            <th>PO Item</th>
                            <th>Material</th>
                            <th>Description</th>
                            <th>PO Qty</th>
                            <th>ID Ticket</th>
                            <th>Ticket Create Date</th>
                            <th>Ticket Qty</th>
                            <th>Delivery Date Agreed</th>
                            <th>Ticket Delivery Date</th>
                            <th>Ticket Delivery Time</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as  $item)
                        <tr class="baseBlock">
                            @php
                                $deliveryDate       = $item->DeliveryDate ;
                                $deliveryDates      = $item->DeliveryDateS ;
                                $releaseDate        = $item->ReleaseDate ;
                                $release            = new DateTime($releaseDate);
                                $dates              = new DateTime($deliveryDate);
                                $date               = new DateTime($deliveryDates);
                                $ticketcreatedate   = new DateTime($item->TicketCreateDate);
                                if ($item->ReleaseDate== null) {
                                    $releases = "-";
                                }else{
                                    $releases = $release->format('d/m/Y') ;
                                }

                                $confirmeddate = new DateTime($item->ConfirmDeliveryDate);
                                $confirmeddates = $confirmeddate->format('d/m/Y');
                            @endphp

                            <td class="text-left po_number">
                                <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>" data-vendor-code-new="<?= $item->VendorCode_new ?>" data-vendor="{{ $item->Name }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                            </td>
                            <td>{{ $item->ItemNumber }}</td>
                            <td>{{ $item->Number_old == null ? '-' : $item->Number_old }}</td>
                            <td class="text-left">{{ $item->Material }}</td>
                            <td class="text-left">{{ $item->Description }}</td>
                            <td>{{ $item->QtySAP }}</td>
                            <td><a href="javascript:void(0);" class="text-dark btn-edit view_ticket" data-toggle="modal" data-id="{{ $item->ID }}"  style="cursor: pointer;">{{ $item->TicketID }}</a></td>
                            <td>{{ $ticketcreatedate->format('d/m/Y') }}</td>
                            <td>{{ $item->Quantity }}</td>
                            <td>{{ $confirmeddates }}</td>
                            <td data-order = "{{ $item->DeliveryDate }}">{{ $dates->format('d/m/Y') }}</td>
                            <td>{{ $dates->format('H:i:s') }}</td>
                            <td><span class="badge badge-warning ">Wait Approve WHS</span></td>

                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                 </div>
            </div>
          </div>
        </div>
        </form>
    </div>
</div>
<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
<script>

      $(document).ready(function() {
         $('#allpayment').click(function(){

        if ($(this).is(':checked') ==  true)
            {
                $('.checkItem').prop('checked', true);
            }
            else
            {
                $('.checkItem').prop('checked', false);
            }
          });
        $(".datepicker").datepicker({
            format: "dd/mm/yyyy",
            daysOfWeekDisabled: [0,6],
            startDate: '+2',
            autoclose: true,
            todayHighlight: true,
            toggleActive: true,

        });
        });
</script>

@endsection
@section('top_javascript')
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
    {{-- detailpo --}}
    @include('po-tracking.panel.modaldetailPO')
@endsection
