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
                    <th>OpenQuantity</th>
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
                            <td>{{ $item->OpenQuantity }}</td>
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
                            @php
                            $activeStage    = $item->ActiveStage;
                            $total    = $item->totalticket;
                            $qty    = $item->ActualQuantity;
                            $openqty    = $item->OpenQuantity;
                            @endphp
                            @if ($menu == "ongoinglocal")

                                    @if ( $activeStage == null && $total == null && $item->totalgr == $qty && $openqty == 0 )
                                        <td>TicketFull</td>
                                    @elseif ( $activeStage == null && $total == null && $openqty == $qty)
                                            <td>Create Ticket</td>
                                    @elseif ($activeStage == null && $total == null && $qty > $openqty)
                                            <td>Ticket ON</td>
                                    @elseif ($activeStage == null && $total == $openqty)
                                            <td>Ticket Full</td>
                                    @elseif ($activeStage == null  && $total < $openqty)
                                            <td>Proforma Payment</td>
                                    @elseif ($activeStage == '2a')
                                            <td>Proforma Payment</td>
                                    @elseif ($activeStage == 2)
                                            <td>proformaInvoice</td>
                                    @elseif ($activeStage == 3  )
                                            <td>Confirm Proforma Payment</td>
                                    @elseif ($activeStage == 4 && $total == null && $qty == $item->totalgr )
                                            <td>Ticket Full</td>
                                    @elseif ( $activeStage == 4 && $total == null && $qty == $openqty )
                                            <td>Create Ticket</td>
                                    @elseif ($activeStage == 4 && $total == null && $qty > $item->openqty )
                                            <td>Ticket On</td>
                                    @elseif ($activeStage == 4 &&  $total < $qty )
                                            <td>Ticket On</td>
                                    @elseif ($activeStage == 4 && $total == $openqty )
                                            <td>TicketFull</td>
                                    @elseif ( $item->totalgr == $qty )
                                            <td>Ticket Full</td>
                                    @elseif ($openqty == 0)
                                            <td>Ticket Full</td>
                                    @endif

                              @else
                                        @if ($activeStage == '2')
                                             <td>Proforma Invoice</td>
                                        @elseif ($activeStage == '2a')
                                             <td>Proforma Payment</td>
                                        @elseif ($activeStage == '2b')
                                             <td>Confirm Payment</td>
                                        @elseif (($activeStage == ''|| $activeStage == 3 || $activeStage == '3a' || $activeStage == '3b' || $activeStage == '3c'))
                                             <td>Sequence Progress</td>
                                        @elseif ($activeStage == 4 && $total == '')
                                             <td>Create Ticket</td>
                                        @elseif ($activeStage == 4 && $total < $qty )
                                                <td>Ticket On</td>
                                        @elseif ($activeStage == 4 && $total == $qty )
                                             <td>Ticket Full</td>
                                        @elseif ($openqty == 0)
                                             <td>Ticket Full</td>
                                        @endif

                                @endif
                    @endforeach
                    </tr>


            </tbody>
        </table>
    </body>
</html>
