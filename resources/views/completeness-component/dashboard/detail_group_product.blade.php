@extends('completeness-component.panel.master')

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data['title'] }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table nowrap table-striped table-bordered" style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td>No.</td>
                                            <td>Production Order</td>
                                            <td>Product Number</td>
                                            <td>Description</td>
                                            <td>Serial Number</td>
                                            <td>Group Product</td>
                                            <td>Status</td>
                                            <td>Status Date</td>
                                            <td>Start Date</td>
                                            <td>Finish Date</td>
                                            <td>Quantity</td>
                                            <td>Good Issue</td>
                                            <td>Allocated</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['db'] as $item)                                          
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td data-order="{{ $item->production_order }}">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('production-order-planning/' . $item->production_order) }}" class="text-dark">
                                                            {{ $item->production_order }}
                                                        </a>                                                       
                                                    @else
                                                        {{ $item->production_order }}
                                                    @endif
                                                </td>
                                                <td data-toggle="tooltip" data-placement="right" title="{{ $item->product_description }}" data-order="{{ $item->product_number }}">
                                                    @if ($data['actionmenu_pro']->r == 1)
                                                        <a href="{{ url('planning-unit/' . $item->product_number) }}" class="text-dark">
                                                            {{ $item->product_number }}
                                                        </a>                                                       
                                                    @else
                                                        {{ $item->product_number }}
                                                    @endif
                                                </td>
                                                <td class="text-left">
                                                    {{ $item->product_description }}
                                                </td>
                                                <td>
                                                    {{ $item->serial_number }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('detail-product-ccr?groupProduct='.str_replace("&", "^", str_replace(" ", "_", $item->group_product))) }}" class="text-dark">
                                                        {{ $item->group_product }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $item->status }}
                                                </td>
                                                <td data-order="{{ $item->date_status_created }}">
                                                    @if ($item->date_status_created != null)
                                                        {{ date('d-m-Y', strtotime($item->date_status_created)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td data-order="{{ $item->sch_start_date }}">
                                                    {{ date('d-m-Y', strtotime($item->sch_start_date)) }}
                                                </td>
                                                <td data-order="{{ $item->sch_finish_date }}">
                                                    {{ date('d-m-Y', strtotime($item->sch_finish_date)) }}
                                                </td>
                                                <td>
                                                    {{ $item->quantity }}
                                                </td>
                                                <td data-order="{{ $item->persen_gi }}">
                                                    {{ $item->persen_gi == null ? "0 %" : str_replace('.', ',', $item->persen_gi).' %' }}
                                                </td>
                                                <td data-order="{{ $item->persen }}">
                                                    {{ $item->persen == null ? "0 %" : str_replace('.', ',', $item->persen).' %' }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="badge badge-primary detailSN" data-id="{{ $item->serial_number }}">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
            $('#myTable').DataTable({
                dom: "flBrtip",
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                stateSave: true,
                paging: true,
                columnDefs: [{
                    targets: [3, 7, 10],
                    visible: false
                }],
                buttons: [
                    'colvis',
                    <?php if ($data['actionmenu_pro']->v==1){ ?> 
                        {
                            text: 'Download',
                            action: function ( e, dt, node, config ) {
                                window.location.href = "{{ url('detail-product-ccr-download?filter='.$data['group_product'].'-'.$data['status']) }}";
                            }
                        }
                    <?php } ?>
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, ['All']]
                ],
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });
        });
    </script>
@endsection
