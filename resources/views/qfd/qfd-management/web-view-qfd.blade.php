<?php
function tanggal_indo($tanggal)
{
    $bulan = array (1 =>   'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}
?>
<html>

<head>
    <style>
        @page {
            margin: 70px 25px;
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
            bottom:10px;
            left: 2px;
            right: 2px;
            /* background-color: lightblue; */
            height: 10px;
        }
        foot {
            position: fixed;
            bottom: 20px;
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
        .line {
            border: 1px solid black;
        }
        .t1 {
            font-family: Arial, Helvetica, sans-serif;
            -moz-tab-size: 4;
            tab-size: 4;
            margin: 8px 4px;

        }
    </style>
</head>

<body style="font-family: Arial, Helvetica, sans-serif">
   
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
        <font size="4" face="Courier New">

        <div style="margin-top: 100px;">
            <center style="margin-bottom: 5px;">
              <strong>QFD</strong> 
            </center>
            <!-- <hr> -->
                <table>
                    <tr>
                        <td>Material Desc</td>
                        <td>:</td>
                        <td>{{ $trxmat->material_description }}</td>
                    </tr>
                    <tr>
                        <td>Material Number</td>
                        <td>:</td>
                        <td>{{ $trxmat->material_number }}</td>
                    </tr>
                </table>
                <br>

              <strong>Detail Process</strong> 
                <br>
                <table border="1" class="table" style="width:100%; font-size: 13.5px; margin-bottom: 200px; border-collapse: collapse;">
                   
                    <tr>
                        <th>Procces Lead Time</th>
                        <th>Process</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Lead Time</th>
                        <th>PIC</th>
                        <th>Remark</th> 
                    </tr>
                    @php($no=0)
                    @php($totaldifproses=0)
                    @php($totaldif=0)
                    @foreach($trxmatdetail as $key => $trxdetaildata)
                    @php($no=$no+1)
                    @php($totaldif=$totaldif+($trxdetaildata->diff))
                    <tr>
                        <td>
                                    @if(isset($trxdetaildata[$key-1]))
                                        @php($diffproses = Helper::Datediff($trxdetaildata[$key-1]->to,$trxdetaildata->from))
                                        @php($totaldifproses=$totaldifproses+($diffproses))

                                        {{ $diffproses }} Days
                                    @else
                                        {{ 0 }} Days
                                    @endif
                                
                        </td>
                        <td>{{ $trxdetaildata->id_proses }}</td>
                        </td>
                        <td>{{ $trxdetaildata->from }}</td>
                        <td>{{ $trxdetaildata->to }}</td>
                        <td> {{ $trxdetaildata->diff }} Days</td>

                        <td>{{ $trxdetaildata->pic }} </td>
                        <td> {{ $trxdetaildata->remark }}</td>
                        
                        <!-- 
                        <td><a href="#" class="btn btn-danger remove"><i
                                    class="glyphicon glyphicon-remove"></i></a></td> -->
                    </tr>
                    @endforeach
                </table>
            </div>
<!-- 
            <div style="margin-top: 30px;">
            </div> -->

    </main>
</body>

</html>