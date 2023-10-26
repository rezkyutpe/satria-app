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
   
   <!--  <header>
        <center>
          <strong>FAKTUR PAJAK</strong> 
        </center>
    </header> -->
    <foot>
         <footer>
            <table style="width:100%; " border="0">
                <tr>
                    <td  style="width:68%;" >
                        <span  style="font-size: 8px;">PEMBERITAHUAN: Faktur Pajak ini telah dilaporkan ke Direktorat Jenderal Pajak dan telah memperoleh persetujuan sesuai <br>dengan ketentuan peraturan perpajakan yang berlaku. PERINGATAN: PKP yang menerbitkan Faktur Pajak yang tidak sesuai <br>dengan keadaan yang sebenarnya dan/atau sesungguhnya sebagaimana dimaksud Pasal 13 ayat (9) UU PPN dikenai sanksi <br>sesuai dengan Pasal 14 ayat (4) UU KUP</span>
                    </td>
                    <td  style="text-align:left;font-size: 11px;width:10%;">1 dari </td>
                    <td  style="text-align:left;font-size: 11px;">1</td>
                </tr>
            </table>
        </footer>
    </foot>
    <main>
        <!-- <font size="2" face="Courier New"> -->
            <center style="margin-bottom: 5px;">
              <strong>FAKTUR PAJAK</strong> 
            </center>
                <table border="0" class="table" style="width:100%; font-size: 13.5px; margin-bottom: 200px; border-collapse: collapse;">
                    <tr>
                        <td height="15" class="line" colspan="3">&nbsp;Kode dan Nomor Seri Faktur Pajak : 01{{ $faktur->fgpengganti }}.{{ substr($faktur->nomorfaktur,0,3)}}-{{substr($faktur->nomorfaktur,3,2) }}.{{ substr($faktur->nomorfaktur,5) }}</td>
                    </tr>
                    <tr>
                        <td height="15" class="line" colspan="3">&nbsp;Pengusaha Kena Pajak</td>
                    </tr>
                    <tr>
                        <td class="line" colspan="3">
                            <table>
                                <tr>
                                    <td width="50">Nama</td>
                                    <td>: {{ $faktur->namapenjual }}</td>
                                </tr>
                                 <tr>
                                    <td>Alamat</td>
                                    <td>: {{ $faktur->alamatpenjual }}</td>
                                </tr>
                                 <tr>
                                    <td>NPWP</td>
                                    <td>: {{ substr($faktur->npwppenjual,0,2) }}.{{ substr($faktur->npwppenjual,2,3) }}.{{ substr($faktur->npwppenjual,5,3) }}.{{ substr($faktur->npwppenjual,8,1) }}-{{substr($faktur->npwppenjual,9,3) }}.{{substr($faktur->npwppenjual,12) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="15" class="line" colspan="3">&nbsp;Pembeli Barang Kena Pajak / Penerima Jasa Kena Pajak </td>
                    </tr>
                    <tr>
                        <td class="line" colspan="3">
                            <table>
                            <tr>
                                    <td width="50">Nama</td>
                                    <td>: {{ $faktur->namalawantransaksi }}</td>
                                </tr>
                                 <tr>
                                    <td>Alamat</td>
                                    <td>: {{ $faktur->alamatlawantransaksi }}</td>
                                </tr>
                                 <tr>
                                    <td>NPWP</td>
                                    <td>: {{ substr($faktur->npwplawantransaksi,0,2) }}.{{ substr($faktur->npwplawantransaksi,2,3) }}.{{ substr($faktur->npwplawantransaksi,5,3) }}.{{ substr($faktur->npwplawantransaksi,8,1) }}-{{substr($faktur->npwplawantransaksi,9,3) }}.{{substr($faktur->npwplawantransaksi,12) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                     <tr style="text-align:center">
                        <td class="line" width="40">No.</td>
                        <td class="line">Nama Barang Kena Pajak / Jasa Kena Pajak</td>
                        <td class="line" width="180">Harga Jual/Penggantian/Uang Muka/Termin </td>
                        
                    </tr>@php($no=1)
                    @foreach($detailfaktur as $detail)
                    <tr>
                        <td class="line" style="text-align:center">{{ $no }}</td>
                        <td class="line">&nbsp;&nbsp;{{ $detail->nama }} <br>&nbsp;&nbsp;Rp{{ number_format($detail->hargasatuan,0,',','.') }} x {{ number_format($detail->jumlahbarang,0,',','.') }}</td>
                        <td class="line" style="text-align:right;">{{ number_format($detail->hargatotal,2,',','.') }}  &nbsp;</td>
                    </tr>
                    @php($no=$no+1)
                    @endforeach
                    
                    <tr>
                        <td height="15" class="line" colspan="2">&nbsp;Harga Jual / Penggantian</td>
                        <td class="line" style="text-align:right;">{{number_format($faktur->jumlahdpp,2,',','.') }}  &nbsp;</td>
                    </tr>
                    <tr>
                        <td height="15" class="line" colspan="2">&nbsp;Dikurangi Potongan Harga</td>
                        <td class="line" style="text-align:right;">0,00  &nbsp;</td>
                    </tr>
                    <tr>
                        <td height="15" class="line" colspan="2">&nbsp;Dikurangi Uang Muka</td>
                        <td class="line" style="text-align:right;">0,00  &nbsp;</td>
                    </tr>
                    <tr>
                        <td height="15" class="line" colspan="2">&nbsp;Dasar Pengenaan Pajak</td>
                        <td class="line" style="text-align:right;">{{number_format($faktur->jumlahdpp,2,',','.')}}  &nbsp;</td>
                    </tr>
                     <tr>
                        <td height="15" class="line" colspan="2">&nbsp;PPN = 10% x Dasar Pengenaan Pajak</td>
                        <td class="line" style="text-align:right;">{{ number_format($faktur->jumlahppn,2,',','.') }}  &nbsp;</td>
                    </tr>
                     <tr>
                        <td height="15" class="line" colspan="2">&nbsp;Total PPnBM (Pajak Penjualan Barang Mewah)</td>
                        <td class="line" style="text-align:right;">{{number_format($faktur->jumlahppnbm,2,',','.') }} &nbsp;</td>
                    </tr>
                </table>
                <table style="width:100%; font-size: 13.5px" border="0">
                            <tr>
                                <td colspan="3" style="font-size: 11px;">Sesuai dengan ketentuan yang berlaku, Direktorat Jendral Pajak mengatur bahwa Faktur Pajak ini telah ditandatangani<br> secara elektronik sehingga tidak diperlukan tanda tangan basah pada Faktur Pajak ini.
                                </td>
                            </tr>
                            <tr style="text-align:center">
                                <td rowspan="4" width="100">
                                    <img src="data:image/png;base64, {!! $qrcode !!}" height="100"></td>
                                    <td></td>
                                <td style="text-align:left">JAKARTA PUSAT, {{ tanggal_indo($faktur->tanggalfaktur)}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <!-- <td rowspan="3">b</td> -->
                                <td></td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <!-- <td>c</td> -->
                                <td><br></td>
                            </tr>
                           
                            <tr >
                                <td width="200"> </td>
                                <!-- <td>e</td> -->
                                <td width="200">LEONARDUS YAMINOTO</td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ $faktur->referensi }}</td>
                            </tr>
                        </table>
            <div style="margin-top: 30px;">
                <!-- <font size="2" face="Courier New"> -->

                  
                   

            </div>

    </main>
</body>

</html>