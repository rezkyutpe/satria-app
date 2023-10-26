<html>

    <head>
        <title>{{ $DataHeader->PNReference }}</title>
        <style>
        /* footer {
            position: fixed;

            left: 2px;
            right: 2px;
            height: 100px;
        }

        foot {
            position: fixed;
            bottom: -60px;
            left: 2px;
            right: 2px;
            height: 10;
        } */

        /* div {
            page-break-after: always;
        }

        div:last-child {
            page-break-after: never;
        } */

        /* .tabel1 {
            width: 105%;
            font-family: sans-serif;
            border-collapse: collapse;
            border: 1px solid black;
        } */

        /* .th {
            border: 1px solid black;
        } */

        /* .td {
            border: 1px solid black;
        } */

        /* .tabel1,
        th,
        td {
            padding: 0px 0px;
            text-align: left;
        } */




        @page {
            margin: 10px 20px;
            size: a4 portrait;
            font-size: 9px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .tabledata td,
        .tabledata th * {
            padding: 2px 8px;
        }

        </style>
    </head>

    <body style="border: solid">
        <header style="position: relative; top: 0px; left: 0px;">
            <table style="width: 100%; height: 10px;">
                <tr>
                    <!-- <td style="width: 20px;">
                        <div style"background: red; height: 50px; width: 20px; border: #f2f5f7; margin-right: 0px; margin-bottom: 2px;"></div>
                    </td> -->
                    <td>
                        <img style="height: 30px;" src="https://www.patria.co.id/images/patria.png" alt="Logo">
                    </td>
                    <td>
                        <!-- <p style="color:#443b3b; text-align:right;">Page 1 of 1</p> -->
                    </td>
                    <!-- <td>
                        <hr style=background: red;height: 10px;width: 15px;border: #f2f5f7;margin-right: 0px;">
                        <hr style=background: red;height: 10px;width: 15px;border: #f2f5f7;margin-right: 0px;">
                        <hr style=background: red;height: 10px;width: 15px;border: #f2f5f7;margin-right: 0px;">
                    </td> -->
                </tr>
            </table>
        </header>


        <main>
            <div style="margin-top: -35px;">
                <center>
                    <p><b><u>COST STRUCTURE</u></b></p>
                </center>
            </div>


            <div>
                @if (!empty($DataHeader->PCRID) && !empty($DataHeader->Opportunity) && !empty($DataHeader->PICTriatra))
                <table class="tableidentity" style="width:100%; margin-left: auto; margin-right: auto; margin-top:5px;">
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>COGSID</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">
                            {{ $DataHeader->COGSID }}
                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Product Category</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">
                            {{ $DataHeader->ProductCategory }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left ">
                            <b>PCRID / CPOID</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left ">
                            @if (!empty($DataHeader->PCRID))
                            {{ $DataHeader->PCRID }}
                            @else
                            {{ $DataHeader->CPOID }}
                            @endif
                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left ">
                            <b>Calculation Type</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left ">

                            {{ $DataHeader->CalculationType }}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>Opportunity</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->Opportunity }}

                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>PN Reference</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->PNReference }}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>PIC Triatra</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->PICTriatra }}

                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Description</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left; word-wrap: break-word">

                            {{ $DataHeader->PNReferenceDesc }}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>Date Issued</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">@php echo(date("d-F-Y")) @endphp</td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Cost Estimator</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->CostEstimator}}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>Validity</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">1 Month</td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Pre Elim. Drw. Number</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->PEDNumber }}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 280px; text-align: left"></td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Unit Weight </b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            @if(empty($DataHeader->UnitWeight))
                            0 Kg
                            @else
                            {{ $DataHeader->UnitWeight }} Kg
                            @endif

                        </td>
                    </tr>
                </table>
                @else
                <table class="tableidentity" style="width:100%; margin-left: auto; margin-right: auto; margin-top:5px;">
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>COGSID</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">
                            {{ $DataHeader->COGSID }}
                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>PN Reference</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->PNReference }}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>Date Issued</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">@php echo(date("d-F-Y")) @endphp</td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Description</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: text-align: left">

                            {{ $DataHeader->PNReferenceDesc }}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>Validity</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">1 Month</td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Cost Estimator</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->CostEstimator}}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left">
                            <b>Product Category</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">
                            {{ $DataHeader->ProductCategory }}
                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Pre Elim. Drw. Number</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            {{ $DataHeader->PEDNumber }}

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align: left ">
                            <b>Calculation Type</b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left ">

                            {{ $DataHeader->CalculationType }}

                        </td>
                        <td style="width: 5px; text-align: left"></td>
                        <td style="width: 100px; text-align: left">
                            <b>Unit Weight </b>
                        </td>
                        <td style="width: 5px; text-align: left">:</td>
                        <td style="width: 280px; text-align: left">

                            @if(empty($DataHeader->UnitWeight))
                            0 Kg
                            @else
                            {{ $DataHeader->UnitWeight }} Kg
                            @endif

                        </td>
                    </tr>
                    
                </table>
                @endif
            </div>

            <!-- <div style="border-bottom: 4px double #333; padding: 5px;"></div> -->

            <div style="margin-top: 10px;">
                <table style="table-layout: auto; ">
                    <tr style="background-color: #f5f5f5">
                        <td colspan="2" style="text-align:center; width:100%;">
                            Material Price & Currency
                    </tr>
                    <tr style="background-color: #f5f5f5">
                        <td style="min-width:500px; vertical-align: top;">
                            <table class="tabledata" style="border-collapse: collapse; width:100%; ">
                                <tr>
                                    <td colspan="7" style="text-align: center; background-color: #92d050">
                                        Material Price (Kg)
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: #92d050">
                                    <td>No</td>
                                    <td>Spec</td>
                                    <td>Price Exmill</td>
                                    <td>Currency Exmill</td>
                                    <td>Price Exstock</td>
                                    <td>Currency Exstock</td>
                                    <td>Un</td>
                                </tr>
                                @foreach ($DataRawMaterialPrice as $i => $item)
                                @php
                                $PriceExmill = number_format($item->PriceExmill,2,",",".");
                                $PriceExstock = number_format($item->PriceExstock,2,",",".");
                                @endphp
                                <tr style="text-align: center;">
                                    <td style="border-right: 1px dotted gray;">{{ $i+1 }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Category }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $PriceExmill }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->CurrencyExmill }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $PriceExstock}}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->CurrencyExstock}}</td>
                                    <td>{{ $item->Un }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        <td style="min-width:240px; vertical-align: top;">
                            <table class="tabledata" style="border-collapse: collapse; width:100%;">
                                <tr>
                                    <td colspan="2" style="text-align: center; background-color: #92d050">
                                        Currency
                                    </td>
                                </tr>
                                @foreach ($DataKurs as $i => $item)
                                @php
                                $KursTengah = "Rp " . number_format($item->KursTengah,2,",",".");
                                @endphp
                                <tr style="text-align: left;">
                                    <td style="border-right: 1px dotted gray; width: 50%">
                                        {{ $item->MataUang }}
                                    </td>
                                    <td>
                                        {{ $KursTengah }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    <tr style="background-color: #f5f5f5">
                        <td colspan="2" style="text-align:center; width:100%;">
                            Import Semifinish Parts or Subcontactors and Consumable
                        </td>
                    </tr>
                    <tr style="background-color: #f5f5f5">
                        <td colspan="2" style="width:100%; vertical-align: top;">
                            <table class="tabledata" style="border-collapse: collapse; width:100%; ">
                                <tr style="text-align: center; background-color: #92d050">
                                    <td>No</td>
                                    <td>Component</td>
                                    <td>Description</td>
                                    <td>Qty</td>
                                    <td>Un</td>
                                    <td>Tax (%)</td>
                                    <td>Price</td>
                                </tr>
                                <tr style="background-color: #ffff00">
                                    <td colspan="7" style="text-align:center; width:100%;">
                                        <b>I. S/F & COMPONENT</b>
                                    </td>
                                </tr>
                                @foreach ($TopDataSFComponent as $i => $item)
                                @php
                                $Price = "Rp " . number_format($item->FinalPrice,2,",",".");
                                $Qty = number_format($item->Qty,2,",",".");
                                @endphp
                                <tr style="background-color: #f5f5f5; text-align: center;">
                                    <td style="border-right: 1px dotted gray;">{{ $i+1 }}</td>
                                    <td style="border-right: 1px dotted gray; text-align: left;">{{ $item->Component }}
                                    </td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Description }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $Qty }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Un }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Tax }}</td>
                                    <td>{{ $Price }}</td>
                                </tr>
                                @endforeach
                                @if ($SumLastDataSFComponent != 0)
                                @php
                                $Price = "Rp " . number_format($SumLastDataSFComponent,2,",",".");
                                @endphp
                                <tr style="background-color: #f5f5f5; text-align: center;">
                                    <td style="border-right: 1px dotted gray;">11</td>
                                    <td style="border-right: 1px dotted gray; text-align: left;">Others</td>
                                    <td style=" border-right: 1px dotted gray;"></td>
                                    <td style="border-right: 1px dotted gray;"></td>
                                    <td style="border-right: 1px dotted gray;"></td>
                                    <td style="border-right: 1px dotted gray;"></td>
                                    <td>{{ $Price }}</td>
                                </tr>
                                @endif
                                <tr style="background-color: #ffff00">
                                    <td colspan="7" style="text-align:center; width:100%;">
                                        <b>II. CONSUMABLES</b>
                                    </td>
                                </tr>
                                @foreach ($TopDataConsumables as $i => $item)
                                @php
                                $Price = "Rp " . number_format($item->FinalPrice,2,",",".");
                                $Qty = number_format($item->Qty,2,",",".");
                                @endphp
                                <tr style="background-color: #f5f5f5; text-align: center;">
                                    <td style="border-right: 1px dotted gray;">{{ $i+1 }}</td>
                                    <td style="border-right: 1px dotted gray; text-align: left;">{{ $item->Component }}
                                    </td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Description }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $Qty }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Un }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Tax }}</td>
                                    <td>{{ $Price }}</td>
                                </tr>
                                @endforeach
                                @if ($SumLastDataConsumables != 0)
                                @php
                                $Price = "Rp " . number_format($SumLastDataConsumables,2,",",".");
                                @endphp
                                <tr style="background-color: #f5f5f5; text-align: center;">
                                    <td style="border-right: 1px dotted gray;">11</td>
                                    <td style="border-right: 1px dotted gray; text-align: left;">Others</td>
                                    <td style="border-right: 1px dotted gray;"></td>
                                    <td style="border-right: 1px dotted gray;"></td>
                                    <td style="border-right: 1px dotted gray;"></td>
                                    <td style="border-right: 1px dotted gray;"></td>
                                    <td>{{ $Price }}</td>
                                </tr>
                                @endif
                                <tr style="background-color: #ffff00">
                                    <td colspan="7" style="text-align:center; width:100%;">
                                        <b>III. OTHERS</b>
                                    </td>
                                </tr>
                                @foreach ($DataOthers as $i => $item)
                                @php
                                $Price = "Rp " . number_format($item->TotalPrice,2,",",".");
                                $Qty = number_format($item->Qty,2,",",".");
                                @endphp
                                <tr style="background-color: #f5f5f5; text-align: center;">
                                    <td style="border-right: 1px dotted gray;">{{ $i+1 }}</td>
                                    <td style="border-right: 1px dotted gray; text-align: left;">{{ $item->PartNumber }}
                                    </td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Description }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $Qty }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Un }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Tax }}</td>
                                    <td>{{ $Price }}</td>
                                </tr>
                                @endforeach
                                <tr style="background-color: #ffff00">
                                    <td colspan="7" style="text-align:center; width:100%;">
                                        <b>IV. MATERIAL COST (KG)</b>
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: #92d050">
                                    <td>No</td>
                                    <td>Spec</td>
                                    <td>Currency</td>
                                    <td>Price / un</td>
                                    <td>Status</td>
                                    <td>Weight (Kg-gross) </td>
                                    <td>Cost</td>
                                </tr>
                                @foreach ($DataRawMaterial as $i => $item)
                                @php
                                $Weight = number_format($item->Weight,2,",",".");
                                $Price = "Rp " . number_format($item->FinalCost,2,",",".");
                                $FinalCost = "Rp " . number_format($item->FinalCost,2,",",".");
                                @endphp
                                <tr style="background-color: #f5f5f5; text-align: center;">
                                    <td style="border-right: 1px dotted gray;">{{ $i+1 }}</td>
                                    <td style="border-right: 1px dotted gray; text-align: left;">
                                        {{ $item->Spesification }}
                                    </td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Currency }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $Price }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Status }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $Weight }}</td>
                                    <td>{{ $FinalCost }}</td>
                                </tr>
                                @endforeach
                                <tr style="background-color: #ffff00">
                                    <td colspan="5"
                                        style="text-align:center; width:100%; border-right: 1px dotted gray;">
                                        <b>TOTAL</b>
                                    </td>
                                    <td style="text-align:center; border-right: 1px dotted gray;">
                                        @php
                                        $TotalWeightMaterial = number_format($TotalWeightMaterial,2,",",".") . " Kg";
                                        @endphp
                                        {{ $TotalWeightMaterial }}
                                    </td>
                                    <td style="text-align:center;">
                                        @php
                                        $Total = "Rp " . number_format($TotalDirectMaterial,2,",",".");
                                        @endphp
                                        {{ $Total }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="background-color: #f5f5f5">
                        <td colspan="2" style="text-align:center; width:100%;">
                            Manufacturing & Process Cost
                        </td>
                    </tr>
                    <tr style="background-color: #f5f5f5">
                        <td colspan="2" style="width:100%; vertical-align: top;">
                            <table class="tabledata" style="border-collapse: collapse; width:100%; ">
                                <tr style="text-align: center; background-color: #92d050">
                                    <td>No</td>
                                    <td colspan="3">Process</td>
                                    <td>UoM</td>
                                    <td>Hours</td>
                                    <td>Cost</td>
                                </tr>
                                @foreach ($DataProcess as $i => $item)
                                @php
                                $Hours = number_format($item->Hours,2,",",".");
                                $Cost = "Rp " . number_format($item->Cost,2,",",".");
                                @endphp
                                <tr style="background-color: #f5f5f5; text-align: center;">
                                    <td style="border-right: 1px dotted gray;">{{ $i+1 }}</td>
                                    <td colspan="3" style="border-right: 1px dotted gray; text-align: left;">
                                        {{ $item->Process }}
                                    </td>
                                    <td style="border-right: 1px dotted gray;">{{ $item->Um }}</td>
                                    <td style="border-right: 1px dotted gray;">{{ $Hours }}</td>
                                    <td>{{ $Cost }}</td>
                                </tr>
                                @endforeach
                                <tr style="background-color: #ffff00">
                                    <td colspan="5"
                                        style="text-align:center; width:100%; border-right: 1px dotted gray;">
                                        <b>TOTAL</b>
                                    </td>
                                    <td style="text-align:center; border-right: 1px dotted gray;">
                                        @php
                                        $TotalHours = number_format($TotalHours,2,",",".");
                                        @endphp
                                        {{ $TotalHours }}
                                    </td>
                                    <td style="text-align:center;">
                                        @php
                                        $TotalProcess = "Rp " . number_format($TotalCostProcess,2,",",".");
                                        @endphp
                                        {{ $TotalProcess }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="background-color: white;">
                        <td style="vertical-align: top;">
                            <table class="tabledata" style="border-collapse: collapse; width:250px;">
                                <tr style="text-align: center; background-color: white">
                                    <td style="text-align:left"><b>Direct Material</b></td>
                                    <td>:</td>
                                    <td style="text-align:left">
                                        @php
                                        $DirectMaterial = "Rp " . number_format($TotalDirectMaterial,2,",",".");
                                        @endphp
                                        {{ $DirectMaterial }}
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: white">
                                    <td style="text-align:left"><b>Direct Labour</b></td>
                                    <td>:</td>
                                    <td style="text-align:left">
                                        @php
                                        $DirectLabour = "Rp " . number_format($TotalCostProcess,2,",",".");
                                        @endphp
                                        {{ $DirectLabour }}
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: white">
                                    <td style="text-align:left"><b>Total COGS</b></td>
                                    <td>:</td>
                                    <td style="text-align:left">
                                        @php
                                        $TotalAmountCOGS = "Rp " . number_format($TotalCOGS,2,",",".");
                                        @endphp
                                        {{ $TotalAmountCOGS }}
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: white;">
                                    <td style="text-align:left"><b>Gross Profit </b></td>
                                    <td>:</td>
                                    <td style="text-align:left">
                                        @php
                                        $GrossProfit = number_format($DataHeader->GrossProfit,2,",",".") . " %";
                                        @endphp
                                        {{ $GrossProfit }}
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: white;">
                                    <td style="text-align:left"><b>Quotation Price</b></td>
                                    <td>:</td>
                                    <td style="text-align:left">
                                        @php
                                        $QuotationPrice = "Rp " . number_format($DataHeader->QuotationPrice,2,",",".");
                                        @endphp
                                        {{ $QuotationPrice }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="background-color: white;">
                        <td colspan="2" style="vertical-align: top;">
                            <table class="tabledata"
                                style="border-collapse: collapse; width:100%; margin-top: 10px; table-layout: auto;">
                                <tr style="text-align: center; background-color: white">
                                    <td>Issued
                                    </td>
                                    <td>Check
                                    </td>
                                    <td>Approved
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: white">
                                    <td><b>Cost Estimator - Staff</b>
                                    </td>
                                    <td><b>Marketing Manager</b>
                                    </td>
                                    <td><b>SCM Division Head</b>
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: white">
                                    <td style="height: 40px;">
                                    </td>
                                </tr>
                                <tr style="text-align: center; background-color: white; ">
                                    <td><b>{{ $DataHeader->CostEstimator }}</b>
                                    </td>
                                    <td><b>{{ $DataHeader->MarketingDeptHead }}</b>
                                    </td>
                                    <td><b>{{ $DataHeader->SCMDivisionHead }}</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </main>
    </body>

</html>
