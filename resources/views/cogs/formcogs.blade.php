@extends('cogs.panel.master')

@section('mycss')
<style>
::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 7px;
}

::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(0, 0, 0, .25);
    -webkit-box-shadow: 0 0 1px rgba(255, 255, 255, .25);
    box-shadow: 0 0 1px rgba(255, 255, 255, .25);
}

</style>
@endsection

@section('content')
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">

        <div class="x_panel">
            <div class="x_title">
                <h2><b>COGS HEADER</b></h2>
                <div class="nav pull-right" role="button">
                    <li><a class="collapse-link p-0"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </div>
                <h6>
                    <a href="#" style="color:white;" class="badge badge-info pull-right mr-3 my-1" id="editHeaderCOGS">
                        <i class="fa fa-pencil fa-xs"></i>
                        Edit Header
                    </a>
                    <!-- {{ url('cogs-import-kurs-form-cogs/'.$data['COGSID']) }} -->
                    <a href="{{ url('cogs-save-new/'.$data['COGSID']) }}" style="color:white;"
                        class="badge badge-warning pull-right mr-3 my-1">
                        <i class="fa fa-save fa-xs"></i>
                        Save New
                    </a>
                </h6>
                <div class="clearfix"></div>
            </div>
            @if(!empty($err_message))
            <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ $err_message }}
            </div>
            @endif
            @if(!empty($suc_message))
            <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ $suc_message }}
            </div>
            @endif

            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        @if(!empty($data['Opportunity']) &&  !empty($data['PICTriatra']))
                        <form method="post" id="SaveHeader" action="{{url('cogs-update-cogs-header')}}"
                            enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="my-2">COGS ID </label>
                                    </div>
                                    <div class="col-10">
                                        <input id="COGSID" name="COGSID" class="form-control" type="text"
                                            value="{{ $data['COGSID'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">PCR ID </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="PCRID" name="PCRID" class="form-control" type="text"
                                            value="{{ $data['PCRID'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">Calculation Type </label>
                                    </div>
                                    <div class="col-4" id="htmlCalculationType">
                                        <input id="CalculationType" name="CalculationType" class="form-control"
                                            type="text" value="{{ $data['CalculationType'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">CPO ID </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="CPOID" name="CPOID" class="form-control" type="text"
                                            value="{{ $data['CPOID'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">Product Category </label>
                                    </div>
                                    <!-- <input id="IDProductCategory" name="IDProductCategory" class="form-control d-none"
                                        type="text" value="{{-- $data['IDProductCategory'] --}}" readonly> -->
                                    <div class="col-4" id="htmlProductCategory">
                                        <input id="ProductCategory" name="ProductCategory" class="form-control"
                                            type="text" value="{{ $data['ProductCategory'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">Opportunity </label>
                                    </div>
                                    <div class="col-4">
                                        <textarea id="Opportunity" name="Opportunity" class="form-control" type="text"
                                            value="{{ $data['Opportunity'] }}" readonly>{{ $data['Opportunity'] }}
                                        </textarea>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">PN Reference </label>
                                    </div>
                                    <div class="col-4" id="htmlPNReference">
                                        <input id="PNReferenceCode" name="PNReferenceCode" class="form-control d-none"
                                            type="text" value="{{ $data['PNReferenceCode'] }}" readonly>
                                        <textarea id="PNReference" name="PNReference" class="form-control" type="text"
                                            value="{{ $data['PNReference'] }}" readonly>{{ $data['PNReference'] }}
                                        </textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">PIC Triatra </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="PICTriatra" name="PICTriatra" class="form-control" type="text"
                                            value="{{ $data['PICTriatra'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">Cost Estimator </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="CostEstimator" name="CostEstimator" class="form-control" type="text"
                                            value="{{ $data['CostEstimator'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">Pre Eliminary Drw. Number </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="PEDNumber" name="PEDNumber" class="form-control" type="text"
                                            value="{{ $data['PEDNumber'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">Marketing Dept Head </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="MarketingDeptHead" name="MarketingDeptHead" class="form-control"
                                            type="text" value="{{ $data['MarketingDeptHead'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">Unit Weight (Kg) </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="UnitWeight" name="UnitWeight" class="form-control NumberInputComma"
                                            type="text" value="{{ $data['UnitWeight'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">SCM Division Head </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="SCMDivisionHead" name="SCMDivisionHead" class="form-control"
                                            type="text" value="{{ $data['SCMDivisionHead'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="mt-4">
                                            <label class="mb-1"><b>Created by : </b> {{ $data['CreatedBy'] }} <b>on
                                                </b>
                                                {{ $data['created_at'] }}</label><br>
                                            <label class="mb-2"><b>Updated by : </b> {{ $data['UpdatedBy'] }} <b>on
                                                </b>
                                                {{ $data['updated_at'] }}</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <h6>
                                                <a href="#" style="color:white;"
                                                    class="d-none badge badge-success pull-right mx-2 mb-2"
                                                    id="saveHeaderCOGS">
                                                    <i class="fa fa-check fa-xs"></i>
                                                    Save
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row mt-2 mx-1" style="border-top: 2px solid #E6E9ED;">
                                </div> -->
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="row">
                                            @if(!empty($data['Attachment']))
                                                @foreach ($data['Attachment'] as $i => $file)
                                                    <div class="col-3 mt-2">
                                                        <div class="row">
                                                            <div class="col-10 p-0">
                                                                <label class="my-0" style="min-height:40px;"><b>Attachment
                                                                        {{ $i+1 }} :</b>
                                                                    {{ $file['AttachmentName'] }}
                                                                </label>
                                                            </div>
                                                            <div class="col-1 p-0">
                                                                <a href="" target="_blank"
                                                                    style="cursor:pointer;width:100%;font-size:100%;padding:0px;"
                                                                    class="downloadAttachment badge badge-info badge-sm pull-right mr-1"
                                                                    name="{{ $file['AttachmentName'] }}"
                                                                    file="{{ $file['AttachmentFile'] }}">
                                                                    <i class="fa fa-download"></i></i>
                                                                </a>
                                                            </div>
                                                            <div class="col-1 p-0">
                                                                <a href=""
                                                                    style="cursor:pointer;width:100%;font-size:100%;padding:0px;"
                                                                    class="buttonAttachment badge badge-success badge-sm pull-right"
                                                                    no="{{ $i+1 }}" name="{{ $file['AttachmentName'] }}"
                                                                    file="{{ $file['AttachmentFile'] }}">
                                                                    <i class="fa fa-expand"></i></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <iframe
                                                                src="data:application/pdf;base64,{{ $file['AttachmentFile'] }}"
                                                                type="application/pdf" frameBorder="0" scrolling="auto"
                                                                marginheight="0" marginwidth="0" height="100%" width="100%"
                                                                allowfullscreen webkitallowfullscreen style="overflow: auto;">
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        @else
                        <form method="post" id="SaveHeader" action="{{url('cogs-update-cogs-header')}}"
                            enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="my-2">COGS ID </label>
                                    </div>
                                    <div class="col-10">
                                        <input id="COGSID" name="COGSID" class="form-control" type="text"
                                            value="{{ $data['COGSID'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">Pre Eliminary Drw. Number </label>
                                    </div>
                                    <div class="col-4">
                                        <textarea id="PEDNumber" name="PEDNumber" class="form-control" type="text"
                                            value="{{ $data['PEDNumber'] }}" readonly>{{ $data['PEDNumber'] }}
                                        </textarea>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">PN Reference </label>
                                    </div>
                                    <div class="col-4" id="htmlPNReference">
                                        <input id="PNReferenceCode" name="PNReferenceCode" class="form-control d-none"
                                            type="text" value="{{ $data['PNReferenceCode'] }}" readonly>
                                        <textarea id="PNReference" name="PNReference" class="form-control" type="text"
                                            value="{{ $data['PNReference'] }}" readonly>{{ $data['PNReference'] }}
                                        </textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">Unit Weight (Kg) </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="UnitWeight" name="UnitWeight" class="form-control NumberInputComma"
                                            type="text" value="{{ $data['UnitWeight'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">Cost Estimator </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="CostEstimator" name="CostEstimator" class="form-control" type="text"
                                            value="{{ $data['CostEstimator'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">Calculation Type </label>
                                    </div>
                                    <div class="col-4" id="htmlCalculationType">
                                        <input id="CalculationType" name="CalculationType" class="form-control"
                                            type="text" value="{{ $data['CalculationType'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">Marketing Dept Head </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="MarketingDeptHead" name="MarketingDeptHead" class="form-control"
                                            type="text" value="{{ $data['MarketingDeptHead'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label class="my-2">Product Category </label>
                                    </div>
                                    <!-- <input id="IDProductCategory" name="IDProductCategory" class="form-control d-none"
                                        type="text" value="{{-- $data['IDProductCategory'] --}}" readonly> -->
                                    <div class="col-4" id="htmlProductCategory">
                                        <input id="ProductCategory" name="ProductCategory" class="form-control"
                                            type="text" value="{{ $data['ProductCategory'] }}" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label class="my-2">SCM Division Head </label>
                                    </div>
                                    <div class="col-4">
                                        <input id="SCMDivisionHead" name="SCMDivisionHead" class="form-control"
                                            type="text" value="{{ $data['SCMDivisionHead'] }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="mt-4">
                                            <label class="mb-1"><b>Created by : </b> {{ $data['CreatedBy'] }} <b>on
                                                </b>
                                                {{ $data['created_at'] }}</label><br>
                                            <label class="mb-2"><b>Updated by : </b> {{ $data['UpdatedBy'] }} <b>on
                                                </b>
                                                {{ $data['updated_at'] }}</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <h6>
                                                <a href="#" style="color:white;"
                                                    class="d-none badge badge-success pull-right mx-2 mb-2"
                                                    id="saveHeaderCOGS">
                                                    <i class="fa fa-check fa-xs"></i>
                                                    Save
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row mt-2 mx-1" style="border-top: 2px solid #E6E9ED;">
                                </div> -->
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="row">
                                            @if(!empty($data['Attachment']))
                                                @foreach ($data['Attachment'] as $i => $file)
                                                    <div class="col-3 mt-2">
                                                        <div class="row">
                                                            <div class="col-10 p-0">
                                                                <label class="my-0" style="min-height:40px;"><b>Attachment
                                                                        {{ $i+1 }} :</b>
                                                                    {{ $file->AttachmentName }}
                                                                </label>
                                                            </div>
                                                            <div class="col-2 p-0">
                                                                <a href=""
                                                                    style="cursor:pointer;width:75%;font-size:100%;padding:0px;"
                                                                    class="buttonAttachment badge badge-success badge-sm pull-right"
                                                                    no="{{ $i+1 }}" name="{{ $file->AttachmentName }}"
                                                                    file="{{ $file->AttachmentFile }}">
                                                                    <i class="fa fa-expand"></i></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <iframe
                                                                src="data:application/pdf;base64,{{ $file->AttachmentFile }}"
                                                                type="application/pdf" frameBorder="0" scrolling="auto"
                                                                marginheight="0" marginwidth="0" height="100%" width="100%"
                                                                allowfullscreen webkitallowfullscreen style="overflow: auto;">
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h2><b>CALCULATION</b></h2>
                <div class="nav pull-right" role="button">
                    <li><a class="collapse-link p-0"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <ul class="nav nav-tabs bar_tabs justify-content-center" id="myTab" role="tablist"
                                style="background: white;">
                                <li class=" nav-item">
                                    <a class="nav-link active" id="CurrencyTab" data-toggle="tab" href="#Currency"
                                        role="tab"><b>Currency</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="RawMaterialTab" data-toggle="tab" href="#RawMaterial"
                                        role="tab"><b>Raw Material</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="SFComponentTab" data-toggle="tab" href="#SFComponent"
                                        role="tab"><b>S/F & Component</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ConsumablesTab" data-toggle="tab" href="#Consumables"
                                        role="tab"><b>Consumables</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ManhourTab" data-toggle="tab" href="#Manhour"
                                        role="tab"><b>Man-Hour</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="OthersTab" data-toggle="tab" href="#Others"
                                        role="tab"><b>Others</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="PDFTab" data-toggle="tab" href="#PDF"
                                        role="tab"><b>Summary</b></a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active " id="Currency" role="tabpanel">
                                    <!-- TAB KURS CURRENCY -->
                                    <div style="padding: 10px 100px;">
                                        <table id="datatable-visibility-form-kurs"
                                            class="table text-center table-striped" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Mata Uang</th>
                                                    <th>Nilai</th>
                                                    <th>Kurs Jual</th>
                                                    <th>Kurs Tengah</th>
                                                    <th>Kurs Beli</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($data['DataKurs']))
                                                @foreach ($data['DataKurs'] as $i => $item)
                                                <tr class="baseBlock">
                                                    <td>{{ $i+1 }}</td>
                                                    <td><b>{{ $item->MataUang }}</b></td>
                                                    <td>{{ $item->Nilai }}</td>
                                                    <td>{{ $item->KursJual }}</td>
                                                    <td>{{ $item->KursTengah }}</td>
                                                    <td>{{ $item->KursBeli }}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="my-2 data-time-update">
                                        <div class="mt-2">
                                            <a><i class="fa fa-clock-o"></i></a>
                                            <span class="d-none data_update_time">{{ $data['updated_at_kurs'] }}</span>
                                            <span class="text-dark update_time"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="RawMaterial" role="tabpanel">
                                    <!-- TAB RAWMATERIAL -->
                                    <div style="padding: 10px;">
                                        @if ($data['CalculationType'] != "New PN")
                                        <a class="btn btn-danger btn-sm pull-right mb-2"
                                            href="{{ url('cogs-get-rawmaterial/'.$data['COGSID']) }}"><i
                                                class="fa fa-download"></i></a>
                                        <a class="btn addRawMaterial btn-primary btn-sm pull-right mb-2 mr-2" href="#">
                                            <i class="fa fa-plus"></i></a>
                                        @else
                                        <a class="btn addRawMaterial btn-primary btn-sm pull-right mb-2" href="#">
                                            <i class="fa fa-plus"></i></a>
                                        @endif
                                        <table id="datatable-visibility-form-raw"
                                            class="table text-center table-striped" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">No</th>
                                                    <th colspan="8" style="border-bottom:0px;">Material Cost
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Spesification</th>
                                                    <th>Weight (Kg-gross)</th>
                                                    <th>Price<sub> / un</sub></th>
                                                    <th>Currency</th>
                                                    <th>Un</th>
                                                    <th>Status</th>
                                                    <th>Final Cost (IDR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($data['DataCOGSRawMaterial']))
                                                @foreach ($data['DataCOGSRawMaterial'] as $i => $item)
                                                <tr class="baseBlock">
                                                    <td>{{ $i+1 }}</td>
                                                    <td><b>{{ $item->Spesification }}</b></td>
                                                    <td>{{ $item->Weight }}</td>
                                                    <td>{{ $item->Price }}</td>
                                                    <td>{{ $item->Currency }}</td>
                                                    <td>{{ $item->Un }}</td>
                                                    <td>{{ $item->Status }}</td>
                                                    <td>{{ $item->FinalCost }}</td>
                                                    <td>
                                                        @if ($data['CalculationType'] == "New PN")
                                                        <a href="#" class="editRawMaterial" data-toggle="modal"
                                                            IDRawMaterial="{{ $item->ID }}"><i
                                                                class=" fa fa-pencil fa-lg"></i></a>
                                                        <a href="#" class="deleteRawMaterial" data-toggle="modal"
                                                            IDRawMaterial="{{ $item->ID }}"><i
                                                                class=" fa fa-trash fa-lg"></i></a>
                                                        @else
                                                        <a href="#" class="editRawMaterialStatus" data-toggle="modal"
                                                            IDRawMaterial="{{ $item->ID }}"><i
                                                                class=" fa fa-pencil fa-lg"></i></a>
                                                        <a href="#" class="deleteRawMaterial" data-toggle="modal"
                                                            IDRawMaterial="{{ $item->ID }}"><i
                                                                class=" fa fa-trash fa-lg"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <th></th>
                                                <th>Total</th>
                                                <th>{{ $data['TotalWeight'] }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ $data['TotalFinalCostRawMaterial'] }}</th>
                                                <th></th>
                                            </tfoot>
                                            @else
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                <div class=" tab-pane fade" id="SFComponent" role="tabpanel">
                                    <!-- TAB SFCOMPONENT -->
                                    <div style="padding: 10px;">
                                        @if ($data['CalculationType'] != "New PN")
                                        <a class="btn btn-danger btn-sm pull-right mb-2"
                                            href="{{ url('cogs-get-sfcomponent/'.$data['COGSID']) }}"><i
                                                class="fa fa-download"></i></a>
                                        <a class="btn addSFComponent btn-primary btn-sm pull-right mb-2 mr-2" href="#">
                                            <i class="fa fa-plus"></i></a>
                                        @else
                                        <a class="btn addSFComponent btn-primary btn-sm pull-right mb-2" href="#">
                                            <i class="fa fa-plus"></i></a>
                                        @endif
                                        <table id="datatable-visibility-form-sfcomponent"
                                            class="table text-center table-striped" cellspacing="0" width="100%"
                                            data-role="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Component</th>
                                                    <th>Description</th>
                                                    <th>Category</th>
                                                    <th>Price</th>
                                                    <th>Currency</th>
                                                    <th>Qty</th>
                                                    <th>Un</th>
                                                    <th>Tax (%)</th>
                                                    <th>Total Price (IDR)</th>
                                                    <th>Last Transaction</th>
                                                    <th>Manual Adjustment</th>
                                                    <th>Final Price (IDR)</th>
                                                    <th>Action</th>
                                                    <th></th>
                                                    <th class="d-none"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($data['DataCOGSSFComponent']))
                                                @foreach ($data['DataCOGSSFComponent'] as $i => $item)
                                                <tr class="baseBlock">
                                                    <td>{{ $i+1 }}</td>
                                                    <td><b>{{ $item->Component }}</b></td>
                                                    <td>{{ $item->Description }}</td>
                                                    <td>{{ $item->Category }}</td>
                                                    <td>{{ $item->Price }}</td>
                                                    <td>{{ $item->Currency }}</td>
                                                    <td>{{ $item->Qty }}</td>
                                                    <td>{{ $item->Un }}</td>
                                                    <td>{{ $item->Tax }}</td>
                                                    <td>{{ $item->TotalPrice }}</td>
                                                    <td>{{ $item->LastTransaction }}</td>
                                                    <td>{{ $item->ManualAdjustment }}</td>
                                                    <td>{{ $item->FinalPrice }}</td>
                                                    <td>
                                                        <a href="#" class="editSFComponent" data-toggle="modal"
                                                            IDSFComponent="{{ $item->ID }}"><i
                                                                class=" fa fa-pencil fa-lg"></i></a>
                                                        <a href="#" class="deleteSFComponent" data-toggle="modal"
                                                            IDSFComponent="{{ $item->ID }}"><i
                                                                class=" fa fa-trash fa-lg"></i></a>
                                                    </td>
                                                    <td></td>
                                                    <td class="d-none">{{ $item->ID }}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>{{ $data['TotalFinalPriceSFComponent'] }}</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="d-none"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Consumables" role="tabpanel">
                                    <!-- TAB CONSUMABLES -->
                                    <div style="padding: 10px;">
                                        @if ($data['CalculationType'] != "New PN")
                                        <a class="btn btn-danger btn-sm pull-right mb-2"
                                            href="{{ url('cogs-get-consumables/'.$data['COGSID']) }}"><i
                                                class="fa fa-download"></i></a>
                                        <a class="btn addConsumables btn-primary btn-sm pull-right mb-2 mr-2" href="#">
                                            <i class="fa fa-plus"></i></a>
                                        @else
                                        <a class="btn addConsumables btn-primary btn-sm pull-right mb-2" href="#">
                                            <i class="fa fa-plus"></i></a>
                                        @endif
                                        <table id="datatable-visibility-form-consumables"
                                            class="table text-center table-striped" cellspacing="0" width="100%"
                                            data-role="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Component</th>
                                                    <th>Description</th>
                                                    <th>Category</th>
                                                    <th>Price</th>
                                                    <th>Currency</th>
                                                    <th>Qty</th>
                                                    <th>Un</th>
                                                    <th>Tax (%)</th>
                                                    <th>Total Price (IDR)</th>
                                                    <th>Last Transaction</th>
                                                    <th>Manual Adjustment</th>
                                                    <th>Final Price (IDR)</th>
                                                    <th>Action</th>
                                                    <th></th>
                                                    <th class="d-none"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($data['DataCOGSConsumables']))
                                                @foreach ($data['DataCOGSConsumables'] as $i => $item)
                                                <tr class="baseBlock">
                                                    <td>{{ $i+1 }}</td>
                                                    <td><b>{{ $item->Component }}</b></td>
                                                    <td>{{ $item->Description }}</td>
                                                    <td>{{ $item->Category }}</td>
                                                    <td>{{ $item->Price }}</td>
                                                    <td>{{ $item->Currency }}</td>
                                                    <td>{{ $item->Qty }}</td>
                                                    <td>{{ $item->Un }}</td>
                                                    <td>{{ $item->Tax }}</td>
                                                    <td>{{ $item->TotalPrice }}</td>
                                                    <td>{{ $item->LastTransaction }}</td>
                                                    <td>{{ $item->ManualAdjustment }}</td>
                                                    <td>{{ $item->FinalPrice }}</td>
                                                    <td>
                                                        <a href="#" class="editConsumables" data-toggle="modal"
                                                            IDConsumables="{{ $item->ID }}"><i
                                                                class=" fa fa-pencil fa-lg"></i></a>
                                                        @if ($data['CalculationType'] == 'New PN')
                                                        <a href="#" class="deleteConsumables" data-toggle="modal"
                                                            IDConsumables="{{ $item->ID }}"><i
                                                                class=" fa fa-trash fa-lg"></i></a>
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td></td>
                                                    <td class="d-none">{{ $item->ID }}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>{{ $data['TotalFinalPriceConsumables'] }}</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="d-none"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Manhour" role="tabpanel">
                                    <!-- TAB MANHOUR -->
                                    <div style="padding: 10px;">
                                        <div style="padding: 10px;">
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="form-group text-left htmlRateManhour">
                                                        <label class="form-control-label"> Rate Manhour
                                                            (IDR): *</label>
                                                        <input id="RateManhour" name="RateManhour" type="text"
                                                            class="form-control d-none" autocomplete="off" value=""
                                                            required readonly>
                                                        <input id="RateManhourFormat" name="RateManhourFormat"
                                                            type="text" class="form-control" autocomplete="off" value=""
                                                            required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group text-left htmlButtonRateManhour">
                                                        <button id="ButtonRateManhour"
                                                            class="btn btn-info btn-sm pull-left mb-2"
                                                            style="margin-top:27px;"><i class="fa fa-edit"></i> Change
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    @if ($data['CalculationType'] != "New PN")
                                                    <a class="btn btn-danger btn-sm pull-right mb-2 getMasterProcess"
                                                        href="#"><i class="fa fa-download"></i></a>
                                                    <a class="btn addProcess btn-primary btn-sm pull-right mb-2 mr-2"
                                                        href="#"><i class="fa fa-plus"></i></a>
                                                    @else
                                                    <a class="btn addProcess btn-primary btn-sm pull-right mb-2 mr-2"
                                                        href="#"><i class="fa fa-plus"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-top: 10px;">
                                            <table id="datatable-visibility-form-manhour"
                                                class="table text-center table-striped" cellspacing="0" width="100%"
                                                data-role="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Process</th>
                                                        <th>Um</th>
                                                        <th>Hours</th>
                                                        <th>Rate Manhour</th>
                                                        <th>Cost (IDR)</th>
                                                        <th>Action</th>
                                                        @if ($data['CalculationType'] == 'New PN')

                                                        @else
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($data['DataCOGSMasterProcess']))
                                                    @foreach ($data['DataCOGSMasterProcess'] as $i => $item)
                                                    <tr class="baseBlock">
                                                        <td>{{ $i+1 }}</td>
                                                        <td>{{ $item->Process }}</td>
                                                        <td>{{ $item->Um }}</td>
                                                        <td>{{ $item->Hours }}</td>
                                                        <td>{{ $item->RateManhour }}</td>
                                                        <td>{{ $item->Cost }}</td>
                                                        <td>
                                                            <a href="#" class="editProcess" data-toggle="modal"
                                                                IDProcess="{{ $item->ID }}"><i
                                                                    class=" fa fa-pencil fa-lg"></i></a>
                                                            <a href="#" class="deleteProcess" data-toggle="modal"
                                                                IDProcess="{{ $item->ID }}"><i
                                                                    class=" fa fa-trash fa-lg"></i></a>
                                                        </td>
                                                        @if ($data['CalculationType'] == 'New PN')
                                                        @else
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        @endif
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th></th>
                                                        <th>{{ $data['TotalHours'] }}</th>
                                                        <th></th>
                                                        <th>{{ $data['TotalCostProcess'] }}</th>
                                                        <th></th>
                                                        @if ($data['CalculationType'] == 'New PN')

                                                        @else
                                                        @endif
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Others" role="tabpanel">
                                    <!-- TAB OTHERS -->
                                    <div style="padding: 10px;">
                                        <a class="btn addOthers btn-primary btn-sm pull-right mb-2" href="#">
                                            <i class="fa fa-plus"></i></a>
                                        <table id="datatable-visibility-form-others"
                                            class="table text-center table-striped" cellspacing="0" width="100%"
                                            data-role="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Part Number</th>
                                                    <th>Description</th>
                                                    <th>Price</th>
                                                    <th>Currency</th>
                                                    <th>Tax (%)</th>
                                                    <th>Qty</th>
                                                    <th>Un</th>
                                                    <th>Total Price (IDR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($data['DataCOGSOthers']))
                                                @foreach ($data['DataCOGSOthers'] as $i => $item)
                                                <tr class="baseBlock">
                                                    <td>{{ $i+1 }}</td>
                                                    <td>{{ $item->PartNumber }}</td>
                                                    <td>{{ $item->Description }}</td>
                                                    <td>{{ $item->Price }}</td>
                                                    <td>{{ $item->Currency }}</td>
                                                    <td>{{ $item->Tax }}</td>
                                                    <td>{{ $item->Qty }}</td>
                                                    <td>{{ $item->Un }}</td>
                                                    <td>{{ $item->TotalPrice }}</td>
                                                    <td>
                                                        <a href="#" class="editOthers" data-toggle="modal"
                                                            IDOthers="{{ $item->ID }}"><i
                                                                class=" fa fa-pencil fa-lg"></i></a>
                                                        <a href="#" class="deleteOthers" data-toggle="modal"
                                                            IDOthers="{{ $item->ID }}"><i
                                                                class=" fa fa-trash fa-lg"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>{{ $data['TotalFinalPriceOthers'] }}</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="PDF" role="tabpanel">
                                    <!-- TAB CALCULATE -->
                                    <div style="padding: 10px;">
                                        <table id="datatable-visibility-amount"
                                            class="table text-center table-striped table-bordered" cellspacing="0"
                                            width="100%" data-role="table">
                                            <thead>
                                                <tr>
                                                    <th>Raw Material</th>
                                                    <th>S/F & Component</th>
                                                    <th>Consumables</th>
                                                    <th>Man Hour</th>
                                                    <th>Others</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr class="baseBlock"> -->
                                                <tr>
                                                    <td>{{ $data['TotalFinalCostRawMaterial'] }}</td>
                                                    <td>{{ $data['TotalFinalPriceSFComponent'] }}</td>
                                                    <td>{{ $data['TotalFinalPriceConsumables'] }}</td>
                                                    <td>{{ $data['TotalCostProcess'] }}</td>
                                                    <td>{{ $data['TotalFinalPriceOthers'] }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div>
                                            <div class="row mt-5">
                                                <div class="col-6">
                                                    <div>
                                                        <form method="post"
                                                            action="{{url('cogs-update-calculated-header') }}"
                                                            enctype="multipart/form-data">
                                                            {{csrf_field()}}
                                                            <input id="COGSID" name="COGSID" class="form-control d-none"
                                                                type="text" value="{{ $data['COGSID'] }}" readonly>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label class="form-control-label my-2">
                                                                        Calculated By
                                                                    </label>
                                                                </div>
                                                                <div class="col-6">
                                                                    <select name="CalculatedBy" id="CalculatedBy"
                                                                        class="form-control" required
                                                                        onchange="actionCalculatedBy()">
                                                                        @foreach ($data['DataCalculatedBy']
                                                                        as $i =>
                                                                        $item)
                                                                        <option value="{{ $item }}">
                                                                            {{ $item }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col-6">
                                                                    <label class="form-control-label my-2">
                                                                        Direct Material
                                                                    </label>
                                                                </div>
                                                                <div class="col-6">
                                                                    <input id="DirectMaterial" name="DirectMaterial"
                                                                        class="form-control d-none" type="text"
                                                                        value="{{ $data['TotalDirectMaterial'] }}"
                                                                        readonly>
                                                                    <input id="DirectMaterialFormat"
                                                                        name="DirectMaterial" class="form-control"
                                                                        type="text" value="" readonly
                                                                        style="font-weight: bold;">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-6">
                                                                    <label class="form-control-label my-2">
                                                                        Direct Labour
                                                                    </label>
                                                                </div>
                                                                <div class="col-6">
                                                                    <input id="DirectLabour" name="DirectLabour"
                                                                        class="form-control d-none" type="text"
                                                                        value="{{ $data['TotalDirectLabour'] }}"
                                                                        readonly>
                                                                    <input id="DirectLabourFormat" name="DirectLabour"
                                                                        class="form-control" type="text" value=""
                                                                        readonly style="font-weight: bold;">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-6">
                                                                    <label class="form-control-label my-2">
                                                                        Total COGS
                                                                    </label>
                                                                </div>
                                                                <div class="col-6">
                                                                    <input id="TotalCOGS" name="TotalCOGS"
                                                                        class="form-control d-none" type="text"
                                                                        value="{{ $data['TotalCOGS'] }}" readonly>
                                                                    <input id="TotalCOGSFormat" name="TotalCOGS"
                                                                        class="form-control" type="text" value=""
                                                                        readonly style="font-weight: bold;">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-6">
                                                                    <label class="form-control-label my-2">
                                                                        Gross Profit (%)
                                                                    </label>
                                                                </div>
                                                                <div class="col-6 htmlGrossProfit">

                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-6">
                                                                    <label class="form-control-label my-2">
                                                                        Quotation Price (IDR)
                                                                    </label>
                                                                </div>
                                                                <div class="col-6 htmlQuotationPrice">

                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-12">
                                                                    <button style="color:white;"
                                                                        class="btn btn-success btn-sm pull-right mb-2"
                                                                        id="saveCalculateCOGS" style="cursor: pointer;">
                                                                        <i class="fa fa-check fa-xs"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="col-6" style="text-align: center;">
                                                    <form method="post" action="{{url('cogs-pdf') }}"
                                                        enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <input id="COGSIDPDF" name="COGSIDPDF" type="text"
                                                            class="form-control d-none" autocomplete="off"
                                                            value="{{ $data['COGSID'] }}" readonly>
                                                        <input id="ArraySFComponent" name="ArraySFComponent"
                                                            class="form-control d-none" value="">
                                                        <input id="ArrayConsumables" name="ArrayConsumables"
                                                            class="form-control d-none" value="">
                                                        <button id="createPDF" class="btn btn-warning btn-md"
                                                            style="position: absolute; top: 50%; color:#61481C ;"
                                                            formtarget="_blank" data-toggle="tooltip">
                                                            <i class="fa fa-file-pdf-o fa-2x mt-1"><b></i></b>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- ##########################################################################################################################
##########################################################################################################################
########################################################################################################################## -->


<!-- RAWMATERIAL -->
<!-- FORM ADD RAWMATERIAL -->
<div class="modal fade" id="modalAddRawMaterial">
    <form method="post" action="{{url('cogs-add-rawmaterial')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h2 class="modal-title">Add Raw Material</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Spesification: *</label>
                            <input id="addSpesificationRaw" name="addSpesificationRaw" type="text" class="form-control"
                                autocomplete="off" value="" required>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Un: *</label>
                            <input id="addUnRaw" name="addUnRaw" type="text" class="form-control AlfabethInput"
                                autocomplete="off" value="" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Price <sub>/ un</sub>: *</label>
                                    <input id="addPriceRaw" name="addPriceRaw" type="text"
                                        class="form-control NumberInputComma" autocomplete="off" value="" required
                                        onkeyup="actionCalculateAddRawMaterial()">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Currency: *</label>
                                    <select name="addCurrencyRaw" id="addCurrencyRaw" class="form-control" required
                                        onchange="actionCalculateAddRawMaterial()">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Status Price: *</label>
                                    <select name="addStatusPriceRaw" id="addStatusPriceRaw" class="form-control"
                                        required onchange="actionCalculateAddRawMaterial()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Kurs: *</label>
                                    <input id="addKursRaw" name="addKursRaw" type="text" class="form-control d-none"
                                        autocomplete="off" value="" required readonly>
                                    <input id="addKursShowRaw" name="addKursShowRaw" type="text" class="form-control"
                                        autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-left">
                            <label class="form-control-label"> Weight (Kg-gross): *</label>
                            <input id="addWeightRaw" name="addWeightRaw" type="text"
                                class="form-control NumberInputComma" autocomplete="off" value="" required
                                onkeyup="actionCalculateAddRawMaterial()">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Final Cost (IDR): *</label>
                            <input id="addFinalCostRaw" name="addFinalCostRaw" type="text" class="form-control d-none"
                                autocomplete="off" value="" required readonly>
                            <input id="addFinalCostShowRaw" name="addFinalCostShowRaw" type="text" class="form-control"
                                autocomplete="off" value="" required readonly>
                        </div>
                    </div>
                    <input id="addCOGSIDRaw" name="addCOGSIDRaw" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM EDIT STATUS RAWMATERIAL -->
<div class="modal fade" id="modalEditStatusRawMaterial">
    <form method="post" action="{{url('cogs-edit-status-rawmaterial')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h2 class="modal-title">Edit Raw Material</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Spesification: *</label>
                            <input id="editStatusSpesification" name="editStatusSpesification" type="text"
                                class="form-control" autocomplete="off" value="" required readonly>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Weight (Kg-gross): *</label>
                            <input id="editStatusWeight" name="editStatusWeight" type="text" class="form-control"
                                autocomplete="off" value="" required readonly>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Price <sub>/ un</sub>: *</label>
                                    <input id="editStatusPrice" name="editStatusPrice" type="text" class="form-control"
                                        autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Currency: *</label>
                                    <input id="editStatusCurrency" name="editStatusCurrency" type="text"
                                        class="form-control" autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Status Price: *</label>
                                    <select name="editStatusStatusPrice" id="editStatusStatusPrice" class="form-control"
                                        required onchange="actionCalculateEditStatusRawMaterial()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Kurs: *</label>
                                    <input id="editStatusKurs" name="editStatusKurs" type="text"
                                        class="form-control d-none" autocomplete="off" value="" required readonly>
                                    <input id="editStatusKursShow" name="editStatusKursShow" type="text"
                                        class="form-control" autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Un: *</label>
                            <input id="editStatusUn" name="editStatusUn" type="text" class="form-control"
                                autocomplete="off" value="" required readonly>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Final Cost (IDR): *</label>
                            <input id="editStatusFinalCost" name="editStatusFinalCost" type="text"
                                class="form-control d-none" autocomplete="off" value="" required readonly>
                            <input id="editStatusFinalCostShow" name="editStatusFinalCostShow" type="text"
                                class="form-control" autocomplete="off" value="" required readonly>
                        </div>
                    </div>
                    <input id="editIDStatusRaw" name="editIDStatusRaw" type="hidden" value="">
                    <input id="editCOGSIDStatusRaw" name="editCOGSIDStatusRaw" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM EDIT RAWMATERIAL -->
<div class="modal fade" id="modalEditRawMaterial">
    <form method="post" action="{{url('cogs-edit-rawmaterial')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h2 class="modal-title">Edit Raw Material</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="form-group text-left">
                            <label class="form-control-label"> Spesification: *</label>
                            <input id="editSpesificationRaw" name="editSpesificationRaw" type="text"
                                class="form-control" autocomplete="off" value="" required>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Un: *</label>
                            <input id="editUnRaw" name="editUnRaw" type="text" class="form-control AlfabethInput"
                                autocomplete="off" value="" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Price <sub>/ un</sub>: *</label>
                                    <input id="editPriceRaw" name="editPriceRaw" type="text"
                                        class="form-control NumberInputComma" autocomplete="off" value="" required
                                        onkeyup="actionCalculateEditRawMaterial()">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Currency: *</label>
                                    <select name="editCurrencyRaw" id="editCurrencyRaw" class="form-control" required
                                        onchange="actionCalculateEditRawMaterial()">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Status Price: *</label>
                                    <select name="editStatusPriceRaw" id="editStatusPriceRaw" class="form-control"
                                        required onchange="actionCalculateEditRawMaterial()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Kurs: *</label>
                                    <input id="editKursRaw" name="editKursRaw" type="text" class="form-control d-none"
                                        autocomplete="off" value="" required readonly>
                                    <input id="editKursShowRaw" name="editKursShowRaw" type="text" class="form-control"
                                        autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Weight (Kg-gross): *</label>
                            <input id="editWeightRaw" name="editWeightRaw" type="text"
                                class="form-control NumberInputComma" autocomplete="off" value="" required
                                onkeyup="actionCalculateEditRawMaterial()">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label"> Final Cost (IDR): *</label>
                            <input id="editFinalCostRaw" name="editFinalCostRaw" type="text" class="form-control d-none"
                                autocomplete="off" value="" required readonly>
                            <input id="editFinalCostShowRaw" name="editFinalCostShowRaw" type="text"
                                class="form-control" autocomplete="off" value="" required readonly>
                        </div>
                    </div>
                    <input id="editIDRaw" name="editIDRaw" type="hidden" value="">
                    <input id="editCOGSIDRaw" name="editCOGSIDRaw" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM DELETE RAWMATERIAL -->
<div class="modal fade" id="modalDeleteRawMaterial">
    <form method="post" action="{{url('cogs-delete-rawmaterial')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h2 class="modal-title">Delete Raw Material</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h3>Warning</h3>
                        <h2 id="messageDeleteRawMaterial"></h2>
                    </div>
                    <input id="deleteIDRaw" name="deleteIDRaw" type="hidden" value="">
                    <input id="deleteCOGSIDRaw" name="deleteCOGSIDRaw" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- RAW MATERIAL -->

<!-- SFCOMPONENT -->
<!-- FORM EDIT SFCOMPONENT -->
<div class="modal fade" id="modalEditSFComponent">
    <form method="post" action="{{url('cogs-edit-sfcomponent')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h2 class="modal-title">Edit S/F & Component</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Component PartNumber: *</label>
                                    <input id="editComponent" name="editComponent" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Description: *</label>
                                    <textarea id="editDescription" name="editDescription" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Category: *</label>
                                    <input id="editCategory" name="editCategory" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price: *</label>
                                            <input id="editPrice" name="editPrice" type="text" class="form-control"
                                                autocomplete="off" value="" required onkeyup="actionCalculateEdit()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Currency: *</label>
                                            <input id="editCurrency" name="editCurrency" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Kurs: *</label>
                                            <input id="editKurs" name="editKurs" type="text" class="form-control d-none"
                                                autocomplete="off" value="" required readonly>
                                            <input id="editKursShow" name="editKursShow" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price after Kurs (IDR): *</label>
                                            <input id="editPriceAfterKurs" name="editPriceAfterKurs" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="editPriceAfterKursShow" name="editPriceAfterKursShow" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Qty: *</label>
                                            <input id="editQty" name="editQty" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateEdit()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Un: *</label>
                                            <input id="editUn" name="editUn" type="text"
                                                class="form-control AlfabethInput" autocomplete="off" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Tax (%): *</label>
                                            <input id="editTax" name="editTax" type="texttext"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateEdit()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Total Price (IDR): *</label>
                                            <input id="editTotalPrice" name="editTotalPrice" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="editTotalPriceShow" name="editTotalPriceShow" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Last Transaction: *</label>
                                    <input id="editLastTransaction" name="editLastTransaction" type="date"
                                        class="form-control" autocomplete="off" value="" required readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Manual Adjustment: *</label>
                                            <input id="editManualAdjustment" name="editManualAdjustment" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateEdit()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Final Price (IDR): *</label>
                                            <input id="editFinalPrice" name="editFinalPrice" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="editFinalPriceShow" name="editFinalPriceShow" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="editID" name="editID" type="hidden" value="">
                    <input id="editCOGSID" name="editCOGSID" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM ADD SFCOMPONENT -->
<div class="modal fade" id="modalAddSFComponent">
    <form method="post" action="{{url('cogs-add-sfcomponent')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h2 class="modal-title">Add S/F & Component</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Component Part Number: *</label>
                                    <input id="addComponent" name="addComponent" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Description: *</label>
                                    <textarea id="addDescription" name="addDescription" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Category: *</label>
                                    <input id="addCategory" name="addCategory" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price: *</label>
                                            <input id="addPrice" name="addPrice" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAdd()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div id="htmlCurrency" class="form-group text-left">
                                            <label class="form-control-label"> Currency: *</label>
                                            <input id="addCurrency" name="addCurrency" type="text" class="form-control"
                                                autocomplete="off" value="" required onkeyup="actionCalculateAdd()">
                                        </div>
                                    </div>
                                </div>
                                <div class=" row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Kurs: *</label>
                                            <input id="addKurs" name="addKurs" type="text" class="form-control d-none"
                                                autocomplete="off" value="" required readonly>
                                            <input id="addKursShow" name="addKursShow" type="text" class="form-control"
                                                autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price after Kurs (IDR): *</label>
                                            <input id="addPriceAfterKurs" name="addPriceAfterKurs" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="addPriceAfterKursShow" name="addPriceAfterKursShow" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Qty: *</label>
                                            <input id="addQty" name="addQty" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAdd()">
                                        </div>
                                    </div>
                                    <div class=" col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Un: *</label>
                                            <input id="addUn" name="addUn" type="text"
                                                class="form-control AlfabethInput" autocomplete="off" value=""
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Tax (%): *</label>
                                            <input id="addTax" name="addTax" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAdd()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Total Price (IDR): *</label>
                                            <input id="addTotalPrice" name="addTotalPrice" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="addTotalPriceShow" name="addTotalPriceShow" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Last Transaction: *</label>
                                    <input id="addLastTransaction" name="addLastTransaction" type="date"
                                        class="form-control datepicker" autocomplete="off" value="" required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Manual Adjustment: *</label>
                                            <input id="addManualAdjustment" name="addManualAdjustment" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAdd()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Final Price (IDR): *</label>
                                            <input id="addFinalPrice" name="addFinalPrice" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="addFinalPriceShow" name="addFinalPriceShow" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="addCOGSID" name="addCOGSID" type="hidden" value="">
                    <input id="addPNReference" name="addPNReference" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM DELETE SFCOMPONENT -->
<div class="modal fade" id="modalDeleteSFComponent">
    <form method="post" action="{{url('cogs-delete-sfcomponent')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h2 class="modal-title">Delete S/F & Component</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h3>Warning</h3>
                        <h2 id="messageDeleteSFComponent"></h2>
                    </div>
                    <input id="deleteID" name="deleteID" type="hidden" value="">
                    <input id="deleteCOGSID" name="deleteCOGSID" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- SFCOMPONENT -->

<!-- CONSUMABLES -->
<!-- FORM EDIT CONSUMABLES -->
<div class="modal fade" id="modalEditConsumables">
    <form method="post" action="{{url('cogs-edit-consumables')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h2 class="modal-title">Edit Consumables</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Component PartNumber: *</label>
                                    <input id="editComponentConsumables" name="editComponentConsumables" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Description: *</label>
                                    <textarea id="editDescriptionConsumables" name="editDescriptionConsumables"
                                        type="text" class="form-control" autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Category: *</label>
                                    <input id="editCategoryConsumables" name="editCategoryConsumables" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price: *</label>
                                            <input id="editPriceConsumables" name="editPriceConsumables" type="text"
                                                class="form-control" autocomplete="off" value="" required
                                                onkeyup="actionCalculateEditConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Currency: *</label>
                                            <input id="editCurrencyConsumables" name="editCurrencyConsumables"
                                                Consumables type="text" class="form-control" autocomplete="off" value=""
                                                required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Kurs: *</label>
                                            <input id="editKursConsumables" name="editKursConsumables" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="editKursShowConsumables" name="editKursShowConsumables"
                                                type="text" class="form-control" autocomplete="off" value="" required
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price after Kurs (IDR): *</label>
                                            <input id="editPriceAfterKursConsumables"
                                                name="editPriceAfterKursConsumables" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="editPriceAfterKursShowConsumables"
                                                name="editPriceAfterKursShowConsumables" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Qty: *</label>
                                            <input id="editQtyConsumables" name="editQtyConsumables" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateEditConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Un: *</label>
                                            <input id="editUnConsumables" name="editUnConsumables" type="text"
                                                class="form-control AlfabethInput" autocomplete="off" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Tax (%): *</label>
                                            <input id="editTaxConsumables" name="editTaxConsumables" type="texttext"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateEditConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Total Price (IDR): *</label>
                                            <input id="editTotalPriceConsumables" name="editTotalPriceConsumables"
                                                type="text" class="form-control d-none" autocomplete="off" value=""
                                                required readonly>
                                            <input id="editTotalPriceShowConsumables"
                                                name="editTotalPriceShowConsumables" type="text" class="form-control"
                                                autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Last Transaction: *</label>
                                    <input id="editLastTransactionConsumables" name="editLastTransactionConsumables"
                                        type="date" class="form-control" autocomplete="off" value="" required readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Manual Adjustment: *</label>
                                            <input id="editManualAdjustmentConsumables"
                                                name="editManualAdjustmentConsumables" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateEditConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Final Price (IDR): *</label>
                                            <input id="editFinalPriceConsumables" name="editFinalPriceConsumables"
                                                type="text" class="form-control d-none" autocomplete="off" value=""
                                                required readonly>
                                            <input id="editFinalPriceShowConsumables"
                                                name="editFinalPriceShowConsumables" type="text" class="form-control"
                                                autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="editIDConsumables" name="editIDConsumables" type="hidden" value="">
                    <input id="editCOGSIDConsumables" name="editCOGSIDConsumables" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM ADD CONSUMABLES -->
<div class="modal fade" id="modalAddConsumables">
    <form method="post" action="{{url('cogs-add-consumables')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h2 class="modal-title">Add Consumables</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Component Part Number: *</label>
                                    <input id="addComponentConsumables" name="addComponentConsumables" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Description: *</label>
                                    <textarea id="addDescriptionConsumables" name="addDescriptionConsumables"
                                        type="text" class="form-control" autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Category: *</label>
                                    <input id="addCategoryConsumables" name="addCategoryConsumables" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price: *</label>
                                            <input id="addPriceConsumables" name="addPriceConsumables" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAddConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div id="htmlCurrencyConsumables" class="form-group text-left">
                                            <label class="form-control-label"> Currency: *</label>
                                            <input id="addCurrencyConsumables" name="addCurrencyConsumables" type="text"
                                                class="form-control" autocomplete="off" value="" required
                                                onkeyup="actionCalculateAddConsumables()">
                                        </div>
                                    </div>
                                </div>
                                <div class=" row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Kurs: *</label>
                                            <input id="addKursConsumables" name="addKursConsumables" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="addKursShowConsumables" name="addKursShowConsumables" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price after Kurs (IDR): *</label>
                                            <input id="addPriceAfterKursConsumables" name="addPriceAfterKursConsumables"
                                                type="text" class="form-control d-none" autocomplete="off" value=""
                                                required readonly>
                                            <input id="addPriceAfterKursShowConsumables"
                                                name="addPriceAfterKursShowConsumables" type="text" class="form-control"
                                                autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Qty: *</label>
                                            <input id="addQtyConsumables" name="addQtyConsumables" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAddConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Un: *</label>
                                            <input id="addUnConsumables" name="addUnConsumables" type="text"
                                                class="form-control AlfabethInput" autocomplete="off" value=""
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Tax (%): *</label>
                                            <input id="addTaxConsumables" name="addTaxConsumables" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAddConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Total Price (IDR): *</label>
                                            <input id="addTotalPriceConsumables" name="addTotalPriceConsumables"
                                                type="text" class="form-control d-none" autocomplete="off" value=""
                                                required readonly>
                                            <input id="addTotalPriceShowConsumables" name="addTotalPriceShowConsumables"
                                                type="text" class="form-control" autocomplete="off" value="" required
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Last Transaction: *</label>
                                    <input id="addLastTransactionConsumables" name="addLastTransactionConsumables"
                                        type="date" class="form-control datepicker" autocomplete="off" value=""
                                        required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Manual Adjustment: *</label>
                                            <input id="addManualAdjustmentConsumables"
                                                name="addManualAdjustmentConsumables" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                required onkeyup="actionCalculateAddConsumables()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Final Price (IDR): *</label>
                                            <input id="addFinalPriceConsumables" name="addFinalPriceConsumables"
                                                type="text" class="form-control d-none" autocomplete="off" value=""
                                                required readonly>
                                            <input id="addFinalPriceShowConsumables" name="addFinalPriceShowConsumables"
                                                type="text" class="form-control" autocomplete="off" value="" required
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="addCOGSIDConsumables" name="addCOGSIDConsumables" type="hidden" value="">
                    <input id="addPNReferenceConsumables" name="addPNReferenceConsumables" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM DELETE SFCOMPONENT -->
<div class="modal fade" id="modalDeleteConsumables">
    <form method="post" action="{{url('cogs-delete-consumables')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h2 class="modal-title">Delete Consumables</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h3>Warning</h3>
                        <h2 id="messageDeleteConsumables"></h2>
                    </div>
                    <input id="deleteIDConsumables" name="deleteIDConsumables" type="hidden" value="">
                    <input id="deleteCOGSIDConsumables" name="deleteCOGSIDConsumables" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- CONSUMABLES -->
<!-- MANHOUR -->
<!-- FORM VIEW MANHOUR -->
<div class="modal fade p-0" tabindex="-1" role="dialog" id="modalMasterProcessData">
    <form method=" post" action="{{url('cogs-get-masterprocess')}}" enctype="multipart/form-data" style="height:100%">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg h-100 my-0 mx-auto d-flex flex-column justify-content-center" role="document"
            style="max-width: 50%; max-height: 100%; overflow-y: initial !important">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h2 class="modal-title">Master Process Data</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left" style="height: 80vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group text-left htmlMPTName">
                                <label class="my-2">Select MPT: * </label>
                                <select id="MPTName" name="MPTName" class="form-control" style="width: 100%">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left">
                                <label class="my-2">Rate MH: * </label>
                                <input id="RateMH" name="RateMH" type="text" class="form-control d-none"
                                    autocomplete="off" value="" required readonly>
                                <input id="RateMHFormat" name="RateMHFormat" type="text" class="form-control"
                                    autocomplete="off" value="" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group text-left">
                                <label class="my-2">Active ? </label>
                                <input id="IsActive" name="IsActive" type="text" class="form-control" autocomplete="off"
                                    value="" required readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group text-left">
                                <label class="my-2">Day Calculated ? </label>
                                <input id="IsTotalDayCalculated" name="IsTotalDayCalculated" type="text"
                                    class="form-control" autocomplete="off" value="" required readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group text-left">
                                <label class="my-2">Total Day: * </label>
                                <input id="TotalDay" name="TotalDay" type="text" class="form-control" autocomplete="off"
                                    value="" required readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group text-left">
                                <label class="my-2">Version: * </label>
                                <input id="Version" name="Version" type="text" class="form-control" autocomplete="off"
                                    value="" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="tableMasterProcess" class="mt-3">
                            </div>
                        </div>
                    </div>
                </div>
                <input id="COGSIDProcess" name="COGSIDProcess" type="hidden" value="">
                <input id="PNReferenceProcess" name="PNReferenceProcess" type="hidden" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM ADD PROCESS -->
<div class="modal fade" id="modalAddProcess">
    <form method="post" action="{{url('cogs-add-process')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h2 class="modal-title">Add Process</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Process: *</label>
                                    <textarea id="addProcessProcess" name="addProcessProcess" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Um: *</label>
                                    <input id="addUmProcess" name="addUmProcess" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Hours: *</label>
                                    <input id="addHoursProcess" name="addHoursProcess" type="text"
                                        class="form-control NumberInputComma" autocomplete="off" value=""
                                        onkeyup="actionCalculateAddProcess()" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Rate MH (IDR): *</label>
                                    <input id="addRateMHProcess" name="addRateMHProcess" type="text"
                                        class="form-control NumberInputComma" autocomplete="off" value=""
                                        onkeyup="actionCalculateAddProcess()" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Cost (IDR): *</label>
                                    <input id="addCostProcess" name="addCostProcess" type="text"
                                        class="form-control d-none" autocomplete="off" value="" required readonly>
                                    <input id="addCostProcessFormat" name="addCostProcessFormat" type="text"
                                        class="form-control" autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="addCOGSIDProcess" name="addCOGSIDProcess" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM UPDATE PROCESS -->
<div class="modal fade" id="modalEditProcess">
    <form method="post" action="{{url('cogs-edit-process')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h2 class="modal-title">Edit Process</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Process: *</label>
                                    <textarea id="editProcessProcess" name="editProcessProcess" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Um: *</label>
                                    <input id="editUmProcess" name="editUmProcess" type="text" class="form-control"
                                        autocomplete="off" value="" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Hours: *</label>
                                    <input id="editHoursProcess" name="editHoursProcess" type="text"
                                        class="form-control NumberInputComma" autocomplete="off" value=""
                                        onkeyup="actionCalculateEditProcess()" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Rate MH (IDR): *</label>
                                    <input id="editRateMHProcess" name="editRateMHProcess" type="text"
                                        class="form-control NumberInputComma" autocomplete="off" value=""
                                        onkeyup="actionCalculateEditProcess()" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Cost (IDR): *</label>
                                    <input id="editCostProcess" name="editCostProcess" type="text"
                                        class="form-control d-none" autocomplete="off" value="" required readonly>
                                    <input id="editCostProcessFormat" name="editCostProcessFormat" type="text"
                                        class="form-control" autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="editIDProcess" name="editIDProcess" type="hidden" value="">
                    <input id="editCOGSIDProcess" name="editCOGSIDProcess" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM DELETE PROCESS -->
<div class="modal fade" id="modalDeleteProcess">
    <form method="post" action="{{url('cogs-delete-process')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h2 class="modal-title">Delete Process</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h3>Warning</h3>
                        <h2 id="messageDeleteProcess"></h2>
                    </div>
                    <input id="deleteIDProcess" name="deleteIDProcess" type="hidden" value="">
                    <input id="deleteCOGSIDProcess" name="deleteCOGSIDProcess" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!--MANHOUR -->
<!-- OTHERS -->
<!-- FORM ADD OTHERS -->
<div class="modal fade" id="modalAddOthers">
    <form method="post" action="{{url('cogs-add-others')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h2 class="modal-title">Add Others</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> PartNumber: *</label>
                                    <input id="addPartNumberOthers" name="addPartNumberOthers" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Description: *</label>
                                    <textarea id="addDescriptionOthers" name="addDescriptionOthers" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price: *</label>
                                            <input id="addPriceOthers" name="addPriceOthers" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                onkeyup="actionCalculateAddOthers()" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div id="htmlCurrencyAddOthers" class="form-group text-left">
                                            <label class="form-control-label"> Currency: *</label>
                                            <input id="addCurrencyOthers" name="addCurrencyOthers" type="text"
                                                class="form-control" autocomplete="off" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class=" row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Kurs: *</label>
                                            <input id="addKursOthers" name="addKursOthers" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="addKursShowOthers" name="addKursShowOthers" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price after Kurs (IDR): *</label>
                                            <input id="addPriceAfterKursOthers" name="addPriceAfterKursOthers"
                                                type="text" class="form-control d-none" autocomplete="off" value=""
                                                required readonly>
                                            <input id="addPriceAfterKursShowOthers" name="addPriceAfterKursShowOthers"
                                                type="text" class="form-control" autocomplete="off" value="" required
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Qty: *</label>
                                            <input id="addQtyOthers" name="addQtyOthers" type="text"
                                                class="form-control NumberInputComma" autocomplete="off"
                                                onkeyup="actionCalculateAddOthers()" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Un: *</label>
                                            <input id="addUnOthers" name="addUnOthers" type="text"
                                                class="form-control AlfabethInput" autocomplete="off" value=""
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Tax (%): *</label>
                                            <input id="addTaxOthers" name="addTaxOthers" type="text"
                                                class="form-control NumberInputComma" autocomplete="off"
                                                onkeyup="actionCalculateAddOthers()" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Total Price (IDR): *</label>
                                            <input id="addTotalPriceOthers" name="addTotalPriceOthers" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="addTotalPriceShowOthers" name="addTotalPriceShowOthers"
                                                type="text" class="form-control" autocomplete="off" value="" required
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="addCOGSIDOthers" name="addCOGSIDOthers" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM EDIT OTHERS -->
<div class="modal fade" id="modalEditOthers">
    <form method="post" action="{{url('cogs-edit-others')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h2 class="modal-title">Edit Others</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> PartNumber: *</label>
                                    <input id="editPartNumberOthers" name="editPartNumberOthers" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                </div>
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Description: *</label>
                                    <textarea id="editDescriptionOthers" name="editDescriptionOthers" type="text"
                                        class="form-control" autocomplete="off" value="" required>
                                    </textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price: *</label>
                                            <input id="editPriceOthers" name="editPriceOthers" type="text"
                                                class="form-control NumberInputComma" autocomplete="off" value=""
                                                onkeyup="actionCalculateEditOthers()" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div id="htmlCurrencyEditOthers" class="form-group text-left">
                                            <label class="form-control-label"> Currency: *</label>
                                            <input id="editCurrencyOthers" name="editCurrencyOthers" type="text"
                                                class="form-control" autocomplete="off" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class=" row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Kurs: *</label>
                                            <input id="editKursOthers" name="editKursOthers" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="editKursShowOthers" name="editKursShowOthers" type="text"
                                                class="form-control" autocomplete="off" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Price after Kurs (IDR): *</label>
                                            <input id="editPriceAfterKursOthers" name="editPriceAfterKursOthers"
                                                type="text" class="form-control d-none" autocomplete="off" value=""
                                                required readonly>
                                            <input id="editPriceAfterKursShowOthers" name="editPriceAfterKursShowOthers"
                                                type="text" class="form-control" autocomplete="off" value="" required
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Qty: *</label>
                                            <input id="editQtyOthers" name="editQtyOthers" type="text"
                                                class="form-control NumberInputComma" autocomplete="off"
                                                onkeyup="actionCalculateEditOthers()" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Un: *</label>
                                            <input id="editUnOthers" name="editUnOthers" type="text"
                                                class="form-control AlfabethInput" autocomplete="off" value=""
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Tax (%): *</label>
                                            <input id="editTaxOthers" name="editTaxOthers" type="text"
                                                class="form-control NumberInputComma" autocomplete="off"
                                                onkeyup="actionCalculateEditOthers()" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group text-left">
                                            <label class="form-control-label"> Total Price (IDR): *</label>
                                            <input id="editTotalPriceOthers" name="editTotalPriceOthers" type="text"
                                                class="form-control d-none" autocomplete="off" value="" required
                                                readonly>
                                            <input id="editTotalPriceShowOthers" name="editTotalPriceShowOthers"
                                                type="text" class="form-control" autocomplete="off" value="" required
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="editIDOthers" name="editIDOthers" type="hidden" value="">
                    <input id="editCOGSIDOthers" name="editCOGSIDOthers" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FORM DELETE OTHERS -->
<div class="modal fade" id="modalDeleteOthers">
    <form method="post" action="{{url('cogs-delete-others')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h2 class="modal-title">Delete Others</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h3>Warning</h3>
                        <h2 id="messageDeleteOthers"></h2>
                    </div>
                    <input id="deleteIDOthers" name="deleteIDOthers" type="hidden" value="">
                    <input id="deleteCOGSIDOthers" name="deleteCOGSIDOthers" type="hidden" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- OTHERS -->
<!-- FORM VIEW ATTACHMENT -->
<div class="modal fade" id="modalAttachment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg my-0 mx-auto d-flex flex-column justify-content-center" role="document"
        style="max-width: 90%;">
        <form method="post" id="Attachment" action="" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-content m-2">
                <div class="modal-header bg-success">
                    <h5 id="modalAttachmentTitle" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalAttachmentBody" class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('myscript')
<script>
//===================================================================AUTOCOMPLETE==================================================



var componentPIR = function() {
    var componentPIR = null;
    $.ajax({
        'global': false,
        'async': false,
        url: "{{url('cogs-search-componentpir')}}",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            ComponentPIR = data.ListComponentPIR;
        }
    });
    return ComponentPIR;
}();

$('#addComponent').autocomplete({
    lookup: ComponentPIR,
    onSelect: function(item) {
        $.ajax({
            url: "{{url('cogs-get-componentpir')}}",
            type: "GET",
            dataType: "JSON",
            data: {
                materialdescription: item.value,
            },
            success: function(data) {
                function first(data) {
                    $('#addDescription').val(data.DataComponent.Description);
                    $('#addCategory').val("SFComponent");
                    $('#addPrice').val(data.DataComponent.NetPrice);
                    $('#addOptionCurrency').val(data.DataComponent.Currency);
                    second();
                };

                function second() {
                    return $.ajax({
                        url: "{{url('cogs-search-kurs-form-cogs')}}",
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            index = data.Kurs.findIndex(item => item.MataUang
                                .replaceAll(
                                    /\s/g,
                                    '') === $('#addOptionCurrency').val()
                                .replaceAll(
                                    /\s/g,
                                    ''));
                            $('#addKurs').val(data.Kurs[index].KursTengah);
                            $('#addKursShow').val(formatterIDR.format(data.Kurs[
                                    index]
                                .KursTengah));
                            actionCalculateAdd();
                        }
                    })
                };
                first(data);
            }
        });
    }
});

