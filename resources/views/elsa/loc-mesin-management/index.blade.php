@extends('fe-layouts.master')

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card card-flush">

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h2 class="text-end pe-0">Master Data Lokasi Mesin</h2>
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
                    @if ($actionmenu->c == 1)
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-loc-mesin">Tambah</a>
                    @endif
                </div>
                <div class="card-body pt-0">
                    <table style="width:100%" class="table align-middle table-row-dashed fs-6 gy-5"
                        id="kt_ecommerce_products_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th class="min-w-150px">Kategori</th>
                                <th class="min-w-100px">Lokasi Mesin</th>
                                <th class="min-w-100px"></th>
                                <th class="min-w-100px"></th>
                                <th class="min-w-100px"></th>
                                <th class="min-w-100px"></th>
                                <th class="text-end min-w-50px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="mesinTable">
                            @php($no = 0)
                            @foreach ($LocMesin as $lc)
                                @php($no = $no + 1)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            {{ $no }}
                                        </div>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder">{{ $lc->kategori_loc }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-800 text-hover-primary fs-5 fw-bolder"
                                                    data-kt-ecommerce-product-filter="product_name"
                                                    href="#">{{ $lc->location_mesin }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder"></span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder"></span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder"></span>
                                    </td>
                                    <td class="pe-0">
                                        <span class="fw-bolder"></span>
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
                                                    <a href="#" class="menu-link px-3 edit" data-bs-toggle="modal"
                                                        data-id="{{ $lc->id_loc_mesin }}"><small>Update</small></a>
                                                @endif
                                                @if ($actionmenu->d == 1)
                                                    <a href="#" class="menu-link px-3 delete" data-bs-toggle="modal"
                                                        data-id="{{ $lc->id_loc_mesin }}"><small>Delete</small></a>
                                                @endif
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

    {{-- Modal Add --}}
    <div class="modal fade" id="add-loc-mesin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Tambah Data Lokasi Mesin</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-secondary fs-2"></i></span>
                    </div>
                </div>
                <form action="{{ url('loc-mesin-insert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="">
                            <div class="mh-300px scroll-y me-n7 pe-7 mb-5">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    Kategori Lokasi
                                </label>
                                <select class="form-select form-select-solid form-select-md" name="kategori_loc"
                                    data-control="select2" id="kategori_loc" data-hide-search="true" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Plant 1">Plant 1</option>
                                    <option value="Plant 2">Plant 2</option>
                                    <option value="Area Lain">Area Lain</option>
                                </select>
                            </div>
                            <div class="fv-row mb-5">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    Nama Lokasi Mesin
                                </label>
                                <input type="text" name="location_mesin" id="location_mesin" autocomplete="off"
                                    maxlength="50" class="form-control form-control-md form-control-solid" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light pull-left" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Update --}}
    <div class="modal fade" id="update-loc-mesin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Update Data Lokasi Mesin</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-secondary fs-2"></i></span>
                    </div>
                </div>
                <form action="{{ url('loc-mesin-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="">
                            <input type="hidden" name="idloc_update" id="idloc_update">
                            <div class="mh-300px scroll-y me-n7 pe-7 mb-5">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    Kategori Lokasi
                                </label>
                                <select class="form-select form-select-solid form-select-md kategori_loc_update"
                                    name="kategori_loc_update" data-control="select2" id="kategori_loc_update"
                                    data-hide-search="true">
                                    <option value="">Pilih Kategori</option>
                                    <option value="Plant 1">Plant 1</option>
                                    <option value="Plant 2">Plant 2</option>
                                    <option value="Area Lain">Area Lain</option>
                                </select>
                            </div>
                            <div class="fv-row mb-5">
                                <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                    Nama Lokasi Mesin
                                </label>
                                <input type="text" name="location_mesin_update" id="location_mesin_update"
                                    autocomplete="off" maxlength="50"
                                    class="form-control form-control-md form-control-solid" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light pull-left" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="delete-loc-mesin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Hapus Data Lokasi Mesin</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-light"> <i
                                class="fas fa-times text-secondary fs-2"></i></span>
                    </div>
                </div>
                <form action="{{ url('loc-mesin-delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="idloc_delete" id="idloc_delete">
                    <div class="modal-body text-center">
                        <h1 style="text-primary">Warning!!</h1>
                        <p id="p-del"></p>
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
            $("#searchMesin").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#mesinTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $("#mesinTable").on("click", ".edit", function(){
                var id = $(this).attr('data-id');
                console.log(id);

                $.ajax({
                    url: "{{ url('get-location') }}?id=" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#idloc_update').val(data.id_loc_mesin);
                        $('#location_mesin_update').val(data.location_mesin);
                        if (data.kategori_loc == 'Plant 1') {
                            $("#kategori_loc_update").val("Plant 1").change();
                        } else if (data.kategori_loc == 'Plant 2') {
                            $("#kategori_loc_update").val("Plant 2").change();
                        } else {
                            $("#kategori_loc_update").val("Area Lain").change();
                        }
                        $('#update-loc-mesin').modal('show');
                    }
                });
            });

            $("#mesinTable").on("click", ".delete", function(){
                var id = $(this).attr('data-id');

                $.ajax({
                    url: "{{ url('get-location') }}?id=" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#idloc_delete').val(data.id_loc_mesin);
                        $('#p-del').html('Are you sure to delete <strong>' + data
                            .location_mesin + '?</strong>');
                        $('#delete-loc-mesin').modal('show');
                    }
                });
            });
        });
    </script>
@endsection
