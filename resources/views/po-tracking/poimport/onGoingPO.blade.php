@extends('po-tracking.panel.master')

@section('content')


<div class="clearfix">
</div>
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
              {{-- @if (count($errors) > 0)
              <div class="alert alert-danger alert-dismissible" role="alert" auto-close="500">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Data failed to process, check input or file size!
                </div>
              @endif --}}
              @if(session()->has('err_message') || $errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert" auto-close="500">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session()->get('err_message') ?? "Data failed to process, check the input form!" }}
                </div>
              @endif
              @if(session()->has('suc_message'))
                  <div class="alert alert-success alert-dismissible" role="alert" auto-close="500">
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
                          <table id="datatable-visibility" class="table nowrap table-bordered table-striped text-center" style="width:100%">
                            <thead>
                              <tr>
                                <th>PO Number</th>
                                <th>PO Item</th>
                                <th>Material</th>
                                <th>Description</th>
                                <th>Qty</th>
                                @if ($actionmenu->c== 1 || $actionmenu->u== 1 || $actionmenu->r== 1 )
                                <th style="width: 5%">Price&nbsp;&frasl;<sub>pcs</sub></th>
                                @endif
                                <th>Delivery Date</th>
                                <th>Progress</th>
                                <th>Detail</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($data as  $item)
                                @php
                                    $PoDate         = $item->Date ;
                                    $confirmedDate  = $item->ConfirmedDate;
                                    $deliveryDate   = $item->DeliveryDate ;
                                    $releaseDate    = $item->ReleaseDate ;
                                    $release        = new DateTime($releaseDate);
                                    $confirmed      = new DateTime($confirmedDate);
                                    $date           = new DateTime($PoDate);
                                    $dates          = new DateTime($deliveryDate);
                                    $temp           = 0;
                                    if ( $item->ReleaseDate== null) {
                                        $releases = "-";
                                    }else{
                                        $releases = $release->format('d/m/Y') ;
                                    }

                                    $price=number_format($item->NetPrice , 2, '.', '.');
                                @endphp
                                <tr class="baseBlock">
                                    <td class="po_number">
                                        <a href="javascript:void(0);" class=" text-dark btn-edit" data-toggle="modal" data-target="#detailsPO" data-po-date="{{ $date->format('d/m/Y') }}" data-vendor-code="{{ $item->VendorCode }}" data-vendor="{{ $item->Vendor }}" data-release-date="{{ $releases }}" data-po-creator="{{ $item->NRP }}" style="cursor: pointer;">{{ $item->Number }}</a>
                                    </td>
                                    <td>{{ $item->ItemNumber }}</td>
                                    <td class="material">{{ $item->Material }}</td>
                                    <td class="text-left">{{ $item->Description }}</td>
                                    @php
                                    $temp = 0;
                                    @endphp

                                    @foreach ($datafinishlocal as $items)
                                    @if ($item->POID == $items->POID && $item->ItemNumber == $items->ItemNumber)
                                            @php
                                            $temp = 1;
                                            @endphp
                                            <td><a href="javascript:void(0);" class="text-dark viewgr" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}" style="cursor: pointer;"> {{ $items->totalgr }} / {{ $item->Quantity }} </a></td>

                                    @endif
                                    @endforeach
                                    @if ($temp == 0)
                                        @if ($item->ActualQuantity == null)
                                        <td><a href="javascript:void(0);" class="text-dark viewgr" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}"  style="cursor: pointer;">  0 / {{ $item->Quantity }} </a></td>
                                        @else
                                        <td><a href="javascript:void(0);" class="text-dark viewgr" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}"  style="cursor: pointer;">  0 / {{ $item->ActualQuantity }} </a></td>

                                        @endif

                                    @endif
                                    @if ($actionmenu->c== 1 || $actionmenu->u== 1 || $actionmenu->r== 1 )
                                    <td>
                                        <div style="float:left;">{{ $item->Currency }}</div>
                                        <div style="float:right;">{{ $price }}</div>
                                    </td>
                                    @endif
                                    <td>{{ $confirmed == '' ? $dates->format('d/m/Y') : $confirmed->format('d/m/Y') }}</td>

                                    {{-- Progress --}}
                                    <td>
                                        @php
                                            $temp = 0;
                                            $activeStage    = $item->ActiveStage;
                                            $class      = '#';
                                            $badge      = 'badge-info';
                                            $text       = 'On Progress SAP';
                                            if ($activeStage == 2 || $activeStage == '2a')
                                            {
                                                if ($actionmenu->r == 1 || $actionmenu->c == 1) {
                                                    $class      = 'proformaInvoice';
                                                    $badge      = 'badge-primary';
                                                }elseif ($actionmenu->v == 1 || $actionmenu->d == 1  || $actionmenu->u == 1) {
                                                    $class      = '#';
                                                    $badge      = 'badge-secondary';
                                                }
                                                $text       = 'Proforma Invoice';
                                            }elseif ($activeStage == 3) {
                                                if ($actionmenu->r == 1 || $actionmenu->u == 1) {
                                                    $class      = 'verifyProforma';
                                                    $badge      = 'badge-info';
                                                }elseif ($actionmenu->v == 1 || $actionmenu->d == 1 || $actionmenu->c == 1) {
                                                    $class      = '#';
                                                    $badge      = 'badge-secondary';
                                                }
                                                $text       = 'Confirm Payment Date';
                                            }elseif ($activeStage == 4) {
                                                if ($actionmenu->r == 1 || $actionmenu->c == 1) {
                                                    $class      = 'preparingDocument';
                                                    $badge      = 'badge-warning';
                                                }elseif ($actionmenu->v == 1 || $actionmenu->d == 1 || $actionmenu->u == 1) {
                                                    $class      = '#';
                                                    $badge      = 'badge-secondary';
                                                }
                                                $text       = 'Preparing Document';
                                            }elseif ($activeStage == 5) {
                                                $text       = 'Good Receipt';
                                                $class      = 'goodReceipt';
                                                $badge      = 'badge-success';
                                            }
                                        @endphp
                                               <a href="javascript:void(0);" class="{{ $class }} badge {{ $badge }}" data-toggle="modal" data-id="{{ $item->ID }}" data-number="{{ $item->Number }}">{{ $text }}</a>

                                    </td>
                                    {{-- Status --}}
                                    <td>
                                            <a href="javascript:void(0);" class="detail-po badge badge-primary" data-toggle="modal"  data-id="{{ $item->ID }}" >Details</a>
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


@section('myscript')
    {{-- Modal Details PO --}}
    @include('po-tracking.panel.modaldetailPO')
    {{-- Modal view_gr --}}
    @include('po-tracking.panel.view_detailgr')

    {{-- Modal Upload Proforma Invoice --}}
    @include('po-tracking.poimport.modal.proforma_invoice')

    {{-- Modal Confirm Proforma Invoice --}}
    @include('po-tracking.poimport.modal.confirm_proforma_invoice')

    {{-- Modal Preparing Document Shipment--}}
    @include('po-tracking.poimport.modal.preparing_document')

    {{-- Modal Good Receipt --}}
    @include('po-tracking.poimport.modal.good_receipt')

    {{-- Modal Detail PO --}}
    @include('po-tracking.poimport.modal.detail_po')

     {{-- Modal View PO --}}
     @include('po-tracking.poimport.modal.view_po')

    <script>
        $(document).ready(function() {
            $('#datatable-modal-visibility').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'colvis'
                ]
            } );
        } );
    </script>
@endsection
