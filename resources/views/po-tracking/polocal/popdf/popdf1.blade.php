<html>

<head>
    <style>
        td {
  text-align: center;
}
        @page {
            margin: 100px 25px;
        }

        header {

            position: fixed;
            top: -150px;
            left: -50px;
            /* background-color: lightblue; */
            height: 50px;
        }

        footer {

            left: 2px;
            right: 2px;
            /* background-color: lightblue; */
            height: 100px;

        }
        main{
        width: 100%;
        height: auto;

        }
        .dashed{
        border: 20px red solid;
        height: 20px;
        margin-bottom: 30%;

        }
        .garis{
        background: red ;
        height: 7px;
        width: 40px;
        border:  #f2f5f7;
        margin-left: 530px;
        }
        .header {
            page-break-after: always;
        }

        .header:last-child {
            page-break-after: never;
        }

        div {
            page-break-after: always;
        }

        div:last-child {
            page-break-after: never;
        }
        .tabel1 {
                font-family: sans-serif;
                width: 105%;
                border-collapse: collapse;
            }
        .th{
             border-bottom: 1px solid ;
             border-top: 1px solid ;
        }
        .td{

        }

        .tabel1, th, td {
                padding: 8px 16px;
                text-align: left;
        }

    </style>
</head>

<body>

    <header class="main">
            <table>
                <tr>
                    <td>
                        <div class="dashed"></div>
                    </td>
                    <td>
                        <img style="margin-left: -30px;" src="{{ public_path('assetss/images/patria.png') }}" alt="Logo" height="40">

                    </td>
                    <td >
                        <hr class="garis" >
                        <hr class="garis">
                        <hr class="garis">
                        <hr class="garis">
                        <hr class="garis">
                        <p style="margin-left: 430px; color:#000000;" >Page 1 / 1</p>
                    </td>

                </tr>
            </table>

    </header>

    <main>
        <font size="1" face="Sans-serif">
            <div style="margin-top: -37px;">
                <table>
                    <tr>
                        <td width="160">
                             </td>
                        <td>
                            <center>
                                <h2>PURCHASE ORDER</h2>
                            </center>
                        </td>

                        <td width="80">

                        </td>
                    </tr>
                </table>
                <table style="margin-left: -20px;">
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">P.O. Number & Date
                               </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: {{ $datapo->Number }} / {{ date('d.m.Y', strtotime($datapo->Date)) }}
                            </p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">P.O. Revision
                               </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: {{ $datapo->Number_old == null ? '-' : $datapo->Number_old }}</p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Supplier No. & Name
                               </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: {{ $datapo->VendorCode }} / {{ $datapo->Vendor }}</p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Supplier Address
                               </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: {{ $datapo->Address }} </p>
                        </th>
                    </tr>
                    <tr >
                        <th width=160>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Supplier Contact Name & Email
                               </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: {{ $datapo->Vendor }} / {{ $datapo->Email }}</p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Phone
                            </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: {{ $datapo->PhoneNo }}</p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Bill To
                            </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: PT UNITED TRACTORS PANDU ENGINEERING <br> &nbsp;
                                Jl. Jababeka XI Blok H 30-40 17530
                                </p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Ship To

                            </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: PT UNITED TRACTORS PANDU ENGINEERING <br> &nbsp;
                                Jl. Jababeka XI Blok H 30-40 17530
                                </p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Freight terms

                            </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: FRC CIKARANG
                            </p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">NPWP ( Tax ID )


                            </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: PT. United Tractors Pandu Engineering - 01.060.602.8-007.000
                            </p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Payment Terms

                               </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: Pay Within 60 Days Due Net</p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Currency


                               </p>
                        </th>
                        <th>
                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: {{ $datapo->Currency }}</p>
                        </th>
                    </tr>
                    <tr >
                        <th width=150>
                            <p style=" font-size:13px; font-family:Sans-serif; margin-top: -20px;">Buyer Contact Name & Email

                               </p>
                        </th>
                        <th>
                            @php
                                if ($datapo->VendorType == "Vendor Local"){
                                    $vendor = "Local" ;
                                }elseif ($datapo->VendorType == "Vendor SubCont"){
                                    $vendor = "Subcont" ;
                                }elseif ($datapo->VendorType == "Vendor Import"){
                                    $vendor = "Import" ;
                                }
                            @endphp

                            <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">: UN COMP {{ $vendor }} /</p>
                        </th>
                    </tr>

                </table>

                <main-section>
                    <table style="margin-left: -20px; margin-top: -27px;">
                        <tr>

                            <td>
                              <p style="font-size:13px; font-family:Sans-serif; font-weight: bold;">Purchase Order line Details,
                                  </p>
                            </td>
                        </tr>
                    </table>
                    <table class="tabel1" style="margin-left: -20px; margin-top: -20px; ">


                        <tbody>
                            <tr>
                                <th class="th">No.</th>
                                <th class="th">Part No.Supp</th>
                                <th class="th">Description </th>
                                <th class="th">Est.Delivery
                                    Date
                                    </th>
                                <th class="th">Qty</th>
                                <th class="th">Uom</th>
                                <th class="th">Unit Price</th>
                                <th class="th">Curr</th>
                                <th class="th">Amount</th>
                            </tr>
                            @php
                            $no = 1;
                            $jumlahkeseluruhan = 0;
                            $jumlahqty =  0 ;
                              @endphp
                        @foreach($data as $key)
                        @php
                          $priceIDR = number_format(substr($key->NetPrice, 0),0,',','.');
                          $qty =  $key->Quantity ;
                          $jumlah =  intval($key->NetPrice) * $qty ;
                          $jumlahkeseluruhan += intval($jumlah);
                          $jumlahqty += $key->Quantity;
                          if ($key->DeliveryDate < '2021-04-01') {
                                $p = (10/100) ;
                            } else {
                               $p = (11/100) ;
                            }
                               $delivery = date('d/m/Y', strtotime($key->DeliveryDate)) ;

                        @endphp
                        <tr>
                            <td class="td">{{ $key->ItemNumber }}</td>
                            <td class="td">{{ $key->Material }}</td>
                            <td class="td">{{ $key->Description }}</td>
                            <td class="td">{{ $delivery }}</td>
                            <td class="td">{{ $key->Quantity }}</td>
                            <td class="td">PC</td>
                            <td class="td">{{ $priceIDR }}</td>
                            <td class="td">{{ $key->Currency }}</td>
                            <td class="td">{{ number_format($jumlah,0,'.','.') }}</td>

                        </tr>

                        @endforeach
                        @php
                        $pajak =  intval($jumlahkeseluruhan) * $p ;
                        $total = $jumlahkeseluruhan + $pajak ;
                        @endphp
                        <tr>
                            <th class="th" colspan="4"><strong>Remarks:<strong></th>

                            <th class="th"  colspan="4"><strong>Total Exclude VAT : </strong></th>

                            <th class="th"  colspan="3" ><strong >{{ number_format($jumlahkeseluruhan,0,'.','.') }} <strong></th>
                        </tr>
                        </tbody>
                    </table>




                </main-section>
            </div>

        </font>
    </main>
    <footer>
        <header-footer style="margin-left: -16px;">
                  <p style="width=500px ;font-size:13px; font-family:Sans-serif; font-weight: bold; margin-bottom: 20px;">Supplier Confirmation : <br>
                    We acknowledge receipt of this Purchase Order and confirm our compliance with the details and other terms and
                    condition like stated on this PO.</p>
        </header-footer>
        <table style="margin-left: -20px;">


            <tr>
                <td width=300>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">Signed :
                       </p>
                </td>
                <td>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;"></p>
                </td>
                <td>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px; margin-left: -72px;">Authorized Person,</p>
                </td>
            </tr>
            <tr >
                <td width=300>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">
                        Name &nbsp; :  </p>
                </td>
                <td>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;"></p>
                </td>
                <td>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px; margin-left: -72px;">{{ $datapo->NRP }}</p>
                </td>
            </tr>
            <tr>
                <td width=300>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;">
                        Date &nbsp; : </p>
                </td>
                <td>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px;"></p>
                </td>
                <td>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold; margin-top: -27px; margin-left: -72px;">PURCHASER : {{ $datapo->PurchaseOrderCreator }}</p>
                </td>
            </tr>
            <tr >
                <td width=300>

                </td>
                <td>
                    <p style="font-size:13px; font-family:Sans-serif; font-weight: bold;"></p>
                </td>
                <td>
                    <p style="font-size:16px; font-family:Sans-serif; font-weight: bold; margin-top: -40px; margin-left: -72px;">
                        ELECTRONICALLY APPROVED</p>
                </td>
            </tr>

        </table>
        <header-footer style="margin-left:-16px; " >
              <p style="font-size:13px;margin-bottom: 20px; margin-top: -40px;"><b>Term and Condition :</b>  <br>
                1. Goods not in accordance with specification will be rejected and held by
                     supplier. <br>
                 2. UTPE has the right to cancel or give a penalty from all or part of this
                     order if not delivered with the time specified. <br>
                 3.  Delivery order must accompany all shipments. <br>
                 4.  In the event of interruption of our business in whole or part by reason of
                     fire, flood, windstorm, earthquake, war, strike, embargo, governmental
                     action, or any causes beyond our control, we shall have the option of
                     cancelling undelivered orders in whole or part. <br>
                 5.  Acceptance of this purchases order or shipment of any part of it will
                     constitute and agreement to all its specifications as to term, delivery and
                     prices. <br>
                 6.  For approval, supplier required to sign this purchase order and send it
                     back to UTPE at the time of invoicing or other specified time stated by
                     UTPE</p>
        </header-footer>
    </footer>
</body>

</html>