$('#editComponent').autocomplete({
    lookup: ComponentPIR,
    onSelect: function(item) {
        $.ajax({
            url: "{{url('cogs-get-componentpir')}}",
            type: "GET",
            dataType: "JSON",
            data: {
                materialdescription: item.value,
            },
            success: function(data) {
                function first(data) {
                    $('#editDescription').val(data.DataComponent.Description);
                    $('#editCategory').val("SFComponent");
                    $('#editPrice').val(data.DataComponent.NetPrice);
                    $('#editCurrency').val(data.DataComponent.Currency);
                    second();
                };

                function second() {
                    return $.ajax({
                        url: "{{url('cogs-search-kurs-form-cogs')}}",
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            index = data.Kurs.findIndex(item => item.MataUang
                                .replaceAll(
                                    /\s/g,
                                    '') === $('#editCurrency').val()
                                .replaceAll(
                                    /\s/g,
                                    ''));
                            $('#editKurs').val(data.Kurs[index].KursTengah);
                            $('#editKursShow').val(formatterIDR.format(data
                                .Kurs[
                                    index]
                                .KursTengah));
                            actionCalculateEdit();
                        }
                    })
                };
                first(data);
            }
        });
    }
});

$('#addComponentConsumables').autocomplete({
    lookup: ComponentPIR,
    onSelect: function(item) {
        $.ajax({
            url: "{{url('cogs-get-componentpir')}}",
            type: "GET",
            dataType: "JSON",
            data: {
                materialdescription: item.value,
            },
            success: function(data) {
                function first(data) {
                    $('#addDescriptionConsumables').val(data.DataComponent.Description);
                    $('#addCategoryConsumables').val("Consumables");
                    $('#addPriceConsumables').val(data.DataComponent.NetPrice);
                    $('#addOptionCurrencyConsumables').val(data.DataComponent.Currency);
                    second();
                };

                function second() {
                    return $.ajax({
                        url: "{{url('cogs-search-kurs-form-cogs')}}",
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            index = data.Kurs.findIndex(item => item.MataUang
                                .replaceAll(
                                    /\s/g,
                                    '') === $(
                                    '#addOptionCurrencyConsumables')
                                .val()
                                .replaceAll(
                                    /\s/g,
                                    ''));
                            $('#addKursConsumables').val(data.Kurs[index]
                                .KursTengah);
                            $('#addKursShowConsumables').val(formatterIDR
                                .format(
                                    data.Kurs[
                                        index]
                                    .KursTengah));
                            actionCalculateAddConsumables();
                        }
                    })
                };
                first(data);
            }
        });
    }
});

