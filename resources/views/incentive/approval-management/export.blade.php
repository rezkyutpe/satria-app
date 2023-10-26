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
                    <td>
                        <img src="https://www.patria.co.id/images/affiliate_company/4_a.jpg" alt="Logo" height="50">
                    </td>
                    <td>
                        <center>
                            <h2>PT. Patria Perikanan Lestari Indonesia</h2>
                            <span>Wing Building Lt. 5
PT United Tractors Tbk.
Jl. Raya Bekasi KM22 - Cakung - Jakarta Timur
</span>
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
        <font size="2" face="Courier New">
            <div style="margin-top: 30px;">
                <table>
                    <tr>
                        <td><strong>Incentive Sales Report </strong></td>
                        <td></td>
                    </tr>
                    
                </table>
                <br>
                
                <table style="width:100%; margin-top:5px;">
                    <thead style="background-color:#c9c9c9;">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>NRP</th>
                            <th>Name</th>
                            <th>Cashin Month</th>
                            <th>Amount Incentive</th>
                            <th>Status</th>
                            <th>Fully Approved</th>
                        </tr>
                    </thead>
                    @php ($no=0)
                    @foreach($approval as $approval)
                    @php ($no=$no+1)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $approval->id_req }}</td>
                            <td>{{ $approval->sales }}</td>
                            <td>{{ $approval->sales_name }}</td>
                            <td>{{ date('Y-m',strtotime($approval->req_month)) }}</td>
                            <td>{{ 'Rp '.number_format($approval->total_inc,0,',','.') }}</td>
                            <td>@if($approval['status']=='0')
                                {{ 'New'}}
                                @elseif($approval['status']=='1')
                                {{ 'Partial Approved'}}
                                @elseif($approval['status']=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif </td>
                            <td>{{ date('Y-m-d',strtotime($approval->updated_at)) }}</td>
                        </tr>

                    @if($no % 20 == 0)
                </table>
            </div>
            <div style="margin-top: 30px;">
                <font size="2" face="Courier New">

                    <table>
                        <tr>
                            <td><strong>Incentive Sales Report </strong></td>
                            <td></td>
                        </tr>
                        
                    </table>
                    <br>
                    
                    <table style="width:100%; margin-top:5px;">
                        <thead style="background-color:#c9c9c9;">
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>NRP</th>
                                <th>Name</th>
                                <th>Cahsin Month</th>
                                <th>Amount Incentive</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        @endif


                        @endforeach
                    </table>
                    <footer>

                        <!-- <table style="width:100%; text-align:center" border="0">
                            <tr>
                                <td></td>
                                <td rowspan="5">
                                    </td>
                                <td>Cikarang,</td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td>Issued by,</td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="200"> {{ 'ttd' }}</td>
                                <td width="200"> {{ 'ttd' }}</td>
                            </tr>
                        </table> -->
                    </footer>

            </div>

    </main>
</body>

</html>