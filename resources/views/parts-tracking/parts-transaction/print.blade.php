<!DOCTYPE html>
<html>
<head>
  <title>Cetak Barcode</title>
  <style>
    .example-print {
        display: none;
    }
    @media print {
    @page { size:  auto; margin-top: 0px; }
    .example-screen {
        display: none;
    }
    .example-print {
        margin-top: -20px;
        display: block;
        position:absolute;
        left:50%;
        transform: rotate(90deg);
        transform-origin: 0;
        }

    }
  </style>
  
</head>

<body style="margin-top: 0px; align: center" onload="myFunction()">

  <div class="example-print">
  <table border="0" >
    <tr>
      <td>{!! QrCode::size(50)->generate($data['parts']['id_transaksi']); !!}</td>
        <td><img src="{{ asset('public/assets/global/img/patria_print.png') }}" style="width: 100px; margin-left:5px; margin-right:5px;"></td>
    </tr>
    <tr>
      <td style="font-size: 9px;color: #000;font-weight: bold;" colspan="2">  {{ $data['parts']['id_transaksi'] }}</td>
    </tr>
  </table>

</body>
<script>
function myFunction() {
    window.print();
}
</script>
</html>