$('#editComponentConsumables').autocomplete({
    lookup: ComponentPIR,
    onSelect: function(item) {
        $.ajax({
            url: "{{url('cogs-get-componentpir')}}",
            type: "GET",
            dataType: "JSON",
            data: {
                materialdescription: item.value,
            },
            success: function(data) {
                function first(data) {
                    $('#editDescriptionConsumables').val(data.DataComponent.Description);
                    $('#editCategoryConsumables').val("Consumables");
                    $('#editPriceConsumables').val(data.DataComponent.NetPrice);
                    $('#editCurrencyConsumables').val(data.DataComponent.Currency);
                    second();
                };

                function second() {
                    return $.ajax({
                        url: "{{url('cogs-search-kurs-form-cogs')}}",
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            index = data.Kurs.findIndex(item => item.MataUang
                                .replaceAll(
                                    /\s/g,
                                    '') === $('#editCurrencyConsumables')
                                .val()
                                .replaceAll(
                                    /\s/g,
                                    ''));
                            $('#editKursConsumables').val(data.Kurs[index]
                                .KursTengah);
                            $('#editKursShowConsumables').val(formatterIDR
                                .format(
                                    data.Kurs[
                                        index]
                                    .KursTengah));
                            actionCalculateEditConsumables();
                        }
                    })
                };
                first(data);
            }
        });
    }
});

