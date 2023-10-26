<html>
    <head>
    </head>
    <body>
<table border="1">
    <thead>
    <tr>
        <th style="background-color: #3adcfc;">NO</th>
        <th style="background-color: #3adcfc;">PR NUMBER</th>
        <th style="background-color: #3adcfc;">PR DATE</th>
        <th style="background-color: #3adcfc;">PR NAME</th>
        <th style="background-color: #3adcfc;">PR DEPT</th>
        <th style="background-color: #3adcfc;">PR QTY</th>
        <th style="background-color: #3adcfc;">Message</th>
        <th style="background-color: #3adcfc;">Allocation For</th>
        <th style="background-color: #3adcfc;">Accept By</th>
        <th style="background-color: #3adcfc;">Approve By</th>
        <th style="background-color: #3adcfc;">Received By</th>
        <th style="background-color: #3adcfc;">Status</th>
    </tr>
    </thead>
    <tbody>
    @php($no=0)
    @foreach($datas as $pr)
        <tr>
            <td>{{ $no }}</td>
            <td>{{ $pr->pr_number}}</td>
            <td>{{ date('Y-m-d H:i:s',strtotime($pr->created_at)) }}</td>
            <td>{{ $pr->pr_name}}</td>
            <td>{{ $pr->dept_name}}</td>
            <td>{{ $pr->pr_quantity}}</td>
            <td>{{ $pr->pr_description}}</td>
            <td>{{ $pr->pr_to}}</td>
            <td>{{ $pr->accept_to_name}}</td>
            <td>{{ $pr->approve_to_name}}</td>
            <td>{{ $pr->pr_received }}</td>
            <td>@if($pr->status=='0')
                 {{ 'New'}}
                @elseif($pr->status=='1')
                 {{ 'Partial Approved'}}
                @elseif($pr->status=='2')
                 {{ 'Fully Approved'}}
                @elseif($pr->status=='3')
                 {{ 'Delivered'}}
                @else
                 {{ '-' }}
                @endif </td>
        </tr>
    @endforeach
    </tbody>
</table>
    </body>
</html>