<html>

<head>
    <style>
        @page {
            margin: 50 25px;
        }

        header {
            position: fixed;
            top: -25px;
            left: 2px;
            right: 20px;
            /* background-color: lightblue; */
            height: 10px;
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
                        <img src="{{ asset('public/assets/global/img/patria.png') }}" alt="Logo" height="25">
                    </td>
                    <td>
                        <center>
                            <h2>PT United Tractors Pandu Engineering </h2>
                            <span>Jln.Jababeka XI Blok H30-40 Kawasan Industri Jababeka Cikarang 17530-Indonesia</span>
                        </center>
                    </td>
                </tr>
            </table>
            <hr>
    </header>
    <foot>
         <footer>
            <!-- <table style="width:100%; " border="0">
                <tr>
                    <td  style="width:68%;" >
                        <span  style="font-size: 8px;">PEMBERITAHUAN: Faktur Pajak ini telah dilaporkan ke Direktorat Jenderal Pajak dan telah memperoleh persetujuan sesuai <br>dengan ketentuan peraturan perpajakan yang berlaku. PERINGATAN: PKP yang menerbitkan Faktur Pajak yang tidak sesuai <br>dengan keadaan yang sebenarnya dan/atau sesungguhnya sebagaimana dimaksud Pasal 13 ayat (9) UU PPN dikenai sanksi <br>sesuai dengan Pasal 14 ayat (4) UU KUP</span>
                    </td>
                    <td  style="text-align:left;font-size: 11px;width:10%;">1 dari </td>
                    <td  style="text-align:left;font-size: 11px;">1</td>
                </tr>
            </table> -->
        </footer>
    </foot>
    <main>
        <font size="3" face="Courier New">

        <div style="margin-top: 100px;" >
            <table style="width: 100%;">
                <tr>
                    <td>ID</td>
                    <td>: </td>
                    <td>{{ $request->id_req}} </td>
                    <td>Claim Montd</td>
                    <td>: </td>
                    <td>{{ date('Y-m',strtotime($request->req_montd)) }}  </td>
                </tr>
                <tr>
                    <td>NRP</td>
                    <td>: </td>
                    <td>{{ $request->sales}}  </td>
                    <td>Status </td>
                    <td>: </td>
                    <td>@if($request->status=='0')
                        {{ 'New'}}
                        @elseif($request->status=='1')
                        {{ 'Partial Approved'}}
                        @elseif($request->status=='2')
                        {{ 'Fully Approved'}}
                        @else
                        {{ '-' }}
                        @endif  </td>
                </tr>
                <tr>
                    <td>Sales Name</td>
                    <td>: </td>
                    <td>{{ $request->sales_name}}  </td>
                    
                    <td></td>
                    <td> </td>
                    <td></td>
                </tr>
            </table>
            <br>
            <h1>List Inv Sales :</h1>

                <table style="width: 100%;" border="1">
                        <tr>
                            <td>No</td>
                            <!-- <td>Sales</td> -->
                            <td>InvNo</td>
                            <td>InvDate</td>
                            <td>CashDate</td>
                            <!-- <td>CustName </td>
                            <td>CustProfile </td>
                            <td>Product </td>
                            <td>Segmen </td> -->
                            <!-- <td>Berat</td> -->
                            <!-- <td class="text-center">Cost</td> -->
                            <td >Cash In</td>
                            <!-- <td>GRADING</td>
                            <td>AGING</td>
                            <td>GPM</td>
                            <td>TARGET</td> -->
                            <!-- <td>INC_EF</td>
                            <td>TOTAL</td>
                            <td>New Cust</td> -->
                            <td>Incentive</td>
                        </tr>
                    
                        @php ($no=0)
                        @php($total=0)
                        @php($total_inc=0)
                        @foreach($incentive as $row)
                        @php ($no=$no+1)
                        @php($total_factor = Helper::Total($row->grading,$row->aging,$row->gpm,$row->target,$row->inc_ef))
                        @php($inc = $row->cash_in*($total_factor/100)*($row->cust_type/100))
                        <tr>
                            <td>{{ $row->no }}</td>
                            <!-- <td>{{ $row->sales_name }}</td> -->
                            <td>{{ $row->inv }}</td>
                            <td>{{ $row->inv_date }}</td>
                            <td>{{ $row->cash_date }}</td>
                            <!-- <td>{{ $row->customer }}</td>
                            <td>{{ $row->cust_profile }}</td>
                            <td>{{ $row->product }}</td>
                            <td>{{ $row->segment }}</td> -->
                            <!-- <td>{{ $row->qty }}</td> -->
                            <!-- <td class="text-right">{{ number_format($row->tot_cost,0,',','.') }}</td> -->
                            <td class="text-right">{{ number_format($row->cash_in,0,',','.') }}</td>
                           <!--  <td>{{ $row->grading }}%</td>
                            <td>{{ $row->aging }}%</td>
                            <td>{{ $row->gpm }}%</td>
                            <td>{{ $row->target }}%</td> -->
                           <!--  <td>{{ $row->inc_ef }}%</td>
                            <td>{{ $total_factor }}%</td>
                            <td>{{ $row->cust_type }}%</td> -->
                            <td class="text-right">{{ number_format($inc,0,',','.') }}</td>
                            
                           
                           
                        </tr>
                        
                        @php ($total = $total+$row->tot_price)
                        @php ($total_inc = $total_inc+$inc)

                        @if($no % 20 == 0)
                      
                       
                </table>
        </div>
        <div style="margin-top: 100px;">

            <table style="width: 100%;">
                <tr>
                    <td>ID</td>
                    <td>: </td>
                    <td>{{ $request->id_req}} </td>
                    <td>Claim Montd</td>
                    <td>: </td>
                    <td>{{ date('Y-m',strtotime($request->req_month)) }}  </td>
                </tr>
                <tr>
                    <td>NRP</td>
                    <td>: </td>
                    <td>{{ $request->sales}}  </td>
                    <td>Status </td>
                    <td>: </td>
                    <td>@if($request->status=='0')
                        {{ 'New'}}
                        @elseif($request->status=='1')
                        {{ 'Partial Approved'}}
                        @elseif($request->status=='2')
                        {{ 'Fully Approved'}}
                        @else
                        {{ '-' }}
                        @endif  </td>
                </tr>
                <tr>
                    <td>Sales Name</td>
                    <td>: </td>
                    <td>{{ $request->sales_name}}  </td>
                    
                    <td></td>
                    <td> </td>
                    <td></td>
                </tr>
            </table>
            <h1>List Inv Sales :</h1>

                <table style="width: 100%;" border="1">
                        <tr>
                            <td>No</td>
                            <!-- <td>Sales</td> -->
                            <td>InvNo</td>
                            <td>InvDate</td>
                            <td>CashDate</td>
                            <!-- <td>CustName </td>
                            <td>CustProfile </td>
                            <td>Product </td>
                            <td>Segmen </td> -->
                            <!-- <td>Berat</td> -->
                            <!-- <td class="text-center">Cost</td> -->
                            <td >Cash In</td>
                            <!-- <td>GRADING</td>
                            <td>AGING</td>
                            <td>GPM</td>
                            <td>TARGET</td> -->
                            <!-- <td>INC_EF</td>
                            <td>TOTAL</td>
                            <td>New Cust</td> -->
                            <td>Incentive</td>
                        </tr>
            @endif
            @endforeach
             <tr>
                <td colspan="2"></td>
                <td><strong>{{  number_format($total,0,',','.') }}</strong></td>
                <td colspan="2"></td>
                <td><strong>{{  number_format($total_inc,0,',','.')  }}</strong></td>
            </tr>
            </table>
        </div>
    </main>
</body>
</html>