//===================================================================AUTOCOMPLETE==================================================
//====================================================================COGS CALCULATE==================================================

$.ajax({
    url: "{{url('cogs-search-calculated-header')}}",
    type: "GET",
    dataType: "JSON",
    data: {
        cogsid: $('#COGSID').val(),
    },
    success: function(data) {
        CalculatedBy = data.Header.CalculatedBy;
        GrossProfit = data.Header.GrossProfit;
        QuotationPrice = data.Header.QuotationPrice;
        if (GrossProfit == 0) {
            QuotationPrice = $('#TotalCOGS').val();
        }
        $("#CalculatedBy").find("[value='" +
            CalculatedBy + "']").attr("selected",
            true);
        if (CalculatedBy == "Gross Profit") {
            $('.htmlGrossProfit').html(`
                <input id="GrossProfit" name="GrossProfit" class="form-control NumberInputComma" type="text" value="">
            `);
            $('.htmlQuotationPrice').html(`
                <input id="QuotationPrice" name="QuotationPrice" class="form-control d-none" type="text" value="" readonly>
                <input id="QuotationPriceFormat" name="QuotationPriceFormat" class="form-control" type="text" value="" readonly style="font-weight: bold;">
            `);
        } else if (CalculatedBy == "Quotation Price") {
            $('.htmlGrossProfit').html(`
                <input id="GrossProfit" name="GrossProfit" class="form-control NumberInputComma" type="text" value="" readonly>
            `);
            $('.htmlQuotationPrice').html(`
                <input id="QuotationPrice" name="QuotationPrice" class="form-control" type="text" value="">
            `);
        }
        $("#GrossProfit").val(parseFloat(GrossProfit).toFixed(2));
        $("#QuotationPrice").val(parseFloat(QuotationPrice).toFixed(2));
        $("#QuotationPriceFormat").val(formatterIDR.format(parseFloat(QuotationPrice)
            .toFixed(2)));
        $("#GrossProfit").keydown(function() {
            $('#GrossProfit').on('input', () => {
                TotalCOGS = parseFloat($('#TotalCOGS').val());
                GrossProfit = $('#GrossProfit').val() == "" ? 0 : (parseFloat($(
                    '#GrossProfit').val()).toFixed(2));
                GrossProfit /= 100;
                QuotationPrice = TotalCOGS / (1 - GrossProfit);
                $('#QuotationPrice').val(QuotationPrice);
                $('#QuotationPriceFormat').val(formatterIDR.format(QuotationPrice));
            });
        });
        $("#QuotationPrice").keydown(function() {
            $('#QuotationPrice').on('input', () => {
                TotalCOGS = parseFloat($('#TotalCOGS').val());
                QuotationPrice = $('#QuotationPrice').val() == "" ? 0 : parseFloat($(
                    '#QuotationPrice').val());
                GrossProfit = (1 - (TotalCOGS / QuotationPrice)).toFixed(2) * 100;
                $('#GrossProfit').val(GrossProfit);
            });
        });

        $(".NumberInputComma").on("keypress", function(evt) {
            if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt
                .which >
                57) {
                evt.preventDefault();
            };
        });

        $(".NumberInputComma").on("input", function(evt) {
            if ($(this).val().replace(/[^.]/g, "").length == 1) {
                var decimalPlace = $(this).val().split(".")[1];
                if (decimalPlace.length > 3) {
                    original = $(this).val().slice(0, -1);
                    console.log(original);
                    $(this).val(original);
                }
            }
            if ($(this).val().replace(/[^.]/g, "").length > 1) {
                original = $(this).val().slice(0, -1);
                $(this).val(original);
            }
        });

    }
});


