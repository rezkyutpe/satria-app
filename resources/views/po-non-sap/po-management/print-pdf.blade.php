<html>

<head>
    <style>
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
        }

        footer {
            position: fixed;
            bottom:-30px;
            left: 2px;
            right: 2px;
            /* background-color: lightblue; */
            height: 100px;
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
    </style>
</head>

<body>
   
    <header>
        <font size="2" face="Courier New">
            <table>
                <tr>
                    <td width="100">
                        <img src="https://www.patria.co.id/images/patria.png" alt="Logo" height="25">
                    </td>
                    <td>
                        <center>
                            <h2>PT United Tractors Pandu Engineering</h2>
                            <span>Jln.Jababeka XI Blok H30-40 Kawasan Industri Jababeka Cikarang 17530-Indonesia</span>
                        </center>
                    </td>
                </tr>
            </table>
            <hr>
    </header>
    <foot>
        <font size="1" face="Courier New" >
        <!-- <p>Page 1</p> -->
    </foot>
    <main>
        <font size="1" face="Courier New">
            <div style="margin-top: 30px;">
                <table>
                    <tr>
                        <td><strong>Memo Internal</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><br></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>To</td>
                        <td>: PT Alfagomma Indonesia</td>
                    </tr>
                    <tr>
                        <td><br></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Nama Product</td>
                        <td>: {{ $po->product }}</td>
                    </tr>
                    <tr>
                        <td>Part Number </td>
                        <td>: {{ $po->pn }}</td>
                    </tr>
                    <tr>
                        <td>No PRO </td>
                        <td width="350">: {{ $po->pro }}</td>
                        <td>Unit</td>
                        <td>: {{ $po->qty }} unit</td>
                    </tr>
                </table>
                <br>
                <span>Mohon diprepare komponen di bawah ini :</span>
                <table style="width:100%; margin-top:5px;">
                    <thead style="background-color:#c9c9c9;">
                        <tr>
                            <th><strong>No<strong></th>
                            <th><strong>PN Patria<strong></th>
                            <th><strong>Description<strong></th>
                            <th><strong>PN Vendor<strong></th>
                            <th><strong>Qty Order<strong></th>
                            <th><strong>Qty Supply<strong></th>
                            <th><strong>Qty Use<strong></th>
                            <th><strong>Oum<strong></th>
                            <th><strong>Keterangan<strong></th>
                        </tr>
                    </thead>
                    @php ($no=0)
                    @foreach($komponen as $key)
                    @php ($no=$no+1)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $key->pn_patria}}</td>
                        <td>{{ $key->description}}</td>
                        <td>{{ $key->pn_vendor}}</td>
                        <td>{{ $key->qty_order}}</td>
                        <td>{{ $key->qty_supply}}</td>
                        <td>{{ $key->qty_use}}</td>
                        <td>{{ $key->uom}}</td>
                        <td>{{ $key->ket}}</td>
                    </tr>

                    @if($no % 30 == 0)
                </table>
            </div>
            <div style="margin-top: 30px;">
                <font size="1" face="Courier New">

                    <table>
                        <tr>
                            <td><strong>Memo Internal</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>To</td>
                            <td>: PT Alfagomma Indonesia</td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nama Product</td>
                            <td>: {{ $po->product }}</td>
                        </tr>
                        <tr>
                            <td>Part Number </td>
                            <td>: {{ $po->pn }}</td>
                        </tr>
                        <tr>
                            <td>No PRO </td>
                            <td width="350">: {{ $po->pro }}</td>
                            <td>Unit</td>
                            <td>: {{ $po->qty }} unit</td>
                        </tr>
                    </table>
                    <br>
                    <span>Mohon diprepare komponen di bawah ini :</span>
                    <table style="width:100%; margin-top:5px;">
                        <thead style="background-color:#c9c9c9;">
                            <tr>
                                <th><strong>No<strong></th>
                                <th><strong>PN Patria<strong></th>
                                <th><strong>Description<strong></th>
                                <th><strong>PN Vendor<strong></th>
                                <th><strong>Qty Order<strong></th>
                                <th><strong>Qty Supply<strong></th>
                                <th><strong>Qty Use<strong></th>
                            <th><strong>Oum<strong></th>
                                <th><strong>Keterangan<strong></th>
                            </tr>
                        </thead>
                        @endif


                        @endforeach
                    </table>
                    <footer>

                        <table style="width:100%; text-align:center" border="0">
                            <tr>
                                <td></td>
                                <td rowspan="5">
                                    <img src="data:image/png;base64, {!! $qrcode !!}" height="125"></td>
                                <td>Cikarang,</td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <!-- <td rowspan="3">b</td> -->
                                <td>Issued by,</td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <!-- <td>c</td> -->
                                <td></td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <!-- <td>d</td> -->
                                <td></td>
                            </tr>
                            <tr>
                                <td width="200"> {{ $po->ttd1 }}</td>
                                <!-- <td>e</td> -->
                                <td width="200"> {{ $po->ttd2 }}</td>
                            </tr>
                        </table>
                    </footer>

            </div>

    </main>
</body>

</html>