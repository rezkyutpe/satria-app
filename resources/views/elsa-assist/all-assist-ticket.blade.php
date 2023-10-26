@extends('fe-layouts.master')

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">

        <div class="content flex-row-fluid" id="kt_content">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <a href="{{ url('open-ticket') }}" class="btn btn-color-gray-600 btn-active-color-primary"><span
                            class="svg-icon svg-icon-5"><i class="fa fa-angle-left fs-2"></i></span>List Open Ticket</a>
                </div>
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h2 class="text-end pe-0">All Ticket Assigned</h2>
                    <!--begin::Toolbar-->
                    <form action="{{ url('filter-all-assist-ticket') }}" method="GET">
                        
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

                <form action="{{ url('mutiple-close-ticket') }}" method="post" class="form">
                    {{ csrf_field() }}
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
                            <div class="w-100 mw-150px"id="filter-status">
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                                    data-placeholder="Status" data-kt-ecommerce-product-filter="status">
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
                                <input type="text" name="noteclose" class="form-control" placeholder="Type Your Note">
                            </div>
                            <div class="d-flex flex-stack">
                                <button type="submit" id="multiple-close" style="display: none;"
                                    class="btn btn-danger">Close Selected <span id="count_check"></span> </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">#</th>
                                    <th class="min-w-100px">ID Ticket</th>
                                    <th class="min-w-100px">Subject</th>
                                    <th class="min-w-70px">Reporter</th>
                                    <th class="text-end min-w-100px">SLA</th>
                                    <th class="text-center min-w-50px">Rate</th>
                                    <th class="min-w-50px">Status</th>
                                    <th class="min-w-50px">Mekanik</th>
                                    <th class="text-end min-w-70px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600">

                                @php($no = 0)
                                @foreach ($data['ticket'] as $ticket)
                                    @php($no = $no + 1)
                                    @php($datediff = Helper::TimeInterval($ticket->respond_time, $ticket->resolve_time == '' ? Date('Y-m-d H:i:s') : $ticket->resolve_time) / 60)
                                    @php($interval = Helper::TimeInterval($ticket->respond_time, $ticket->resolve_time == '' ? Date('Y-m-d H:i:s') : $ticket->resolve_time) / 60)
                                    @php($resolution = $ticket->resolution_time == 0 ? 0 : $ticket->resolution_time * 60)
                                    @php($intervalpercent = $resolution == 0 ? 0 : round(($interval / $resolution) * 100))
                                    @php($duration = '(' . floor($datediff / 60) . ':' . gmdate('i:s', $interval * 60) . ')')
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" id="selected-item" name="ticketselect[]"
                                                    onchange="selectTicket()" type="checkbox" value="{{ $ticket->id }}"
                                                    @if ($ticket->flag != 3) disabled @endif />
                                                <div style="margin-left: 10px;">#{{ $no }}</div>
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
                                            <span class="fw-bolder">{{ $ticket->subject }}</span><br>
                                            <span style="color: grey;font-size:12px">{{ $ticket->desc_mesin }}</span><br>
                                            <span style="color: grey;font-size:11px">{{ $ticket->location }}</span>
                                        </td>
                                        <td class="pe-0" data-order="20">
                                            <span class="fw-bolder">{{ $ticket->reporter_name }}<br>
                                                <span
                                                    style="color: grey;font-size:12px">{{ $ticket->dept_reporter }}</span><br>
                                                <span style="color: grey;font-size:11px">At
                                                    {{ date('d F Y H:i:s', strtotime($ticket->created_at)) }}</span></span>
                                        </td>
                                        <td class="text-end pe-0" data-order="rating-3">
                                            <div class="d-flex flex-column w-100 me-2 mt-2">
                                                <span
                                                    class="text-gray-400 me-2 fw-boldest mb-2">{{ $intervalpercent <= 100 ? $intervalpercent : '100+' }}%
                                                    {{ isset($ticket->respond_time) ? $duration : '-' }}</span>
                                                <div class="progress bg-light-danger w-100 h-5px">
                                                    <div @if ($intervalpercent <= 100) class="progress-bar bg-success" @else class="progress-bar bg-danger" @endif
                                                        role="progressbar"
                                                        style="width: {{ $resolution == 0 ? 0 : round(($interval / $resolution) * 100) }}%">
                                                    </div>
                                                </div>
                                                <span class="text-gray-400 fw-bold d-block">SLA:
                                                    {{ $ticket->sla_name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center pe-0">
                                            <span class="svg-icon svg-icon-2 text-dark">
                                                {{ $ticket->rate }} <i class="bi bi-star-fill text-warning"></i>
                                            </span>
                                        </td>
                                        <td class="pe-0" data-order="{{ $ticket->flow_name }}">
                                            <div class="badge badge-light-primary"
                                                style="background-color: {{ $ticket->bg_color }}; color: {{ $ticket->fg_color }};">
                                                {{ $ticket->flow_name }} </div>

                                        </td>
                                        <td class="pe-0">
                                            <span class="fw-bolder">{{ $ticket->assist_name }}</span>
                                        </td>
                                        <td class="text-end pe-0">
                                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <span class="svg-icon svg-icon-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span></a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="#"
                                                        onclick="getHistory({{ $ticket->id }})"class="menu-link px-3"
                                                        tooltip="New App" data-bs-toggle="modal"
                                                        data-bs-target="#detail-ticket" class="menu-link px-3">Detail</a>
                                                </div>

                                                @if ($ticket->flag == 1)
                                                    <div class="menu-item px-3">
                                                        <a href="#"
                                                            onclick="setAssign({{ $ticket->id }},'Assign Ticket')"class="menu-link px-3"
                                                            tooltip="New App" data-bs-toggle="modal"
                                                            data-bs-target="#action-ticket" class="menu-link px-3">Assign
                                                            to</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#"
                                                            onclick="setProccess({{ $ticket->id }},'Proccess')"
                                                            class="menu-link px-3" tooltip="New App"
                                                            data-bs-toggle="modal" data-bs-target="#action-ticket"
                                                            class="menu-link px-3">Proccess</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#"
                                                            onclick="setCancel({{ $ticket->id }},'Cancel')"
                                                            class="menu-link px-3" tooltip="New App"
                                                            data-bs-toggle="modal" data-bs-target="#action-ticket"
                                                            class="menu-link px-3">Cancel</a>
                                                    </div>
                                                @elseif($ticket->flag == 2 || $ticket->flag == 3 || $ticket->flag == 9)
                                                    @if ($ticket->flag != 3)
                                                        <div class="menu-item px-3">
                                                            <a href="#"
                                                                onclick="setEscalate({{ $ticket->id }},'Escalate')"
                                                                class="menu-link px-3" tooltip="New App"
                                                                data-bs-toggle="modal" data-bs-target="#action-ticket"
                                                                class="menu-link px-3">Escalate</a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a href="#"
                                                                onclick="setResolve({{ $ticket->id }},'Resolve')"
                                                                class="menu-link px-3" tooltip="New App"
                                                                data-bs-toggle="modal" data-bs-target="#action-ticket"
                                                                class="menu-link px-3">Resolve</a>
                                                        </div>
                                                    @else
                                                        @if (Auth::user()->dept != '8884')
                                                            <div class="menu-item px-3">
                                                                <a href="#"
                                                                    onclick="setEscalate({{ $ticket->id }},'Escalate')"
                                                                    class="menu-link px-3" tooltip="New App"
                                                                    data-bs-toggle="modal" data-bs-target="#action-ticket"
                                                                    class="menu-link px-3">Escalate</a>
                                                            </div>
                                                        @endif
                                                        @php($interval = Helper::TimeInterval(date('Y-m-d H:i:s'), $ticket->resolve_time))
                                                        @if ($interval >= '32400')
                                                            <div class="menu-item px-3">
                                                                <a href="#"
                                                                    onclick="setClose({{ $ticket->id }},'Close')"
                                                                    class="menu-link px-3" tooltip="New App"
                                                                    data-bs-toggle="modal" data-bs-target="#action-ticket"
                                                                    class="menu-link px-3">Close</a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @elseif($ticket->flag != 5 && $ticket->flag != 6)
                                                    <div class="menu-item px-3">
                                                        <a href="#"
                                                            onclick="setCancel({{ $ticket->id }},'Cancel')"
                                                            class="menu-link px-3" tooltip="New App"
                                                            data-bs-toggle="modal" data-bs-target="#action-ticket"
                                                            class="menu-link px-3">Cancel</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
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
            function selectTicket() {
                var checkedValue = $('#selected-item:checked');
                var selected = document.getElementsByName('ticketselect[]');
                var count_checked = 0;
                for (var i = 0; i < selected.length; i++) {
                    console.log(selected[i].value);
                    selected[i].checked ? count_checked = count_checked + 1 : ' ';
                }
                // console.log(count_checked);
                if (count_checked == 0) {
                    document.getElementById('count_check').innerHTML = '';
                    document.getElementById('multiple-close').style.display = "none";
                    document.getElementById('note-close').style.display = "none";
                    document.getElementById('filter-status').style.display = "block";
                } else {
                    document.getElementById('count_check').innerHTML = '(' + count_checked + ')';
                    document.getElementById('multiple-close').style.display = "block";
                    document.getElementById('note-close').style.display = "block";
                    document.getElementById('filter-status').style.display = "none";
                }
            }

            function review(x) {
                // indextr = x.rowIndex;
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

            function setEscalate(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('escalate-ticket') }}" method="post" enctype="multipart/form-data">
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

            function setResolve(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('resolve-ticket') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span >Reduce Inventory : </span>
                        </label>
                        <select style="width: 100%;"  name="inventory" onchange="getinventory(this)"  class="form-control js-example-basic-single" data-live-search="true">
                                <option value="">- Select Inventory -</option>
                                @foreach ($data['inventory'] as $inventory)
                                    <option value="{{ $inventory->inventory_id }}" >{{ $inventory->inventory_nama }}</option>
                                @endforeach
                            </select>
                    </div>
                    
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Qty</span>
                        </label>
                        <input type="number" name="reduce"  id="reduce" class="form-control"  autocomplete="off">  
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="` + x + `">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">ANALYSIS ROOT CAUSE BREAKDOWN ?</span>
						</label>
						<input type="text"  name="analisis" placeholder="Penyebab Kerusakan Mesin" class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">CORRECTIVE ACTION ?</span>
						</label>
						<input type="text"  name="corrective" placeholder="Langkah Perbaikan"  class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">PREVENTIVE ACTION ?</span>
						</label>
						<input type="text"  name="preventive" placeholder="Langkah Pencegahan" class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">CONSUMABLE USED ?</span>
						</label>
						<input type="text"  name="consumable" placeholder="Barang Habis Pakai"  class="form-control form-control-lg form-control-solid" />
					</div>
					<div class="fv-row mb-10" id="maintenance" style="display: block;">
						<label class="d-flex align-items-center fs-5 fw-bold mb-2">
							<span class="">SERVICES AND COSTS ?</span>
						</label>
						<input type="text"  name="costs" placeholder="Jasa dan Biaya" class="form-control form-control-lg form-control-solid" />
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

            function setClose(x, s) {
                $('#ticketAction').empty();
                var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">` + s + ` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">` + s + `</a> Ticket</div>
                </div>   <form action="{{ url('close-ticket') }}" method="post" enctype="multipart/form-data">
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

            function getinventory(sel) {
                var id = sel.value;

                APP_URL = '{{ url('/') }}';
                $.ajax({
                    url: APP_URL + '/get-inventory/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
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
