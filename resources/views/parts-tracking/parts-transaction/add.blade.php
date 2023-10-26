@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
.panel-heading .accordion-toggle:after {
    /* symbol for "opening" panels */
    font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
    content: "\e114";    /* adjust as needed, taken from bootstrap.css */
    float: right;        /* adjust as needed */
    color: grey;         /* adjust as needed */
}
.panel-heading .accordion-toggle.collapsed:after {
    /* symbol for "collapsed" panels */
    content: "\e080";    /* adjust as needed, taken from bootstrap.css */
}
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
    .timeline {
    position: relative;
    padding: 21px 0px 10px;
    margin-top: 4px;
    margin-bottom: 30px;
}

.timeline .line {
    position: absolute;
    width: 4px;
    display: block;
    background: currentColor;
    top: 0px;
    bottom: 0px;
    margin-left: 30px;
}

.timeline .separator {
    border-top: 1px solid currentColor;
    padding: 5px;
    padding-left: 40px;
    font-style: italic;
    font-size: .9em;
    margin-left: 30px;
}

.timeline .line::before { top: -4px; }
.timeline .line::after { bottom: -4px; }
.timeline .line::before,
.timeline .line::after {
    content: '';
    position: absolute;
    left: -4px;
    width: 12px;
    height: 12px;
    display: block;
    border-radius: 50%;
    background: currentColor;
}

.timeline .panel {
    position: relative;
    margin: 10px 0px 21px 70px;
    clear: both;
}

.timeline .panel::before {
    position: absolute;
    display: block;
    top: 8px;
    left: -24px;
    content: '';
    width: 0px;
    height: 0px;
    border: inherit;
    border-width: 12px;
    border-top-color: transparent;
    border-bottom-color: transparent;
    border-left-color: transparent;
}

.timeline .panel .panel-heading.icon * { font-size: 20px; vertical-align: middle; line-height: 40px; }
.timeline .panel .panel-heading.icon {
    position: absolute;
    left: -59px;
    display: block;
    width: 40px;
    height: 40px;
    padding: 0px;
    border-radius: 50%;
    text-align: center;
    float: left;
}

.timeline .panel-outline {
    border-color: transparent;
    background: transparent;
    box-shadow: none;
}

.timeline .panel-outline .panel-body {
    padding: 10px 0px;
}

.timeline .panel-outline .panel-heading:not(.icon),
.timeline .panel-outline .panel-footer {
    display: none;
}
</style>

@endsection

@section('content')

