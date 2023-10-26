<div class="modal-header">
    <h4 class="modal-title">
        Reject Request Kendaraan
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="card shadow">
		<div class="card-header">
			<h4 class="fw-bold">Yakin membatalkan perjalanan ini ?</h4>
		</div>
		<div class="card-body">
			<div class="row py-1 container">
				<div class="col d-flex flex-column justify-content-around">
					<div>
						<h4 class="fw-bold">Pemohon</h4>
						<span>{{ $trip->pemohon }}</span>
					</div>
					<br>
					<div>
						<h4 class="fw-bold">Keperluan</h4>
						<span>{{ $trip->keperluan }}</span>
					</div>
					<br>
					<div>
						<h4 class="fw-bold">Jenis Perjalanan</h4>
						<span>
							@php 
								switch ($trip->jenis_perjalanan) {
									case 1:
									echo "One Way" ;
									break;
									case 2:
									echo "Round Trip" ;
									break;
								} 
								@endphp
						</span>
					</div>
					<br>
					<div>
						<h4 class="fw-bold">Penumpang</h4>
						@if ($trip->penumpang == '')
							{{ '-' }}
						@else
							<ul>
								@foreach (explode(",", $trip->penumpang) as $p)
									<li>
										<span>{{ $p }}</span><br>
									</li>
								@endforeach
							</ul>
						@endif
					</div>
					<br>
				</div>
				<div class="col d-flex flex-column justify-content-around">
					<div>
						<h4 class="fw-bold">Tujuan</h4>
						<span>{{ $trip->tujuan.", ".$trip->wilayah }}</span>
					</div>
					<br>
					<div>
						<h4 class="fw-bold">Berangkat</h4>
						<span>{{ date("d M Y H:i",strtotime($trip->waktu_berangkat)) }}</span>
					</div>
					<br>
					<div>
						<h4 class="fw-bold">Pulang</h4>
						@if ($trip->waktu_pulang != null)
							<span>{{ date("d M Y H:i",strtotime($trip->waktu_pulang)) }}</span>
						@else
							<span> - </span>
						@endif
					</div>
					<br>
				</div>
			</div>
			<div class="form-group mt-3">
				<label for="note" class="mandatory">Catatan</label>
				<textarea name="note" id="note" rows="5" class="form-control mb-3" placeholder="Catatan untuk pemohon"></textarea>
				<div id="message" class="invalid-feedback mb-3">Wajib diisi</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
    <div class="inline">
        <button class="btn btn-danger float-right" onclick="cancel('{{ $trip->id_trip }}')">Cancel</button>
        <button class="btn btn-secondary float-right mr-1" data-dismiss="modal">Batal</button>
    </div>
</div>

<script>
    mandatory();
</script>

