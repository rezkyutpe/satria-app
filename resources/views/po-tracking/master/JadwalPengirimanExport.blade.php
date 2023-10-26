<html>
    <head>
    </head>
    <body>

        <table border="1">
            <thead>
                <tr >
                    <th>No</th>
                    <th>Vendor Name</th>
                    <th>PO Number</th>
                    <th>PO Item</th>
                    <th>Material</th>
                    <th>Description</th>
                    <th>PO Qty</th>
                    <th>ID Ticket</th>
                    <th>Ticket Qty</th>
                    <th>Delivery Date Agreed</th>
                    <th>Ticket Delivery Date</th>
                    <th>AcceptDate</th>
                    <th>SecurityDate</th>
                    <th>WarehouseDate</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1 ;
                @endphp
                   @foreach ($dB as  $item)
                   <tr class="baseBlock">
                       @php
                       $PoDate         = $item->Date ;
                       $DeliveryDateSAP  = $item->DeliveryDates;
                       $confirmedDate  = $item->ConfirmDeliveryDate;
                       $deliveryDate   = $item->DeliveryDate ;
                       $releaseDate    = $item->ReleaseDate ;
                       $acceptedate = $item->AcceptedDate ;
                       $securitydate = $item->SecurityDate ;
                       $warehousedate = $item->WarehouseDate ;
                       if ($item->SecurityDate == null) {
                           $security = "" ;
                       }
                       else {
                               $securitys =  new DateTime($securitydate) ;
                               $security = $securitys->format('d/m/Y H:i:s');
                       }
                       if ($item->WarehouseDate == null) {
                           $warehouse ="";
                       }
                       else {
                               $warehouses =  new DateTime($warehousedate) ;
                               $warehouse = $warehouses->format('d/m/Y H:i:s');
                       }
                       if ($item->Number== null) {
                               $number = $item->Numbers;
                       }else{
                               $number =$item->Number;
                       }
                       if ($item->ItemNumber== null) {
                               $itemnumber = $item->ItemNumbers;
                       }else{
                               $itemnumber = $item->ItemNumber ;
                       }
                       if ($item->POQuantity== 0) {
                               $qtysap = $item->QtySAP;
                       }else{
                               $qtysap = $item->POQuantity ;
                       }
                       if ($item->Material== null) {
                               $material = $item->Materials;
                       }else{
                               $material = $item->Material ;
                       }
                       if ($item->Description== null) {
                               $description = $item->Descriptions;
                       }else{
                               $description = $item->Description ;
                       }

                       $release        = new DateTime($releaseDate);
                       $confirmed      = new DateTime($confirmedDate);
                       $deliverydates      = new DateTime($DeliveryDateSAP);
                       $date           = new DateTime($PoDate);
                       $dates          = new DateTime($deliveryDate);

                       $accept          = new DateTime($acceptedate);
                       if ($item->DeliveryDates == null) {
                           $confirmeddates = $confirmed->format('d/m/Y') ;
                       }else{
                           $confirmeddates = $deliverydates->format('d/m/Y') ;
                       }
                       if ($item->ReleaseDate== null) {
                           $releases = "-";
                       }else{
                           $releases = $release->format('d/m/Y') ;
                       }

                   @endphp
                         <td>{{ $no++ }}</td>
                       <td>{{ $item->Name }}</td>
                       <td>
                          {{ $number }}
                       </td>
                       <td>{{ $itemnumber }}</td>
                       <td >{{ $material }}</td>
                       <td>{{ $description }}</td>
                       <td>{{ $item->POQuantity }}</td>
                       <td>{{ $item->TicketID }}</td>
                       <td>{{ $item->Quantity }}</td>
                       <td >{{ $confirmeddates }}</td>
                       <td >{{ $dates->format('d/m/Y H:i:s') }}</td>
                       <td >{{ $accept->format('d/m/Y H:i:s') }}</td>
                       <td >{{  $security }}</td>
                       <td >{{ $warehouse}}</td>
                        @if ($item->status == 'A')
                            <td>Approve Warehouse</td>
                        @elseif ($item->status == 'D')
                            <td>On Delivery</td>
                        @elseif ($item->status == 'S')
                            <td>In Security</td>
                        @elseif ($item->status == 'W')
                            <td>At Warehouse</td>
                        @elseif ($item->status == 'R')
                            <td>On Progress Parking</td>
                        @elseif ($item->status == 'X')
                            <td>Ticket Close</td>
                        @endif
                   </tr>
                   @endforeach


            </tbody>
        </table>
    </body>
</html>
