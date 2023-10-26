<html>
    <head>
    </head>
    <body>
<table border="1">
    <thead>
    <tr>
        <th style="background-color: #3adcfc;">NO</th>
        <th style="background-color: #3adcfc;">MACHINE NAME</th>
        <th style="background-color: #3adcfc;">RAISING DATE</th>
        <th style="background-color: #3adcfc;">EARLY IDENTIFICATION FROM USER</th>
        <th style="background-color: #3adcfc;">RAISER</th>
        <th style="background-color: #3adcfc;">PROCESS DATE</th>
        <th style="background-color: #3adcfc;">RESPONSE TIME</th>
        <th style="background-color: #3adcfc;">SLA</th>
        <th style="background-color: #3adcfc;">ANALYSIS ROOT CAUSE BREAKDOWN</th>
        <th style="background-color: #3adcfc;">CORRECTIVE ACTION</th>
        <th style="background-color: #3adcfc;">PREVENTIVE ACTION</th>
        <th style="background-color: #3adcfc;">CONSUMABLE USED</th>
        <th style="background-color: #3adcfc;">SERVICES AND COSTS</th>
        <th style="background-color: #3adcfc;">RESOLVING DATE</th>
        <th style="background-color: #3adcfc;">DURATION</th>
        <th style="background-color: #3adcfc;">CLOSING DATE</th>
        <th style="background-color: #3adcfc;">ASSISTER/TECHNICIAN</th>
        <th style="background-color: #3adcfc;">ASSIGNMENT NOTE</th>
        <th style="background-color: #3adcfc;">STATUS</th>
    </tr>
    </thead>
    <tbody>
    @php($no=0)
    @foreach($datas as $ticket)
    @php($datediff = (Helper::TimeInterval($ticket->respond_time,$ticket->resolve_time=='' ? Date('Y-m-d H:i:s') : $ticket->resolve_time)/60))
    @php($exp_date =date('Y-m-', strtotime('+4 month', strtotime($ticket->tanggalfaktur))).'25')
    @php($no=$no+1)
    @php($str = $ticket->description);
    @php($arr = explode(";",$str));
    @php($interval = (Helper::TimeInterval($ticket->respond_time,$ticket->resolve_time=='' ? Date('Y-m-d H:i:s') : $ticket->resolve_time)))
    @php($responsediff = (Helper::TimeInterval(date('Y-m-d H:i:s',strtotime($ticket->created_at)),$ticket->respond_time=='' ? Date('Y-m-d H:i:s') : $ticket->respond_time)/60))
    @php($responseinterval = (Helper::TimeInterval(date('Y-m-d H:i:s',strtotime($ticket->created_at)),$ticket->respond_time=='' ? Date('Y-m-d H:i:s') : $ticket->respond_time)))
        <tr>
            <td>{{ $no }}</td>
            <td>{{ $ticket->subject}}</td>
            <td>{{ date('Y-m-d H:i:s',strtotime($ticket->created_at)) }}</td>
            <td>{{ $ticket->message }}</td>
            <td>{{ $ticket->reporter_name }}</td>
            <td>{{ isset($ticket->respond_time) ? $ticket->respond_time : '-' }}</td>
            <td>{{ isset($ticket->respond_time) ? floor($responsediff/60).':'.gmdate('i:s', $responseinterval) : '-' }}</td>
            <td>{{ $ticket->sla_name }}</td>
            <!-- <td>{{ isset($arr[0]) ? $arr[0] : $ticket->description}}</td>
            <td>{{ isset($arr[1]) ? $arr[1] : $ticket->description}}</td>
            <td>{{ isset($arr[2]) ? $arr[2] : $ticket->description}}</td>
            <td>{{ isset($arr[3]) ? $arr[3] : $ticket->description}}</td>
            <td>{{ isset($arr[4]) ? $arr[4] : $ticket->description}}</td> -->
            <td>{{ $ticket->analisis }}</td>
            <td>{{ $ticket->corrective }}</td>
            <td>{{ $ticket->preventive }}</td>
            <td>{{ $ticket->consumable }}</td>
            <td>{{ $ticket->costs }}</td>
            <td>{{ $ticket->resolve_time }}</td>
            <td>{{ isset($ticket->resolve_time) ? floor($datediff/60).':'.gmdate('i:s', $interval) : '-' }}</td>
            <td>{{ isset($ticket->closed_at) ? date('Y-m-d H:i:s',strtotime($ticket->closed_at)) : '-' }}</td>
            <td>{{ $ticket->assist_name }}</td>
            <td>{{ $ticket->assign_description }}</td>
            <td>{{ $ticket->flow_name }}</td>
            
        </tr>
    @endforeach
    </tbody>
</table>
    </body>
</html>