function actionCalculatedBy() {

    CalculatedBy = $('#CalculatedBy').val();
    if (CalculatedBy == "Gross Profit") {
        // console.log('X')
        $('.htmlGrossProfit').html(`
            <input id="GrossProfit" name="GrossProfit" class="form-control NumberInputComma" type="text" value="">
        `);
        $('.htmlQuotationPrice').html(`
            <input id="QuotationPrice" name="QuotationPrice" class="form-control d-none" type="text" value="" readonly>
            <input id="QuotationPriceFormat" name="QuotationPriceFormat" class="form-control" type="text" value="" readonly style="font-weight: bold;">
        `);
        $('#GrossProfit').val(0);
        $('#QuotationPrice').val($('#TotalCOGS').val());
        $('#QuotationPriceFormat').val(formatterIDR.format($('#TotalCOGS').val()));
        $('#GrossProfit').on('input', () => {
            TotalCOGS = parseFloat($('#TotalCOGS').val());
            GrossProfit = $('#GrossProfit').val() == "" ? 0 : (parseFloat($('#GrossProfit').val()).toFixed(
                2));
            GrossProfit /= 100;
            QuotationPrice = TotalCOGS / (1 - GrossProfit);
            $('#QuotationPrice').val(QuotationPrice);
            $('#QuotationPriceFormat').val(formatterIDR.format(QuotationPrice));
        });
    } else if (CalculatedBy == "Quotation Price") {
        // console.log('Y')
        $('.htmlGrossProfit').html(`
            <input id="GrossProfit" name="GrossProfit" class="form-control NumberInputComma" type="text" value="" readonly>
        `);
        $('.htmlQuotationPrice').html(`
            <input id="QuotationPrice" name="QuotationPrice" class="form-control" type="text" value="">
        `);
        $('#GrossProfit').val(0);
        $('#QuotationPrice').val($('#TotalCOGS').val());
        $('#QuotationPrice').on('input', () => {
            TotalCOGS = parseFloat($('#TotalCOGS').val());
            QuotationPrice = $('#QuotationPrice').val() == "" ? 0 : parseFloat($('#QuotationPrice').val());
            GrossProfit = (1 - (TotalCOGS / QuotationPrice)).toFixed(2) * 100;
            $('#GrossProfit').val(GrossProfit);
        });
    }
    $(".NumberInputComma").on("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        };
    });

    $(".NumberInputComma").on("input", function(evt) {
        if ($(this).val().replace(/[^.]/g, "").length == 1) {
            var decimalPlace = $(this).val().split(".")[1];
            if (decimalPlace.length > 3) {
                original = $(this).val().slice(0, -1);
                console.log(original);
                $(this).val(original);
            }
        }
        if ($(this).val().replace(/[^.]/g, "").length > 1) {
            original = $(this).val().slice(0, -1);
            $(this).val(original);
        }
    });
}
//====================================================================COGS CALCULATE==================================================

$(".datepicker").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true,
    orientation: "top auto",
    todayBtn: true,
    todayHighlight: true,
});

var formatterIDR = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
});

$(".NumberInput").on("keypress", function(evt) {
    console.log(evt.which);
    if (evt.which != 8 && evt.which != 0 && (evt.which < 48 || evt.which > 57)) {
        evt.preventDefault();
    }
});

$(".NumberInputComma").on("keypress", function(evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which > 57) {
        evt.preventDefault();
    };
});

$(".NumberInputComma").on("input", function(evt) {
    if ($(this).val().replace(/[^.]/g, "").length == 1) {
        var decimalPlace = $(this).val().split(".")[1];
        if (decimalPlace.length > 3) {
            original = $(this).val().slice(0, -1);
            console.log(original);
            $(this).val(original);
        }
    }
    if ($(this).val().replace(/[^.]/g, "").length > 1) {
        original = $(this).val().slice(0, -1);
        $(this).val(original);
    }
});


$(".AlfabethInput").on("keypress", function(evt) {
    if (evt.which >= 48 && evt.which <= 57) {
        evt.preventDefault();
    }
});


if (sessionStorage.getItem("saveTab")) {
    $('.nav-link[href="' + sessionStorage.getItem("saveTab") + '"]').tab('show');
} else {
    $(() => {
        $('#myTab li:first-child a').tab('show')
    })
}

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    sessionStorage.setItem("saveTab", $(this).attr('href'));
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
});

//====================================================================CALCULATION==================================================
//===================================================================================================================//
$('#DirectMaterialFormat').val(formatterIDR.format($('#DirectMaterial').val()));
$('#DirectLabourFormat').val(formatterIDR.format($('#DirectLabour').val()));
$('#TotalCOGSFormat').val(formatterIDR.format($('#TotalCOGS').val()));

function actionCalculateAddRawMaterial() {
    Weight = $('#addWeightRaw').val() == "" ? 0 : parseFloat($('#addWeightRaw').val());
    Price = $('#addPriceRaw').val() == "" ? 0 : parseFloat($('#addPriceRaw').val());
    Kurs = parseFloat($('#addKursRaw').val());
    FinalCost = Price * Kurs * Weight;
    $("#addFinalCostRaw").val(FinalCost);
    $("#addFinalCostShowRaw").val(formatterIDR.format(FinalCost));
}

function actionCalculateEditStatusRawMaterial() {
    PriceType = "Price" + String($('#editStatusStatusPrice').val());
    CurrencyType = "Currency" + String($('#editStatusStatusPrice').val());

    function first() {
        return $.ajax({
            url: "{{url('cogs-search-statuspricerawmaterial')}}",
            type: "GET",
            dataType: "JSON",
            data: {
                material: $("#PNReferenceCode").val(),
                category: $("#editStatusSpesification").val(),
            },
            success: function(data) {
                $.each(data.RawMaterialGroup, (i, val) => {
                    if (i == PriceType) {
                        $('#editStatusPrice').val(val)
                    }
                    if (i == CurrencyType) {
                        $('#editStatusCurrency').val(val)
                    }
                });
            }
        });
    }

    function second() {
        Currency = $('#editStatusCurrency').val();
        return $.ajax({
            url: "{{url('cogs-search-kurs-form-cogs')}}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $.each(data.Kurs, (i, val) => {
                    if (val.MataUang == Currency) {
                        $('#editStatusKurs').val(val.KursTengah);
                        $('#editStatusKursShow').val(formatterIDR.format(val
                            .KursTengah));
                    }
                });
            }
        });
    }

    function third() {
        Weight = $('#editStatusWeight').val() == "" ? 0 : parseFloat($('#editStatusWeight').val());
        Price = $('#editStatusPrice').val() == "" ? 0 : parseFloat($('#editStatusPrice').val());
        Kurs = parseFloat($('#editStatusKurs').val());
        FinalCost = Price * Kurs * Weight;
        $("#editStatusFinalCost").val(FinalCost);
        $("#editStatusFinalCostShow").val(formatterIDR.format(FinalCost));
    }

    first().then(second).then(third);
}

function actionCalculateEditRawMaterial() {

    function first() {
        Currency = $('#editCurrencyRaw').val();
        return $.ajax({
            url: "{{url('cogs-search-kurs-form-cogs')}}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $.each(data.Kurs, (i, val) => {
                    if (val.MataUang == Currency) {
                        $('#editKursRaw').val(val.KursTengah);
                        $('#editKursShowRaw').val(formatterIDR.format(val
                            .KursTengah));
                    }
                });
            }
        });
    }

    function second() {
        Weight = $('#editWeightRaw').val() == "" ? 0 : parseFloat($('#editWeightRaw').val());
        Price = $('#editPriceRaw').val() == "" ? 0 : parseFloat($('#editPriceRaw').val());
        Kurs = parseFloat($('#editKursRaw').val());
        FinalCost = Price * Kurs * Weight;
        $("#editFinalCostRaw").val(FinalCost);
        $("#editFinalCostShowRaw").val(formatterIDR.format(FinalCost));
    }

    first().then(second);
}
//===================================================================================================================//

function actionCalculateAddOthers() {
    calculatePriceAfterKursOthers();

    function calculatePriceAfterKursOthers() {
        Price = $('#addPriceOthers').val() == "" ? 0 : parseFloat($('#addPriceOthers').val());
        Kurs = parseFloat($('#addKursOthers').val());
        PriceAfterKurs = Price * Kurs;
        $('#addPriceAfterKursOthers').val(PriceAfterKurs);
        $('#addPriceAfterKursShowOthers').val(formatterIDR.format(PriceAfterKurs));
        calculateTotalPriceOthers(PriceAfterKurs);
    }

    function calculateTotalPriceOthers(PriceAfterKurs) {
        Qty = parseFloat($('#addQtyOthers').val() == "" ? 0 : $('#addQtyOthers').val());
        Tax = parseFloat($('#addTaxOthers').val() == "" ? 0 : $('#addTaxOthers').val());
        TotalPrice = (PriceAfterKurs + ((Tax / 100) * PriceAfterKurs)) * Qty;
        $('#addTotalPriceOthers').val(TotalPrice);
        $('#addTotalPriceShowOthers').val(formatterIDR.format(TotalPrice));
    }
}

function actionCalculateEditOthers() {
    calculatePriceAfterKursOthers();

    function calculatePriceAfterKursOthers() {
        Price = $('#editPriceOthers').val() == "" ? 0 : parseFloat($('#editPriceOthers').val());
        Kurs = parseFloat($('#editKursOthers').val());
        PriceAfterKurs = Price * Kurs;
        $('#editPriceAfterKursOthers').val(PriceAfterKurs);
        $('#editPriceAfterKursShowOthers').val(formatterIDR.format(PriceAfterKurs));
        calculateTotalPriceOthers(PriceAfterKurs);
    }

    function calculateTotalPriceOthers(PriceAfterKurs) {
        Qty = parseFloat($('#editQtyOthers').val() == "" ? 0 : $('#editQtyOthers').val());
        Tax = parseFloat($('#editTaxOthers').val() == "" ? 0 : $('#editTaxOthers').val());
        TotalPrice = (PriceAfterKurs + ((Tax / 100) * PriceAfterKurs)) * Qty;
        $('#editTotalPriceOthers').val(TotalPrice);
        $('#editTotalPriceShowOthers').val(formatterIDR.format(TotalPrice));
    }
}

//===================================================================================================================//

function actionCalculateAddProcess() {
    Hours = $('#addHoursProcess').val() == "" ? 0 : parseFloat($('#addHoursProcess').val());
    RateMH = $('#addRateMHProcess').val() == "" ? 0 : parseFloat($('#addRateMHProcess').val());
    Cost = Hours * RateMH;
    $('#addCostProcess').val(Cost);
    $('#addCostProcessFormat').val(formatterIDR.format(Cost));
}

function actionCalculateEditProcess() {
    Hours = $('#editHoursProcess').val() == "" ? 0 : parseFloat($('#editHoursProcess').val());
    RateMH = $('#editRateMHProcess').val() == "" ? 0 : parseFloat($('#editRateMHProcess').val());
    Cost = Hours * RateMH;
    $('#editCostProcess').val(Cost);
    $('#editCostProcessFormat').val(formatterIDR.format(Cost));
}

//===================================================================================================================//

function actionCalculateEditConsumables() {
    calculatePriceAfterKursConsumables();

    function calculatePriceAfterKursConsumables() {
        Price = $('#editPriceConsumables').val() == "" ? 0 : parseFloat($('#editPriceConsumables').val());
        Kurs = parseFloat($('#editKursConsumables').val());
        PriceAfterKurs = Price * Kurs;
        $('#editPriceAfterKursConsumables').val(PriceAfterKurs);
        $('#editPriceAfterKursShowConsumables').val(formatterIDR.format(PriceAfterKurs));
        calculateTotalPriceConsumables(PriceAfterKurs);
    }

    function calculateTotalPriceConsumables(PriceAfterKurs) {
        Qty = parseFloat($('#editQtyConsumables').val() == "" ? 0 : $('#editQtyConsumables').val());
        Tax = parseFloat($('#editTaxConsumables').val() == "" ? 0 : $('#editTaxConsumables').val());
        TotalPrice = (PriceAfterKurs + ((Tax / 100) * PriceAfterKurs)) * Qty;
        $('#editTotalPriceConsumables').val(TotalPrice);
        $('#editTotalPriceShowConsumables').val(formatterIDR.format(TotalPrice));
        calculateFinalPriceConsumables(TotalPrice);
    }

    function calculateFinalPriceConsumables(TotalPrice) {
        ManualAdjustment = parseFloat($('#editManualAdjustmentConsumables').val() == "" ? 1 : $(
                '#editManualAdjustmentConsumables')
            .val());
        FinalPrice = ManualAdjustment * TotalPrice;
        $('#editFinalPriceConsumables').val(FinalPrice);
        $('#editFinalPriceShowConsumables').val(formatterIDR.format(FinalPrice));
    }
}

function actionCalculateAddConsumables() {
    calculatePriceAfterKursConsumables();

    function calculatePriceAfterKursConsumables() {
        Price = $('#addPriceConsumables').val() == "" ? 0 : parseFloat($('#addPriceConsumables').val());
        Kurs = parseFloat($('#addKursConsumables').val());
        PriceAfterKurs = Price * Kurs;
        $('#addPriceAfterKursConsumables').val(PriceAfterKurs);
        $('#addPriceAfterKursShowConsumables').val(formatterIDR.format(PriceAfterKurs));
        calculateTotalPriceConsumables(PriceAfterKurs);
    }

    function calculateTotalPriceConsumables(PriceAfterKurs) {
        Qty = parseFloat($('#addQtyConsumables').val() == "" ? 0 : $('#addQtyConsumables').val());
        Tax = parseFloat($('#addTaxConsumables').val() == "" ? 0 : $('#addTaxConsumables').val());
        TotalPrice = (PriceAfterKurs + ((Tax / 100) * PriceAfterKurs)) * Qty;
        $('#addTotalPriceConsumables').val(TotalPrice);
        $('#addTotalPriceShowConsumables').val(formatterIDR.format(TotalPrice));
        calculateFinalPriceConsumables(TotalPrice);
    }

    function calculateFinalPriceConsumables(TotalPrice) {
        ManualAdjustment = parseFloat($('#addManualAdjustmentConsumables').val() == "" ? 1 : $(
                '#addManualAdjustmentConsumables')
            .val());
        FinalPrice = ManualAdjustment * TotalPrice;
        $('#addFinalPriceConsumables').val(FinalPrice);
        $('#addFinalPriceShowConsumables').val(formatterIDR.format(FinalPrice));
    }
}

