@extends('po-tracking.panel.master')
@section('content')

<style>
    .image-preview{
        display:inline-block;
        width:  100px;
        height: 100px;
        object-fit: cover;
        margin: 5px;
    }
    .image-preview:hover {
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
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            @include('po-tracking.panel.cardmenu_subcontractor')
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
            {{-- End Alert --}}

            <div class="x_content">

                {{-- End Search --}}

                <form method="post" action="{{url('skip-proformasubcontractor')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-12">
                        @if ( $actionmenu->u==1)
                            <input type="submit" class="btn btn-danger" value="Skip Proforma" >
                        @endif
                        <div class="card-box table-responsive">
                          <table id="datatable-responsive" class="table table-striped table-bordered text-center" style="width:100%">

                            <thead>
                              <tr>

                                <th>Vendor Name</th>
                                <th>PO Number</th>
                                <th>PO Number OLD</th>
                                <th>PO Item</th>
                                <th>Material</th>
                                <th>Description</th>
                                <th>GR Qty / Qty</th>
                                <th>Currency</th>
                                <th>Price&nbsp;&frasl;<sub>pcs</sub></th>
                                <th>Delivery Date SAP</th>
                                <th>Delivery Date Agreed</th>
                                <th>Progress</th>
                                <th style="width: 60px;">Action</th>
                                @if ($actionmenu->u== 1)
                                    <th class="no-sort"><input type="checkbox" name="ID" class="checkItems" id="allpayments"></th>
                                @endif
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as  $item)
                                    @php
                                       $sisair = $item->totalgr - $item->totalir ;
                                        $source         = $item->Date;
                                        $sources        = $item->ConfirmedDate;
                                        $deliverydate   = $item->DeliveryDate;
                                        $release_date   = $item->ReleaseDate;
                                        $release        = new DateTime($release_date);
                                        $date           = new DateTime($source);
                                        $delivery       = new DateTime($deliverydate);
                                        $confirmed      = new DateTime($sources);
                                        $temp           = 0;
                                        $priceIDR       = number_format(substr($item->NetPrice, 0),0,',','.');

                                        $activeStage    = $item->ActiveStage;
                                        $class          = '#';
                                        $btn          = 'btn-info';
                                        $text           = 'On Progress SAP';

                                        if ( $item->ReleaseDate== null) {
                                            $releases = "-";
                                        }
                                        else{
                                            $releases = $release->format('d/m/Y') ;
                                        }
                                        if (isset($item->ConfirmedDate) ) {
                                            $dates = $confirmed->format('d/m/Y') ;
                                        }
                                        else{
                                            $dates = $delivery->format('d/m/Y') ;
                                        }
                                        if ($item->DeliveryDate == null) {
                                            $delivery_date      = "-";
                                        }else{
                                            $delivery_date      = new DateTime($deliverydate);
                                            $delivery_date      = $delivery_date->format('d/m/Y');
                                        }
                                    @endphp
                                    <tr class="baseBlock">
                                        <td>{{ $item->Vendor }}</td>
                                        <td class="po_number">
                                            <a href="javascript:void(0);" class="text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="<?= $item->VendorCode ?>"  data-vendor-code-new="<?= $item->VendorCode_new ?>"data-vendor="{{ $item->Vendor }}" data-release-date="{{ $releases}}" data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                                        </td>
                                        <td>{{ $item->Number_old == null ? '-' : $item->Number_old }}</td>
                                        <td>{{ $item->ItemNumber }}</td>
                                        <td class="material text-left">{{ $item->Material }}</td>
                                        <td class="text-left">{{ $item->Description }}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="text-dark viewgr" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}" style="cursor: pointer;"> {{ $item->totalgr }} / {{ $item->ActualQuantity ?? $item->Quantity }} </a>
                                        </td>

                                        <td>{{$item->Currency}}</td>
                                        <td>
                                            <div style="float:right;">{{ $priceIDR }}</div>
                                        </td>

                                        <td data-order="{{$item->DeliveryDate}}">{{$delivery_date}}</td>
                                        <td data-order="{{$item->ConfirmedDate}}">{{ $dates }}</td>

                                        <td>
                                            @php
                                                $activeStage    = $item->ActiveStage;
                                                $total          = $item->totalticket;
                                                $qty            = $item->ActualQuantity;
                                                $openqty        = $item->OpenQuantity;

                                                //ProformaInvoice Button
                                                if (($actionmenu->u== 1) && $activeStage == '2')
                                                {
                                                    $text       = 'Proforma Invoice';
                                                    $class      = 'proformaInvoice';
                                                    $btn      = 'btn-danger ';
                                                }
                                                elseif ($activeStage == '2') {
                                                    $text       = 'Wait Approve Procurement';
                                                    $class      = 'cekpo';
                                                    $btn      = 'btn-secondary';
                                                }
                                                elseif ($actionmenu->v== 1 && $activeStage == '2a') {
                                                    $text       = 'Proforma';
                                                    $class      = 'proformaInvoice';
                                                    $btn      = 'btn-danger';
                                                }
                                                elseif ($activeStage == '2a') {
                                                    $text       = 'Wait Vendor Proforma Invoice';
                                                    $class      = 'cekpo';
                                                    $btn      = 'btn-secondary';
                                                }
                                                elseif ( $actionmenu->u== 1 && $activeStage == '2b') {
                                                    $text       = 'Confirm Payment';
                                                    $class      = 'proformaInvoice';
                                                    $btn      = 'btn-primary';
                                                }
                                                elseif ($activeStage == '2b') {
                                                    $text       = 'Wait Procurement Approve Proforma';
                                                    $class      = 'cekpo';
                                                    $btn      = 'btn-secondary';
                                                }
                                                //END of ProformaInvoice Button

                                                //Sequence Progress Button
                                                elseif ($actionmenu->v == 1  && ($activeStage == ''|| $activeStage == 3 || $activeStage == '3a' || $activeStage == '3b' || $activeStage == '3c' || $activeStage == 1)) {
                                                    $text       = 'Sequence Progress';
                                                    $class      = 'sequenceprogressclass';
                                                    $btn        = 'btn-primary';
                                                }
                                                else if ( ($activeStage == '' || $activeStage == 3 || $activeStage == 1))
                                                {
                                                    $text       = 'Sequence Progress';
                                                    $class      = '#';
                                                    $btn        = 'btn-secondary';
                                                }
                                                //END of Sequence Progress Button

                                                //Ticketing Button
                                                elseif ($actionmenu->v == 1 && $activeStage == 4 && $total == null)
                                                {
                                                    $text       = 'Create Ticket';
                                                    $class      = 'Ticketing';
                                                    $btn        = 'btn-info';
                                                }
                                                elseif ($activeStage == 4 && $total == null)
                                                {
                                                    $text       = 'Wait Vendor Create Ticket';
                                                    $class      = '#';
                                                    $btn        = 'btn-secondary';
                                                }
                                                elseif ($actionmenu->v== 1 && $activeStage == 4 && $total < $qty ) {
                                                        $text       = 'Ticket On';
                                                        $class      = 'Ticketing';
                                                        $badge      = 'btn-warning';
                                                }elseif ($activeStage == 4 && $total < $qty ) {
                                                        $text       = 'Vendor Ticket On';
                                                        $class      = 'cekpo';
                                                        $badge      = 'btn-secondary';
                                                }
                                                elseif ($actionmenu->v== 1 && $activeStage == 4 && $total == $qty ) {
                                                    $text       = 'Ticket Full';
                                                    $class      = 'cekpo';
                                                    $btn        = 'btn-danger';
                                                }
                                                elseif ($activeStage == 4 && $total == $qty ) {
                                                    $text       = 'Vendor Ticket Full';
                                                    $class      = 'cekpo';
                                                    $btn        = 'btn-secondary';
                                                }
                                                else  {
                                                    $text       = 'KOSONG';
                                                    $class      = 'cekpo';
                                                    $btn        = 'btn-secondary';
                                                }
                                                //END of Ticketing Button
                                                if($openqty == 0) {
                                                    $text       = 'Ticket FULL';
                                                    $class      = '#';
                                                    $btn        = 'btn-danger ';
                                                }
                                            @endphp
                                            <button type="button" class="{{ $class }} btn-sm {{ $btn }}" data-toggle="modal" data-id="{{ $item->ParentID ?? $item->ID }}" data-number="{{ $item->Number }}">{{ $text }}</button>
                                                {{-- @if ($text == "Ticket On")
                                                <button type="button" class="sequenceprogressclass btn-sm btn-primary" data-toggle="modal" data-id="{{ $item->ParentID ?? $item->ID }}" data-number="{{ $item->Number }}">Sequence Progress</button>

                                                @endif --}}
                                        </td>
                                            <td>
                                                @if(!empty($item->PrimerActualDate))
                                                <div class="sequenceprogressclass" data-toggle="modal" data-id="{{ $item->ParentID ?? $item->ID }}" data-number="{{ $item->Number }}">
                                                    <button type="button" class="btn-sm btn-primary" data-toggle="tooltip" title="Sequence Progress">
                                                        <i class="fa fa-list"></i>
                                                    </button>
                                                </div>
                                                @endif
                                                    <a href="{{ url("po-pdf/$item->Number/ongoing") }}" class="btn btn-danger btn-sm" formtarget="_blank" target="_blank" data-toggle="tooltip" title="PO File"><i class="fa fa-download"></i></a>
                                                    @if (!empty($item->ProformaInvoiceDocument))
                                                        <a class="btn btn-info btn-sm" href="{{ url("subcontractordownloadproforma/$item->ProformaInvoiceDocument") }}" data-toggle="tooltip" title="Proforma Invoice File"><i class="fa fa-file"></i></a>
                                                    @endif
                                                    @if (!empty($item->ApproveProformaInvoiceDocument))
                                                        <a class="btn btn-info btn-sm" href="{{ url("downloadproforma/$item->ApproveProformaInvoiceDocument") }}" data-toggle="tooltip" title="Payment Invoice File"> <i class="fa fa-arrow-circle-down"></i></a>
                                                    @else
                                                    @endif
                                                     @if ($actionmenu->u==1)
                                                    <button type="button" name="action" class="btn btn-warning btn-sm exampleModalUpdate" data-id="{{ $item->ID }}" data-number="{{ $item->Number }}" data-itemnumber="{{ $item->ItemNumber }}" data-status="Update" data-qty="{{ $item->ActualQuantity ?? $item->Quantity }}" data-toggle="modal" ><i class="fa fa-edit"></i></button>
                                                      @endif

                                                     @if($actionmenu->v== 1)
                                                        @if($item->totalparking == $sisair && $item->totalparking != null &&  $item->Status == "Approve Parking")
                                                        <a href="javascript:;" class="btn btn-success btn-sm parkinginvoice"  data-toggle="tooltip" title="Invoice Parking Approve"  data-id="{{ $item->Number }}" data-item="{{ $item->ItemNumber }}"><i class="fa fa-check-square"></i></a>
                                                        @elseif ($item->totalparking == $sisair && $item->totalparking != null)
                                                        <a href="javascript:;" class="btn btn-primary btn-sm parkinginvoice"  data-toggle="tooltip" title="Wait Approve Invoice"  data-id="{{ $item->Number }}" data-item="{{ $item->ItemNumber }}"><i class="fa fa-file-text-o"></i></a>
                                                        @elseif ($item->totalgr > $item->totalir)
                                                        <a href="javascript:;" class="btn btn-warning btn-sm parkinginvoice"  data-toggle="tooltip" title="Parking Invoice"  data-id="{{ $item->Number }}" data-item="{{ $item->ItemNumber }}"><i class="fa fa-file-text-o"></i></a>
                                                        @endif
                                                     @endif

                                                    <a data-toggle="tooltip" title="Chat" class="btn btn-success btn-sm" onclick="getChat('{{$item->Number}}','{{$item->ItemNumber}}','{{$item->Vendor}}','{{$item->NRP}}')" style="text-decoration:none;color:black">
                                                                                    <i class="fa fa-comment fa-lg custom--1"></i></a>
                                            </td>
                                        @if ( $actionmenu->u== 1 )
                                            @if ($item->ActiveStage==2)
                                                <td>
                                                <input type="checkbox" id="id"  name="Number[]" class="checkItems" value="{{ $item->Number }}" >
                                                <input type="hidden"  name="vendor"  value="Subcont" >
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
        <form method="post" action="{{ url('updatedata-subcont') }}" enctype="multipart/form-data">
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            fixedHeader                     : true,
            scrollCollapse                  : true,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }],
            buttons: [
                    'pageLength','colvis','copy','pdf', 'print',

                    {
                        text: 'Excel',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('po-open-download/subcontractorongoing/1') }}";
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

{{-- Modal Sequence Progress --}}
@include('po-tracking.subcontractor.modal.sequenceprogress')
{{-- Modal Sequence Progress --}}
@include('po-tracking.subcontractor.modal.proforma_invoice')
{{-- Modal Ticket po --}}
@include('po-tracking.subcontractor.modal.ticket_po')
{{-- Modal Detail PO --}}
@include('po-tracking.panel.modaldetailPO')
{{-- Modal viewgr  --}}
@include('po-tracking.panel.view_detailgr')
{{-- Modal parking invoive  --}}
@include('po-tracking.panel.parking_invoice')

@endsection
