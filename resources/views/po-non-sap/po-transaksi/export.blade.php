<table>
    <tr>
        <td>Memo Internal</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>To</td>
        <td>: PT Alfagomma Indonesia</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Nama Product</td>
        <td>: {{ $po->product }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Part Number </td>
        <td>: {{ $po->pn }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>No PRO </td>
        <td>: {{ $po->pro }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Unit</td>
        <td>: {{ $po->qty }} unit</td>
        
    </tr>
    <thead>
    <tr>
        <th>No</th>
        <th>PN Patria</th>
        <th>Description</th>
        <th>PN Vendor</th>
        <th>Qty Order</th>
        <th>Qty Supply</th>
        <th>Qty Use</th>
                            <th>Oum</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
     @php ($no=0)
                    @foreach($komponen as $key)
                    @php ($no=$no+1)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $key->pn_patria}}</td>
                        <td>{{ $key->description}}</td>
                        <td>{{ $key->pn_vendor}}</td>
                        <td>{{ $key->qty_order}}</td>
                        <td>{{ $key->qty_supply}}</td>
                        <td>{{ $key->qty_use}}</td>
                        <td>{{ $key->uom}}</td>
                        <td>{{ $key->ket}}</td>
                    </tr>
    @endforeach
    </tbody>
</table>