<div class="content-body-white">
    @if(session()->has('err_message'))
        <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session()->get('err_message') }}
        </div>
    @endif
    @if(session()->has('suc_message'))
        <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session()->get('suc_message') }}
        </div>
    @endif
    <div class="page-head">
        <div class="page-title">
            <!-- <h1>Parts Transactions </h1> -->
        </div>
    </div>
    <div class="row">
        
    <form method="post" action="{{url('parts-transaction-insert')}}" enctype="multipart/form-data">
          {{csrf_field()}}
        <input type="hidden" name="id" id="slot" value="{{ $data['cus_id'] }}">
        <input type="hidden" name="mwp" id="mwp">
        <input type="hidden" name="status" value="1">
        <input type="hidden" name="tgl_transaksi" value="{{ date('Y-m-d H:i:s') }}">
        <div class="col-lg-6">

        <div class="form-group">
            <label>ID Transaksi</label>
            <input type="text" class="form-control" name="id_transaksi" id="id_transaksi" placeholder="Isi ID Transaksi" readonly required>

        </div>

        <div class="form-group">
            <label>Jenis Hose</label>
            <select name="hose_transaksi" id="hose_transaksi" class="form-control selectpicker" data-live-search="true" onchange="onCheck()" required>
                @foreach ($data['jenishose'] as $row)
                    <option value="{{ $row->id_jhose }}">{{ $row->nama_hose }}</option>
                @endforeach
        </select>
        </div>
        <div class="form-group">
            <label>Konfigurasi Hose</label>
            <select name="konf_transaksi" id="konf_transaksi" class="form-control selectpicker" data-live-search="true" onchange="onCheck()" required>
                @foreach ($data['konfhose'] as $row)
                    <option value="{{ $row->id_khose }}">{{ $row->nama_khose }}</option>
                @endforeach
        </select>
        </div>
        <div class="form-group">
            <label>Diameter</label>
            <select name="diameter" id="diameter" class="form-control selectpicker" data-live-search="true" onchange="onCheck()" required>
            <option value="">Nothing selected</option>
                @foreach ($data['diameter'] as $row)
                    <option value="{{ $row->id_diameter }}">{{ $row->ukuran_diameter }}</option>
                @endforeach
        </select>
        </div>
        <div class="form-group">
            <label>Panjang Hose</label>
            <input type="text" class="form-control" name="panjang" id="panjang" placeholder="Silahkan Isi Manual !" onchange="onCheck()" required>
        </div>
        </div>
        <div class="col-lg-6">
        <div class="form-group">
            <label>Part Number Hose Assembly</label>
            <input type="text" class="form-control" name="pn_assy" id="pn_assy" placeholder="Generate Otomatis" readonly required>
        </div>
        <div class="form-group">
            <label>Aplikasi Hose</label>
            <!-- <input type="text" class="form-control" name="aplikasi" placeholder="Silahkan Isi Manual !" required> -->
            <select name="aplikasi" id="aplikasi" class="form-control selectpicker" data-live-search="true" required>
                @foreach ($data['aplikasi'] as $row)
                    <option value="{{ $row->id_app }}">{{ $row->nama_app }}</option>
                @endforeach
        </select>
        </div>
        <div class="form-group">
            <label>Lokasi Install Hose</label>
            <select name="lokasi" id="lokasi" class="form-control selectpicker" data-live-search="true" required>
                @foreach ($data['lokasi'] as $row)
                    <option value="{{ $row->id_lokasi }}">{{ $row->nama_lokasi }}</option>
                @endforeach
        </select>
        </div>

        <div class="form-group">
            <label>Lifetime</label>
            <select name="lifetime" id="lifetime" class="form-control selectpicker" data-live-search="true" required>
                @foreach ($data['lifetime'] as $row)
                    <option value="{{ $row->jml }}">{{ $row->ket }} Tahun</option>
                @endforeach
        </select>
        </div>
        <div class="form-group">
            <label>Serial Number Unit</label>
            <select name="sn_unit" id="sn_unit" class="form-control selectpicker" data-live-search="true" required>
                @foreach ($data['snunit'] as $row)
                    <option value="{{ $row->id_unit }}">{{ $row->sn_unit }}</option>
                @endforeach
        </select>
        </div>
        <input type="hidden" name="customer" value=" ">
        <br><br>
        </div>
        <div class="col-lg-6">
        <div class="form-group">
            <label>Fitting1</label>
            <select name="fitting1" id="fitting1" class="form-control selectpicker" data-live-search="true" onchange="onCheck()" required>
            <option value="">Nothing selected</option>
                @foreach ($data['fitting'] as $row)
                    <option value="{{ $row->id_fitting }}">{{ $row->nama_fitting }}</option>
                @endforeach
        </select>
        </div>
        <div class="form-group">
            <label>Fitting2</label>
            <select name="fitting2" id="fitting2" class="form-control selectpicker" data-live-search="true" onchange="onCheck()">
            <option value=""></option>
        </select>
        </div>
        <div class="form-group">
            <label>Ukuran Fitting1</label>
            <select  id="ukuran_fitting1" name="ukuran1" class="form-control selectpicker" data-live-search="true"  onchange="onCheck()" required>
                <option value=""></option>
        </select>
        </div>
        <div class="form-group">
            <label>Ukuran Fitting2</label>
            <select  id="ukuran_fitting2" name="ukuran2" class="form-control selectpicker" data-live-search="true" required>
            
            <option value=""></option>
        </select>
        </div>
        </div>
        <div class="col-lg-6">
        <fieldset class="form-group">
            <div class="row">
            <legend class="col-form-label col-sm-2 pt-0">Kondisi</legend>
            <div class="col-sm-10">
                <div class="form-check">
                <input class="form-check-input" type="radio" name="kondisi_transaksi" id="gridRadios1" value="Normal" checked>
                <label class="form-check-label" for="gridRadios1">
                    Normal
                </label>
                </div>
                <div class="form-check">
                <input class="form-check-input" type="radio" name="kondisi_transaksi" id="gridRadios2" value="Broken">
                <label class="form-check-label" for="gridRadios2">
                    Broken
                </label>
                </div>
            </div>
            </div>
        </fieldset>
        <input type="checkbox" id="myCheck" onclick="GetPNAssy()" required>
        <label for="myCheck"> Ceklis disini untuk generate Product Number Hose Assembly :</label><br>

        <center><button type="submit" class="btn btn-info">Submit</button></center>
        </div>
    </div>
    
    </form>
