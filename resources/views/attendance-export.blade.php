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

        u {    
    border-bottom: 1px dotted #000;
    text-decoration: none;
    width: 100%;
}
        header {
            position: fixed;
            top: -25px;
            /*background-color: lightblue; */
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
        table,tbody, td {
        border: 1px solid black;
        border-collapse: collapse;
        }
        div:last-child {
            page-break-after: never;
        }
        .line {
            border: 1px solid black;
            text-align: center;
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
            <img src="{{ env('BASE_HEADER_LOGO') }}" alt="Logo" style="margin-left:10px; margin-top:-10px;width:30%;">
    </header>
   
    <main>
        <font size="2" face="Courier New">
            <center><h2>Attendance Report </center>
            <hr>
            <br>
            
            <p> <strong> NRP. </strong> <u>{{ $userdetail->nrp }}</u>       <strong> Name </strong><span style="width:100%;border-bottom: 1px dotted #000;text-decoration: none;">{{ $userdetail->name }}</span> </p>
            <p> <strong> DEPT. </strong><u>{{ $userdetail->department }}</p></u>
            <p> <strong> MONTH. </strong><u>{{ date('F Y',strtotime($_GET['q'].'-01'))}}</p></u>
            <table style="width:100%; font-size: 13.5px" border="0">
            <tbody>
                <tr>
                    <td  class="line" >WFH</td>
                    <td  class="line" >SICK</td>
                    <td  class="line" >PERMIT</td>
                    <td  class="line" >LEAVE</td>
                    <td  class="line" >OTHER</td>
                </tr>
                <tr>
                    <td  class="line" >{{ isset(array_count_values(array_column($attendance->toArray(), 'work_metode'))['WFH']) ? array_count_values(array_column($attendance->toArray(), 'work_metode'))['WFH'] : '0' }}</td>
                    <td  class="line" >0</td>
                    <td  class="line" >0</td>
                    <td  class="line" >0</td>
                    <td  class="line" >0</td>
                </tr></tbody>
            </table>
            <br>
            <br>
        <font size="2" face="Courier New">
            <table style="width:100%;text-align: center; font-size: 13.5px" border="1">
            <tr>
                <td rowspan="2">Date</td>
                <td colspan="2">MORNING</td>
                <td colspan="2">AFTERNOON</td>
                <td colspan="2">OVERTIME</td>
                <td rowspan="2">SIGN</td>
            </tr>
            
            <tr>
                <td>In</td>
                <td>Out</td>
                <td>In</td>
                <td>Out</td>
                <td>In</td>
                <td>Out</td>
            </tr>
            @for($i=0;$i<=30;$i++)
            @php($no=0)
            @foreach($attendance as $item)
            @if($i+1==date('d',strtotime($item->in_time)))
            @php($no=$no+1)
            <tr @if(date('l',strtotime($item->in_time))=="Saturday" || date('l',strtotime($item->in_time))=="Sunday") style="background-color:lightgrey" @endif>

                <td>{{ $i+1 }}</td>
                <td @if(date('Hi',strtotime(isset($item->revice_in_time) ? $item->revice_in_time : $item->in_time))>730)style="color: red;" @endif>{{ isset($item->revice_in_time) ? date('H:i',strtotime($item->revice_in_time)) : (isset($item->in_time) ? date('H:i',strtotime($item->in_time)) : '-')}}</td>
                <td>{{ ($item->is_ovt==1) ? '' : (isset($item->revice_out_time) ? date('H:i',strtotime($item->revice_out_time)) : (isset($item->out_time) ? date('H:i',strtotime($item->out_time)) : '-'))}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ ($item->is_ovt==1) ? isset($item->revice_out_time) ? date('H:i',strtotime($item->revice_out_time)) : (isset($item->out_time) ? date('H:i',strtotime($item->out_time)) : '-') : '' }}</td>
                <td></td>
            </tr>
            @endif
            @endforeach
            @if($no!=1)
            <tr @if(date('l',strtotime(date('Y-m-',strtotime($item->in_time)).($i+1)))=="Saturday" || date('l',strtotime(date('Y-m-',strtotime($item->in_time)).($i+1))) == "Sunday")style="background-color:lightgrey"@endif >
                <td>{{ $i+1 }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endif
            @endfor
            </table>
            <div style="margin-top: 30px;">
                <!-- <font size="2" face="Courier New"> -->

            </div>

    </main>
</body>

</html>