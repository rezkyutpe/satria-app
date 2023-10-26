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
<div class="">
    <div class="page-title">
        <div class="title_left">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent; font-size: 24px;">
                    <li class="breadcrumb-item active" aria-current="page"
                        onmouseover="this.style.textDecoration='underline';"
                        onmouseout="this.style.textDecoration='none';">
                        <a href="{{ url('cogs-list') }}">{{ $data['title1'] }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"
                        onmouseover="this.style.textDecoration='underline';"
                        onmouseout="this.style.textDecoration='none';">
                        <a href="#">{{ $data['title2'] }}</a>
                    </li>
                    <input id="SubmenuDelete" class="d-none" value="{{ $data['title2'] }}">
                </ol>
            </nav>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5   form-group pull-right top_search">
                <div class="input-group">
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> {{ $data['subtitle'] }} </h2>
                    <div class="nav pull-right" role="button">
                        <li><a class="collapse-link p-0"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </div>
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
                    <table id="datatable-visibility-classification"
                        class="table text-center table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID COGS</th>
                                <th>Updated On</th>
                                <th>PN Reference</th>
                                <th>PN Description</th>
                                <th>COGS</th>
                                <th>GP (%)</th>
                                <th>Quotation Price</th>
                                <th>Product Category</th>
                                <th>Cost Estimator</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($data))
                            @php
                            $i =1;
                            @endphp
                            @foreach ($data['dataCreated'] as $item)
                            @if (empty($item->Opportunity) && empty($item->PCRID) && empty($item->CPOID) && empty($item->APCROwner) && empty($item->PIC))
                            @php
                            $UpdateCOGS = date("Y-m-d H:i:s",strtotime($item->COGSUpdate));
                            $TotalCOGS = $item->TotalRawMaterial + $item->TotalSFComponent + $item->TotalConsumables +
                            $item->TotalProcess + $item->TotalOthers;
                            @endphp
                            <tr class="baseBlock">
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->COGSID }}</td>
                                <td>{{ $UpdateCOGS }}</td>
                                <td>
                                    @if (!empty($item->PNReference))
                                    {{ $item->PNReference }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($item->PNReferenceDesc))
                                    {{ $item->PNReferenceDesc }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $TotalCOGS }}</td>
                                <td>{{ $item->GrossProfit }}</td>
                                <td>{{ $item->QuotationPrice }}</td>
                                <td>{{ $item->ProductCategory }}</td>
                                <td>{{ $item->CostEstimator }}</td>
                                <td>
                                    @if($data['actionmenu']->u==1)
                                    <a href="{{ url('cogs-form-cogs/'.$item->COGSID.'/view') }}"
                                        style="color:white; margin:2px;" class="badge badge-warning form-cogs"
                                        cogsid="{{ $item->COGSID }}">
                                        <i class="fa fa-pencil fa-xs"></i>
                                        Form
                                    </a>
                                    @endif
                                    @if($data['actionmenu']->d==1)
                                    <a href="#" style="color:white; margin:2px;" class="badge badge-danger delete-cogs"
                                        cogsid="{{ $item->COGSID }}" menu="list">
                                        <i class="fa fa-trash fa-xs"></i>
                                        Delete
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @else
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal fade" id="delete">
    <form method="post" action="{{url('cogs-delete-cogs')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>Warning</h3>
                    <h2 id="message"></h2>
                </div>
                <input id="COGSIDdelete" type="hidden" name="COGSIDdelete" value="">
                <input id="PCRCPOIDdelete" type="hidden" name="PCRCPOIDdelete" value="">
                <input id="CategoryNamedelete" type="hidden" name="CategoryNamedelete" value="">
                <input id="Menu" type="hidden" name="Menu" value="">
                <input id="Submenu" type="hidden" name="Submenu" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('myscript')
<script>
$(document).ready(function() {
    $('#datatable-visibility-classification').DataTable({
        scrollX: true,
        scrollY: '450px',
        "sScrollXInner": "100%",
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
                targets: 5,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {
                targets: 6,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, '')
            },
            {
                targets: 7,
                type: 'num',
                render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            }
        ],
        "fnInitComplete": () => {
            var styleAttributeThead = $('table').find('thead').find(
                'tr').find('th');
            console.log(styleAttributeThead.attr('style'));
            styleAttributeThead.attr({
                'style': 'background-color: rgba(0, 0, 0, 0.3);',
            });
            $('.dataTables_scrollBody thead tr').css({
                visibility: 'collapse'
            });
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        },
        // 'rowCallback': function(row, data, index) {
        //     console.log(data[8]);
        //     if (data[8].toUpperCase() == 'OPEN') {
        //         $(row).find('td:eq(8)').css('background-color', '#16ff01');
        //         $(row).find('td:eq(8)').css('color', '#000000');
        //     }
        //     if (data[8].toUpperCase() != 'OPEN') {
        //         $(row).find('td:eq(8)').css('background-color', '#ff0e01');
        //         $(row).find('td:eq(8)').css('color', '#000000');
        //     }
        //     if (data[8].toUpperCase() == '') {
        //         $(row).find('td:eq(8)').css('background-color', '#F7A76C');
        //         $(row).find('td:eq(8)').css('color', '#000000');
        //         $(row).find('td:eq(8)').html('CPO Order');
        //     }
        // }
    });


    $('.card-box').attr({
        'style': 'overflow-x: scroll !important;',
    });

    $('.delete-cogs').on("click", function(e) {
        e.preventDefault();
        var cogsid = $(this).attr("cogsid");
        var menu = $(this).attr("menu");
        $.ajax({
            url: "{{url('cogs-search-cogs-header')}}",
            data: {
                cogsid: cogsid,
            },
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.CogsHeader[0].PCRID) {
                    var ID = (data.CogsHeader[0].PCRID)
                } else {
                    var ID = (data.CogsHeader[0].CPOID)
                }
                $('#COGSIDdelete').val(data.CogsHeader[0].ID);
                $('#PCRCPOIDdelete').val(ID);
                $('#CategoryNamedelete').val(data.CogsHeader[0].ProductCategory);
                $('#Menu').val(menu);
                $('#Submenu').val($('#SubmenuDelete').val().replaceAll(' ', ''));
                $('#message').html(
                    "Anda yakin ingin menghapus data COGS ?");
                $('#delete').modal('show');
            }
        });
    });

});
</script>
@endsection