</div>


@endsection

@section('myscript')
<script>
		function GetPNAssy(){

			var khose = document.getElementById("konf_transaksi").value;
			var fitting1 = document.getElementById("fitting1").value;
			var ukuran1 = document.getElementById("ukuran_fitting1").value;
			var fitting2 = document.getElementById("fitting2").value;
			var ukuran2 = document.getElementById("ukuran_fitting2").value;
			var jhose = document.getElementById("hose_transaksi").value;
			var diameter = document.getElementById("diameter").value;
			var panjang = document.getElementById("panjang").value/10;
			var checkBox = document.getElementById("myCheck");
			var slot = document.getElementById("slot").value;
			var sn_unit = document.getElementById("sn_unit").value;
			var lifetime = document.getElementById("lifetime").value;
			var lokasi = document.getElementById("lokasi").value;
			if (checkBox.checked == true){
				if(diameter == '') {
					alert("Warning! Diameter Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if (panjang == '') {
					alert("Warning! Panjang Hose Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if(fitting1 == '') {
					alert("Warning! Fitting1 Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if(fitting2 == '') {
					alert("Warning! Fitting2 Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if(ukuran1 == '') {
					alert("Warning! Ukuran Fitting 1 Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if(ukuran2 == '') {
					alert("Warning! Ukuran Fitting 2 Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if(lokasi == '') {
					alert("Warning! Lokasi Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if(lifetime == '') {
					alert("Warning! Lifetime Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else if(sn_unit == '') {
					alert("Warning! SN Unit Harus Diisi");
					document.getElementById("myCheck").checked = false;
				}else{
	  				document.getElementById("pn_assy").value = 'B-H'+khose+fitting1+ukuran1+fitting2+ukuran2+jhose+diameter+panjang;
		  			document.getElementById("id_transaksi").value = 'B-H'+khose+fitting1+ukuran1+fitting2+ukuran2+jhose+diameter+panjang+slot;
				}
			}else{
		  		document.getElementById("pn_assy").value = null;
		  		document.getElementById("id_transaksi").value = null;
			}

		}

		function onCheck(){
				var fitting1 = document.getElementById("fitting1").value;
		 		var fitting2 = document.getElementById("fitting2").value;
		 		var ukuran1 = document.getElementById("ukuran_fitting1").value;
		 		var khose = document.getElementById("konf_transaksi").value;
		 		var jhose = document.getElementById("hose_transaksi").value;
		 		var diameter = document.getElementById("diameter").value;

				$.ajax({
				 url:APP_URL+'/parts-fetch-mwp/'+jhose+"/"+diameter,
				 method:"GET",
				 data:{},
				 success:function(data){
							$('#mwp').html(data);
							document.getElementById("mwp").value = data;

							console.log(data);
				 }
				});
				if(khose == '1'){

					if(fitting1 == fitting2){
						console.log("sama");
						console.log(document.getElementById("fitting2").value);
						console.log(document.getElementById("ukuran_fitting1").value);
						$.ajax({
						 url:APP_URL+'/parts-fetch-fitting-size/'+document.getElementById("fitting2").value+"/"+document.getElementById("ukuran_fitting1").value,
						 method:"GET",
						 data:{},
						 success:function(data){
									$('#ukuran_fitting2').html(data).selectpicker('refresh');

									console.log(data);
						 }
						});
					}else{

							 $('#fitting1').change(function(){
											// if(ukuran1==''){

											console.log("uk1");
												$.ajax({
												 url:APP_URL+'/parts-fetch-fitting-size/'+document.getElementById("fitting1").value+"/0",
												 method:"GET",
												 data:{},
												 success:function(data){
															$('#ukuran_fitting1').html(data).selectpicker('refresh');
												 }
												});

											});

											console.log("beda ni");
												$.ajax({
												 url:APP_URL+'/parts-fetch-fitting-size/'+document.getElementById("fitting2").value+"/0",
												 method:"GET",
												 data:{},
												 success:function(data){
															$('#ukuran_fitting2').html(data).selectpicker('refresh');

												 }
												});

					}
				}else{
						if(ukuran1==''){
					console.log("elbow");
						$.ajax({
						 url:APP_URL+'/parts-fetch-fitting-size/'+document.getElementById("fitting1").value+"/0",
						 method:"GET",
						 data:{},
						 success:function(data){
									$('#ukuran_fitting1').html(data).selectpicker('refresh');
						 }
						});
					}
						console.log("elbow");
						$.ajax({
						 url:APP_URL+'/parts-fetch-fitting-size/'+document.getElementById("fitting2").value+"/0",
						 method:"GET",
						 data:{},
						 success:function(data){
									$('#ukuran_fitting2').html(data).selectpicker('refresh');

						 }
						});
				}
			document.getElementById("myCheck").checked = false;
			document.getElementById("pn_assy").value = null;
			document.getElementById("id_transaksi").value = null;

		}
		function buttonVal(){
			  if (checkBox.checked == false){
			  	alert("Warning! Mohon Ceklist Generate Part Number Assambly");
				document.getElementById("myCheck").checked = false;
			  }
		}

</script>
<script type="text/javascript">


	$(document).ready(function(){
        
        APP_URL = '{{url('/')}}' ;
		$('#fitting1').change(function(){

		 		var khose = document.getElementById("konf_transaksi").value;
				if(khose == '1'){

					var fitting1 = document.getElementById("fitting1").value;

					console.log(document.getElementById("konf_transaksi").value);
					$.ajax({
					 url:APP_URL+'/parts-fetch-fitting/'+document.getElementById("fitting1").value,
					 method:"GET",
					 data:{},
					 success:function(data){
								$('#fitting2').html(data).selectpicker('refresh');

					 }
					});
				}else{
					$.ajax({
					 url:APP_URL+'/parts-fetch-fitting/0',
					 method:"GET",
					 data:{},
					 success:function(data){
								$('#fitting2').html(data).selectpicker('refresh');

					 }
					});


				}

		});



		$('#konf_transaksi').change(function(){
		  	var khose = document.getElementById("konf_transaksi").value;
						if(khose == '1'){
							console.log(document.getElementById("konf_transaksi").value);
							$.ajax({
							 url:"url-index.php/posts/fetch_fitting/"+document.getElementById("fitting1").value,
							 method:"GET",
							 data:{},
							 success:function(data){
										$('#fitting2').html(data).selectpicker('refresh');

							 }
							});
						}else{
							$.ajax({
							 url:"url-index.php/posts/fetch_fitting/",
							 method:"GET",
							 data:{},
							 success:function(data){
										$('#fitting2').html(data).selectpicker('refresh');

							 }
							});
							alert("Info! Pastikan bahwa Fitting1 adalah jenis Fitting yang Straight");
							document.getElementById("info").value = "Pastikan bahwa Fitting1 adalah jenis Fitting yang Straight";
							document.getElementById("ukuran_fitting1").value=null;
						}
			});
			$(window).bind("load", function() {
				window.setTimeout(function() {
					$(".alertflash").fadeTo(1000, 0).slideUp(1000, function() {
						$(this).remove();
					});
				}, 1000);
			});
		});

</script>

@endsection