//===================================================================================================================//

function actionCalculateEdit() {
    calculatePriceAfterKurs();

    function calculatePriceAfterKurs() {
        Price = $('#editPrice').val() == "" ? 0 : parseFloat($('#editPrice').val());
        Kurs = parseFloat($('#editKurs').val());
        PriceAfterKurs = Price * Kurs;
        $('#editPriceAfterKurs').val(PriceAfterKurs);
        $('#editPriceAfterKursShow').val(formatterIDR.format(PriceAfterKurs));
        calculateTotalPrice(PriceAfterKurs);
    }

    function calculateTotalPrice(PriceAfterKurs) {
        Qty = parseFloat($('#editQty').val() == "" ? 0 : $('#editQty').val());
        Tax = parseFloat($('#editTax').val() == "" ? 0 : $('#editTax').val());
        TotalPrice = (PriceAfterKurs + ((Tax / 100) * PriceAfterKurs)) * Qty;
        $('#editTotalPrice').val(TotalPrice);
        $('#editTotalPriceShow').val(formatterIDR.format(TotalPrice));
        calculateFinalPrice(TotalPrice);
    }

    function calculateFinalPrice(TotalPrice) {
        ManualAdjustment = parseFloat($('#editManualAdjustment').val() == "" ? 1 : $('#editManualAdjustment')
            .val());
        FinalPrice = ManualAdjustment * TotalPrice;
        $('#editFinalPrice').val(FinalPrice);
        $('#editFinalPriceShow').val(formatterIDR.format(FinalPrice));
    }
}

function actionCalculateAdd() {
    calculatePriceAfterKurs();

    function calculatePriceAfterKurs() {
        Price = $('#addPrice').val() == "" ? 0 : parseFloat($('#addPrice').val());
        Kurs = parseFloat($('#addKurs').val());
        PriceAfterKurs = Price * Kurs;
        $('#addPriceAfterKurs').val(PriceAfterKurs);
        $('#addPriceAfterKursShow').val(formatterIDR.format(PriceAfterKurs));
        calculateTotalPrice(PriceAfterKurs);
    }

    function calculateTotalPrice(PriceAfterKurs) {
        Qty = parseFloat($('#addQty').val() == "" ? 0 : $('#addQty').val());
        Tax = parseFloat($('#addTax').val() == "" ? 0 : $('#addTax').val());
        TotalPrice = (PriceAfterKurs + ((Tax / 100) * PriceAfterKurs)) * Qty;
        $('#addTotalPrice').val(TotalPrice);
        $('#addTotalPriceShow').val(formatterIDR.format(TotalPrice));
        calculateFinalPrice(TotalPrice);
    }

    function calculateFinalPrice(TotalPrice) {
        ManualAdjustment = parseFloat($('#addManualAdjustment').val() == "" ? 1 : $('#addManualAdjustment')
            .val());
        FinalPrice = ManualAdjustment * TotalPrice;
        $('#addFinalPrice').val(FinalPrice);
        $('#addFinalPriceShow').val(formatterIDR.format(FinalPrice));
    }
}

//===================================================================================================================//
//====================================================================CALCULATION==================================================


