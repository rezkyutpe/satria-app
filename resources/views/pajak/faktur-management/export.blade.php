<html>
    <head>
    </head>
    <body>
<table border="1">
    <thead>
    <tr>
        <th style="background-color: #3adcfc;">FM</th>
        <th style="background-color: #3adcfc;">KD_JENIS_TRANSAKSI</th>
        <th style="background-color: #3adcfc;">FG_PENGGANTI</th>
        <th style="background-color: #3adcfc;">NOMOR_FAKUR</th>
        <th style="background-color: #3adcfc;">MASA_PAJAK</th>
        <th style="background-color: #3adcfc;">TAHUN_PAJAK</th>
        <th style="background-color: #3adcfc;">TANGGAL_FAKTUR</th>
        <th style="background-color: #3adcfc;">NPWP</th>
        <th style="background-color: #3adcfc;">NAMA</th>
        <th style="background-color: #3adcfc;">ALAMAT_LENGKAP</th>
        <th style="background-color: #3adcfc;">JUMLAH_DPP</th>
        <th style="background-color: #3adcfc;">JUMLAH_PPN</th>
        <th style="background-color: #3adcfc;">JUMLAH_PPNBM</th>
        <th style="background-color: #3adcfc;">IS_CREDITABLE</th>
        <th style="background-color: #3adcfc;">IS_EXPIRED</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $pajak)
    @php($exp_date =date('Y-m-', strtotime('+4 month', strtotime($pajak->tanggalfaktur))).'25')
        <tr>
            <td>FM</td>
            <td>{{ $pajak->kdjenistransaksi}}</td>
            <td>{{ $pajak->fgpengganti }}</td>
            <td>{{ $pajak->nomorfaktur }}</td>
            <td>
                <!-- {{ date('m',strtotime($pajak->tanggalfaktur)) }} -->
                @if($exp_date>=date('Y-m-', strtotime($pajak->date_scan)))
                    @if(date('m-Y',strtotime($pajak->date_scan))==date('m-Y',strtotime($pajak->tanggalfaktur)))
                        {{ date('m',strtotime($pajak->date_scan)) }}
                    @elseif(date('m-Y',strtotime($pajak->date_scan))>=date('m-Y',strtotime($pajak->tanggalfaktur)))
                        {{ date('m', strtotime('-1 month', strtotime($pajak->date_scan))) }}
                    @else
                    @endif
                @else
                    {{ date('m',strtotime($pajak->tanggalfaktur)) }}
                @endif
            </td>
            <td>{{ date('Y',strtotime($pajak->tanggalfaktur)) }}</td>
            <td>{{ date('d/m/Y',strtotime($pajak->tanggalfaktur)) }}</td>
            <td>{{ $pajak->npwppenjual }}</td>
            <td>{{ $pajak->namapenjual }}</td>
            <td>{{ $pajak->alamatpenjual }}</td>
            <td>{{ $pajak->jumlahdpp }}</td>
            <td>{{ $pajak->jumlahppn }}</td>
            <td>{{ $pajak->jumlahppnbm }}</td>
            <td>{{ $pajak->is_creditable }}</td>
            <td>@if($exp_date>=date('Y-m-', strtotime($pajak->date_scan)))
                {{ 0 }}
                @else
                {{ 1}}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
    </body>
</html>