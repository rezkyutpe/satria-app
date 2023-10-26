<html>

<head>
    <style>
        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            margin-top: 250px;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 4cm;
            font: 14px sans-serif;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: -250px;
            left: -50px;
            right: -50px;
            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            /* color: white; */
            /* text-align: center; */
            /* line-height: 1.5cm; */
        }

        /** Custom CSS **/
        .pagenum:before {
            content: counter(page);
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .column {
            float: left;
        }

        .column-right {
            float: right;
        }

        #kotakmerah {
            height: 70px;
            width: 20px;
            background-color: #ef3a38;
        }

        #garis-merah {
            height: 8px;
            width: 20px;
            background-color: #ef3a38;
            margin-top: 22px;
        }

        .content {
            margin-top: 80px;
        }

        .tabel1 {
            font-size: 13px;
            text-align: left;
        }

        .th {
            text-align: left;
            padding: 5px;
        }

        .td {
            padding: 5px;
        }
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>
        <div class="row">
            <div class="column">
                <div id="kotakmerah"></div>
            </div>
            <div class="column" style="margin-left:20px;margin-top:15px;">
                <div id="logo">
                    <img src="{{ public_path('assetss/images/patria.png') }}" alt="Logo" height="40">
                </div>
            </div>
            <div class="column-right" style="margin-top:-22px">
                <div id="garis-merah"></div>
                <div id="garis-merah"></div>
                <div id="garis-merah"></div>
            </div>
        </div>
        <div class="row" style="text-align:center;padding-top:40px">
            <div class="column" style="margin: auto;width: 45%; text-align:left; padding-left:40px;">
                <table>
                    <tr>
                        <td>PT. UNITED TRACTORS PANDU ENGINEERING</td>
                    </tr>
                    <tr>
                        <td>Jl. Jababeka XI Blok H 30-40 Kawasan Industri Jababeka, Cikarang 17530, Indonesia</td>
                    </tr>
                    <tr>
                        <td>Phone: +62 21 8935016 Fax : +62 21 8934772 / 6353</td>
                    </tr>
                </table>
            </div>
            <div class="column"
                style="margin: auto;width: 45%; text-align:left; padding-left:40px;page-break-inside: avoid;">
                <table>
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
                        @php
                            if ($datapo->VendorType == 'Vendor Local') {
                                $vendor = 'Local';
                            } elseif ($datapo->VendorType == 'Vendor SubCont') {
                                $vendor = 'Subcont';
                            } elseif ($datapo->VendorType == 'Vendor Import') {
                                $vendor = 'Import';
                            }
                        @endphp
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
                        <td>
                            @if ($datapo->TermPayment != null)
                                {{ $datapo->TermPayment }}
                            @else
                                Within 30 days fr posting date
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="column-right" style="margin-right:30px;margin-top:-28px;font-size: 10px;">
                Page <span class="pagenum"></span>
            </div>
        </div>
        <div class="row" style="text-align:center; font:18px ;padding:10px;">
            <div>
                <u>Pemesanan Pembelian</u>
            </div>
        </div>
    </header>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <div class="content">
            <div class="row" style="text-align:center;margin:-80px;padding-top:5px;">
                <div class="column" style="margin: auto;width: 45%; text-align:left;">
                    <table>
                        <tr>
                            <td>Kepada :</td>
                        </tr>
                        <tr>
                            <td>{{ $datapo->VendorCode }}</td>
                        </tr>
                        <tr>
                            <td>{{ $datapo->Vendor }}</td>
                        </tr>
                        <tr>
                            <td>{{ $datapo->Address }}</td>
                        </tr>
                        <tr>
                            <td>Telp & Fax : {{ $datapo->PhoneNo }}</td>
                        </tr>
                    </table>
                </div>
                <div class="column" style="margin: auto;width: 45%; text-align:left;padding-left:80px">
                    <table>
                        <tr>
                            <td>Dikirim ke :</td>
                        </tr>
                        <tr>
                            <td>Unit Cikarang</td>
                        </tr>
                        <tr>
                            <td>
                                Jl. Jababeka XI Blok H 30-40 Kawasan Industri Jababeka 17530
                            </td>
                        </tr>
                        <tr>
                            <td>Telp & Tax :</td>
                        </tr>
                        <tr>
                            <td>021-8935016/021-8936353</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row" style="text-align:center;margin:-80px; margin-top:90px;">
                <table>
                    <tr>
                        <td>
                            Dengan Hormat,
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Dengan ini kami akan melakukan pemesanan untuk produk berikut ini :
                        </td>
                    </tr>
                </table>
                <table class="tabel1" style="width:100%;border-collapse: collapse;">
                    <tr style="border-top: 1px solid;border-bottom: 1px solid;">
                        <th class="th">No.</th>
                        <th class="th">Part No.Supp</th>
                        <th class="th">Nama Barang </th>
                        <th class="th">Delivery Date</th>
                        <th class="th">Jumlah</th>
                        <th class="th">Satuan</th>
                        <th class="th">Harga Satuan</th>
                        <th class="th">Total</th>
                    </tr>
                    @foreach ($data as $key)
                        @php
                            $no = 1;
                            $jumlahkeseluruhan = 0;
                            $jumlahqty = 0;

                            $priceIDR = number_format(substr($key->NetPrice, 0), 0, ',', '.');
                            $qty = $key->Quantity;
                            $jumlah = intval($key->NetPrice) * $qty;
                            $jumlahqty += $key->Quantity;
                            if ($key->DeliveryDate < '2021-04-01') {
                                $p = 10 / 100;
                            } else {
                                $p = 11 / 100;
                            }
                            $delivery = date('d/m/Y', strtotime($key->DeliveryDate));
                        @endphp
                        <tr>
                            <td class="td">{{ $key->ItemNumber }}</td>
                            <td class="td">{{ $key->Material }}</td>
                            <td class="td">{{ $key->Description }}</td>
                            <td class="td">{{ $delivery }}</td>
                            <td class="td">{{ $key->Quantity }}</td>
                            <td class="td">PCS</td>
                            <td class="td" style="white-space: nowrap;text-align:right;">{{ $priceIDR }}</td>
                            <td class="td" style="white-space: nowrap;text-align:right;">
                                {{ number_format($jumlah, 0, '.', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr style="border-top: 1px solid;border-bottom: 1px solid;">
                        <td class="td" colspan="5"></td>
                        <td class="td" colspan="2"><strong>Total Tanpa Pajak: </strong></td>
                        <td class="td" colspan="1" style="white-space: nowrap;text-align:right;">
                            <strong>
                                @php
                                    foreach ($data as $key) {
                                        $qty = $key->Quantity;
                                        $jumlah = floatval($key->NetPrice) * $qty;
                                        $jumlahkeseluruhan += floatval($jumlah);
                                    }
                                    $total_sum = number_format($jumlahkeseluruhan, 0, '.', '.');
                                @endphp
                                {{ $total_sum }}
                            </strong>
                        </td>
                    </tr>
                </table>
                <div class="row" style="margin-top:30px;page-break-inside: avoid;">
                    <div class="column">
                        <table style="margin-left:10px">
                            <tr>
                                <td></td>
                                <td style="text-align: center;">Dipesan Oleh</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align: center; padding-top:70px;">{{ $datapo->PurchaseOrderCreator }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">(
                                    {{ $datapo->nrp_code == null ? '-' : $datapo->nrp_code }} )</td>
                            </tr>
                        </table>
                    </div>
                    <div class="column-right">
                        <table style="margin-right:10px">
                            <tr>
                                <td></td>
                                <td style="text-align: center;">Disetujui Oleh,</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                @if ($datapo->ReleaseDate == null)
                                    <td style="text-align: center; padding-top:70px;">
                                        {{ $datapo->Vendor == null ? '-' : $datapo->Vendor }}
                                    </td>
                                @else
                                    @if ($datapo->ApproveName == null)
                                        <td style="text-align: center; padding-top:70px;">
                                            {{ $datapo->Vendor == null ? '-' : $datapo->Vendor }}
                                        </td>
                                    @else
                                        <td style="text-align: center; padding-top:70px;">
                                            {{ $datapo->ApproveName == null ? '-' : $datapo->ApproveName }}
                                        </td>
                                    @endif

                                @endif

                                <td></td>
                            </tr>
                            <tr>
                                @if ($datapo->ReleaseDate == null)
                                    <td colspan="3" style="text-align: center;">(
                                        {{ $datapo->VendorCode == null ? '-' : $datapo->VendorCode }} )
                                    </td>
                                @else
                                    @if ($datapo->ApproveName == null)
                                        <td colspan="3" style="text-align: center;">
                                            ({{ $datapo->VendorCode == null ? '-' : $datapo->VendorCode }} )
                                        </td>
                                    @else
                                        <td colspan="3" style="text-align: center;">
                                            ( {{ $datapo->ApproveBy == null ? '-' : $datapo->ApproveBy }} )
                                        </td>
                                    @endif

                                @endif
                            </tr>
                            <tr>

                                <td colspan="3" style="text-align: center;">
                                    (Disetujui Secara Elektronik)
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="content" style="page-break-before: always;margin:-80px; margin-top:10px;">
            <table style="width: 97%; padding: 5px;">
                <tr>
                    <td>
                        Syarat dan Ketentuan:
                    </td>
                </tr>
                <tr>
                    <td>
                        <ol>
                            <li>Purchase order ini ditandatangani secara legal oleh pihak yang berwenang dari PT United
                                Tractors Pandu
                                Engineering.</li>
                            <li>Barang yang spesifikasinya tidak sesuai akan ditolak dan ditanggung oleh supplier.</li>
                            <li>PT United Tractors Pandu Engineering mempunyai hak untuk membatalkan atau memberikan
                                denda atas
                                semua atau sebagian dari order yang tidak diserahkan pada waktu yang telah ditentukan.
                            </li>
                            <li>Surat jalan harus disertakan dalam semua pengiriman.</li>
                            <li>Jika ada kejadian yang mengganggu usaha kami baik sebagian maupun seluruhnya, karena
                                kebakaran, banjir,
                                gempa bumi, peperangan, pemogokan, embargo, tindakan-tindakan pemerintah, atau
                                sebab-sebab yang
                                berada diluar kemampuan kami untuk menghindarinya, kami mempunyai opsi untuk membatalkan
                                sebagian
                                atau seluruh barang yang belum kami serahkan.</li>
                            <li>Persetujuan atas pesanan pembelian ini atau pengiriman atas bagiannya akan mengacu dan
                                disesuaikan
                                dengan semua spesifikasi, termasuk persyaratan, pengiriman dan harga.</li>
                            <li>Untuk persetujuan, supplier wajib menandatangani pesanan pembelian ini dan mengirimkan
                                kembali kepada PT
                                United Tractors Pandu Engineering pada saat penagihan atau waktu lain yang ditentukan PT
                                United Tractors
                                Pandu Engineering.
                            </li>
                        </ol>
                    </td>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>
