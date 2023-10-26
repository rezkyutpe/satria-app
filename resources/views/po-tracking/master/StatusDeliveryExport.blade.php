<html>
    <head>
    </head>
    <body>

        <table border="1">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    @if ($status == 1)
                    <th>Report Ranking Delivery terbaik</th>
                    @else
                    <th>Report Ranking Delivery terburuk</th>
                    @endif
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>

                </tr>
                <tr>
                    <th>No</th>
                    <th>Vendor Name</th>
                    <th>Vendor Type</th>
                    <th>PO Item</th>
                    <th>Late</th>
                    <th>Ontime</th>
                    <th>Early</th>
                    <th>Performance(%)</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1 ;
                @endphp
                    @foreach($dB as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->VendorName }}</td>
                        <td>{{ $item->VendorType }}</td>
                        <td>{{ $item->totalStatusDelivery }}</td>
                        <td>{{ $item->countLate }}</td>
                        <td>{{ $item->countOntime }}</td>
                        <td>{{ $item->countEarly }}</td>
                        <td>{{ number_format($item->performance , 2, '.', '.') }}% </td>
                        <td></td>
                    @endforeach
                    </tr>


            </tbody>
        </table>
    </body>
</html>
