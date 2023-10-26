@extends('cogs.panel.master')
@section('mycss')
<style>
::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 7px;
}

::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(0, 0, 0, .25);
    -webkit-box-shadow: 0 0 1px rgba(255, 255, 255, .25);
    box-shadow: 0 0 1px rgba(255, 255, 255, .25);
}

</style>
@endsection

@section('content')
<div class="clearfix"></div>
<div class="row">

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Populasi</h2>
                @if($actionmenu->u==1)
                <a class="btn btn-refresh btn-success btn-sm pull-right" href="{{ url('tsm-import-sn') }}">Update
                    Serial Number <i class="fa fa-refresh">
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
                <div class="row content-table">
                    <div class="col-sm-12 ">
                        <div class="card-box">
                            <table id="datatable-visibility-sn"
                                class="table text-center table-striped table-bordered nowrap" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SN</th>
                                        <th>MAT_DESC</th>
                                        <th>MAT_NUM</th>
                                        <th>CUST_NAME</th>
                                        <th>DELIV_DATE</th>
                                        <th>created_at</th>
                                        <th>updated_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data))
                                    @foreach ($data as $i => $item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td class="text-left"><b>{{ $item->SN }}</b></td>
                                        <td>{{ $item->MAT_DESC }}</td>
                                        <td>{{ $item->MAT_NUM }}</td>
                                        <td>{{ $item->CUST_NAME }}</td>
                                        <td>{{ $item->DELIV_DATE }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->updated_at }}</td>
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
$(document).ready(function() {
    $('#datatable-visibility-sn').DataTable({
        scrollX: true,
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print'
        ],
        columnDefs: [
            // {
            //     targets: 7,
            //     type: 'num',
            //     render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            // },
            // {
            //     targets: 8,
            //     type: 'num',
            //     render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            // },
            // {
            //     targets: 9,
            //     type: 'num',
            //     render: $.fn.dataTable.render.number('.', ',', 2, '')
            // }
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('table').find('thead').find(
                'th');
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            $('.dataTables_scrollBody thead tr').css({
                visibility: 'collapse'
            });
        },
    });
    $('.card-box').attr({
        'style': 'overflow-x: scroll !important;',
    });

});
</script>
@endsection
