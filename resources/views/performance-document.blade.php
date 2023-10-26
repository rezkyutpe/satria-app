<html>

<head>
    <style>
        @page {
            margin: 100px 70px;
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
p {
  text-align: justify;
  text-justify: inter-word;
}
    </style>
</head>

<body>
    <header>
        <font size="2" face="arial,helvetica">
        <img src="{{ $eletter['header'] }}" alt="Logo" style="margin-left:-50px; margin-top:-40px;width:120%;">
            <table style="width:100%;">
                <tr>
                    <td width="250">
                    </td>
                    <td style="text-align:right;"> 
                        <h2>PRIVATE & CONFIDENTIAL</h2>
                        <strong>{{ $eletter['locationsigned'] }}, {{ date('d F Y',strtotime($eletter['datesigned'])) }}</strong>
                    </td>
                </tr>
                <tr>
                    <td width="250"><br><br>
                        Kepada Yth<br>
                        <strong>Saudar{{ $eletter['gender'] }} {{ $eletter['name'] }}</strong><br>
                        NRP : {{ $eletter['nrp'] }} <br>
                        UTPE HEAD OFFICE
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
            <p>
                <center><h2>Hal : Penilaian Performance dan Bonus tahun {{ date('Y',strtotime($eletter['datesigned'])) }}</h2><u>{{ $eletter['letterno'] }}</u></center>
                <br><br>
                Dengan Hormat,<br><br>
                Kami, atas nama manajemen {{ $eletter['company'] }}, mengucapkan terima kasih atas kinerja Saudar{{ $eletter['gender'] }} selama tahun {{ date('Y',strtotime($eletter['datesigned'])) }}. Komitmen 
                dan dedikasi profesional yang telah Saudar{{ $eletter['gender'] }} berikan memiliki kontribusi nyata terhadap performa perusahaan di tahun {{ date('Y',strtotime($eletter['datesigned'])) }}.
                <br><br>
                Dewan Direksi {{ $eletter['company'] }} menetapkan hasil Performance Approval dan Bonus (gross) tahun {{ date('Y',strtotime($eletter['datesigned'])) }} atas nama Saudar{{ $eletter['gender'] }} sebagai berikut :<br><br>
            </p>
            <table>
                <tr style="height: 0;">
                    <td width="30">
                    </td>
                    <td width="100"> <strong>Performance</strong></td>
                    <td> <strong>: {{ $eletter['performance'] }}</strong></td>
                </tr>
                <tr style="height: 0;">
                    <td width="30">
                    </td>
                    <td  width="100"> <strong>Rupiah Bonus</strong></td>
                    <td> <strong>: Rp. {{ number_format($eletter['amount'],0,',','.'); }}</strong></td>
                </tr>
                <tr style="height: 0;">
                    <td width="30">
                    </td>
                    <td  width="100"> <strong>Terbilang</strong></td>
                    <td style="text-transform: capitalize"> <strong>: {{ $eletter['terbilang'] }} Rupiah</strong></td>
                </tr>
            </table>
            <p>
                <br><br>
                Kami berharap agar Saudar{{ $eletter['gender'] }}, dapat terus meningkatkan kinerja dan berkontribusi maksimal untuk meningkatkan performa perusahaan di tahun-tahun mendatang.<br> <br> <br>
            </p>
            <table>
                <tr style="height: 0;">
                    <td width="300"><strong>{{ $eletter['company'] }}</strong>
                    </td>
                </tr>
                
                <tr style="height: 0;">
                    <td width="300"> <br> <img src="" alt="Logo" height="25"> <br>
                    </td>
                </tr>
                
                <tr style="height: 0;">
                    <td width="300"><u><strong>{{ $eletter['president'] }}</strong></u><br> <strong>Presiden Direktur</strong>
                    </td>
                </tr>
            </table>
    </header>
    <foot>
        <font size="1" face="arial,helvetica" >
        <!-- <p>Page 1</p> -->
    </foot>
    <main>
        <font size="1" face="arial,helvetica">

    </main>
</body>

</html>