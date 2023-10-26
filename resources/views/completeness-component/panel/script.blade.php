<!-- jQuery -->
<script src="{{ asset('public/assetss/datatable/js/jquery-3.5.1.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('public/assetss/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ asset('public/assetss/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- DateJS -->
<script src="{{ asset('public/assetss/vendors/DateJS/build/date.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ asset('public/assetss/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- Custom Theme Scripts -->
<script src="{{ asset('public/assetss/build/js/custom.min.js') }}"></script>
<!-- Datatables -->
<script src="{{ asset('public/assetss/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/jszip.min.js') }}"></script>

{{-- Sweeetalert --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#btn-delete', function() {
            event.preventDefault();
            const url = $(this).attr("data-url");
            Swal.fire({
                title: 'Are you sure?',
                text: "You are attempting to log out of system",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logout-form').submit();
                } else {
                    return false;
                }
            })
        });
    });
</script>

{{-- Toast --}}
<script src="{{ asset('public/assetss/js/toastr.min.js') }}"></script>
<script>
    @if(Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-custom",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "50000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr.error("{{ session('error') }}", "{{ session('title') }}");
    @endif
    @if(Session::has('success'))
        toastr.options = {
            "closeButton": true,
            "debug": true,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-custom",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "50000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr.success("{{ session('success') }}", "{{ session('title') }}");
    @endif
</script>

{{-- select2 --}}
<script src="{{ asset('public/assetss/js/select2.min.js') }}"></script>
<script>
    $('.select2').select2({
        placeholder: 'Select an option'
    });
    $('.select2-multiple').select2({
        placeholder: 'Select an option',
        multiple: true,
        minimumResultsForSearch: Infinity
    });
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

{{-- Chat PO Tracking --}}
<script>
    function getChat(val,vol){
        var out = document.getElementById("chat-content");
        $.ajax({
            url : "{{url('cek-coment-potracking-ccr')}}?number="+val+"&item="+vol,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('#datachat').empty();
                $('#datasubmit').empty();
                $('#header').empty();
                $("#header").append
                (`
                   PO No. : `+val+` | Item Number : `+vol+`<br> Material : `+data.Po.Material+` | Vendor : `+data.Po.Vendor+`
                `);

                for(i=0;i<data.datar.length;i++)
                {
                    var date            = new Date(data.datar[i].created_at);
                    var dd              = String(date.getDate()).padStart(2, '0');
                    var mm              = String(date.getMonth() + 1).padStart(2, '0');
                    var yyyy            = date.getFullYear();
                    var tanggalcoment   = dd + '/' + mm + '/' + yyyy;
                    let time            = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
                    
                    if(data.datar[i].is_read == 1){
                        var $status = "Baca" ;
                    }else{
                        var  $status = "" ;
                    }

                    if(data.datar[i].user_by == data.Name){
                        $("#datachat").append
                        (`
                            <div class="outgoing_msg">
                            <div class="sent_msg">
                                <p>`+data.datar[i].comment+`</p>
                                <span class="time_date" style="font-size:10px;">`+data.datar[i].user_by+` | `+tanggalcoment+` | `+time+` &nbsp; &nbsp;&nbsp; `+status+`</span> </div>
                            </div>
                        `);
                    }else{
                        $("#datachat").append
                        (`
                            <div class="incoming_msg">
                                <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p>`+data.datar[i].comment+`</p>
                                    <span class="time_date" style="font-size:10px;">`+data.datar[i].user_by+` | `+tanggalcoment+` | `+time+`</span> </div>
                                </div>
                            </div>
                        `);
                    }
                }

                $("#datasubmit").append
                (`
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-11">
                            <input type="text" name="comment" id="entersave"  placeholder="Type a message" required>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-primary" id="id_of_button" onclick="Save()"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                        </div>
                    </div>
                `);

                $("#entersave").keyup(function(event) {
                    if (event.keyCode === 13) {
                        $("#id_of_button").click();
                    }
                });

                $('#names').val(data.Name);
                $('#numbers').val(val);
                $('#vendors').val(data.Po.Vendor);
                $('#items').val(vol);
                $('#proc').val(data.Po.NRP);
                $('#chatings').modal('show');
            }
        });
    }

    function Save() {
        var number    = $("input[name=numbers]").val();
        var item      = $("input[name=items]").val();
        var namer     = $("input[name=namer]").val();
        var comment   = $("input[name=comment]").val();
        var vendors   = $("input[name=vendors]").val();
        var proc      = $("input[name=proc]").val();

        $.ajax({
            url: "{{ url('insert-comment-potracking-ccr') }}",
            type: "POST",
            data: {
                Number:number,
                Vendor:vendors,
                Comment:comment,
                Proc:proc,
                Item:item,
                Name:namer
            },
            success:function(data){
                var out = document.getElementById("chat-content");
                $('#chat-content').scrollTop(out.scrollHeight+500);
                $("input[name=comment]").val("");
                getChat(number,item);
            },

        });
    }
</script>

<script>
    $(document).ready(function() {
        // Komentar GI On Going
        $('#myTable tbody').on('change', '.komentar-material-ongoing', function() {
            var id_material       = $(this).find(':selected').attr('data-id');
            var value_komentar    = $(this).find(':selected').val();
            if (value_komentar != '' || value_komentar != null) {
                $.ajax({
                    url  : "{{ url('create-comment-pro') }}",
                    type : "POST",
                    data :{
                        id_material    : id_material,
                        value          : value_komentar
                    },
                    success: function(data) {
                        if (data.kode == 1) {
                            toastr.success("Data successfully updated", "Success !!");
                        }else{
                            toastr.error("Data failed to save", "Error !!");
                        }
                    }
                });           
            }
        });
        
        // Komentar GI History
        $('#myTable tbody').on('change', '.komentar-material-history', function() {
            var id_material       = $(this).find(':selected').attr('data-id');
            var value_komentar    = $(this).find(':selected').val();
            if (value_komentar != '' || value_komentar != null) {
                $.ajax({
                    url : "{{ url('create-comment-pro') }}",
                    type    : "POST",
                    data:{
                        id_material_history : id_material,
                        value               : value_komentar
                    },
                    success: function(data) {
                        if (data.kode == 1) {
                            toastr.success("Data successfully updated", "Success !!");
                        }else{
                            toastr.error("Data failed to save", "Error !!");
                        }
                    }
                });           
            }
        });

        // Modal detail Stock
        $('#myTable tbody').on('click', '.detailStock', function() {
            var material_number = $(this).attr('data-material-number');
            $.ajax({
                url: "{{ url('detail-stock-ccr') }}?material=" + material_number,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#detailStock').empty();
                    $('#dataMaterialStock').empty();

                    $('#dataMaterialStock').html(
                        data.deskripsi.material_number + ' - ' + data.deskripsi.material_desc
                    );

                    if (data.detail_stock.length == 0) {
                        $("#detailStock").append(`
                            <tr>
                                <td colspan= "4">No Data Available</td>
                            </tr>
                        `);
                    } else {
                        for (i = 0; i < data.detail_stock.length; i++) {
                            no = i + 1;
                            $("#detailStock").append(`
                                <tr>
                                    <td>` + no + `</td>
                                    <td>` + data.detail_stock[i].plant + `</td>
                                    <td>` + data.detail_stock[i].storage_location + `</td>
                                    <td>` + data.detail_stock[i].stock + `</td>
                                </tr>
                            `);
                        }
                    }
                    $('#StockModal').modal('show');
                }
            });
        }); 

        // Modal Plotting Stock 
        $('#myTable tbody').on('click', '.plottingStock', function() {
            var material_number = $(this).attr('data-material-number');
            $.ajax({
                url: "{{ url('ccr-detail-plotting-stock') }}?material_number=" + material_number,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#detailPlotting').empty();
                    $('#dataMaterial').empty();

                    $('#dataMaterial').html(
                        data.desc.material_number + ' - ' + data.desc.material_description
                    );

                    if (data.minus.length == 0) {
                        if (data.stock.stock == 0) {
                            $("#detailPlotting").append(`
                                <tr>
                                    <td colspan= "7">No Data Available ( Stock 0 )</td>
                                </tr>
                            `);
                        } else {
                            $("#detailPlotting").append(`
                                <tr>
                                    <td colspan= "7">No Data Available</td>
                                </tr>
                            `);
                        }
                    } else {
                        for (i = 0; i < data.minus.length; i++) {
                            no = i + 1;
                            var production_order    = data.minus[i].production_order;
                            
                            var product_number      = data.minus[i].product_number;
                            
                            var date                = new Date(data.minus[i].requirement_date);
                            var tanggal             = String(date.getDate()).padStart(2, '0');
                            var bulan               = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var tahun               = date.getFullYear();
                            var requirement_date    = tanggal + '-' + bulan + '-' + tahun;

                            $("#detailPlotting").append(`
                                <tr>
                                    <td>` + no + `</td>
                                    <td>` + production_order + `</td>
                                    <td>` + product_number + `</td>
                                    <td>` + requirement_date + `</td>
                                    <td>` + data.minus[i].requirement_quantity + `</td>
                                    <td>` + data.minus[i].alokasi_stock + `</td>
                                    <td>` + data.minus[i].sisa_stock + `</td>
                                </tr>
                            `);
                        }
                    }
                    $('#plottingStockModal').modal('show');
                }
            });
        }); 

        // modal detail minus / shortage
        $('#myTable tbody').on('click', '.detailMinus', function() {
            var matnr   = $(this).attr('data-matnrMinus');
            var date    = $("#sesi_date").val();
            $.ajax({
                url                                   : "{{ url('detail-minus-ccr') }}",
                data: {matnrMinus: matnr, date:date},
                type                                  : "GET",
                dataType                              : "JSON",
                success: function(data) {
                    $('#detailDataMinus').empty();
                    $('#judulMinus').empty();

                    $('#judulMinus').html(
                        data.desc.material_number + ' - ' + data.desc.material_description
                    );

                    if (data.minus.length == 0) {
                        $("#detailDataMinus").append(`
                            <tr>
                                <td colspan           = "9">No Data Available</td>
                            </tr>
                        `);
                    } else {
                        for (b = 0; b < data.minus.length; b++) {
                            num                       = b + 1;
                            var date                  = new Date(data.minus[b].BDTER);
                            var tanggal               = String(date.getDate()).padStart(2, '0');
                            var bulan                 = String(date.getMonth() + 1).padStart(2,'0'); //January is 0!
                            var tahun                 = date.getFullYear();
                            var req_date              = tanggal + '-' + bulan + '-' + tahun;
                            var tanggal_dibutuhkan    = data.minus[b].BDTER == null ? '-' : req_date;

                            $("#detailDataMinus").append(`
                                <tr>
                                    <td>` + num + `</td>
                                    <td>` + data.minus[b].AUFNR + `</td>
                                    <td>` + data.minus[b].PLNBEZ + `</td>
                                    <td class= "text-left">` + data.minus[b].DESC_PLNBEZ + `</td>
                                    <td>` + tanggal_dibutuhkan + `</td>
                                    <td>` + data.minus[b].BDMNG + `</td>
                                    <td>` + data.minus[b].ENMNG + `</td>
                                    <td>` + data.minus[b].RESERVE + `</td>
                                    <td>` + data.minus[b].MINUS_PLOTTING + `</td>
                                </tr>
                            `);
                        }
                    }
                    $('#modalMinus').modal('show');
                }
            });
        }); 

        // modal detail SN 
        $('#myTable tbody').on('click', '.detailSN', function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ url('detailSN') }}?id=" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    var group_product         = data.data_sn.group_product;
                    var start_date            = '-';
                    var finish_date           = '-';
                    var create_date_pro       = '-';
                    var act_release           = '-';
                    var status                = data.data_sn.status;

                    if (data.data_sn.sch_start_date != null) {
                        var tgl_start   = new Date(data.data_sn.sch_start_date);
                        var tanggal     = String(tgl_start.getDate()).padStart(2, '0');
                        var bulan       = String(tgl_start.getMonth() + 1).padStart(2,'0'); //January is 0!
                        var tahun       = tgl_start.getFullYear();
                        start_date      = tanggal + '-' + bulan + '-' + tahun;
                    }else{
                        start_date      = '-';
                    }

                    if (data.data_sn.sch_finish_date != null) {
                        var tgl_gltrp   = new Date(data.data_sn.sch_finish_date);
                        var tanggal2    = String(tgl_gltrp.getDate()).padStart(2, '0');
                        var bulan2      = String(tgl_gltrp.getMonth() + 1).padStart(2,'0'); //January is 0!
                        var tahun2      = tgl_gltrp.getFullYear();
                        finish_date     = tanggal2 + '-' + bulan2 + '-' + tahun2;
                    }else{
                        finish_date     = '-';
                    }

                    if (data.data_sn.create_date_pro != null) {
                        var datum       = new Date(data.data_sn.create_date_pro);
                        var tanggal3    = String(datum.getDate()).padStart(2, '0');
                        var bulan3      = String(datum.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun3      = datum.getFullYear();
                        create_date_pro       = tanggal3 + '-' + bulan3 + '-' + tahun3;
                    }else{
                        create_date_pro       = '-';
                    }

                    if (data.data_sn.act_release != null) {
                        var release_date    = new Date(data.data_sn.act_release);
                        var tanggal4        = String(release_date.getDate()).padStart(2, '0');
                        var bulan4          = String(release_date.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun4          = release_date.getFullYear();
                        act_release               = tanggal4 + '-' + bulan4 + '-' + tahun4;
                    }else{
                        act_release               = '-';
                    }

                    if (data.data_sn.date_status_created != null) {
                        var status_date       = new Date(data.data_sn.date_status_created);
                        var tanggal5          = String(status_date.getDate()).padStart(2, '0');
                        var bulan5            = String(status_date.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun5            = status_date.getFullYear();
                        date_status_created   = tanggal5 + '-' + bulan5 + '-' + tahun5;
                    }else{
                        date_status_created = '-';
                    }


                    if (group_product == null) {
                        group_product = '-';
                    }

                    if (status == 'CRTD') {
                        status = 'CRTD';
                    } else if (status == 'REL') {
                        status = 'CRTD REL';
                    } else if (status == 'PDLV') {
                        status = 'CRTD REL PDLV';
                    } else if (status == 'DLV') {
                        status = 'CRTD REL DLV';
                    } else if (status == 'TECO') {
                        status = 'CRTD REL DLV TECO'
                    } else if (status == 'CLSD') {
                        status = 'CRTD REL DLV TECO CLSD';
                    } else if (status == null) {
                        status = '-';
                    }

                    $('#proDetail').empty();
                    var html =
                        `   
                        <table class="table table-striped"  style="margin-top:-15px;margin-left:-15px;width:107%">
                            <tr>
                                <td>PRO Creator</td>
                                <td>:</td>
                                <td>` + data.data_sn.nama_creator + `</td>
                            </tr>
                            <tr>
                                <td>Total PRO Order</td>
                                <td>:</td>
                                <td>` + data.data_sn.quantity + `</td>
                            </tr>
                            <tr>
                                <td style="width: 27%">Production Order</td>
                                <td style="width: 1%">:</td>
                                <td>` + data.data_sn.production_order + `</td>
                            </tr>
                            <tr>
                                <td>Serial Number</td>
                                <td>:</td>
                                <td>` + data.data_sn.serial_number + `</td>
                            </tr>
                            <tr>
                                <td>Product Number</td>
                                <td>:</td>
                                <td>` + data.data_sn.product_number + `</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>:</td>
                                <td>` + data.data_sn.product_description + `</td>
                            </tr>
                            <tr>
                                <td>Group Product</td>
                                <td>:</td>
                                <td>` + group_product + `</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>` + status + `</td>
                            </tr>
                            <tr>
                                <td>Status Date</td>
                                <td>:</td>
                                <td>` + date_status_created + `</td>
                            </tr>
                            <tr>
                                <td>Create Date</td>
                                <td>:</td>
                                <td>` + create_date_pro + `</td>
                            </tr>
                            <tr>
                                <td>Release Date</td>
                                <td>:</td>
                                <td>` + act_release + `</td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td>:</td>
                                <td>` + start_date + `</td>
                            </tr>
                            <tr>
                                <td>Finish Date</td>
                                <td>:</td>
                                <td>` + finish_date + `</td>
                            </tr>`;
                    $('#proDetail').append(html);
                    $('#detailPROModal').modal('show');
                }
            });
        });
    });
</script>

{{-- Real time notification header --}}
{{-- <script> 
    $(document).ready(function(){
        setInterval(function(){
        $("#notifikasi-komentar").load(" #notifikasi-komentar");
        }, 30000);
    });
</script> --}}
