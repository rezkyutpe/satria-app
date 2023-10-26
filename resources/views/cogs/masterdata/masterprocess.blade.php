@extends('cogs.panel.master')
@section('content')


<div class="clearfix"></div>
<div class="row">

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Master Process</h2>
                @if($actionmenu->u==1)
                <a class="btn btn-refresh btn-success btn-sm pull-right"
                    href="{{ url('cogs-import-masterprocess') }}">Update Database <i class="fa fa-refresh">
                    </i>
                </a>
                @endif
                <div class="clearfix"></div>
            </div>
            @if(session()->has('err_message'))
            <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('err_message') }}
            </div>
            @endif
            @if(session()->has('suc_message'))
            <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('suc_message') }}
            </div>
            @endif
            <div class="x_content">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box ">

                            <table id="datatable-visibility-masterprocess"
                                class="table text-center table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Total Day</th>
                                        <th>Product Name</th>
                                        <th>Process Order</th>
                                        <th>Process Name</th>
                                        <th>Process Group</th>
                                        <th>Man Hour</th>
                                        <th>Man Power</th>
                                        <th>Cycle Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data))
                                    @foreach ($data as $i => $item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td class="text-left"><b>{{ $item->Name }}</b></td>
                                        <td>{{ $item->TotalDay }}</td>
                                        <td>{{ $item->ProductName }}</td>
                                        <td>{{ $item->ProcessOrder }}</td>
                                        <td>{{ $item->ProcessName }}</td>
                                        <td>{{ $item->ProcessGroup }}</td>
                                        <td>{{ $item->ManHour }}</td>
                                        <td>{{ $item->ManPower }}</td>
                                        <td>{{ $item->CycleTime }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="my-2 data-time-update">
                            <div class="mt-2">
                                <a><i class="fa fa-clock-o"></i></a>
                                <span class="d-none data_update_time">{{ $updated_at }}</span>
                                <span class="text-dark update_time"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('myscript')
<script>
$('#datatable-visibility-masterprocess').DataTable({
    stateSave: true,
    dom: 'Bfrtip',
    buttons: [
        'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL'
        }], 'print'
    ],
    columnDefs: [{
            targets: 7,
            type: 'num',
            render: $.fn.dataTable.render.number('.', ',', 1, '')
        },
        {
            targets: 9,
            type: 'num',
            render: $.fn.dataTable.render.number('.', ',', 1, '')
        }
    ],
    "fnInitComplete": () => {
        var styleAttributeThead = $('table').find('thead').find(
            'th');
        styleAttributeThead.attr({
            'style': 'background-color: rgba(0, 0, 0, 0.3);',
        });
    },
});
</script>
@endsection
