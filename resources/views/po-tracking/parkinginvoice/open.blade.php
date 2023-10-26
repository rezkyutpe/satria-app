@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Open Parking Invoice</h2>
                    <div class="clearfix"></div>
                </div>

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

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <div class="x_content">
                                <div class="mb-4">
                                    <form action="{{ url('openparkinginvoice') }}" method="POST">
                                    @csrf
                                        <div class="rows">
                                            <div class="cols">
                                                Find by Column :
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="cols ml-2">
                                                <select class="form-control" id="kolomnya" name="kolom">
                                                    <option value="uservendors.Name" {{ session('kolom') == 'uservendors.Name' ? 'selected' : '' }}>Vendor Name</option>
                                                    <option value="po.PurchaseOrderCreator" {{ session('kolom') == 'po.PurchaseOrderCreator' ? 'selected' : '' }}>PO Creator</option>
                                                    <option value="po.Number" {{ session('kolom') == 'po.Number' ? 'selected' : '' }}>PO Number</option>
                                                    <option value="purchasingdocumentitem.Material" {{ session('kolom') == 'purchasingdocumentitem.Material' ? 'selected' : '' }}>Part Number</option>
                                                    <option value="purchasingdocumentitem.Description" {{ session('kolom') == 'purchasingdocumentitem.Description' ? 'selected' : '' }}>Part Number Description</option>
                                                </select>
                                            </div>
                                            <div class="col col-4">
                                                    <input type="text" class="form-control" name="isi" placeholder="search here..." value="{{ session('isi') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="cols ml-2">
                                                <a href="{{ url('openparkinginvoice') }}?reset=1" class="btn btn-danger">Reset</a>
                                            </div>
                                            <div class="cols">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <table id="datatable-responsive" class="table text-center table-striped table-bordered dt-responsive" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Vendor</th>
                                            <th>PO Creator</th>
                                            <th>PO Number</th>
                                            <th>Item Number</th>
                                            <th>Part Number</th>
                                            <th>Description</th>
                                            <th>Qty PO</th>
                                            <th>Qty Open Parking</th>
                                            @if($actionmenu->v == 1)
                                                <th>PO File</th>
                                            @endif
                                            @if($actionmenu->c == 1)
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataPO as $item)
                                                <tr>
                                                    <td>{{ $item->Name }}</td>
                                                    <td>{{ $item->PurchaseOrderCreator }}</td>
                                                    <td class="po_number">
                                                        <a href="javascript:;"
                                                            class="text-dark btn-edit"
                                                            data-toggle="modal"
                                                            data-target="#detailsPO"
                                                            data-po-date="{{ (new DateTime($item->Date))->format('d/m/Y') }}"
                                                            data-vendor-code="{{ $item->VendorCode }}"
                                                            data-vendor-code-new="{{ $item->VendorCode_new }}"
                                                            data-vendor="{{ $item->Name }}"
                                                            data-release-date="{{ (new DateTime($item->ReleaseDate))->format('d/m/Y') }}"
                                                            data-po-creator="{{ $item->PurchaseOrderCreator }}"
                                                            data-po-nrp="{{ $item->NRP }}"
                                                            style="cursor: pointer;">
                                                            {{ $item->Number }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->ItemNumber }}</td>
                                                    <td>{{ $item->Material }}</td>
                                                    <td>{{ $item->Description }}</td>
                                                    <td> <a href="javascript:;" class="text-dark viewgr" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}" style="cursor: pointer;">
                                                        {{ $item->ActualQuantity ?? $item->Quantity }}
                                                    </td>
                                                    <td> <a href="javascript:;" class="text-dark viewgr" data-toggle="modal" data-number="{{ $item->POID }}" data-item="{{ $item->ItemNumber }}" style="cursor: pointer;">
                                                        {{ $item->totalgr - $item->totalir }}
                                                    </td>
                                                    <td>
                                                        @if($actionmenu->v == 1)
                                                            <a href="{{ url("po-pdf/$item->Number/ongoing") }}" class="btn btn-danger btn-sm" formtarget="_blank" target="_blank" data-toggle="tooltip" title="PO File"><i class="fa fa-download"></i></a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($actionmenu->c == 1)
                                                            <a href="javascript:;" class="btn btn-sm btn-primary parkinginvoice" data-id="{{ $item->Number }}" data-toggle="tooltip" title="Request Parking Invoice"><i class="fa fa-file-text"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                    @if(method_exists($dataPO, 'links'))
                                    <tfoot>
                                        <tr>
                                            <td colspan="9">
                                                    {{$dataPO->links()}}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
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
            //data tablenya
            $('#datatable-responsive').DataTable({
                dom: @if(method_exists($dataPO, 'links')) 't' @else 'ftip' @endif,
                scrollCollapse: true,
                stateSave: true,
                ordering:false,
                // columnDefs: [{
                //     visible: false
                // }],
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                }
            });
        });
    </script>

    {{-- Modal detailPO  --}}
    @include('po-tracking.panel.modaldetailPO')

    {{-- Modal viewgr  --}}
    @include('po-tracking.panel.view_detailgr')

    {{-- Modal parking invoive  --}}
    @include('po-tracking.parkinginvoice.modal.parking_invoice')
@endsection
