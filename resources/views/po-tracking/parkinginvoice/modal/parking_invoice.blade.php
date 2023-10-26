<div class="modal fade" id="ParkingInvoice" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-blue" id="Judulparking">
            </div>
            <div class="modal-body">
                <form id="formreqparking" method="POST" action="{{url('parkinginvoice')}}" enctype="multipart/form-data">
                @csrf
                    <div id="dataFormProformaparking"></div>
                    <div class="table-responsive">
                        <div id="message_parking">Data tidak tersedia untuk di parking<br> atau <br>Nomor SPB sudah pernah di parking</div>
                        <table id="table_data_parking" class="table nowrap table-striped text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Item No.<br><small>SPB Number</small></th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Qty PO</th>
                                    <th>Doc Number</th>
                                    <th>Doc Item</th>
                                    <th>Qty Parking</th>
                                    <th>Price <div class="currency"></div></th>
                                    <th>Total Price <div class="currency"></div></th>
                                </tr>
                            </thead>
                            <tbody id="dataPOparking">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7"></td>
                                    <td class="text-left">Amount</td>
                                    <td id="totalbayars" class="text-right"></td>
                                    <input id="amount_val" type="hidden" name="amount" value="">
                                </tr>
                                <tr>
                                    <td colspan="7"></td>
                                    <td class="text-left">PPn 11%</td>
                                    <td id="ppns" class="text-right"></td>
                                    <input id="ppns_val" type="hidden" name="ppn" value="">
                                </tr>
                                <tr id="pphs1">
                                    <td colspan="7"></td>
                                    <td class="text-left align-middle">PPh 23</td>
                                    <td class="text-right text-nowrap">
                                        - <input id="pph" class="text-right mr-1 readonly" name="pph" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7"></td>
                                    <td class="text-left font-weight-bold">Amount Total</td>
                                    <td class="text-right" id="totalharga"></td>
                                    <input id="amountotal_val" type="hidden" name="amounttotal" value="">
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-around">
                        <div class="form-group" id="proformauploudparking">
                        </div>
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
        $('#pphs1').hide();

        $('#datatable-responsive tbody').on('click', '.parkinginvoice', function ()
        {
            $(this).append(`
                <div id="loading_button">
                    Loading<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </div>
            `);
                var id = $(this).attr('data-id');

                // var id = $(this).attr('data-number');
                $.ajax({
                    url : "{{url('view_cariparking')}}?number="+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('.currency').empty();
                        $('.currency').html('('+data.dataOngoing[0].Currency+')');
                        $('#dataPOparking').empty();
                        $('#dataUploadProformaparking').empty();
                        $('#dataFormProformaparking').empty();
                        $(".datepicker").datepicker();
                        $('#modalfooterparking').empty();
                        $('#downloadparking').empty();
                        $('#Judulparking').empty();
                        $('#proformauploudparking').empty();

                        var invoice         = new Date();
                        var tanggale        = String(invoice.getDate()).padStart(2, '0');
                        var bulane          = String(invoice.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahune          = invoice.getFullYear();
                        var InvoiceDates    = tanggale + '/' + bulane + '/' + tahune;

                        const d = new Date();
                        var tanggals        = String(d.getDate()).padStart(2, '0');
                        var bulans         = String(d.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahuns           = d.getFullYear();

                        var invoicedate  = InvoiceDates;

                        $("#Judulparking").append(`<h6 text-center>Request Parking Invoice</h6>`);
                        $("#dataFormProformaparking").append(`
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="invoice_date">Invoice Date :</label>
                                        <input id="invoice_date" type="text" class="form-control datepicker readonly" name="invoice_date" value="`+invoicedate+`" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="po_no">Purchase Order Number :</label>
                                        <div class="input-group">
                                            <input id="po_no" type="text" name="Number" class="form-control readonly" value="`+data.dataPO.Number+`">
                                            <span class="input-group-btn">
                                                <button type="button" class="form-control btn btn-light" data-toggle="tooltip" title="Copy PO Number" onclick="copyToClipboard('`+data.dataPO.Number+`')"><i class="fa fa-clone"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="invoice_no">Invoice Number :</label>
                                        <input id="invoice_no" type="text" name="invoice_no" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="tax_invoice_no">No.Faktur Pajak :</label>
                                        <input id="tax_invoice_no" type="text" name="reference" class="form-control" value="" required ><br>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="col-4">
                                    <label for="SPB">Ref. Surat Jalan :</label>
                                        <div id="SPB"></div>
                                </div>
                                <div id="pphs" class="col-6">
                                </div>
                            </div>
                        `);

                        $("#tax_invoice_no").inputmask("999.999-99.99999999",{ "clearIncomplete": true });
                        var only_null_spb = 0;
                        for(i=0;i<data.dataSPB.length;i++){
                            if(data.dataSPB[i] != null){
                                $("#SPB").append(
                                    `<div class="row mt-2">
                                        <input type="checkbox" class="spbclass" name="SPB_Number[]" value="`+data.dataSPB[i]+`">&nbsp;
                                        `+data.dataSPB[i]+`
                                    </div>
                                `);
                                only_null_spb = 1;
                            }
                        }

                        var str_html = '';
                        $('input:checkbox[class="spbclass"]').click(function(){
                            $('#pphs1').hide();
                            if($('input.spbclass:checked').length <= 0){
                                $("#dataParking").empty();
                                $("#pphs").empty();
                                $("#dataPOparking").empty();
                                $("#totalbayars").empty();
                                $("#ppns").empty();
                                $("#totalharga").empty();
                                $('#pphs').hide();
                            }
                            else{
                                $('#pphs').show();
                                $("#dataParking").empty();
                                $("#pphs").empty();
                                $("#dataPOparking").empty();
                                str_html = '';
                                
                                str_html +=`
                                    <input id="cb_tax" type="checkbox">
                                    <label for="cb_tax">Tax Code | PPh 23 Description</label>
                                    <select id="wi_tax" class="form-control" name="wi_tax_code">
                                    <option value="" selected disabled hidden>--NaN--</option>
                                `;
                                var showtaxcode = 0;
                                for(i=0;i<data.data_wth_vendor.length;i++){
                                    if(data.data_wth_vendor[i].WithholdingTaxType == 'W2' && data.data_wth_vendor[i].WithholdingTaxCode != ''){
                                        showtaxcode = data.data_wth_vendor[i].WithholdingTaxCode;
                                        break;
                                    }
                                }
                                if(showtaxcode == 0){
                                    for(i=0;i<data.data_tax_code.length;i++){
                                        str_html +=`
                                        <option value="`+data.data_tax_code[i].tax_code+`">`+data.data_tax_code[i].tax_code+` | `+data.data_tax_code[i].name+`</option>
                                        `;
                                    }
                                }else{
                                    for(i=0;i<data.data_tax_code.length;i++){
                                        if(showtaxcode == data.data_tax_code[i].tax_code){
                                            str_html +=`
                                            <option value="`+data.data_tax_code[i].tax_code+`">`+data.data_tax_code[i].tax_code+` | `+data.data_tax_code[i].name+`</option>
                                            `;
                                            break;
                                        }
                                    }
                                }

                                str_html +=`</select>
                                    <input id="wi_tax2" type="checkbox" name="wi_tax_type" value="W2" hidden>
                                `;
                                $("#pphs").html(str_html);

                                if(data.data_wth_vendor.length == 0){
                                    $('#pphs').hide();
                                }
                                else if(data.data_wth_vendor.length == 1 && data.data_wth_vendor[0].WithholdingTaxType == 'W2'){
                                    $('#cb_tax').prop("checked",true);
                                    $('#cb_tax').prop("required",false);

                                    $("#wi_tax").prop("disabled",false);
                                    $("#wi_tax").prop("required",true);
                                }
                                else if(data.data_wth_vendor.length == 1 && data.data_wth_vendor[0].WithholdingTaxType == '23'){
                                    $('#pphs').hide();
                                }
                                else if(data.data_wth_vendor.length >= 2){
                                    $('#cb_tax').prop("required",false);
                                    $("#wi_tax").prop("disabled",false);
                                    $("#wi_tax").prop("required",false);
                                }
                                else{
                                    $('#pphs').hide();
                                }
                            }

                            var totalbayar = 0;
                            var ppn = 0;
                            var amounttotal = 0;

                            $('input:checkbox[class="spbclass"]').each(function () {
                                if(this.checked){
                                    for(i=0;i<data.dataOngoing.length;i++){
                                        if(data.dataOngoing[i].RefDocumentNumber == this.value){
                                            var tgl             = new Date(data.dataOngoing[i].ConfirmedDate);
                                            var tanggal         = String(tgl.getDate()).padStart(2, '0');
                                            var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                                            var tahun           = tgl.getFullYear();
                                            var ConfirmedDate   = tanggal + '/' + bulan + '/' + tahun;
                                            var float           = Number.parseFloat(price,'');
                                            
                                            var total       = data.dataOngoing[i].NetPrice * (data.dataOngoing[i].GoodsReceiptQuantity) ;
                                            totalbayar      += parseInt(total);
                                            var totalbayars =  totalbayar.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                                            
                                            var ppn = (11/100);

                                            ppn         = Math.ceil(totalbayar * ppn);
                                            var ppns    =  ppn.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                            amounttotal         = Math.ceil(totalbayar + ppn);
                                            var amounttotals    = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                            var qty         = data.dataOngoing[i].Quantity ;
                                            var totalsub    = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                                            
                                            var price       = data.dataOngoing[i].NetPrice;
                                            var float       = Number.parseFloat(price,'');
                                            var subst       = float.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                                            var qtyparking  = data.dataOngoing[i].GoodsReceiptQuantity;

                                            if(data.dataOngoing[i].MovementType == '105'){
                                                var docnum      = data.dataOngoing[i].DocumentNumberRef;
                                                var docnumitem  = data.dataOngoing[i].DocumentNumberItemRef;
                                            }else{
                                                var docnum      = data.dataOngoing[i].DocumentNumber;
                                                var docnumitem  = data.dataOngoing[i].DocumentNumberItem;
                                            }
                                            $("#dataPOparking").append(`
                                                <tr>
                                                    <td>`+data.dataOngoing[i].ItemNumber+`<br><small>`+data.dataOngoing[i].RefDocumentNumber+`</small></td>
                                                    <td class="text-left">`+data.dataOngoing[i].Material+`</td>
                                                    <td class="text-left">`+data.dataOngoing[i].Description+`</td>
                                                    <td>`+data.dataOngoing[i].Quantity+`</td>
                                                    <td>`+docnum+`</td>
                                                    <td>`+docnumitem+`</td>
                                                    <td>`+data.dataOngoing[i].GoodsReceiptQuantity+`</td>
                                                    <td class="text-right">`+subst+`</td>
                                                    <td class="text-right">`+totalsub+`</td>

                                                    <input type="hidden" name="PONumber[]" value="`+data.dataPO.Number+`">
                                                    <input type="hidden" name="ItemNumber[]" value="`+data.dataOngoing[i].ItemNumber+`">
                                                    <input type="hidden" name="Material[]" value="`+data.dataOngoing[i].Material+`">
                                                    <input type="hidden" name="Description[]" value="`+data.dataOngoing[i].Description+`">
                                                    <input type="hidden" name="GRQuantity[]" value="`+data.dataOngoing[i].GoodsReceiptQuantity+`">
                                                    <input type="hidden" name="GRDate[]" value="`+data.dataOngoing[i].GoodsReceiptDate+`">
                                                    <input type="hidden" name="DocumentNumber[]" value="`+docnum+`">
                                                    <input type="hidden" name="DocumentNumberItem[]" value="`+docnumitem+`">
                                                    <input type="hidden" name="Price[]" value="`+price+`">
                                                    <input type="hidden" name="Currency[]" value="`+data.dataOngoing[0].Currency+`">
                                                    <input type="hidden" name="TotalPrice[]" value="`+total+`">
                                                    <input type="hidden" name="RefDocumentNumber[]" value="`+data.dataOngoing[i].RefDocumentNumber+`">
                                                    <input type="hidden" name="VAT[]" value="`+data.dataOngoing[i].VAT+`">
                                                    <input type="hidden" name="UoM[]" value="`+data.dataOngoing[i].UoM+`">
                                                    <input type="hidden" name="CompanyCode[]" value="`+data.dataPO.CompanyCode+`">
                                                    <input type="hidden" name="VendorName[]" value="`+data.dataVendor.Name+`">
                                                    <input type="hidden" name="POCreator[]" value="`+data.dataPO.PurchaseOrderCreator+`">
                                                </tr>
                                            `);
                                        }
                                    }
                                    //Amount
                                    $('#totalbayars').empty();
                                    $('#totalbayars').html(totalbayars);

                                    //PPh 23
                                    $('#pph').empty();
                                    $('#pph').html(0);

                                    //PPn 11%
                                    $('#ppns').empty();
                                    $('#ppns').html(ppns);

                                    //Amount Total
                                    $('#totalharga').empty();
                                    $('#totalharga').html(amounttotals);

                                    $('#upload_file_pdf').val('');
                                }
                            });
                            $('#upload_file_pdf').val('');
                            var totalbayarplusppn = Math.ceil(totalbayar + (totalbayar * (11/100)));
                            $('#amount_val').val(totalbayar);
                            $('#ppns_val').val(Math.ceil(totalbayar * (11/100)));

                            amounttotal = Math.ceil(totalbayar + (totalbayar * (11/100)));
                            amounttotals = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                            $('#amountotal_val').val(amounttotal);
                            $('#totalharga').html(amounttotals);

                            //Checkbox Tax Code
                            $(document).on("change", "#cb_tax", function () {
                                if ($(this).is(':checked')) {
                                    $("#wi_tax").prop("disabled",false);
                                    $("#wi_tax").prop("required",true);
                                }
                                else{
                                    $("#wi_tax").val("");
                                    $("#wi_tax").prop("disabled",true);
                                    $("#wi_tax").prop("required",false);

                                    $('#pphs1').hide();
                                    $('#pph').empty();
                                    $('#totalharga').empty();
                                    $("#wi_tax2").prop("checked",false);
                                    wi_tax = 0;
                                    wi_taxs = wi_tax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                    amounttotal = Math.ceil(totalbayar + (totalbayar * (11/100)) - wi_tax);
                                    amounttotals = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                    $('#pph').val(wi_taxs);
                                    $('#amountotal_val').val(amounttotal);
                                    $('#totalharga').html(amounttotals);
                                }
                            });

                            //Jika PO Jasa di pilih
                            $(document).on("change", "#wi_tax", function () {
                                if($("#wi_tax").attr("selectedIndex") != 0) {
                                // if ($(this).is(':checked')) {
                                    $('#pphs1').show();
                                    $('#pph').empty();
                                    $('#totalharga').empty();
                                    $("#wi_tax2").prop("checked",true);
                                    wi_tax = Math.floor(totalbayar * (2/100));
                                    wi_taxs = wi_tax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                    amounttotal = Math.ceil(totalbayar + (totalbayar * (11/100)) - wi_tax);
                                    amounttotals = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                    $('#pph').val(wi_taxs);
                                    $('#amountotal_val').val(amounttotal);
                                    $('#totalharga').html(amounttotals);
                                // }
                                }
                                if($("#wi_tax").attr("selectedIndex") == 0) {
                                // if ($(this).is(':checked') == false) {
                                    $('#pphs1').hide();
                                    $('#pph').empty();
                                    $('#totalharga').empty();
                                    $("#wi_tax2").prop("checked",false);
                                    wi_tax = 0;
                                    wi_taxs = wi_tax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                    amounttotal = Math.ceil(totalbayar + (totalbayar * (11/100)) - wi_tax);
                                    amounttotals = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                                    $('#pph').val(wi_taxs);
                                    $('#amountotal_val').val(amounttotal);
                                    $('#totalharga').html(amounttotals);
                                // } 
                                }
                            });
                        });

                        var extra = 0;
                        $('#pph').on("keyup", function(e) {
                            if(this.value == ''){
                                this.value = 0;
                            }
                            this.value = this.value.replace(/[^0-9\.]/g,'');
                            var temp_harga = $('#pph').val().split('.').join("");
                            
                            var input = parseInt(temp_harga);
                            $('#pph').val(input.toLocaleString('id-ID'));

                            var harga = parseInt($('#amount_val').val());
                            amounttotal = Math.ceil(harga + (harga * (11/100)) - input);
                            amounttotals = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                            $('#amountotal_val').val(amounttotal);
                            $('#totalharga').html(amounttotals);
                        });

                        var proforma = "";
                        var labels = "";
                        var labelparking = "";
                        var action = `<label for="filename">Upload Dokumen Invoice, Faktur Pajak, Surat Jalan, Tanda Terima Barang</label><br>
                        <input id="upload_file_pdf" type="file" name="filename[]" data-show-upload="false" data-show-caption="true" multiple capture="camera" accept="application/pdf" required>
                        <div id="dataUploadProformaparking"></div>
                        <small class="text-danger">*Type file PDF</small>`;

                        $("#modalfooterparking").append
                            (`
                            <button type="submit" id="tombol_submit" class="btn btn-primary btn-sm pull-left">Save</button>
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>

                            `);
                        $(".datepicker").datepicker({
                            format: "dd/mm/yyyy",
                        });

                        $("#proformauploudparking").append
                            (`
                            `+labels+`
                            `+proforma+` <br>
                            `+labelparking+`
                            `+action+`
                            `);

                        $(".readonly").on('keydown paste', function(e){
                        if(e.keyCode != 9) // ignore tab
                            e.preventDefault();
                        });

                        if(only_null_spb == 0){
                            $("#tombol_submit").hide();
                            $("#dataFormProformaparking").hide();
                            $("#proformauploudparking").hide();
                            $("#downloadparking").hide();
                            $("#table_data_parking").hide();

                            $("#message_parking").show();
                        }
                        else{
                            $("#tombol_submit").show();
                            $("#dataFormProformaparking").show();
                            $("#proformauploudparking").show();
                            $("#downloadparking").show();
                            $("#table_data_parking").show();

                            $("#message_parking").hide();
                        }

                        $('#loading_button').remove();
                        $('#ParkingInvoice').modal('show');
                    }
             });

        });

        $('#formreqparking').submit(function() {
            checked = $("input:checkbox[class='spbclass']:checked").length;
            if(!checked) {
                Swal.fire("Anda harus pilih Ref. Surat Jalan");
                return false;
            }
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
