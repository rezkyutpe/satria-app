@extends('completeness-component.panel.master')

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title ml-3">
                    <h2>{{ $data['title'] }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="x_content">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped table-bordered" style="text-align: center" cellspacing="0" width="100%">
                                    <thead style="background-color: rgb(42,63,84);color:white">
                                        <tr>
                                            <td class="align-middle">No.</td>
                                            <td class="text-center align-middle">App</td>
                                            <td class="text-center align-middle">Sender</td>
                                            <td class="text-center align-middle">PO No.</td>
                                            <td class="text-center align-middle">Item Number</td>
                                            <td class="text-center align-middle">Material</td>
                                            <td class="text-center align-middle">Chat</td>
                                            <td class="text-center align-middle">Created At</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['comment'] as $comment)
                                            <tr
                                                @if ($comment['is_read'] == 1)
                                                    class="text-secondary"
                                                @else
                                                    class="font-weight-bold"
                                                @endif
                                            >
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">
                                                    <span 
                                                    @if ($comment['apps'] == 'CCR')
                                                        class="badge badge-primary"
                                                    @else
                                                        class="badge badge-success"
                                                    @endif
                                                    >
                                                        {{ $comment['apps'] }}
                                                    </span>
                                                </td>
                                                <td class="text-left align-middle">{{ $comment['sender'] }}</td>
                                                <td class="align-middle">{{ $comment['po_no'] }}</td>
                                                <td class="align-middle">{{ $comment['itemNumber'] }}</td>
                                                <td class="align-middle text-left">{{ $comment['material'] }}</td>
                                                <td class="text-left align-middle">
                                                    @if ($comment['apps'] == 'CCR')
                                                       <a href="{{ url('material-detail/' . $comment['material']) }}" 
                                                            @if ($comment['is_read'] == NULL)
                                                                class="text-dark"
                                                            @endif
                                                        >
                                                            {{ $comment['chat'] }}
                                                        </a> 
                                                    @else
                                                        <a onclick="getChat('{{$comment['po_no']}}','{{$comment['itemNumber']}}')" style="cursor: pointer">
                                                            {{ $comment['chat'] }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="align-middle">{{ date('d-m-Y H:i:s', strtotime($comment['created_at'])) }}</td>
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
    {{-- <script>
        $(document).ready(function() {
            setInterval(function() {
                $("#komentar").load(" #komentar > *");
            }, 15000);
        });
    </script> --}}

    
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "flBrtip",
                fixedHeader: true,
                scrollCollapse: true,
                paging: true,
                buttons: [
                    'colvis'
                ],
                select: true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "615px");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });
        });
    </script>
@endsection
