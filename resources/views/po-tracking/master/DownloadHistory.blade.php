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
                    <th>Quantity GR</th>
                    <th>Total GR</th>
                    @if ($status == 1)
                    <th>NetPrice</th>
                    <th>TotalAmount</th>
                    @endif
                    <th>DeliveryDateSAP</th>
                    <th>DeliveryDateAgreed</th>
                    <th>GR Date</th>
                    <th>PODate</th>
                    <th>POReleaseDate</th>
                    <th>DocumentNumber</th>
                    <th>DocumentNumberItem</th>
                    <th>PO Category</th>
                    <th>Movement Type</th>
                    @if ($category == 2)
                    <th>Vendor</th>
                    <th>VendorCode</th>
                    @endif
                    <th>PO Creator</th>


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
                            <td>{{ $item->GoodsReceiptQuantity }}</td>
                            <td>{{ $item->totalgr }}</td>
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
                            <td>{{ $item->GoodsReceiptDate }}</td>
                            <td>{{ $item->PODate }}</td>
                            <td>{{ $item->POReleaseDate }}</td>
                            <td>{{ $item->DocumentNumber }}</td>
                            <td>{{ $item->DocumentNumberItem }}</td>
                            <td>{{ $item->POCategory }}</td>
                            <td>{{ $item->MovementType }}</td>
                            @if ($category == 2)
                                <td>{{ $item->Vendor }}</td>
                                <td>{{ $item->VendorCode }}</td>
                            @endif
                            <td>{{ $item->PurchaseOrderCreator}}</td>

                    @endforeach
                    </tr>


            </tbody>
        </table>
    </body>
</html>
