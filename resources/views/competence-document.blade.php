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
                <center><h2>Hal : Golongan Dan Gaji</h2><u>{{ $eletter['letterno'] }}</u></center>
                <br><br>
                Dengan Hormat,<br><br>
                Golongan dan Gaji Saudar{{ $eletter['gender'] }} adalah sebagai berikut :<br><br>
            </p>
            <table>
                <tr style="height: 0;">
                    <td width="30">
                    </td>
                    <td width="100"> <strong>Golongan</strong></td>
                    <td> <strong>: {{ $eletter['performance'] }}</strong></td>
                </tr>
                <tr style="height: 0;">
                    <td width="30">
                    </td>
                    <td  width="100"> <strong>Gaji (gross)</strong></td>
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
                Ketetapan tersebut diatas berlaku sejak Januari {{ date('Y',strtotime($eletter['datesigned']))+1 }} sampai dengan adanya ketetapan selanjutnya.<br> <br> 
                Demikian pemberitahuan kami, semoga perubahan ini dapat lebih memacu semangat Saudar{{ $eletter['gender'] }}, sehingga dapat meningkatkan kompetensi diri untuk kemajuan Perusahaan.<br> <br> <br>
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