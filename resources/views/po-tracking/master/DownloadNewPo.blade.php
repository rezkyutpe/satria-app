<html>
    <head>
    </head>
    <body>

        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Number</th>
                    <th>ItemNumber</th>
                    <th>Material</th>
                    <th>Description</th>
                    <th>QtySAP</th>
                    <th>QtyAgreed</th>
                    @if ($status == 1)
                    <th>NetPrice</th>
                    <th>TotalAmount</th>
                    @endif
                    <th>DeliveryDateSAP</th>
                    <th>DeliveryDateAgreed</th>
                    <th>PODate</th>
                    <th>POReleaseDate</th>
                    @if ($category == 2)
                    <th>Vendor</th>
                    <th>VendorCode</th>
                    @endif
                    <th>PO Creator</th>
                    <th>Progress</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1 ;
                @endphp
                    @foreach($dB as $item)
                    <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->Number }}</td>
                            <td>{{ $item->ItemNumber }}</td>
                            <td>{{ $item->Material }}</td>
                            <td>{{ $item->Description }}</td>
                            <td>{{ $item->Quantity }}</td>
                            <td>{{ $item->ConfirmedQuantity }}</td>
                            @if ($status == 1)
                                @if ($item->ConfirmedQuantity == null)
                                    <td>{{ $item->NetPrice }}</td>
                                    <td>{{ $item->Quantity * $item->NetPrice }}</td>
                                @else
                                    <td>{{ $item->NetPrice }}</td>
                                    <td>{{ $item->ConfirmedQuantity *  $item->NetPrice  }}</td>
                                @endif
                            @endif
                            <td>{{ $item->DeliveryDate }}</td>
                            <td>{{ $item->ConfirmedDate }}</td>
                            <td>{{ $item->PODate }}</td>
                            <td>{{ $item->POReleaseDate }}</td>
                            @if ($category == 2)
                            <td>{{ $item->Vendor }}</td>
                            <td>{{ $item->VendorCode }}</td>
                            @endif
                            <td>{{ $item->PurchaseOrderCreator}}</td>
                        @if ($item->ActiveStage == 1 )
                            <td>Negotiated</td>
                        @else
                            <td>ConfirmPO</td>
                        @endif
                    @endforeach
                    </tr>


            </tbody>
        </table>
    </body>
</html>
