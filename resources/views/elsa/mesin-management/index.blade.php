@extends('fe-layouts.master')

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card card-flush">

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h2 class="text-end pe-0">Master Data Mesin</h2>
                </div>
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" data-kt-ecommerce-product-filter="search"
                                class="form-control form-control-solid w-250px ps-14" id="searchMesin"
                                placeholder="Search Mesin" />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0" id="aa" style="display:none">
                    <table style="width:100%" class="table align-middle table-row-dashed fs-6 gy-5"
                        id="kt_ecommerce_products_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th>Nama Mesin</th>
                                <th>Equipment Number</th>
                                <th>Dimension</th>
                                <th>Manufacture Asset</th>
                                <th>Location</th>
                                <th>Room</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="mesinTable">
                            @php($no = 0)
                            @foreach ($mesin as $mesin)
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
                                                    data-kt-ecommerce-product-filter="product_name"
                                                    href="{{ url('mesin-detail') }}/{{ $mesin->id_mesin }}">{{ $mesin->desc_mesin }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">{{ $mesin->equipment_number }}</span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">
                                            {{ $mesin->dimension }}
                                        </span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">{{ $mesin->manufacture_asset }}</span>
                                    </td>
                                    <td class="pe-1">
                                        <span class="fw-bolder">{{ $mesin->location }}</span>
                                    </td>
                                    <td class="pe-1">
                                        <span class="fw-bolder">{{ $mesin->room }}</span>
                                    </td>
                                    <td class="text-end">
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
                                                @if ($actionmenu->u == 1)
                                                    <a href="#" id="edit-mesin-btn" data-id="{{ $mesin->id_mesin }}"
                                                        class="menu-link px-3 edit-mesin-btn"
                                                        data-bs-toggle="modal"><small>Update</small></a>
                                                @endif
                                                @if ($actionmenu->d == 1)
                                                    <a href="#" class="menu-link px-3 delete-mesin-btn"
                                                        data-bs-toggle="modal"
                                                        data-id="{{ $mesin->id_mesin }}"><small>Delete</small></a>
                                                @endif
                                                @if ($actionmenu->v == 1)
                                                    <a class="menu-link px-3"
                                                        onclick="location.href='{{ url('mesin-detail') }}/{{ $mesin->id_mesin }}';"><small>Detail</small></a>
                                                @endif
                                                {{-- @if ($actionmenu->d == 1)
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                        data-bs-target="#modal-coba"><small>Detail</small></a>
                                                @endif --}}
                                            </div>
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

    {{-- Modal Insert --}}
    <div class="modal fade" id="add-mesin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Tambah Data Mesin</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-danger fs-2"></i></span>
                    </div>
                </div>
                <form action="{{ url('mesin-insert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="nama_mesin" placeholder="Nama Mesin"
                                required>
                            <label for="nama_mesin">Nama Mesin</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" name="kategori" id="kategori">
                                <option selected>-- Pilih Kategori</option>
                                <option value="PB">PB</option>
                                <option value="Welding">Welding</option>
                            </select>
                            <label for="kategori">Kategori</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="function" placeholder="Function">
                            <label for="function">Function</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="dimensi" placeholder="Dimensi">
                            <label for="dimensi">Dimensi</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="tools" placeholder="Tools">
                            <label for="tools">Tools</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="remarks" placeholder="Remarks">
                            <label for="remarks">Remarks</label>
                        </div>
                        <a class="btn btn-primary" onclick="addspec()"><small>Tambah spek mesin</small></a>
                        <div id="detailMesin" class="mt-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="description" id="description"
                                    placeholder="Description">
                                <label for="description">Description</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="unit" id="unit"
                                    placeholder="Unit">
                                <label for="unit">Unit</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="value" id="value"
                                    placeholder="Value">
                                <label for="value">Value</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Modal Update --}}
    <div class="modal fade" id="update-mesin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen p-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Update Data Mesin</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-secondary fs-2"></i></span>
                    </div>
                </div>
                <div class="content-scroll scroll-y m-5">
                    <form action="{{ url('mesin-update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id_mesin_update" id="id_mesin_update">
                            <input type="hidden" name="id_detail_mesin_update" id="id_detail_mesin_update">
                            <input type="hidden" name="id_file_mesin_update" id="id_file_mesin_update">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            Nama Mesin
                                        </label>
                                        <input type="text" id="nama_mesin" name="nama_mesin" autocomplete="off"
                                            maxlength="50" class="form-control form-control-md form-control-solid"
                                            disabled />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fs-6 fw-bold mb-2"><span class="required">Category</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                            title="Form Required"></i>
                                    </div>
                                    <div class="mh-300px scroll-y me-n7 pe-7">
                                        <select class="form-select form-select-solid form-select-md" name="category"
                                            id="category" data-control="select2" data-hide-search="true" required>
                                            <option value="Choose">Choose Category</option>
                                            <option value="PB">PB</option>
                                            <option value="Welding">Welding</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            <span class="required">Function</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                data-bs-toggle="tooltip"title="Form Required"></i>
                                        </label>
                                        <input type="text" id="function" name="function" autocomplete="off"
                                            maxlength="50" placeholder="Function"
                                            class="form-control form-control-md form-control-solid" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            <span class="required">Material</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                data-bs-toggle="tooltip"title="Form Required"></i>
                                        </label>
                                        <input type="text" id="material" name="material" autocomplete="off"
                                            maxlength="50" placeholder="Material"
                                            class="form-control form-control-md form-control-solid" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            <span class="required">Length</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                data-bs-toggle="tooltip"title="Form Required"></i>
                                        </label>
                                        <input type="text" id="length_mesin" name="length_mesin" autocomplete="off"
                                            maxlength="50" placeholder="Length"
                                            class="form-control form-control-md form-control-solid" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            <span class="required">Width</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                data-bs-toggle="tooltip"title="Form Required"></i>
                                        </label>
                                        <input type="text" id="width" name="width" autocomplete="off"
                                            maxlength="50" placeholder="Width"
                                            class="form-control form-control-md form-control-solid" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            <span class="required">Thickness</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                data-bs-toggle="tooltip"title="Form Required"></i>
                                        </label>
                                        <input type="text" id="thickness" name="thickness" autocomplete="off"
                                            maxlength="50" placeholder="Thickness"
                                            class="form-control form-control-md form-control-solid" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            <span class="required">Tools</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                data-bs-toggle="tooltip"title="Form Required"></i>
                                        </label>
                                        <input type="text" id="tools" name="tools" autocomplete="off"
                                            maxlength="50" placeholder="Tool"
                                            class="form-control form-control-md form-control-solid" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fv-row mb-5">
                                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                            <span class="required">Remarks</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                data-bs-toggle="tooltip"title="Form Required"></i>
                                        </label>
                                        <input type="text" id="remarks" name="remarks" autocomplete="off"
                                            maxlength="50" placeholder="Remarks"
                                            class="form-control form-control-md form-control-solid" required />
                                    </div>
                                </div>
                            </div>
                            <hr class="text-secondary">
                            {{-- User Manual Mesin File --}}
                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                Attach User Manual Mesin File
                            </label>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input class="form-control" type="file" name="mesinFile" id="mesinFile"
                                        accept=".pdf">
                                    <label><small>*Format file .pdf</small></label>
                                </div>
                                <div id="filess" class="col-md-6">
                                    <p id="fileexist" class="btn btn-primary"></p>
                                </div>
                            </div>
                            <hr class="text-secondary">
                            {{-- Spek --}}
                            <div class="row">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    Spek Mesin
                                </label>
                            </div>
                            <div class="">
                                <table class="mb-2 table" id="spek_exist_tbl" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="fs-5 fw-bold" style="width: 33%;">Description</th>
                                        <th class="fs-5 fw-bold" style="width: 34%;" >Unit</th>
                                        <th class="fs-5 fw-bold" style="width: 27%;">Value</th>
                                        <th></th>
                                </thead>
                                </tr>
                                <tbody id="spek_exist">

                                </tbody>
                            </table>
                            </div>
                            
                            <div class="spekMesin" id="spekMesin">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="fv-row mb-3">
                                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                                <span class="required">Description</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                    data-bs-toggle="tooltip"title="Form Required"></i>
                                            </label>
                                            <input type="text" name="description[]" autocomplete="off" maxlength="50"
                                                placeholder="Description"
                                                class="form-control form-control-md form-control-solid" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-3">
                                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                                <span class="required">Unit</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                    data-bs-toggle="tooltip"title="Form Required"></i>
                                            </label>
                                            <input type="text" name="unit[]" autocomplete="off" maxlength="50"
                                                placeholder="Unit"
                                                class="form-control form-control-md form-control-solid" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-3">
                                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                                <span class="required">Value</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                    data-bs-toggle="tooltip"title="Form Required"></i>
                                            </label>
                                            <input type="text" name="value[]" autocomplete="off" maxlength="50"
                                                placeholder="Value"
                                                class="form-control form-control-md form-control-solid" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 mb-3 btn-group ms-auto me-5">
                                    <a type="button" class="btn btn-primary me-3"
                                        onclick="addrows('spekMesin')"><small>+</small></a>
                                    <a type="button" class="btn btn-light"
                                        onclick="delrows('spekMesin')"><small>-</small></a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light pull-left"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="update_submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="delete-mesin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Hapus Data Mesin</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-secondary fs-2"></i></span>
                    </div>
                </div>
                <form action="{{ url('mesin-delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_mesin_delete" id="id_mesin_delete">
                    <div class="modal-body text-center">
                        <h1 style="text-primary">Warning!!</h1>
                        <p>Are you sure to delete <strong id="strongDelete"></strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light pull-left" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('myscript')
    <script>
        $(document).ready(function() {
            document.getElementById("add-mesin").addEventListener('shown.bs.modal', function() {
                document.getElementById("nama_mesin").focus()
            })
            $("#searchMesin").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#mesinTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#detailMesin").hide();
            $("#aa").css("display", "block");
        });

        $('#update-submit').click(function() {
            $('#mesinFile').val(null);
        });

        $('.edit-mesin-btn').click(function() {
            var id = $(this).attr('data-id');
            console.log(id);

            $.ajax({
                url: "{{ url('get-detail-mesin') }}?id=" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data.datadetail);
                    $('#spek_exist').empty();
                    $('#id_mesin_update').val(data.datamesin.id_mesin);
                    $('#id_detail_mesin_update').val(data.datadetail.id_detail_mesin);
                    $('#id_file_mesin_update').val(data.datafile.id_file);
                    $('#nama_mesin').val(data.datamesin.desc_mesin);
                    if (data.datadetail.category == 'PB') {
                        $("#category").val("PB").change();
                    } else if (data.datadetail.category == 'Welding') {
                        $("#category").val("Welding").change();
                    } else {
                        $("#category").val("Choose").change();
                    }
                    $('#function').val(data.datadetail.function);
                    $('#material').val(data.datadetail.material);
                    $('#length_mesin').val(data.datadetail.length_mesin);
                    $('#width').val(data.datadetail.width);
                    $('#thickness').val(data.datadetail.thickness);
                    $('#tools').val(data.datadetail.tools);
                    $('#remarks').val(data.datadetail.remarks);

                    if (data.datafile.length == 0) {
                        $('#filess').hide();
                    } else {
                        $('#filess').show();
                        document.getElementById('fileexist').innerHTML = `<small>` + data.datafile
                            .file_name + `</small>`;
                    }

                    if (data.dataspek.length > 0) {
                        $('#spek_exist_tbl').show();
                    } else {
                        $('#spek_exist_tbl').hide();
                    }

                    for (i = 0; i < data.dataspek.length; i++) {
                        $('#spek_exist').append(`<tr>
                                        <td style="min-width:175px;">` + data.dataspek[i].description + `</td>
                                        <td style="min-width:175px;">` + data.dataspek[i].unit + `</td>
                                        <td>` + data.dataspek[i].value + `</td>
                                        <td><a class="btn btn-sm btn-light ms-10" onclick="if(confirm('Are you sure to delete this spek mesin?')) window.location.href='{{ url('delete-spek-mesin/` + data.dataspek[i].id_spek_mesin + `') }}'">-</a></td>`
                        )
                    }

                    $('#update-mesin').modal('show');
                }
            });
        });

        $('.delete-mesin-btn').click(function() {
            var id = $(this).attr('data-id');
            console.log(id);

            $.ajax({
                url: "{{ url('get-detail-mesin') }}?id=" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#id_mesin_delete').val(data.datamesin.id_mesin);
                    $('#strongDelete').html(data.datamesin.desc_mesin + `?`);
                    $('#delete-mesin').modal('show');
                }
            });
        });

        function addspec() {
            // $("addSpek").on("click", function() {
            console.log("tttt");
            $("#detailMesin").show();
            // });
        }

        var j = 0;

        function addrows(modal) {
            j++;

            $('#' + modal).append(`
                    <div class="row">
                        <div class="col-md-4">
                            <div class="fv-row mb-3">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    <span class="required">Description</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                                        data-bs-toggle="tooltip"title="Form Required"></i>
                                </label>
                                <input type="text" name="description[]" autocomplete="off" maxlength="50"
                                    placeholder="Description"
                                    class="form-control form-control-md form-control-solid" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fv-row mb-3">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    <span class="required">Unit</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                                        data-bs-toggle="tooltip"title="Form Required"></i>
                                </label>
                                <input type="text" name="unit[]" autocomplete="off" maxlength="50"
                                    placeholder="Unit"
                                    class="form-control form-control-md form-control-solid" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fv-row mb-3">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    <span class="required">Value</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                                        data-bs-toggle="tooltip"title="Form Required"></i>
                                </label>
                                <input type="text" name="value[]" autocomplete="off" maxlength="50"
                                    placeholder="Value"
                                    class="form-control form-control-md form-control-solid" />
                            </div>
                        </div>
                    </div>
                `);
            $('#n_row').val(j);
        }

        function delrows(modal) {
            if (j != 0) {
                $('#' + modal).children().eq(j).remove();
                j--;
                $('#n_row').val(j);
            }
        }
    </script>
@endsection
