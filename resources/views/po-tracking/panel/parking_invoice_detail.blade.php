<div class="modal fade" id="ParkingInvoice_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-blue" id="Judulparking">
            </div>
            <div class="modal-body">
                <div id="datacreatorandvendor"></div>
                <form id="parking_detail_form" method="POST" action="{{url('parkinginvoice')}}" enctype="multipart/form-data">
                @csrf
                    <div id="dataFormProformaparking"></div>
                    <div class="cols float-right p-2" id="idwitax"></div>
                    <div class="table-responsive">
                        <table class="table nowrap table-striped text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Item No.<br><small>SPB Number</small></th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Doc Number</th>
                                    <th>Doc Item</th>
                                    <th>Qty Parking</th>
                                    <th>Price <div class="currency"></div></th>
                                    <th>Total Price <div class="currency"></div></th>
                                </tr>
                            </thead>
                            <tbody id="dataPOparking"></tbody>
                            <tfoot id="footer_table">
                                <tr>
                                    <td colspan="6"></td>
                                    <td class="text-left">Amount</td>
                                    <td id="totalprices" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td class="text-left">PPn 11%</td>
                                    <td id="ppns" class="text-right"></td>
                                </tr>
                                <tr id="pphs">
                                    <td colspan="6"></td>
                                    <td class="text-left">PPh 23</td>
                                    <td class="text-right" id="pph"></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td class="text-left font-weight-bold">Amount Total</td>
                                    <td class="text-right" id="totalharga"></td>
                                    <input id="totalharga_val" type="hidden" name="amount" value="">
                                </tr>
                                <tr id="tr_remark">
                                </tr>
                                <tr id="tr_reject">
                                    <td colspan="8">
                                        <label for="text_remark">Comment Rejection</label>
                                        <textarea class="text-left" name="remark_parking" style="width:100%" id="text_remark"></textarea>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-around">
                        <div class="form-group" id="downloadparking">
                        </div>
                        <div id="modalfooterparking"></div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.parkinginvoice_detail').on('click', function ()
        {
            $(this).append(`
                <div id="loading_button">
                    Loading<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </div>
            `);
            $('#tr_remark').hide();
            $('#tr_remark').empty();
            var id = $(this).attr('data-parking');
            var number = $(this).attr('data-created');
            $.ajax({
                url : "{{url('view_cariparking_detail')}}?inv_no="+id+"&created_at="+number,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('.currency').empty();
                    $('.currency').html('('+data.header.Currency+')');
                    $('#dataPOparking').empty();
                    $('#dataUploadProformaparking').empty();
                    $('#dataFormProformaparking').empty();
                    $('#idwitax').empty();
                    $(".datepicker").datepicker();
                    $('#modalfooterparking').empty();
                    $('#downloadparking').empty();
                    $('#Judulparking').empty();
                    $("#datacreatorandvendor").empty();
                    $('#proformauploudparking').empty();

                    var inv             = data.header.InvoiceDate;
                    var invoice         = new Date(inv);
                    var tanggale        = String(invoice.getDate()).padStart(2, '0');
                    var bulane          = String(invoice.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var tahune          = invoice.getFullYear();

                    var invoicedate    = tanggale + '/' + bulane + '/' + tahune;
                    var invoicemethod     = data.header.InvoiceNumber;
                    var reference         = data.header.Reference;
                    if(data.header.wi_tax_code == null){
                        var witaxcode = 'fa-square-o';
                    }else{
                        var witaxcode = 'fa-check-square-o';
                    }

                    $("#Judulparking").append(`<h6>Parking Invoice</h6>`);
                    $("#datacreatorandvendor").append(`
                            <div class="row justify-content-around">
                                <div class="col">
                                    PO Supplier : <b>`+data.header.VendorName+`</b>
                                </div>
                                <div class="col">
                                    PO Creator : <b>`+data.header.POCreator+`</b>
                                </div>
                            </div>
                            <hr>
                        `);
                    $("#dataFormProformaparking").append(`
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice Date :</label>
                                    <input id="invoice_date" type="text" class="form-control readonly" name="invoice_date" value="`+invoicedate+`" required>
                                    <input type="hidden" name="created_at" value="`+data.header.created_at+`" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="po_no">Purchase Order Number :</label>
                                    <div class="input-group">
                                        <input id="po_no" type="text" name="Number" class="form-control readonly" value="`+data.header.Number+`" required>
                                        <span class="input-group-btn">
                                            <button type="button" class="form-control btn btn-light" data-toggle="tooltip" title="Copy PO Number" onclick="copyToClipboard('`+data.header.Number+`')"><i class="fa fa-clone"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="invoice_no">Invoice Number :</label>
                                    <div class="input-group">
                                        <input id="invoice_no" type="text" name="invoice_no" class="form-control readonly" value="`+invoicemethod+`" required>
                                        <span class="input-group-btn">
                                            <button type="button" class="form-control btn btn-light" data-toggle="tooltip" title="Copy Invoice Number" onclick="copyToClipboard('`+invoicemethod+`')"><i class="fa fa-clone"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="tax_invoice_no">No.Faktur Pajak :</label>
                                    <div class="input-group">
                                        <input id="tax_invoice_no" type="text" name="reference" class="form-control readonly" value="`+reference+`" required >
                                        <span class="input-group-btn">
                                            <button type="button" class="form-control btn btn-light" data-toggle="tooltip" title="Copy No.Faktur Pajak" onclick="copyToClipboard('`+reference+`')"><i class="fa fa-clone"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    var taxname = '';
                    var taxcode = '';
                    for(i=0;i<data.tax_code.length;i++){
                        if(data.tax_code[i].tax_code == data.header.wi_tax_code){
                            taxcode = '<i class="fa fa-check"></i> '+data.tax_code[i].tax_code;
                            taxname = ' | '+data.tax_code[i].name;
                            break;
                        }
                    }
                    $("#idwitax").append(taxcode+taxname);
                    var totalprice = 0;
                    var ppn = 0;
                    var amounttotal = 0;
                    for(i=0;i<data.body.length;i++){
                        var total       = data.body[i].TotalPrice;
                        var totals      = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                        totalprice      += parseInt(total);
                        var totalprices = totalprice.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                        amounttotal         = data.header.GrossAmount;
                        var amounttotals    = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                        var price       = data.body[i].Price;
                        var prices      = price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                        $("#dataPOparking").append(`
                            <tr>
                                <td>`+data.body[i].ItemNumber+`<br><small>`+data.body[i].RefDocumentNumber+`</small></td>
                                <td class="text-left">`+data.body[i].Material+`</td>
                                <td class="text-left">`+data.body[i].Description+`</td>
                                <td>`+data.body[i].DocumentNumber+`</td>
                                <td>`+data.body[i].DocumentNumberItem+`</td>
                                <td>`+data.body[i].Qty+`</td>
                                <td class="text-right">`+prices+`</td>
                                <td class="text-right">`+totals+`</td>
                            </tr>
                        `);
                    }
                    //Amount
                    $('#totalprices').empty();
                    $('#totalprices').html(totalprices);

                    //PPh 23
                    $('#pph').empty();
                    if(data.header.wi_tax_code == null){
                        $('#pph').html(0);
                        $('#pphs').hide();
                    }else{
                        $('#pphs').show();
                        var pph = data.header.PPH.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                        $('#pph').html('-'+pph.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                    }

                    //PPn 11%
                    var ppns = data.header.PPN.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    $('#ppns').empty();
                    $('#ppns').html(ppns);

                    //Amount Total
                    var amounttotals = data.header.AmountTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    $('#totalharga').empty();
                    $('#totalharga').html(amounttotals);

                    $("#downloadparking").append(`
                        <label for="filename">File Parking Invoice </label><br>
                    `);
                    for(j=0;j<data.document.length;j++){
                        $("#downloadparking").append(`
                            <a class="btn btn-danger btn-sm" href="{{ url('public/potracking/parking_invoice/`+data.header.Number+`/`+data.document[j].FileName+`') }}"> <i class="fa fa-download"> </i> `+data.document[j].FileName+`</a>
                        `);
                    }
                    if(data.header.Status == "Approve Parking"){
                        var color = "success";
                        var fungsi = "button";
                        var remark = "#";
                        var ico = "fa-check";
                        var reject_button = ``;
                        var textnya = "Parking Approved";
                        $('#tr_reject').hide();
                        var identitas_tombol = 'approved_btn';
                    }
                    else if(data.header.Status == "Reject Parking"){
                        var color = "danger";
                        var fungsi = "button";
                        var remark = "#";
                        var ico = "fa-times";
                        var reject_button = ``;
                        var textnya = "Parking Rejected";
                        $('#tr_reject').hide();
                        var identitas_tombol = 'rejected_btn';

                        $('#tr_remark').show();
                        $('#tr_remark').html(`<td colspan="8" align="left"><b>Comment Rejection :</b><br>`+data.header.Remark+`</td>`);
                    }

                    /* Untuk user mas Kholidun */
                    //Status Validate Softcopy Document PDF
                    // else if(data.action == "Update" && data.header.Status == "Request Parking" && actionmenu == '1'){
                    //     var color = "info";
                    //     var fungsi = "button";
                    //     var remark = "Update";
                    //     var ico = "";
                    //     var textnya = "Validate Document?";
                    //     var reject_button = `<button id="reject_button" type="button" class="btn btn-danger btn-sm pull-left"> Reject Parking</button><br>`;
                    //     $('#tr_reject').show();
                    //     var identitas_tombol = 'validate_button';
                    // }
                    //Status Receive Hardcopy
                    else if(data.header.Status == "Request Parking" && data.user == "adm.proc2@patria.co.id"){
                        var color = "primary";
                        var fungsi = "button";
                        var remark = "Update";
                        var ico = "";
                        var textnya = "Receive Document?";
                        var reject_button = `<button id="reject_button" type="button" class="btn btn-danger btn-sm pull-left"> Reject Parking</button><br>`;
                        $('#tr_reject').show();
                        var identitas_tombol = 'receive_button';
                    }
                    //Status Approve Parking
                    else if(data.header.Status == "Receive Document" && data.user == "adm.proc2@patria.co.id"){
                        var color = "primary";
                        var fungsi = "button";
                        var remark = "Update";
                        var ico = "";
                        var textnya = "Approve Parking?";
                        var reject_button = `<button id="reject_button" type="button" class="btn btn-danger btn-sm pull-left"> Reject Parking</button><br>`;
                        $('#tr_reject').show();
                        var identitas_tombol = 'approve_button';
                    }


                    /* Untuk PROC-S-11 */

                    //Status Validate Softcopy Document PDF
                    // else if(data.user == "PROC-S-11" && data.header.Status == "Request Parking"){
                    //     var color = "info";
                    //     var fungsi = "button";
                    //     var remark = "Update";
                    //     var ico = "";
                    //     var textnya = "Validate Document?";
                    //     var reject_button = `<button id="reject_button" type="button" class="btn btn-danger btn-sm pull-left"> Reject Parking</button><br>`;
                    //     $('#tr_reject').show();
                    //     var identitas_tombol = 'validate_button';
                    // }
                    //Status Receive Hardcopy
                    else if(data.user == "PROC-S-11" && data.header.Status == "Request Parking"){
                        var color = "primary";
                        var fungsi = "button";
                        var remark = "Update";
                        var ico = "";
                        var textnya = "Receive Document?";
                        var reject_button = `<button id="reject_button" type="button" class="btn btn-danger btn-sm pull-left"> Reject Parking</button><br>`;
                        $('#tr_reject').show();
                        var identitas_tombol = 'receive_button';
                    }
                    //Status Approve Parking
                    else if(data.user == "PROC-S-11" && data.header.Status == "Receive Document"){
                        var color = "primary";
                        var fungsi = "button";
                        var remark = "Update";
                        var ico = "";
                        var textnya = "Approve Parking?";
                        var reject_button = `<button id="reject_button" type="button" class="btn btn-danger btn-sm pull-left"> Reject Parking</button><br>`;
                        $('#tr_reject').show();
                        var identitas_tombol = 'approve_button';
                    }


                    /* Untuk Vendor */

                    else if (data.header.Status == "Request Parking") {
                        var color = "secondary";
                        var fungsi = "button";
                        var ico = "fa-pause";
                        var remark = "#";
                        var textnya = "Mohon tunggu, softcopy dokumen sedang divalidasi";
                        var reject_button = ``;
                        $('#tr_reject').hide();
                        var identitas_tombol = 'wait_approve_btn';
                    }
                    // else if (data.header.Status == "Validate Document" && actionmenu != '1') {
                    //     var color = "secondary";
                    //     var fungsi = "button";
                    //     var ico = "fa-pause";
                    //     var remark = "#";
                    //     var textnya = "Softcopy dokumen sudah valid! Mohon kirim hardcopy dokumen";
                    //     var reject_button = ``;
                    //     $('#tr_reject').hide();
                    //     var identitas_tombol = 'wait_approve_btn';
                    // }
                    else{
                        var color = "secondary";
                        var fungsi = "button";
                        var ico = "fa-pause";
                        var remark = "#";
                        var textnya = "Softcopy dokumen sudah valid! Mohon kirim hardcopy dokumen dan tunggu proses approve parking";
                        var reject_button = ``;
                        $('#tr_reject').hide();
                        var identitas_tombol = 'wait_approve_btn';
                    }
                    $("#modalfooterparking").append(`
                        <input id="remark_tombol" type="hidden" name="`+remark+`" class="btn btn-`+color+` btn-sm pull-left" value="`+textnya+`"></input>
                        <button id="`+identitas_tombol+`" type="`+fungsi+`" class="btn btn-`+color+` btn-sm pull-left" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;">`+textnya+` <i class="fa `+ico+`"></i></button>
                        `+reject_button+`
                        <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal"> Back </button>
                    `);

                    $(".datepicker").datepicker({
                        format: "dd/mm/yyyy",
                    });

                    $(".readonly").on('keydown paste focus mousedown', function(e){
                        if(e.keyCode != 9) // ignore tab
                        e.preventDefault();
                    });


                    $("#reject_button").on('click', function (){
                        $("#text_remark").attr('required', 'required');
                        $("#remark_tombol").val('Reject Parking?');
                        if (!$.trim($("#text_remark").val())) {
                            $("#text_remark").focus();
                        }else{
                            $("#parking_detail_form").submit();
                        }
                    });

                    // $("#validate_button").on('click', function (){
                    //     $("#text_remark").empty();
                    //     $("#text_remark").removeAttr('required');
                    //     $("#remark_tombol").val('Validate Document?');
                    //     if (!$.trim($("#text_remark").val())) {
                    //         $("#parking_detail_form").submit();
                    //     }else{
                    //         $("#parking_detail_form").submit();
                    //     }
                    // });

                    $("#receive_button").on('click', function (){
                        $("#text_remark").empty();
                        $("#text_remark").removeAttr('required');
                        $("#remark_tombol").val('Receive Document?');
                        if (!$.trim($("#text_remark").val())) {
                            $("#parking_detail_form").submit();
                        }else{
                            $("#parking_detail_form").submit();
                        }
                    });

                    $("#approve_button").on('click', function (){
                        $("#text_remark").empty();
                        $("#text_remark").removeAttr('required');
                        $("#remark_tombol").val('Approve Parking?');
                        if (!$.trim($("#text_remark").val())) {
                            $("#parking_detail_form").submit();
                        }else{
                            $("#parking_detail_form").submit();
                        }
                    });

                    $('#loading_button').remove();
                    $('#ParkingInvoice_detail').modal('show');

                }
            });
        });

    });
    
    //FUNCTION COPY TO CLIPBOARD
    function copyToClipboard(element) {
        navigator.clipboard.writeText(element);
        new PNotify({
            // title: 'Non-Blocking Notice',
            type: 'info',
            text: ''+element+' Copied to clipboard!',
            nonblock: {
                nonblock: true
            },
            styling: 'bootstrap3',
            delay: 1500,
            addclass: 'success'
        });
    }
</script>
