@extends('fe-layouts.master')

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">

        <div class="content flex-row-fluid" id="kt_content">

            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    @if ($data['slug'] == 'open')
                        <a href="{{ url('all-assist-user-ticket') }}"
                            class="btn btn-color-gray-600 btn-active-color-primary"><span class="svg-icon svg-icon-5"><i
                                    class="fa fa-angle-left fs-2"></i></span>List All Assign Ticket</a>
                    @elseif($data['slug'] == 'assign')
                        <a href="{{ url('open-user-ticket') }}"
                            class="btn btn-color-gray-600 btn-active-color-primary"><span class="svg-icon svg-icon-5"><i
                                    class="fa fa-angle-left fs-2"></i></span>List Ticket Opened</a>
                    @endif
                </div>
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h2 class="text-end pe-0">{{ $data['title'] }}</h2>
                    <!--begin::Toolbar-->
                    @if ($data['slug'] == 'open')
                        <form action="{{ url('filter-open-user-ticket') }}" method="GET">
                        @else
                            <form action="{{ url('filter-all-assist-user-ticket') }}" method="GET">
                    @endif
                    
                    <div class="card-toolbar">
                        <div class="col me-1">
                            <div class="row">
                                <label for="">Start date:</label>
                            </div>
                            <input type="date" class="form-control" name="startdate" id="startdate"
                                onchange="handlerFromDate(event);"
                                @if (isset($data['startdate_filter'])) value="{{ $data['startdate_filter'] }}"
                                    @else
                                    value="{{ $data['startdatemonth'] }}" @endif>
                        </div>
                        <div class="col me-1">
                            <div class="row">
                                <label for="">End date:</label>
                            </div>
                            <input type="date" class="form-control" name="enddate" id="enddate"
                                onchange="handlerToDate(event);"
                                @if (isset($data['enddate_filter'])) value="{{ $data['enddate_filter'] }}"
                                    @else
                                    value="{{ $data['enddatemonth'] }}" @endif>
                        </div>
                        <div class="card-toolbar">
                            <button class="btn btn btn-light mt-6" id="filter" type="submit">Filter</button>
                        </div>
                    </div>
                    </form>
                    <!--end::Toolbar-->
                </div>
                @if ($data['slug'] == 'open')
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="text" data-kt-ecommerce-product-filter="search"
                                    class="form-control form-control-solid w-250px ps-14" placeholder="Search ID Ticket" />
                            </div>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-100 mw-150px">
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                                    data-placeholder="Assign" data-kt-ecommerce-product-filter="status">
                                    <option></option>
                                    <option value="all">All</option>
                                    <option value="Assigned">Is Assign</option>
                                    <option value="Not Assign">Not Assign</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ url('mutiple-close-ticket') }}" method="post" class="form">
                        {{ csrf_field() }}
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                fill="currentColor" />
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" data-kt-ecommerce-product-filter="search"
                                        class="form-control form-control-solid w-250px ps-14"
                                        placeholder="Search ID Ticket" />
                                </div>
                            </div>

                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                <div class="w-100 mw-150px"id="filter-status">
                                    <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="true" data-placeholder="Status"
                                        data-kt-ecommerce-product-filter="status">
                                        <option></option>
                                        <option value="all">All</option>
                                        <option value="Opened">Opened</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Resolved">Resolved</option>
                                        <option value="Escalated">Escalated</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Canceled">Canceled</option>
                                    </select>
                                </div>
                                <div class="w-100 mw-150px" id="note-close" style="display: none;">
                                    <input type="text" name="noteclose" class="form-control"
                                        placeholder="Type Your Note">
                                </div>
                                <div class="d-flex flex-stack">
                                    <button type="submit" id="multiple-close" style="display: none;"
                                        class="btn btn-danger">Close Selected <span id="count_check"></span> </button>
                                </div>
                            </div>
                        </div>
                @endif

                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th class="min-w-100px">ID Ticket</th>
                                <th class="min-w-100px">Date</th>
                                <th class="min-w-100px">Mesin</th>
                                <th class="min-w-100px">Lokasi</th>
                                <th class="min-w-100px">Subject</th>
                                <th class="min-w-70px">Reporter</th>
                                <th class="min-w-50px">Last Modified</th>
                                <th class="text-end min-w-50px">Status</th>
                                @if ($data['slug'] == 'open')
                                    <th class="text-end min-w-50px">Is Assign</th>
                                @else
                                    <th class="text-end min-w-50px">Mekanik</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600">

                            @php($no = 0)
                            @foreach ($data['ticket'] as $ticket)
                                @php($no = $no + 1)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            {{ $no }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-800 text-hover-primary fs-5 fw-bolder"
                                                    data-kt-ecommerce-product-filter="product_name">#{{ $ticket->ticket_id }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pe-0">
                                        <span
                                            class="fw-bolder">{{ date_format($ticket->created_at, 'H:i:s, d M Y') }}</span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">{{ $ticket->desc_mesin }}</span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">{{ $ticket->location }}</span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">{{ substr($ticket->subject, 0, 50) }}</span>
                                    </td>
                                    <td class="pe-0" data-order="20">
                                        <span class="fw-bolder">{{ $ticket->reporter_name }}<br>
                                            <span
                                                style="color: grey;font-size:10px">{{ $ticket->dept_reporter }}</span></span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">
                                            {{ date('d F Y', strtotime($ticket->created_at)) }}</span>
                                    </td>

                                    <td class="text-end pe-0">
                                        <div class="badge badge-light-primary"
                                            style="background-color: {{ $ticket->bg_color }}; color: {{ $ticket->fg_color }};">
                                            {{ $ticket->flow_name }}</div>
                                    </td>
                                    <td class="text-end pe-0"
                                        data-order="@if ($ticket->assist_id == '') {{ 'Not Assign' }}@else {{ 'Assigned' }} @endif">
                                        <div
                                            class=">
@if ($ticket->assist_id == '')
{{ 'badge badge-light-danger' }}
@else
{{ 'badge badge-light-primary' }}
@endif">
                                            @if ($ticket->assist_id == '')
                                                {{ 'Not Assign' }}
                                            @else
                                                {{ $ticket->assist_name ?? '' }}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('myscript')
    <div class="modal fade" id="action-ticket" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mw-650px">
            <div class="modal-content">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-danger fs-2"></i></span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                    <div id="ticketAction"></div>

                </div>
            </div>
        </div>
        <script>
            function review(x) {
                // indextr = x.rowIndex;
                console.log(x);
                document.getElementById('ticket_id').value = x;
            }

            function handlerFromDate(e) {
                document.getElementById('enddate').min = e.target.value;
            }

            function handlerToDate(e) {
                document.getElementById('startdate').max = e.target.value;
            }

            function setProccess(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('proccess-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Sla : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="sla" class="form-control" required>
                            @foreach ($data['sla'] as $sla)
                                <option value="{{ $sla->id }}">{{ $sla->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function setAssign(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('asign-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Assist : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="assist" class="form-control" required>
                                @foreach ($data['assist'] as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function setCancel(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('cancel-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
                $("#ticketAction").append(tr_str);
            }

            function getinventory(sel) {
                var id = sel.value;

                APP_URL = '{{ url('/') }}';
                $.ajax({
                    url: APP_URL + '/get-inventory/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response['data']);
                        $("input[name=reduce]").val(response['data'].inventory_qty);
                        $("input[name=reduce]").attr({
                            "max": response['data'].inventory_qty,
                            "min": 1
                        });
                    }
                });
            }
        </script>
    @endsection
