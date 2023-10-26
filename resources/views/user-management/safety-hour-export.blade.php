<table>
    <thead>
        <tr>
            <th>NRP</th>
            <th>Name</th>
            <th>Departement</th>
            <th>Position</th>
            <th>Hours</th>
            <th>Minutes</th>
            <th>PRS</th>
            <th>SWC</th>
            <th>SL</th>
            <th>SDC</th>
            <th>PRDOFF</th>
        </tr>
    </thead>
    <tbody>
        @if (count($data) > 0)
            @foreach ($data as $safety)
                <tr>
                    <td>{{ $safety['nrp'] }}</td>
                    <td>{{ $safety['name'] }}</td>
                    <td>{{ $safety['department'] }}</td>
                    <td>{{ $safety['position'] }}</td>
                    <td>{{ intval($safety['hours']) }}</td>
                    <td>{{ $safety['minutes'] }}</td>
                    <td>{{ $safety['PRS'] }}</td>
                    <td>{{ $safety['SWC'] }}</td>
                    <td>{{ $safety['SL'] }}</td>
                    <td>{{ $safety['SDC'] }}</td>
                    <td>{{ $safety['PRSOFF'] }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
