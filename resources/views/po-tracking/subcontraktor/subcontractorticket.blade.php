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
            top: -60px;
            left: 2px;
            right: 20px;
            /* background-color: lightblue; */
            height: 100px;
            position: relative !important;
        }


        footer {
            position: fixed;
            bottom:-30px;
            left: 2px;
            right: 2px;
            /* background-color: lightblue; */
            height: 100px;
        }
        .page-break {
                page-break-after: always;
            }
        foot {
            position: fixed;
            bottom: -60px;
            left: 2px;
            right: 2px;
            /* background-color: lightblue; */
            height: 10;
        }

        div {
            page-break-after: always;
        }

        div:last-child {
            page-break-after: never;
        }
        .tabel1 {
                font-family: sans-serif;
                color: #444;
                border-collapse: collapse;
                width: 100%;
                border: 1px solid #f2f5f7;
            }

            .tabel1 tr th{
                background: #35A9DB;
                color: #fff;
                font-weight: normal;
            }

            .tabel1, th, td {
                padding: 10px 20px;
                text-align: left;
            }

            .tabel1 tr:hover {
                background-color: #f5f5f5;
            }

            .tabel1 tr:nth-child(even) {
                background-color: #f2f2f2;
            }
    </style>

    <title>{{ $dataviewticket->TicketID }}</title>
</head>

<body>

    <header>
        <font size="2" face="Courier New">
            <table>
                <tr>
                    <td width="50">
                        <img src="{{ public_path('assetss/images/patria.png') }}" alt="Logo" height="25">
                    </td>
                    <td>
                        <div style="margin-left:20px">
                        <center>
                            <h2 style="white-space: nowrap">PT United Tractors Pandu Engineering</h2>
                            <span>Jln.Jababeka XI Blok H30-40 Kawasan Industri Jababeka Cikarang 17530-Indonesia</span>
                        </center>
                        </div>
                    </td>

                    <td width="160">
                        <img style="margin : -30px 5px auto 10px" src="data:image/png;base64, {!! $qrcode !!}" height="160" >
                    </td>
                </tr>
            </table>
            <hr>
    </header>

    <main>
        <font size="1" face="Courier New">
            <div>
                <table>
                    <tr>
                        <td width="160">
                             </td>
                        <td>
                            <center>
                                <h2>DELIVERY TICKET</h2>

                            </center>
                        </td>

                        <td width="80">
                            <center>
                            </center>
                        </td>
                    </tr>
                </table>

                        <table>


                            <tr>
                                <th >No Ticket</th>
                                <td>: {{ $dataviewticket->TicketID }}</td>
                                <th ></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th >PO Number</th>
                                <td>: {{ $dataviewticket->Number }}</td>
                                <th >PO Date</th>
                                <td>: {{ date('d/m/Y', strtotime($dataviewticket->Date)) }} </td>
                            </tr>
                            <tr>
                                <th >Vendor Name</th>
                                <td>: {{ $dataviewticket->Name }}</td>
                                <th >Vendor Code</th>
                                <td>: {{ $dataviewticket->VendorCode }}</td>
                            </tr>
                            <tr>
                                <th >Delivery Note</th>
                                <td>: {{ $dataviewticket->DeliveryNote }}</td>
                                <th >Header Text</th>
                                <td>: {{ $dataviewticket->headertextgr }}</td>
                            </tr>
                            <tr>
                                <th >WH Confirmation Date</th>
                                <td>: {{ date('d/m/Y', strtotime($dataviewticket->DeliveryDate)); }}</td>
                                <th >WH Confirmation Time</th>
                                <td>: {{ date('H:i:s', strtotime($dataviewticket->DeliveryDate)); }}</td>
                            </tr>
                            <tr>
                                <th >SPB Date</th>
                                <td>: {{ date('d/m/Y', strtotime($dataviewticket->SPBDate)); }}</td>
                            </tr>
                        </table>

                <table class="tabel1" >
                    <thead style="background-color:#c9c9c9;">
                        <tr>
                            <th><strong>Item Number<strong></th>
                            <th><strong>Part Number<strong></th>
                            <th><strong>Description<strong></th>
                            <th><strong>Delivery Date<strong></th>
                            <th><strong>Qty<strong></th>

                        </tr>
                    </thead>

                    @foreach($data as $key)

                    <tr>

                        <td>{{ $key->ItemNumber}}</td>
                        <td>{{ $key->Material}}</td>
                        <td>{{ $key->Description}}</td>
                        <td>{{ date('d/m/Y', strtotime($dataviewticket->DeliveryDates)); }}</td>
                        <td>{{ $key->Quantity}}</td>

                    </tr>
                    @endforeach
                    
                    {{-- @for($i=0;$i<20;$i++)
                    <tr>
                        <td colspan="5">
                        test text{{$i}}
                        </td>
                    </tr>
                    @endfor --}}
                </table>
                <table style="margin-top: 80px; margin-left: 50px;">">
                    <tr>
                        <th width="160">Vendor</th>
                        <th width="160">Security</th>
                        <th width="160">Warehouse</th>
                    </tr>
                </table>
            </div>


        </font>
    </main>
</body>

</html>
