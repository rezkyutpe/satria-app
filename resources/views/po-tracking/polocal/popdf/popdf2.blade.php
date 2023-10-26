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
        .column {
                float: left;
                width: 50%;
                padding: 5px;
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
                width: 100%;
                border-collapse: collapse;
            }
        .th{
             border-bottom: 1px solid ;
             border-top: 1px solid ;
        }
        .wrapper-page {
        page-break-after: always;
        }

       .td{
        text-align: center;
        }

        .tabel1, th, td {

                text-align: left;
                font-size: 13px;
                padding: 1px 2px;
        }
         .headerclass{
            padding: 8px 16px;
            text-align: left;
         }

    </style>
</head>

<body>

    <header class="main">
            <table>
                <tr>
                    <td class="headerclass">
                        <div class="dashed"></div>
                    </td>
                    <td class="headerclass">
                        <img style="margin-left: -30px;" src="{{ public_path('assetss/images/patria.png') }}" alt="Logo" height="40">

                    </td>
                    <td class="headerclass">
                        <hr class="garis" >
                        <hr class="garis">
                        <hr class="garis">
                        <hr class="garis">
                        <hr class="garis">
                        <p style="margin-left: 430px; color:#000000;" >Page of 1 of 2</p>
                    </td>

                </tr>
            </table>

    </header>
    @php
                    if ($datapo->VendorType == "Vendor Local"){
                        $vendor = "Local" ;
                    }elseif ($datapo->VendorType == "Vendor SubCont"){
                        $vendor = "Subcont" ;
                    }elseif ($datapo->VendorType == "Vendor Import"){
                        $vendor = "Import" ;
                    }
                  @endphp
    <main>
        <font size="1" face="Sans-serif">
            <div style="margin-top: -15px;">
                <table class="column">
                    <tr>
                        <td>PT. UNITED TRACTORS PANDU ENGINEERING</td>
                    </tr>
                    <tr>
                        <td>Jl. Jababeka XI Blok H 30-40 Kawasan Industri Jababeka,<br>
                            Cikarang 17530, Indonesia</td>
                    </tr>
                    <tr>
                        <td>Phone: +62 21 8935016 Fax : +62 21 8934772 / 6353</td>
                    </tr>
                </table>
                <table class="column">
                    <tr>
                        <td>PO No</td>
                        <td>:</td>
                        <td>{{ $datapo->Number }}</td>
                    </tr>
                    <tr>
                        <td>PO Revision</td>
                        <td>:</td>
                        <td>{{ $datapo->Number_old == null ? '-' : $datapo->Number_old }}</td>
                    </tr>

                    <tr>
                        <td>PO Date</td>
                        <td>:</td>
                        <td>{{ date('d.m.Y', strtotime($datapo->Date)) }}</td>
                    </tr>
                    <tr>
                        <td>Order Type</td>
                        <td>:</td>
                        <td>{{ $datapo->Type }}/UTPE PO Prod.{{ $vendor }}</td>
                    </tr>
                    <tr>
                        <td>Incoterm</td>
                        <td>:</td>
                        <td>FRC</td>
                    </tr>
                    <tr>
                        <td>Currency</td>
                        <td>:</td>
                        <td>{{ $datapo->Currency }}</td>
                    </tr>
                    <tr>
                        <td>Payment Terms</td>
                        <td>:</td>
                        <td>Within 30 days fr posting date</td>
                    </tr>
                </table>

                @php
                if ($datapo->VendorType == "Vendor Local"){
                    $vendor = "Local" ;
                }elseif ($datapo->VendorType == "Vendor SubCont"){
                    $vendor = "Subcont" ;
                }elseif ($datapo->VendorType == "Vendor Import"){
                    $vendor = "Import" ;
                }
               @endphp

                <main-section>
                    <table style="margin-left: -20px; margin-top: 22%;">

                        <tr>
                            <td width="190">
                                 </td>
                            <td style="font-size: 14px;">
                                <center>
                                        <u> Pemesanan Pembelian</u>
                                </center>
                            </td>

                            <td width="80">

                            </td>
                        </tr>
                    </table>

                </main-section>
                <table class="column" style="margin-top: 4%;">
                    <tr>
                        <td>Kepada :</td>
                    </tr>
                    <tr>
                        <td>{{$datapo->VendorCode}}</td>
                    </tr>
                    <tr>
                        <td>{{$datapo->Vendor}}</td>
                    </tr>
                    <tr>
                        <td>{{$datapo->Address}}</td>
                    </tr>
                    <tr>
                        <td>Telp & Fax : {{$datapo->PhoneNo}}</td>
                    </tr>
                </table>
                <table class="column"  style="margin-top: 4%;" >
                    <tr>
                        <td>Dikirim ke :</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Unit Cikarang</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Jl. Jababeka XI Blok H 30-40 <br> Kawasan Industri
                            Jababeka 17530</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Telp & Tax :</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>021-8935016/021-8936353</td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>

                    <table style="margin-top: 22%; " >
                        <tr>
                            <td width=300>Dengan Hormat,</td>
                        </tr>
                        <tr >
                            <td>Dengan ini kami akan melakukan pemesanan untuk produk berikut ini:</td>
                        </tr>
                    </table>
                    <table class="tabel1" >

                        <tbody>
                            <tr>
                                <th class="th">No.</th>
                                <th class="th">Part No.Supp</th>
                                <th class="th">Nama Barang </th>
                                <th class="th">Delivery
                                    Date
                                    </th>
                                <th class="th">Jumlah</th>
                                <th class="th">Satuan</th>
                                <th class="th">Harga Satuan</th>
                                <th class="th">Total</th>
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
                            <td class="td">PCS</td>
                            <td class="td">{{ $priceIDR }}</td>
                            <td class="td">{{ number_format($jumlah,0,'.','.') }}</td>

                        </tr>

                        @endforeach
                        @php
                        $pajak =  intval($jumlahkeseluruhan) * $p ;
                        $total = $jumlahkeseluruhan + $pajak ;
                        @endphp
                        <tr>
                            <th class="th" colspan="4"><strong><strong></th>

                            <th class="th"  colspan="3"><strong>Total Tanpa Pajak: </strong></th>

                            <th class="th"  colspan="2" ><strong >{{ number_format($jumlahkeseluruhan,0,'.','.') }} <strong></th>
                        </tr>
                        </tbody>
                    </table>





            </div>

        </font>
    </main>
    <footer class="wrapper-page" style="margin-top: 2%;">
            <table class="column">
                <tr>
                    <td></td>
                    <td style="text-align: center;">Dipesan Oleh</td>
                    <td></td>
                </tr>

                <tr >
                    <td></td>
                    <td style="text-align: center; padding:70px; ">{{$datapo->PurchaseOrderCreator}}</td>
                    <td></td>
                </tr>


            </table>
            <table class="column">
                <tr>
                    <td></td>
                    <td style="text-align: center;">Diterima Oleh</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td style="text-align: center; padding:70px;">{{$datapo->Vendor}}</td>
                    <td></td>
                </tr>
              </table>

    </footer>
    <main>
        <font size="1" face="Sans-serif">
            <div style="margin-top: -15px;">
                  <table class="column">
                        <tr>
                            <td>PT. UNITED TRACTORS PANDU ENGINEERING</td>
                        </tr>
                        <tr>
                            <td>Jl. Jababeka XI Blok H 30-40 Kawasan Industri Jababeka,<br>
                                Cikarang 17530, Indonesia</td>
                        </tr>
                        <tr>
                            <td>Phone: +62 21 8935016 Fax : +62 21 8934772 / 6353</td>
                        </tr>
                    </table>
                    <table class="column">
                        <tr>
                            <td>PO No</td>
                            <td>:</td>
                            <td>{{ $datapo->Number }}</td>
                        </tr>
                        <tr>
                            <td>PO Revision</td>
                            <td>:</td>
                            <td>{{ $datapo->Number_old == null ? '-' : $datapo->Number_old }}</td>
                        </tr>

                        <tr>
                            <td>PO Date</td>
                            <td>:</td>
                            <td>{{ date('d.m.Y', strtotime($datapo->Date)) }}</td>
                        </tr>
                        <tr>
                            <td>Order Type</td>
                            <td>:</td>
                            <td>{{ $datapo->Type }}/UTPE PO Prod.{{ $vendor }}</td>
                        </tr>
                        <tr>
                            <td>Incoterm</td>
                            <td>:</td>
                            <td>FRC</td>
                        </tr>
                        <tr>
                            <td>Currency</td>
                            <td>:</td>
                            <td>{{ $datapo->Currency }}</td>
                        </tr>
                        <tr>
                            <td>Payment Terms</td>
                            <td>:</td>
                            <td>Within 30 days fr posting date</td>
                        </tr>
                    </table>


                    <main-section>
                        <table style="margin-left: -20px; margin-top: 22%;">

                            <tr>
                                <td width="190">
                                     </td>
                                <td style="font-size: 14px;">
                                    <center>
                                            <u> Pemesanan Pembelian</u>
                                    </center>
                                </td>

                                <td width="80">

                                </td>
                            </tr>
                        </table>

                    </main-section>
                </div>

            </font>
        </main>
        <div class="row" style="margin-top: 1%;">
            <table style=" float: left;
            width: 97%;
            padding: 5px;" >
              <tr>
                  <td >
                    Syarat dan Ketentuan:
                  </td>

              </tr>
              <tr>
                <td>
                    <ol>
                        <li>Purchase order ini ditandatangani secara legal oleh pihak yang berwenang dari PT United Tractors Pandu
                            Engineering.</li>
                        <li>Barang yang spesifikasinya tidak sesuai akan ditolak dan ditanggung oleh supplier.</li>
                        <li>PT United Tractors Pandu Engineering mempunyai hak untuk membatalkan atau memberikan denda atas
                            semua atau sebagian dari order yang tidak diserahkan pada waktu yang telah ditentukan.</li>
                        <li>Surat jalan harus disertakan dalam semua pengiriman.</li>
                        <li>Jika ada kejadian yang mengganggu usaha kami baik sebagian maupun seluruhnya, karena kebakaran, banjir,
                            gempa bumi, peperangan, pemogokan, embargo, tindakan-tindakan pemerintah, atau sebab-sebab yang
                            berada diluar kemampuan kami untuk menghindarinya, kami mempunyai opsi untuk membatalkan sebagian
                            atau seluruh barang yang belum kami serahkan.</li>
                        <li>Persetujuan atas pesanan pembelian ini atau pengiriman atas bagiannya akan mengacu dan disesuaikan
                            dengan semua spesifikasi, termasuk persyaratan, pengiriman dan harga.</li>
                        <li>Untuk persetujuan, supplier wajib menandatangani pesanan pembelian ini dan mengirimkan kembali kepada PT
                            United Tractors Pandu Engineering pada saat penagihan atau waktu lain yang ditentukan PT United Tractors
                            Pandu Engineering.
                            </li>
                      </ol>
                  </td>

              </tr>

             </table>
         </div>
</body>

</html>