$(document).ready(function() {
    var buttonAttachment = $('.buttonAttachment');
    for (var i = 0; i < buttonAttachment.length; i++) {
        buttonAttachment[i].addEventListener("click", function(e) {
            e.preventDefault();
            var No = $(this).attr('no');
            var Name = $(this).attr('name');
            var File = $(this).attr('file');
            console.log(No);
            console.log(Name);
            var Title = "Attachment " + No + " : " + Name
            var Body = `<iframe src="data:application/pdf;base64,` + File + `" type="application/pdf"
                        frameBorder="0" scrolling="auto" marginheight="0" marginwidth="0" height="550px" width="100%"
                        allowfullscreen webkitallowfullscreen style="overflow: auto;">
                        </iframe>`
            $('#modalAttachment').modal('show');
            $('#modalAttachmentTitle').html(Title);
            $('#modalAttachmentBody').html(Body);
        })
    }


    $('button').hover(function() {
        $(this).css('cursor', 'pointer');
    });
    //===================================================================================================================//
    $("textarea").each(function() {
        this.setAttribute("style", "resize:none");
    });

    $(".right_col").each(function() {
        $.each(this.attributes, function() {
            if (this.specified) {
                if (this.name == "style") {
                    this.value = "min-height : auto;";
                }
            }
        });
    });

    // $(".col-md-3").css("min-height", "auto");

    //===================================================================================================================//
    $('.getMasterProcess').on("click", function(e) {
        e.preventDefault();
        $('#modalMasterProcessData').modal('show');

        function first() {
            return $.ajax({
                url: "{{url('cogs-search-masterprocess')}}",
                type: "GET",
                dataType: "JSON",
                data: {
                    pnreference: $("#PNReferenceCode").val(),
                },
                success: function(data) {
                    $('#MPTName').html('');
                    $.each(data.MPTName, (i, val) => {
                        $('#MPTName').append(
                            `<option value="` + val
                            .MPTName + `">` + val
                            .MPTName + `</option>`);
                    });
                    $.ajax({
                        url: "{{url('cogs-search-masterprocess')}}",
                        type: "GET",
                        dataType: "JSON",
                        data: {
                            pnreference: $("#PNReferenceCode").val(),
                            mptnameselected: $('#MPTName').find(
                                "option:first-child").val(),
                        },
                        success: function(data) {
                            $('#COGSIDProcess').val($('#COGSID').val());
                            $('#PNReferenceProcess').val($(
                                    '#PNReferenceCode')
                                .val());
                            $('#RateMH').val($('#RateManhour').val());
                            $('#RateMHFormat').val(formatterIDR.format($(
                                '#RateManhour').val()));
                            IsActive = data.MasterProcess[0].IsActive == 1 ?
                                "Yes" :
                                "No";
                            IsTotalDayCalculated = data.MasterProcess[0]
                                .IsTotalDayCalculated == 1 ? "Yes" :
                                "No";
                            // $('#MPTPNName').val(data.MasterProcess[0].Name);
                            // $('#ProductName').val(data.MasterProcess[0]
                            //     .ProductName);
                            $('#Version').val(data.MasterProcess[0]
                                .Version);
                            $('#TotalDay').val(data.MasterProcess[0]
                                .TotalDay);
                            $('#IsActive').val(IsActive);
                            $('#IsTotalDayCalculated').val(
                                IsTotalDayCalculated);
                            $('#tableMasterProcess').html(`
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="background-color:#F6F6F6;">Process Order</th>
                                            <th style="background-color:#F6F6F6;">Process Group</th>
                                            <th style="background-color:#F6F6F6;">Process Name</th>
                                            <th style="background-color:#F6F6F6;">ManHour</th>
                                            <th style="background-color:#F6F6F6;">ManPower</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            `);
                            $.each(data.MasterProcess, (i, val) => {
                                $('#tableMasterProcess').find(
                                        'tbody')
                                    .append(`
                                    <tr class="text-center">
                                        <td><b> ` + val.ProcessOrder + `</b></td>
                                        <td><b> ` + val.ProcessGroup + `</b></td>
                                        <td> ` + val.ProcessName + `</td>
                                        <td> ` + parseFloat(val.ManHour) + `</td>
                                        <td> ` + val.ManPower + `</td>
                                    </tr>
                                `)
                            });
                            if ($("#IsActive").val() == "No") {
                                $("#IsActive").css("background-color",
                                    "#FF0063");
                                $("#IsActive").css("border-color",
                                    "#FF0063");
                                $("#IsActive").css("color", "#f7f7f7");
                            } else {
                                $("#IsActive").css("background-color",
                                    "#5cb85c");
                                $("#IsActive").css("border-color",
                                    "#5cb85c");
                                $("#IsActive").css("color", "#f7f7f7");
                            }
                        }
                    });
                }
            });
        }
        first().then(
            $('#MPTName').on('change', function(e) {
                $.ajax({
                    url: "{{url('cogs-search-masterprocess')}}",
                    type: "GET",
                    dataType: "JSON",
                    data: {
                        pnreference: $("#PNReferenceCode").val(),
                        mptnameselected: this.value,
                    },
                    success: function(data) {
                        $('#COGSIDProcess').val($('#COGSID').val());
                        $('#PNReferenceProcess').val($('#PNReferenceCode')
                            .val());
                        $('#RateMH').val($('#RateManhour').val());
                        $('#RateMHFormat').val(formatterIDR.format($(
                            '#RateManhour').val()));
                        IsActive = data.MasterProcess[0].IsActive == 1 ?
                            "Yes" :
                            "No";
                        IsTotalDayCalculated = data.MasterProcess[0]
                            .IsTotalDayCalculated == 1 ? "Yes" :
                            "No";
                        // $('#MPTPNName').val(data.MasterProcess[0].Name);
                        // $('#ProductName').val(data.MasterProcess[0].ProductName);
                        $('#Version').val(data.MasterProcess[0]
                            .Version);
                        $('#TotalDay').val(data.MasterProcess[0]
                            .TotalDay);
                        $('#IsActive').val(IsActive);
                        $('#IsTotalDayCalculated').val(IsTotalDayCalculated);
                        $('#tableMasterProcess').html(`
                            <table class="table table-bordered table-md">
                                <thead>
                                    <tr class="text-center">
                                        <th style="background-color:#F6F6F6;">Process Order</th>
                                        <th style="background-color:#F6F6F6;">Process Group</th>
                                        <th style="background-color:#F6F6F6;">Process Name</th>
                                        <th style="background-color:#F6F6F6;">ManHour</th>
                                        <th style="background-color:#F6F6F6;">ManPower</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        `);
                        $.each(data.MasterProcess, (i, val) => {
                            $('#tableMasterProcess').find('tbody')
                                .append(`
                                    <tr class="text-center">
                                        <td><b> ` + val.ProcessOrder + `</b></td>
                                        <td><b> ` + val.ProcessGroup + `</b></td>
                                        <td> ` + val.ProcessName + `</td>
                                        <td> ` + parseFloat(val.ManHour) + `</td>
                                        <td> ` + val.ManPower + `</td>
                                    </tr>
                                `)
                        });
                        if ($("#IsActive").val() == "No") {
                            $("#IsActive").css("background-color",
                                "#FF0063");
                            $("#IsActive").css("border-color",
                                "#FF0063");
                            $("#IsActive").css("color", "#f7f7f7");
                        } else {
                            $("#IsActive").css("background-color",
                                "#5cb85c");
                            $("#IsActive").css("border-color",
                                "#5cb85c");
                            $("#IsActive").css("color", "#f7f7f7");
                        }
                    }
                });
            })
        );

    });

    //===================================================================================================================//
    RateManhour = 165000;
    $("#RateManhour").val(RateManhour);
    $("#RateManhourFormat").val(formatterIDR.format(RateManhour));
    $("#ButtonRateManhour").on("click", function() {
        if ($(this).hasClass('btn-info')) {
            $(this).removeClass('btn-info');
            $(this).addClass('btn-success');
            $(this).html(`<i class="fa fa-check"></i> OK`);
            currRateManhour = $("#RateManhour").val();
            $(".htmlRateManhour").html(`
                    <label class="form-control-label"> New Rate Manhour (IDR): *</label>
                    <input id="NewRateManhour" name="NewRateManhour" type="text" class="form-control InputNumber" autocomplete="off" value="" required>
                    
            `);
            $("#NewRateManhour").on("keypress", function(evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
            $("#NewRateManhour").val(currRateManhour);
        } else {
            newRateManhour = $("#NewRateManhour").val();
            $(this).removeClass('btn-success');
            $(this).addClass('btn-info');
            $(this).html(`<i class="fa fa-pencil"></i> Change`);
            $(".htmlRateManhour").html(`
                    <label class="form-control-label"> Rate Manhour (IDR): *</label>
                    <input id="RateManhour" name="RateManhour" type="text" class="form-control d-none" autocomplete="off" value="" required readonly>
                    <input id="RateManhourFormat" name="RateManhourFormat" type="text" class="form-control" autocomplete="off" value="" required readonly>
            `);
            $("#RateManhour").val(newRateManhour);
            $("#RateManhourFormat").val(formatterIDR.format(newRateManhour));
        }
    });


    //===================================================================================================================//
    $('.addRawMaterial').on("click", function(e) {
        e.preventDefault();
        $('#modalAddRawMaterial').modal('show');
        $('#addCOGSIDRaw').val($('#COGSID').val());
        $('#addCurrencyRaw').html(``);
        $.ajax({
            url: "{{url('cogs-search-kurs-form-cogs')}}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $.each(data.Kurs, (i, val) => {
                    $('#addCurrencyRaw').append(
                        `<option id="` + val
                        .ID + `" currency="` + val.KursTengah +
                        `" value="` +
                        val
                        .MataUang + `">` + val
                        .MataUang +
                        `</option>`);
                });
                $("#addCurrencyRaw").find("#26").attr("selected",
                    true);
                Kurs = $("#addCurrencyRaw option:selected").attr("currency");
                $('#addKursRaw').val(Kurs);
                $('#addKursShowRaw').val(formatterIDR.format(Kurs));
                actionCalculateAddRawMaterial();

                $("#addCurrencyRaw").change(function() {
                    Kurs = $("#addCurrencyRaw option:selected").attr(
                        "currency");
                    $('#addKursRaw').val(Kurs);
                    $('#addKursShowRaw').val(formatterIDR.format(Kurs));
                    actionCalculateAddRawMaterial();
                });


            }
        });
        $('#addStatusPriceRaw').html(``);
        $('#addStatusPriceRaw').append(`
            <option value="Exmill">Exmill</option>
            <option value="Exstock">Exstoc</option>
        `);

    });

    //===================================================================================================================//
    $('.addSFComponent').on("click", function(e) {
        e.preventDefault();
        $('#modalAddSFComponent').modal('show');
        $('#addCOGSID').val($('#COGSID').val());
        $('#addPNReference').val($('#PNReference').val());
        $('#addComponent').val('');
        $('#addDescription').val('');
        $('#addCategory').val('');
        $('#addPrice').val(0);
        $('#addQty').val(0);
        $('#addTax').val(0);
        $('#addManualAdjustment').val(1);
        $('#htmlCurrency').html(`
            <label class="form-control-label"> Currency: *</label>
            <select id="addOptionCurrency" name="addOptionCurrency" class="form-control" style="width: 100%">
            </select>`);

        $.ajax({
            url: "{{url('cogs-search-kurs-form-cogs')}}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $.each(data.Kurs, (i, val) => {
                    $('#addOptionCurrency').append(`<option id="` + val
                        .ID + `" currency="` + val.KursTengah +
                        `" value="` +
                        val
                        .MataUang + `">` + val
                        .MataUang +
                        `</option>`);
                });
                $("#addOptionCurrency").find("#26").attr("selected", true);
                // $('#addKurs').val($("#addOptionCurrency").find("#26").attr(
                //     "currency"));
                // $('#addKursShow').val(formatterIDR.format($("#addOptionCurrency")
                //     .find(
                //         "#26").attr("currency")));
                Kurs = $("#addOptionCurrency option:selected").attr("currency");
                $('#addKurs').val(Kurs);
                $('#addKursShow').val(formatterIDR.format(Kurs));
                actionCalculateAdd();
            }
        });

        $("#addOptionCurrency").change(function() {
            Kurs = $("#addOptionCurrency option:selected").attr("currency");
            $('#addKurs').val(Kurs);
            $('#addKursShow').val(formatterIDR.format(Kurs));
            actionCalculateAdd();
        });
    });

    //===================================================================================================================//
    $('.addConsumables').on("click", function(e) {
        e.preventDefault();
        $('#modalAddConsumables').modal('show');
        $('#addCOGSIDConsumables').val($('#COGSID').val());
        $('#addPNReferenceConsumables').val($('#PNReference').val());
        $('#addComponenConsumables').val('');
        $('#addDescriptionConsumables').val('');
        $('#addCategoryConsumables').val('');
        $('#addPriceConsumables').val(0);
        $('#addQtyConsumables').val(0);
        $('#addTaxConsumables').val(0);
        $('#addManualAdjustmentConsumables').val(1);
        $('#htmlCurrencyConsumables').html(`
            <label class="form-control-label"> Currency: *</label>
            <select id="addOptionCurrencyConsumables" name="addOptionCurrencyConsumables" class="form-control" style="width: 100%">
            </select>`);

        $.ajax({
            url: "{{url('cogs-search-kurs-form-cogs')}}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $.each(data.Kurs, (i, val) => {
                    $('#addOptionCurrencyConsumables').append(
                        `<option id="` + val
                        .ID + `" currency="` + val.KursTengah +
                        `" value="` +
                        val
                        .MataUang + `">` + val
                        .MataUang +
                        `</option>`);
                });
                $("#addOptionCurrencyConsumables").find("#26").attr("selected",
                    true);
                Kurs = $("#addOptionCurrencyConsumables option:selected").attr(
                    "currency");
                $('#addKursConsumables').val(Kurs);
                $('#addKursShowConsumables').val(formatterIDR.format(Kurs));
                actionCalculateAddConsumables();
            }
        });

        $("#addOptionCurrencyConsumables").change(function() {
            Kurs = $("#addOptionCurrencyConsumables option:selected").attr("currency");
            $('#addKursConsumables').val(Kurs);
            $('#addKursShowConsumables').val(formatterIDR.format(Kurs));
            actionCalculateAddConsumables();
        });
    });
    //===================================================================================================================//
    $('.addProcess').on("click", function(e) {
        e.preventDefault();
        $('#modalAddProcess').modal('show');
        $('#addCOGSIDProcess').val($('#COGSID').val());
        $('#addProcessProcess').val('');
        $('#addUmProcess').val('Hours');
        $('#addHoursProcess').val(0);
        $('#addRateMHProcess').val($('#RateManhour').val());
        actionCalculateAddProcess();
    });
    //===================================================================================================================//
    $('.addOthers').on("click", function(e) {
        e.preventDefault();
        $('#modalAddOthers').modal('show');
        $('#addCOGSIDOthers').val($('#COGSID').val());
        $('#addPartNumberOthers').val('');
        $('#addDescriptionOthers').val('');
        $('#addPriceOthers').val(0);
        $('#addTaxOthers').val(0);
        $('#addQtyOthers').val(0);
        $('#addUnOthers').val('');
        $('#htmlCurrencyAddOthers').html(`
            <label class="form-control-label"> Currency: *</label>
            <select id="addOptionCurrencyOthers" name="addOptionCurrencyOthers" class="form-control" style="width: 100%">
            </select>`);

        $.ajax({
            url: "{{url('cogs-search-kurs-form-cogs')}}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $.each(data.Kurs, (i, val) => {
                    $('#addOptionCurrencyOthers').append(
                        `<option id="` + val
                        .ID + `" currency="` + val.KursTengah +
                        `" value="` +
                        val
                        .MataUang + `">` + val
                        .MataUang +
                        `</option>`);
                });
                $("#addOptionCurrencyOthers").find("#26").attr("selected",
                    true);
                Kurs = $("#addOptionCurrencyOthers option:selected").attr("currency");
                $('#addKursOthers').val(Kurs);
                $('#addKursShowOthers').val(formatterIDR.format(Kurs));
                actionCalculateAddOthers();
            }
        });

        $("#addOptionCurrencyOthers").change(function() {
            Kurs = $("#addOptionCurrencyOthers option:selected").attr("currency");
            $('#addKursOthers').val(Kurs);
            $('#addKursShowOthers').val(formatterIDR.format(Kurs));
            actionCalculateAddOthers();
        });
    });
    //===================================================================================================================//
    $('.editRawMaterialStatus').on("click", function() {
        $('#modalEditStatusRawMaterial').modal('show');

        id = $(this).attr('IDRawMaterial');
        $('#editIDStatusRaw').val(id);
        $('#editCOGSIDStatusRaw').val($('#COGSID').val());


        function first() {
            return $.ajax({
                url: "{{url('cogs-search-rawmaterial')}}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $('#editStatusSpesification').val(data.RawMaterial.Spesification);
                    $('#editStatusWeight').val(data.RawMaterial.Weight);
                    $('#editStatusPrice').val(data.RawMaterial.Price);
                    $('#editStatusCurrency').val(data.RawMaterial.Currency);
                    $('#editStatusUn').val(data.RawMaterial.Un);
                    $('#editStatusStatusPrice').html(``);
                    $('#editStatusStatusPrice').append(`
                    <option value="Exmill">Exmill</option>
                    <option value="Exstock">Exstoc</option>
                    `);
                    $('#editStatusStatusPrice').val(data.RawMaterial.Status);
                }
            });
        }

        function second() {
            Currency = $('#editStatusCurrency').val();
            return $.ajax({
                url: "{{url('cogs-search-kurs-form-cogs')}}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $.each(data.Kurs, (i, val) => {
                        if (val.MataUang == Currency) {
                            $('#editStatusKurs').val(val.KursTengah);
                            $('#editStatusKursShow').val(formatterIDR.format(val
                                .KursTengah));
                        }
                    });
                    actionCalculateEditStatusRawMaterial()
                }
            });
        }
        first().then(second);
    });

    $('.editRawMaterial').on("click", function() {
        id = $(this).attr('IDRawMaterial');
        $('#modalEditRawMaterial').modal('show');

        function first() {
            return $.ajax({
                url: "{{url('cogs-search-rawmaterial')}}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $('#editIDRaw').val(data.RawMaterial.ID);
                    $('#editCOGSIDRaw').val(data.RawMaterial.COGSID);
                    $('#editSpesificationRaw').val(data.RawMaterial.Spesification);
                    $('#editWeightRaw').val(data.RawMaterial.Weight);
                    $('#editPriceRaw').val(data.RawMaterial.Price);
                    Currency = data.RawMaterial.Currency;
                    $('#editStatusPriceRaw').html(``);
                    $('#editStatusPriceRaw').append(`
                    <option value="Exmill">Exmill</option>
                    <option value="Exstock">Exstoc</option>
                    `);
                    $('#editStatusPriceRaw').val(data.RawMaterial.Status);
                    $('#editUnRaw').val(data.RawMaterial.Un);
                    $('#editFinalCostRaw').val(data.RawMaterial.FinalCost);
                    $('#editFinalCostShowRaw').val(formatterIDR.format(data.RawMaterial
                        .FinalCost));
                }
            });
        }

        function second() {
            return $.ajax({
                url: "{{url('cogs-search-kurs-form-cogs')}}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $.each(data.Kurs, (i, val) => {
                        $('#editCurrencyRaw').append(
                            `<option id="` + val
                            .ID + `" currency="` + val.KursTengah +
                            `" value="` +
                            val
                            .MataUang + `">` + val
                            .MataUang +
                            `</option>`);
                    });
                    $("#editCurrencyRaw").find("[value='" +
                        Currency + "']").attr("selected",
                        true);
                    Kurs = $("#editCurrencyRaw option:selected").attr("currency");
                    $('#editKursRaw').val(Kurs);
                    $('#editKursShowRaw').val(formatterIDR.format(Kurs));
                    actionCalculateEditRawMaterial();

                    $("#editCurrencyRaw").change(function() {
                        Kurs = $("#editCurrencyRaw option:selected").attr(
                            "currency");
                        $('#editKursRaw').val(Kurs);
                        $('#editKursShowRaw').val(formatterIDR.format(Kurs));
                        actionCalculateEditRawMaterial();
                    });
                }
            });

        }
        first().then(second);
    });
    //===================================================================================================================//
    $('.editSFComponent').on("click", function() {
        id = $(this).attr('IDSFComponent');

        function first() {
            return $.ajax({
                url: "{{url('cogs-search-sfcomponent')}}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $('#modalEditSFComponent').modal('show');
                    $('#editID').val(data.SFComponent.ID);
                    $('#editCOGSID').val(data.SFComponent.COGSID);
                    $('#editComponent').val((data.SFComponent.Component).trim());
                    $('#editDescription').val((data.SFComponent.Description).trim());
                    $('#editCategory').val((data.SFComponent.Category).trim());
                    $('#editPrice').val(data.SFComponent.Price);
                    $('#editCurrency').val(data.SFComponent.Currency);
                    $('#editQty').val(data.SFComponent.Qty);
                    $('#editUn').val(data.SFComponent.Un);
                    $('#editTax').val(data.SFComponent.Tax);
                    $('#editTotalPrice').val(data.SFComponent.TotalPrice);
                    $('#editTotalPriceShow').val(formatterIDR.format(data
                        .SFComponent
                        .TotalPrice));
                    $('#editLastTransaction').val(data.SFComponent.LastTransaction);
                    $('#editManualAdjustment').val(data.SFComponent
                        .ManualAdjustment);
                    $('#editFinalPrice').val(data.SFComponent.FinalPrice);
                    $('#editFinalPriceShow').val(formatterIDR.format(data
                        .SFComponent
                        .FinalPrice));
                }
            });
        };

        function second() {
            return $.ajax({
                url: "{{url('cogs-search-kurs-form-cogs')}}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    index = data.Kurs.findIndex(item => item.MataUang.replaceAll(
                        /\s/g,
                        '') === $('#editCurrency').val().replaceAll(/\s/g,
                        ''));
                    $('#editKurs').val(data.Kurs[index].KursTengah);
                    $('#editKursShow').val(formatterIDR.format(data.Kurs[index]
                        .KursTengah));
                    actionCalculateEdit();
                }
            })
        };

        first().then(second);
    });
    //===================================================================================================================//
    $('.editConsumables').on("click", function() {
        id = $(this).attr('IDConsumables');

        function first() {
            return $.ajax({
                url: "{{url('cogs-search-consumables')}}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $('#modalEditConsumables').modal('show');
                    $('#editIDConsumables').val(data.Consumables.ID);
                    $('#editCOGSIDConsumables').val(data.Consumables.COGSID);
                    $('#editComponentConsumables').val(data.Consumables.Component
                        .trim());
                    $('#editDescriptionConsumables').val(data.Consumables
                        .Description.trim());
                    $('#editCategoryConsumables').val(data.Consumables.Category.trim());
                    $('#editPriceConsumables').val(data.Consumables.Price);
                    $('#editCurrencyConsumables').val(data.Consumables.Currency);
                    $('#editQtyConsumables').val(data.Consumables.Qty);
                    $('#editUnConsumables').val(data.Consumables.Un);
                    $('#editTaxConsumables').val(data.Consumables.Tax);
                    $('#editTotalPriceConsumables').val(data.Consumables
                        .TotalPrice);
                    $('#editTotalPriceShowConsumables').val(formatterIDR.format(data
                        .Consumables
                        .TotalPrice));
                    $('#editLastTransactionConsumables').val(data.Consumables
                        .LastTransaction);
                    $('#editManualAdjustmentConsumables').val(data.Consumables
                        .ManualAdjustment);
                    $('#editFinalPriceConsumables').val(data.Consumables
                        .FinalPrice);
                    $('#editFinalPriceShowConsumables').val(formatterIDR.format(data
                        .Consumables
                        .FinalPrice));
                }
            });
        };

        function second() {
            return $.ajax({
                url: "{{url('cogs-search-kurs-form-cogs')}}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    index = data.Kurs.findIndex(item => item.MataUang.replaceAll(
                            /\s/g,
                            '') === $('#editCurrencyConsumables').val()
                        .replaceAll(
                            /\s/g,
                            ''));
                    $('#editKursConsumables').val(data.Kurs[index].KursTengah);
                    $('#editKursShowConsumables').val(formatterIDR.format(data.Kurs[
                            index]
                        .KursTengah));
                    actionCalculateEditConsumables();
                }
            })
        };
        first().then(second);
    });

    //===================================================================================================================//
    $('.editProcess').on("click", function() {
        $ID = $(this).attr('IDProcess')
        $.ajax({
            url: "{{url('cogs-search-process')}}",
            type: "GET",
            data: {
                id: $ID
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalEditProcess').modal('show');
                $('#editCOGSIDProcess').val(data.Process.COGSID);
                $('#editIDProcess').val(data.Process.ID);
                $('#editCOGSIDProcess').val(data.Process.COGSID);
                $('#editProcessProcess').val(data.Process.Process);
                $('#editUmProcess').val(data.Process.Um);
                $('#editHoursProcess').val(data.Process.Hours);
                $('#editRateMHProcess').val($('#RateManhour').val());
                actionCalculateEditProcess();
            }
        });
    });
    //===================================================================================================================//
    $('.editOthers').on("click", function() {
        $ID = $(this).attr('IDOthers');
        $.ajax({
            url: "{{url('cogs-search-others')}}",
            type: "GET",
            data: {
                id: $ID
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalEditOthers').modal('show');
                $('#editIDOthers').val(data.Others.ID);
                $('#editCOGSIDOthers').val(data.Others.COGSID);
                $('#editPartNumberOthers').val(data.Others.PartNumber);
                $('#editDescriptionOthers').val(data.Others.Description);
                $('#editPriceOthers').val(data.Others.Price);
                Currency = data.Others.Currency;
                $('#editTaxOthers').val(data.Others.Tax);
                $('#editQtyOthers').val(data.Others.Qty);
                $('#editUnOthers').val(data.Others.Un);
                $('#htmlCurrencyEditOthers').html(`
            <label class="form-control-label"> Currency: *</label>
            <select id="editOptionCurrencyOthers" name="editOptionCurrencyOthers" class="form-control" style="width: 100%">
            </select>`);

                $.ajax({
                    url: "{{url('cogs-search-kurs-form-cogs')}}",
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $.each(data.Kurs, (i, val) => {
                            $('#editOptionCurrencyOthers').append(
                                `<option id="` + val
                                .ID + `" currency="` + val
                                .KursTengah +
                                `" value="` +
                                val
                                .MataUang + `">` + val
                                .MataUang +
                                `</option>`);
                        });

                        $("#editOptionCurrencyOthers").find("[value='" +
                            Currency + "']").attr("selected", true);
                        $('#editKursOthers').val($(
                                "#editOptionCurrencyOthers")
                            .find("[value='" + Currency + "']").attr(
                                "currency"));
                        $('#editKursShowOthers').val(formatterIDR.format($(
                            "#editOptionCurrencyOthers").find(
                            "[value='" + Currency + "']").attr(
                            "currency")));
                        Kurs = $(
                                "#editOptionCurrencyOthers option:selected")
                            .attr("currency");
                        $('#editKursOthers').val(Kurs);
                        $('#editKursShowOthers').val(formatterIDR.format(
                            Kurs));
                        actionCalculateEditOthers();
                    }
                });

                $("#editOptionCurrencyOthers").change(function() {
                    Kurs = $("#editOptionCurrencyOthers option:selected").attr(
                        "currency");
                    $('#editKursOthers').val(Kurs);
                    $('#editKursShowOthers').val(formatterIDR.format(Kurs));
                    actionCalculateEditOthers();
                });
            }
        });
    });
    //===================================================================================================================//
    $('.deleteRawMaterial').on("click", function() {
        $.ajax({
            url: "{{url('cogs-search-rawmaterial')}}",
            type: "GET",
            data: {
                id: $(this).attr('IDRawMaterial')
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalDeleteRawMaterial').modal('show');
                $('#deleteIDRaw').val(data.RawMaterial.ID);
                $('#deleteCOGSIDRaw').val(data.RawMaterial.COGSID);
                $('#messageDeleteRawMaterial').html(
                    "Anda yakin ingin menghapus material <b>" +
                    data
                    .RawMaterial.Spesification + "</b> ?");
            }
        });
    });

    //===================================================================================================================//
    $('.deleteSFComponent').on("click", function() {
        $.ajax({
            url: "{{url('cogs-search-sfcomponent')}}",
            type: "GET",
            data: {
                id: $(this).attr('IDSFComponent')
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalDeleteSFComponent').modal('show');
                $('#deleteID').val(data.SFComponent.ID);
                $('#deleteCOGSID').val(data.SFComponent.COGSID);
                $('#messageDeleteSFComponent').html(
                    "Anda yakin ingin menghapus component <b>" +
                    data
                    .SFComponent.Component + "</b> ?");
            }
        });
    })

    //===================================================================================================================//
    $('.deleteConsumables').on("click", function() {
        $.ajax({
            url: "{{url('cogs-search-consumables')}}",
            type: "GET",
            data: {
                id: $(this).attr('IDConsumables')
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalDeleteConsumables').modal('show');
                $('#deleteIDConsumables').val(data.Consumables.ID);
                $('#deleteCOGSIDConsumables').val(data.Consumables.COGSID);
                $('#messageDeleteConsumables').html(
                    "Anda yakin ingin menghapus consumable <b>" +
                    data
                    .Consumables.Component + "</b> ?");
            }
        });
    })
    //===================================================================================================================//
    $('.deleteProcess').on("click", function() {
        $.ajax({
            url: "{{url('cogs-search-process')}}",
            type: "GET",
            data: {
                id: $(this).attr('IDProcess')
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalDeleteProcess').modal('show');
                $('#deleteIDProcess').val(data.Process.ID);
                $('#deleteCOGSIDProcess').val(data.Process.COGSID);
                $('#messageDeleteProcess').html(
                    "Anda yakin ingin menghapus process <b>" +
                    data
                    .Process.Process + "</b> ?");
            }
        });
    });
    //===================================================================================================================//
    $('.deleteOthers').on("click", function() {
        $.ajax({
            url: "{{url('cogs-search-others')}}",
            type: "GET",
            data: {
                id: $(this).attr('IDOthers')
            },
            dataType: "JSON",
            success: function(data) {
                $('#modalDeleteOthers').modal('show');
                $('#deleteIDOthers').val(data.Others.ID);
                $('#deleteCOGSIDOthers').val(data.Others.COGSID);
                $('#messageDeleteOthers').html(
                    "Anda yakin ingin menghapus part number <b>" +
                    data
                    .Others.PartNumber + "</b> ?");
            }
        });
    });
    //===================================================================================================================//
    //===================================================================================================================//
    $('#datatable-visibility-form-kurs').DataTable({
        stateSave: true,
        scrollY: 375,
        scrollCollapse: false,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', 'pdf', 'print', {
                className: `btn-refresh-table-kurs`,
                text: '<i class="fa fa-refresh"></i>',
                titleAttr: 'Refresh Kurs',
            },
        ],
        columnDefs: [{
                targets: 3,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 4,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 5,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('#Currency').find('.dataTables_scrollHead').find(
                'th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
        },
    });

    $('#datatable-visibility-form-raw').DataTable({
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', 'pdf', 'print',
        ],
        columnDefs: [{
                targets: 2,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 3,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 7,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            }
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('#RawMaterial').find('thead').find('th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            var styleAttributeTfoot = $('#RawMaterial').find('tfoot').find('th');
            styleAttributeTfoot.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
        },
        footerCallback: function(tfoot, data, start, end, display) {
            var thWeight = $('#RawMaterial').find('tfoot').find('tr').find('th').eq(2);
            thWeight.text($.fn.dataTable.render.number('.', ',', 2, '').display(
                thWeight.text()));
            var thCostRawMaterial = $('#RawMaterial').find('tfoot').find('tr').find('th').eq(7);
            thCostRawMaterial.text($.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(
                thCostRawMaterial.text()));
        }
    });

    var tableSFComponent = $('#datatable-visibility-form-sfcomponent').DataTable({
        scrollX: true,
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print'
        ],
        columnDefs: [{
                targets: 3,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 6,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 8,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 9,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 11,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 12,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 14,
                checkboxes: {
                    selectRow: true
                }
            },
        ],
        'select': {
            'style': 'multi'
        },
        "fnInitComplete": () => {
            var styleAttributeThead = $('#SFComponent').find('thead').find('tr').find('th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            var styleAttributeTfoot = $('#SFComponent').find('tfoot').find('tr').find('th');
            styleAttributeTfoot.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });

        },
        footerCallback: function(tfoot, data, start, end, display) {
            var thTotalSFComponent = $('#SFComponent').find('tfoot').find('tr').find('th').eq(12);
            thTotalSFComponent.text($.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(
                thTotalSFComponent.text()))
        },
        // "initComplete": function(settings, json) {
        //     $('.dataTables_scrollBody thead tr').css({
        //         'height': '0px'
        //     });
        //     $('.dataTables_scrollBody tfoot tr').css({
        //         'height': '0px'
        //     });
        // }
    });

    tableSFComponent.on('select', function(e) {
        var rowsSelectedSFComponent = tableSFComponent.rows('.selected').data();
        var arraySFComponent = [];
        $.each(rowsSelectedSFComponent, (i, val) => {
            arraySFComponent.push(val[15]);
        });
        $("#ArraySFComponent").val(JSON.stringify(arraySFComponent));
        $('#SFComponent').find('.select-item').html(rowsSelectedSFComponent.length +
            ' row(s) selected');
    });

    tableSFComponent.on('deselect', function(e) {
        var rowsSelectedSFComponent = tableSFComponent.rows('.selected').data();
        var arraySFComponent = [];
        $.each(rowsSelectedSFComponent, (i, val) => {
            arraySFComponent.push(val[15]);
        });
        $("#ArraySFComponent").val(JSON.stringify(arraySFComponent));
        $('#SFComponent').find('.dataTables_info').append(
            `<span class="select-info"><span class="select-item">` + rowsSelectedSFComponent
            .length + ` row(s) selected</span></span>`
        );
    });

    var tableConsumables = $('#datatable-visibility-form-consumables').DataTable({
        scrollX: true,
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print'
        ],
        columnDefs: [{
                targets: 4,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 6,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 8,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 9,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 11,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 12,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 14,
                checkboxes: {
                    selectRow: true
                }
            },
        ],
        'select': {
            'style': 'multi'
        },
        "fnInitComplete": () => {
            var styleAttributeThead = $('#Consumables').find('thead').find('tr').find('th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            var styleAttributeTfoot = $('#Consumables').find('tfoot').find('tr').find('th');
            styleAttributeTfoot.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
        },
        footerCallback: function(tfoot, data, start, end, display) {
            var thTotalConsumables = $('#Consumables').find('tfoot').find('tr').find('th').eq(12);
            thTotalConsumables.text($.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(
                thTotalConsumables.text()))
        },
    });

    tableConsumables.on('select', function(e) {
        var rowsSelectedConsumables = tableConsumables.rows('.selected').data();
        var arrayConsumables = [];
        $.each(rowsSelectedConsumables, (i, val) => {
            arrayConsumables.push(val[15]);
        });
        $("#ArrayConsumables").val(JSON.stringify(arrayConsumables));
        $('#Consumables').find('.select-item').html(rowsSelectedConsumables.length +
            ' row(s) selected');
    });

    tableConsumables.on('deselect', function(e) {
        var rowsSelectedConsumables = tableConsumables.rows('.selected').data();
        var arrayConsumables = [];
        $.each(rowsSelectedConsumables, (i, val) => {
            arrayConsumables.push(val[15]);
        });
        $("#ArrayConsumables").val(JSON.stringify(arrayConsumables));
        $('#Consumables').find('.dataTables_info').append(
            `<span class="select-info"><span class="select-item">` + rowsSelectedConsumables
            .length + ` row(s) selected</span></span>`
        );
    });

    $('#datatable-visibility-form-manhour').DataTable({
        scrollX: true,
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print',
        ],
        columnDefs: [{
                targets: 3,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 4,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 5,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('#Manhour').find('thead').find('tr').find('th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            var styleAttributeTfoot = $('#Manhour').find('tfoot').find('th');
            styleAttributeTfoot.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
        },
        footerCallback: function(tfoot, data, start, end, display) {
            var thHours = $('#Manhour').find('tfoot').find('tr').find('th').eq(3);
            thHours.text($.fn.dataTable.render.number('.', ',', 1, '').display(thHours.text()))
            var thCostProcess = $('#Manhour').find('tfoot').find('tr').find('th').eq(5);
            thCostProcess.text($.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(
                thCostProcess.text()))
        },
    });

    $('#datatable-visibility-form-others').DataTable({
        scrollX: true,
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print',
        ],
        columnDefs: [{
                targets: 3,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 0, '')
            },
            {
                targets: 5,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 6,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 1, '')
            },
            {
                targets: 8,
                type: 'num',


                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ')
            },
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('#Others').find('thead').find('tr').find('th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            var styleAttributeTfoot = $('#Others').find('tfoot').find('th');
            styleAttributeTfoot.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
        },
        footerCallback: function(tfoot, data, start, end, display) {
            var thTotalOthers = $('#Others').find('tfoot').find('tr').find('th').eq(8);
            thTotalOthers.text($.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(
                thTotalOthers.text()))
        },
    });

    $('#datatable-visibility-amount').DataTable({
        stateSave: true,
        scrollCollapse: true,
        dom: 't',
        "ordering": false,
        columnDefs: [{
                targets: 0,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 1,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 2,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 3,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 4,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('#PDF').find('thead').find('tr').find('th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.1);color:gray;',
            });
            $('#PDF tbody tr td').css('font-weight', 'bold');
            $('#datatable-visibility-amount').css('border-bottom', '2px solid #dee2e6');
            $('#PDF thead tr th').css('border-bottom', '2px solid #dee2e6');
            $('#PDF tbody tr td').css('border-bottom', '2px solid #dee2e6');
            $('#PDF tfoot tr th').css('border-bottom', '2px solid #dee2e6');
        },
    });


    //===================================================================================================================//

    $(document).on('click', '.btn-refresh-table-kurs', function() {
        var COGSID = $("#COGSID").val();
        $(".btn-refresh-table-kurs").addClass('disabled');
        $(".btn-refresh-table-kurs").attr('disabled', 'disabled');;
        $(".btn-refresh-table-kurs").html(`<i class="fa fa-refresh fa-spin"></i>`);
        window.location = `{{ url('cogs-import-kurs-form-cogs/` +
            COGSID + `') }}`
    });

    var oldProductCategory = $('#ProductCategory').val();
    var oldCalculationType = $('#CalculationType').val();
    var oldPNReferenceCode = $('#PNReferenceCode').val();
    var oldPNReference = $('#PNReference').val();
    var oldPEDNumber = $('#PEDNumber').val();
    var oldUnitWeight = $('#UnitWeight').val();

    //===================================================================================================================//
    $('#editHeaderCOGS').on("click", function(e) {

        if ($(this).hasClass('badge-info')) {
            $(this).removeClass('badge-info');
            $(this).addClass('badge-danger');
            $(this).html('<i class="fa fa-close fa-xs"></i> Cancel Edit');
            $('#PEDNumber').prop('readonly', false);
            $('#UnitWeight').prop('readonly', false);
            $('#ProductCategory').prop('readonly', false);
            $('#saveHeaderCOGS').removeClass('d-none');
            $('.collapse-link > .fa-chevron-down').click();


            $.ajax({
                url: "{{url('cogs-search-calculation-type')}}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#htmlCalculationType').html(`
                            <select id="newCalculationType" name="newCalculationType" class="form-control" style="width: 100%">
                            </select>
                        `);
                    $.each(data.CalculationType, (i, val) => {
                        $('#newCalculationType').append(
                            `<option value="` +
                            data
                            .CalculationType[i] + `">` + data
                            .CalculationType[i] + `</option>`)
                    });
                    $("#newCalculationType").find("[value='" + oldCalculationType +
                        "']").attr("selected", true);

                    function NewPN() {
                        return $.ajax({
                            url: "{{url('cogs-search-product-category-manual')}}",
                            type: "GET",
                            dataType: "JSON",
                            success: function(data) {
                                $('#htmlProductCategory').html(
                                    `<select id="newProductCategory" name="newProductCategory" class="form-control" style="width: 100%"></select> `
                                );
                                $.each(data.ProductCategory, (i, val) => {
                                    $('#newProductCategory')
                                        .append(
                                            `<option value="` +
                                            val.CategoryName +
                                            `">` + val
                                            .CategoryName +
                                            `</option>`
                                        )
                                });
                                console.log(oldProductCategory);
                                $('#newProductCategory').val(
                                    oldProductCategory);
                                $('#htmlPNReference').html(
                                    `<input id="newPNReference" name="newPNReference" class="form-control" type="text" value="" required>`
                                )
                                $("#newPNReference").val("");
                                // if (oldPNReferenceCode != "") {
                                //     $("#newPNReference").val(
                                //         oldPNReferenceCode);
                                // } else {
                                //     $("#newPNReference").val(oldPNReference);
                                // }
                            }
                        });
                    };

                    function RepeatOrder() {
                        $.ajax({
                            url: "{{url('cogs-search-product-category-auto')}}",
                            type: "GET",
                            dataType: "JSON",
                            success: function(data) {
                                $('#htmlProductCategory').html(
                                    `<select id="newProductCategory" name="newProductCategory" class="form-control" style="width: 100%"></select> `
                                );
                                $.each(data.ProductCategory, (i, val) => {
                                    $('#newProductCategory').append(
                                        `<option value="` +
                                        val.Category + `">` +
                                        val.Category +
                                        `</option>`
                                    )
                                });
                                $('#newProductCategory').val(
                                    oldProductCategory);
                                $('#htmlPNReference').html(
                                    `<select id="newPNReference" name="newPNReference" class="form-control" style="width: 100%" required><option value=""></option> </select>`
                                );
                                $.ajax({
                                    url: "{{url('cogs-search-bom')}}",
                                    data: {
                                        category: $(
                                                '#newProductCategory'
                                            )
                                            .val()
                                    },
                                    type: "GET",
                                    dataType: "JSON",
                                    success: function(data) {
                                        htmlPNReferenceOption =
                                            ``;
                                        $.each(data.PNReference,
                                            (i, val) => {
                                                htmlPNReferenceOption
                                                    += (`<option value="` +
                                                        val
                                                        .Material +
                                                        `">` +
                                                        val
                                                        .Material +
                                                        ` (` +
                                                        val
                                                        .Description +
                                                        `)</option>`
                                                    )
                                            });
                                        $('#newPNReference')
                                            .html(
                                                htmlPNReferenceOption
                                            );
                                    }
                                });
                                $('#newProductCategory').on('change',
                                    function(
                                        e) {
                                        if ($('#newCalculationType')
                                            .val() == 'Repeat Order') {
                                            $('#htmlPNReference').html(
                                                `<select id="newPNReference" name="newPNReference" class="form-control" style="width: 100%" required> <option value=""></option></select>`
                                            );
                                            $('#newPNReference').html(
                                                '');
                                            $.ajax({
                                                url: "{{url('cogs-search-bom')}}",
                                                data: {
                                                    category: this
                                                        .value
                                                },
                                                type: "GET",
                                                dataType: "JSON",
                                                success: function(
                                                    data) {
                                                    htmlPNReferenceOption
                                                        =
                                                        ``;
                                                    $.each(data
                                                        .PNReference,
                                                        (i,
                                                            val
                                                        ) => {
                                                            htmlPNReferenceOption
                                                                +=
                                                                (`<option value="` +
                                                                    val
                                                                    .Material +
                                                                    `">` +
                                                                    val
                                                                    .Material +
                                                                    ` (` +
                                                                    val
                                                                    .Description +
                                                                    `)</option>`
                                                                )
                                                        }
                                                    );
                                                    $('#newPNReference')
                                                        .html(
                                                            htmlPNReferenceOption
                                                        );
                                                }
                                            });
                                        }
                                    });
                            }
                        });
                    };

                    if ($("#newCalculationType").val() == "New PN") {
                        console.log("A");
                        NewPN();
                    }
                    if ($("#newCalculationType").val() == 'Repeat Order') {
                        console.log("B");
                        RepeatOrder();
                    }

                    $('#newCalculationType').on('change', function(e) {
                        if ($("#newCalculationType").val() == "New PN") {
                            console.log("A");
                            NewPN();
                        }
                        if ($("#newCalculationType").val() == 'Repeat Order') {
                            console.log("B");
                            RepeatOrder();
                        }
                    });
                }
            });


        } else if ($(this).hasClass('badge-danger')) {
            $('#PEDNumber').prop('readonly', true);
            $('#PEDNumber').val(oldPEDNumber);
            $('#UnitWeight').prop('readonly', true);
            $('#UnitWeight').val(oldUnitWeight);
            $(this).removeClass('badge-danger');
            $(this).addClass('badge-info');
            $(this).html(`<i class="fa fa-pencil fa-xs"></i> Edit Header`);
            $('#htmlProductCategory').html(`
                <input id="ProductCategory" name="ProductCategory" class="form-control" type="text" value="{{ $data['ProductCategory'] }}" readonly>
            `)
            $('#htmlCalculationType').html(`
                <input id="CalculationType" name="CalculationType" class="form-control" type="text" value="{{ $data['CalculationType'] }}" readonly>
            `)
            $('#htmlPNReference').html(`
                <input id="PNReferenceCode" name="PNReferenceCode" class="form-control d-none" type="text" value="{{ $data['PNReferenceCode'] }}" readonly>
                <textarea id="PNReference" name="PNReference" class="form-control" type="text" value="{{ $data['PNReference'] }}" readonly>{{ $data['PNReference'] }}
                </textarea>
            `);
            $("textarea").each(function() {
                this.setAttribute("style", "resize:none");
            });
            $('#saveHeaderCOGS').addClass('d-none');
        }
    });

    //===================================================================================================================//
    $('#saveHeaderCOGS').on("click", function(e) {
        if ($('#newPNReference').val() == "") {
            $('#newPNReference').val($('#COGSID').val())
        }
        Swal.fire({
            title: 'Update Header',
            html: "<b>Data akan terhapus!</b><br>Data Raw Material, S/F & Component, Consumables, Man-Hour, dan Others akan terhapus, apakah anda yakin ingin memperbaharui header!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#SaveHeader").submit();
            }
        })
    });
});
</script>
@endsection
