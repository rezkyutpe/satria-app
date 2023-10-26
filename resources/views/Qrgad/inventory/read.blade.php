<div class="table-responsive">
    <table id="table" class="display table table-striped table-hover dataTable" >
        <thead class="bg-primary text-white">
            <tr>
                <td class="text-center">#</td>
                <td style="text-align: center">Kode Konsumable</td>
                <td style="text-align: center">Nama Konsumable</td>
                <td style="text-align: center">Kategori</td>
                <td style="text-align: center">Sub Kategori</td>
                <td style="text-align: center">Stock</td>
                <td style="text-align: center">Last Entry</td>
                <td style="text-align: center">Aksi</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($tabelinventory as $ti)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="text-align: center" >{{ $ti->id_konsumable }}</td>
                    <td>{{ $ti->nama_konsumable }}</td>
                    <td>{{ $ti->kategori_konsumable }}</td>
                    <td>{{ $ti->sub_kategori_konsumable }}</td>
                    <td style="text-align: center">
                        @if ($ti->stock == "")
                             <span class="badge badge-danger">0 {{ $ti->satuan }}</span>
                        @else
                            @if ($ti->stock <= $ti->minimal_stock)
                                <span class="badge badge-danger"> {{ $ti->stock }} {{ $ti->satuan }} </span>
                            @else
                                <span class="badge badge-success"> {{ $ti->stock }} {{ $ti->satuan }} </span>
                            @endif
                        @endif
                    </td>
                    <td style="text-align: center">{{ $ti->last_entry }}</td>
                    <td style="text-align: center">
                        <div class="form-button-action">
                            <a href="{{ url('/konsumable') }}/{{ $ti->id_konsumable }}/edit" type="button" data-toggle="tooltip" title="" class="btn btn-link btn-warning btn-lg" data-original-title="Ubah">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ '#' }}" type="button" data-value="{{ $ti->id_konsumable }}" data-toggle="modal" data-target="#modalDelete" rel="tooltip" onclick="$('#modalDelete #formDelete').attr('action','{{ url('/konsumable/'. $ti->id_konsumable) }}' )" class="delete-modal btn btn-link btn-danger" data-original-title="Hapus">
                                <i class="fa fa-times"></i>
                            </a>
                            <a href="{{ url('/inventory-tambah') }}/{{ $ti->id_konsumable }}" type="button" data-toggle='tooltip' title="" class="btn btn-link btn-success btn-lg" data-original-title="Tambah Inventory">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<script>
    datatable();
    tooltip();
</script>