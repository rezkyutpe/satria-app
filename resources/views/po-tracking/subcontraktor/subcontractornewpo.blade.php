@extends('po-tracking.panel.master')
@section('content')


<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $header_title }}</h2>
                    {{-- <ul class="nav navbar-right panel_toolbox pl-5">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul> --}}
                    <div class="clearfix"></div>
                </div>

                @include('po-tracking.panel.cardmenu_subcontractor')

                {{-- Alert --}}
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

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <table id="datatable-responsive" class="table table-bordered text-center table-hover" style="width:100%">
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
                                        <tr>
                                            @if ($actionmenu->v == 1 || $actionmenu->d == 1 || $actionmenu->u == 1 || $actionmenu->r == 1 )
                                                <th>Vendor Name</th>
                                            @endif
                                            <th>PO Number</th>
                                            <th>Item ID</th>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            @if ($actionmenu->c== 1 || $actionmenu->u== 1  || $actionmenu->r== 1 || $status == 1)
                                                <th>Currency</th>
                                                <th>Price&nbsp;&frasl;<sub>pcs</sub></th>
                                            @endif
                                                <th>Delivery Date SAP</th>
                                                <th>Delivery Date Agreed</th>
                                            @if ($actionmenu->v == 1 || $actionmenu->d == 1 || $actionmenu->u == 1 || $actionmenu->r == 1)
                                                <th>Req.Date CCR</th>
                                            @endif
                                                <th>Progress</th>

                                                <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $k=> $item)
                                            @php
                                                $source         =  $item->Date ;
                                                $sources        =  $item->DeliveryDate;
                                                
                                                $release_date   = $item->ReleaseDate ;
                                                $release        = new DateTime($release_date);
                                                $date           = new DateTime($source);
                                                $dates          = new DateTime($sources);
                                                $req_date_ccr   = new DateTime($item->req_date);
                                                if ($item->DeliveryDate== null) {
                                                    $datess = "-";
                                                }
                                                else{
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

                                                if ($actionmenu->c == 1 || $actionmenu->r == 1) {
                                                    $confirm_class  = "confirmpo";
                                                    $progress       = "Confirm PO";
                                                    $color          = "primary";
                                                }
                                                else if($actionmenu->v == 1 || $actionmenu->d == 1 || $actionmenu->u == 1){
                                                    $progress       = "Confirm PO";
                                                    $color          = "secondary";
                                                    $confirm_class  = "#";
                                                }
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
                                                $priceIDR=number_format(substr($item->NetPrice, 0),0,',','.');
                                                $formatQuantity=number_format(substr($item->Quantity, 0),0,',','.');


                                            @endphp
                                            <tr class="baseBlock">
                                                @if ($actionmenu->v == 1 || $actionmenu->d == 1 || $actionmenu->u == 1 || $actionmenu->r == 1)
                                                    <td>{{ $item->Vendor }}</td>
                                                @endif
                                                <td class="po_number"> {{--Details PO--}}
                                                    <a href="javascript:void(0);"
                                                        class="text-dark btn-edit"
                                                        data-toggle="modal"
                                                        data-target="#detailsPO"
                                                        data-vendor-code="{{ $item->VendorCode }}"
                                                        data-vendor="{{ $item->Vendor }}"
                                                        data-release-date="{{ $releases }}"
                                                        data-po-creator="{{ $item->PurchaseOrderCreator }}" data-po-nrp="{{ $item->NRP }}"
                                                        data-po-date="{{ $date->format('d/m/Y') }}"
                                                        style="cursor: pointer;">
                                                        {{ $item->Number }}
                                                    </a>
                                                </td>
                                                <td>{{ $item->ItemNumber }}</td>
                                                <td class="text-left">{{ $item->Material }}</td>
                                                <td class="text-left">{{ $item->Description }}</td>
                                                <td><div style="float:right;">{{ $formatQuantity }}</div></td>
                                                @if ($actionmenu->c== 1 || $actionmenu->u== 1  || $actionmenu->r== 1 || $status == 1)
                                                <td>{{$item->Currency}}</td>
                                                <td>
                                                    {{ $priceIDR }}
                                                </td>
                                                @endif
                                                <td data-order="{{$item->DeliveryDate}}">{{ $datess }}</td>
                                                <td data-order="{{$item->ConfirmedDate}}">{{ $confirmed_date }}</td>
                                                @if ($actionmenu->v == 1 || $actionmenu->d == 1 || $actionmenu->u == 1 || $actionmenu->r == 1)
                                                    <td>{{ $item->req_date == null ? '-' : $req_date_ccr->format('d/m/Y') }}</td>
                                                @endif
                                                <td>
						@if ($actionmenu->c == 1 || $actionmenu->r == 1 )
                                                     <form method="post" action="{{url('ConfirmPOSubcont')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="POID" value="{{ $item->POID}}">

                                                    <button type="submit" style="color: #fff; border:#fff" class="badge badge-primary"  onclick="return confirm('Apakah anda Yakin Confirm PO {{ $item->Number }} ?');">Confirm PO</button>
                                             	   </form>						
						@else
 						    <a href="#"  class="{{ $confirm_class }} badge badge-{{ $color }}" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}">
                                                        {{ $progress }}
                                                    </a>
                                                @endif
                                                 </td>                                                 @if ($actionmenu->c== 1 || $actionmenu->u== 1 || $actionmenu->r== 1 )
                                                <td>
                                                        <a href="{{ url("po-pdf/$item->Number/newpo") }}" class="btn btn-danger btn-sm" formtarget="_blank" target="_blank" data-toggle="tooltip" title="PO File"><i class="fa fa-download"></i></a>
                                                         <a data-toggle="tooltip" title="Chat" class="btn btn-success btn-sm" onclick="getChat('{{$item->Number}}','{{$item->ItemNumber}}','{{$item->Vendor}}','{{$item->NRP}}')" style="text-decoration:none;color:black">
                                                                                    <i class="fa fa-comment fa-lg custom--1"></i></a>
                                                </td>
                                                  @else
                                                 <td>
                                                    <a data-toggle="tooltip" title="Chat" class="btn btn-success btn-sm" onclick="getChat('{{$item->Number}}','{{$item->ItemNumber}}','{{$item->Vendor}}','{{$item->NRP}}')" style="text-decoration:none;color:black">
                                                                                    <i class="fa fa-comment fa-lg custom--1"></i></a>
                                                </td>
                                                @endif
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
                    <?php if ($actionmenu->r==1 || $actionmenu->u==1|| $actionmenu->c==1 || $status == 1){ ?>
                    {
                        text: 'Excel',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('po-open-download/subcontractornewpo/1') }}";
                        }
                    }
                    <?php } else { ?>
                    {
                        text: 'Excel',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('po-open-download/subcontractornewpo/2') }}";
                        }
                    }
                 <?php } ?>
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
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

{{-- Modal Detail PO --}}
@include('po-tracking.panel.modaldetailPO')

 {{-- Modal negosiasi  --}}
 @include('po-tracking.panel.view_confirmpo')
 {{-- Modal confirmpo --}}
 @include('po-tracking.panel.view_negosiasipo')

{{-- Modal Cancel PO --}}
@include('po-tracking.subcontraktor.modal.cancelpo')

@endsection
