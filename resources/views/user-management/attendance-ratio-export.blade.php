<table>
    <thead>
        <tr>
            <th>EMP ID</th>
            <th>NRP</th>
            <th>Name</th>
            <th>Departement</th>
            <th>Position</th>
            <th>PRS</th>
        </tr>
    </thead>
    <tbody>
        @if (count($data) > 0)
            @foreach ($data as $safety)
                <tr>
                    <td>{{ $safety['emp_id'] }}</td>
                    <td>{{ $safety['nrp'] }}</td>
                    <td>{{ $safety['name'] }}</td>
                    <td>{{ $safety['department'] }}</td>
                    <td>{{ $safety['position'] }}</td>
                    <td>{{ $safety['PRS'] }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
