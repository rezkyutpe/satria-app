<div class="modal fade" id="ParkingInvoice" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-blue" id="Judulparking">
            </div>
            <div class="modal-body">
                <form method="POST" action="{{url('parkinginvoicelocal')}}" enctype="multipart/form-data">
                @csrf
                    <span id="dataFormProformaparking"></span>
                    <div class="table-responsive">
                        <table class="table nowrap table-striped text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Item No.</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Qty PO</th>
                                    <th>Qty Parking </th>
                                    <th>Price (IDR)</th>
                                    <th>Total (IDR)</th>
                                </tr>
                            </thead>
                            <tbody id="dataPOparking">
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group" id="proformauploudparking">
                    </div>
                    <div class="form-group" id="downloadparking">
                    </div>
                    <div class="modal-footer" id="modalfooterparking">
                         </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#datatable-responsive tbody').on('click', '.parkinginvoice', function ()
        {

                var id = $(this).attr('data-id');
                var item = $(this).attr('data-item');

                // var id = $(this).attr('data-number');
                $.ajax({
                    url : "{{url('view_cariparking')}}?number="+id+"&item="+item,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('#dataPOparking').empty();
                        $('#dataUploadProformaparking').empty();
                        $('#dataFormProformaparking').empty();
                        $(".datepicker").datepicker();
                        $('#modalfooterparking').empty();
                        $('#downloadparking').empty();
                        $('#Judulparking').empty();
                        $('#proformauploudparking').empty();
                        var totalbayar = 0;
                        for(i=0;i<data.dataOngoing.length;i++)
                        {

                            var tgl             = new Date(data.dataOngoing[i].ConfirmedDate);
                            var tanggal         = String(tgl.getDate()).padStart(2, '0');
                            var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var tahun           = tgl.getFullYear();
                            var ConfirmedDate   = tanggal + '/' + bulan + '/' + tahun;
                            var price           = data.dataOngoing[i].NetPrice;
                            var float           = Number.parseFloat(price,'');

                            var total = data.dataOngoing[i].NetPrice * (data.dataOngoing[i].totalgr - data.dataOngoing[i].totalir) ;
                            totalbayar += parseInt(total);

                            var totalbayars =  totalbayar.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                                if (data.data.DeliveryDate < '2021-04-01') {
                                    var ppn = (10/100) ;
                                } else {
                                    var ppn = (11/100) ;
                                }
                            var ppn = totalbayar * ppn ;
                            var ppns =  ppn.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            var amounttotal = totalbayar + ppn ;
                            var amounttotals = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            var qty          = data.dataOngoing[i].Quantity ;
                            var totalsub          = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            if(data.data.ConfirmReceivedPaymentDate == null){
                                var inv = data.data.InvoiceDate ;
                            }else{
                                var inv = data.data.ConfirmReceivedPaymentDate ;
                            }
                            var invoice = new Date(inv);
                            var tanggale         = String(invoice.getDate()).padStart(2, '0');
                            var bulane          = String(invoice.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var tahune           = invoice.getFullYear();
                            var InvoiceDates   = tanggale + '/' + bulane + '/' + tahune;
                            const d = new Date();
                            var tanggals        = String(d.getDate()).padStart(2, '0');
                            var bulans         = String(d.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var tahuns           = d.getFullYear();
                            // var status = "";
                            // var invoicedate = "";
                            // var taxinvoice = "";
                            // var invoicemethod = "";
                            if(data.showactive == 1){
                                if(data.data.ConfirmReceivedPaymentDate== null || data.data.TaxInvoice== null || data.data.InvoiceMethod == null){
                                    var invoicedate = tanggals + '/' + bulans + '/' + tahuns;
                                    var reference = "";
                                    var invoicemethod = "";
                                    var   assignment  = "";
                                    var   headertText =  "";
                                    var   status = "";
                                    }else{
                                        var   invoicemethod  = data.data.InvoiceMethod;
                                        var   invoicedate  = InvoiceDates;
                                        var   reference =  data.data.TaxInvoice;
                                        var   assignment  = "";
                                        var   headertText =  "";
                                        var   status = "";

                                    }
                            }else{
                                    var   invoicemethod  = data.data.InvoiceNumber;
                                    var   invoicedate  = InvoiceDates;
                                    var   assignment  =  data.data.Assignment;
                                    var   reference =  data.data.Reference;
                                    var   headertText =  data.data.HeadertText;
                                    var   status = "readonly";

                            }

                            var float           = Number.parseFloat(price,'');
                            var qty  = data.dataOngoing[i].Quantity ;
                            var qtyparking = data.dataOngoing[i].totalgr - data.dataOngoing[i].totalir ;
                            var subst = float.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                            $("#dataPOparking").append
                            (`
                                <tr>
                                    <td>`+data.dataOngoing[i].ItemNumber+`</td>
                                    <input type="hidden" name="Material[]" class="form-control" value="`+data.dataOngoing[i].Material+`">
                                    <input type="hidden" name="Description[]" class="form-control" value="`+data.dataOngoing[i].Description+`">
                                    <input type="hidden" name="ItemNumber[]" class="form-control" value="`+data.dataOngoing[i].ItemNumber+`">
                                    <input type="hidden" name="Qty[]" class="form-control" value="`+qtyparking+`">
                                    <td class="text-left">`+data.dataOngoing[i].Material+`</td>
                                    <td class="text-left">`+data.dataOngoing[i].Description+`</td>
                                    <td data-toggle="tooltip" title="Total GR `+data.dataOngoing[i].totalgr+` Total IR `+data.dataOngoing[i].totalir+`">`+data.dataOngoing[i].Quantity+`</td>
                                    <td>`+qtyparking+`</td>
                                    <td>`+subst+`</td>
                                    <td>`+totalsub+`</td>

                                </tr>
                            `);
                        }

                        $("#dataPOparking").append
                            (`

                                <tr>
                                    <td></td>
                                    <td class="text-left"></td>
                                    <td class="text-left"></td>
                                    <td></td>
                                    <td></td>
                                    <td  class="text-left">Amount</td>
                                    <td>`+totalbayars+`</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-left"></td>
                                    <td class="text-left"></td>
                                    <td></td>
                                    <td></td>
                                    <td  class="text-left">PPN</td>
                                    <td>`+ppns+`</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-left"></td>
                                    <td class="text-left"></td>
                                    <td></td>
                                    <td></td>
                                    <td  class="text-left">Amount Total</td>
                                    <td>`+amounttotals+`</td>
                                    <input type="hidden" name="amount" class="form-control" value="`+amounttotal+`">
                                </tr>
                            `);


                     $("#Judulparking").append
                        (`
                        <h6 text-center>Parking Invoice</h6>
                        `);
                        $("#dataFormProformaparking").append
                        (`
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice Date</label>
                                    <input type="text" class="form-control datepicker" `+status+` name="invoice_date" value="`+invoicedate+`" required >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                 <div class="form-group">
                                 <label for="po_no">Purchase Order Number</label>

                                  <input type="text" name="Number" class="form-control" value="`+data.data.Number+`" readonly>

                                  </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="invoice_no">Invoice Number</label>
                                    <input type="text" name="invoice_no" `+status+` class="form-control" value="`+invoicemethod+`" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="tax_invoice_no">Assignment Text</label>
                                    <input type="text" name="Assignment" `+status+` class="form-control" value="`+assignment+`"  required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="invoice_no">Header Text</label>
                                    <input type="text" name="headertext" `+status+` class="form-control"  value="`+headertText+`" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="tax_invoice_no">Reference</label>
                                    <input type="text" name="reference" `+status+`  class="form-control" value="`+reference+`" required >
                                </div>
                            </div>

                        </div>
                        `);
                       let text=  data.data.ProformaInvoiceDocument;
                       if(data.showactive == 1){
                            if(data.data.ProformaInvoiceDocument == null){
                                var proforma = "";
                                var labels = "";
                                var labelparking = "";
                                var action =  `<label for="filename">Upload Dokumen Invoice, Faktur Pajak, Surat Jalan, Tanda Terima Barang</label><br>
                                <input type="file" name="filename[]" data-show-upload="false" data-show-caption="true" multiple capture="camera" required>
                                <div id="dataUploadProformaparking"></div>
                                <small class="text-danger">*Type file PDF</small>`
                            }else{
                                var proforma = `<a class="btn btn-danger btn-sm" href="{{ url('downloadproforma/`+data.document.ProformaInvoiceDocument+`') }}"> <i class="fa fa-download"> </i> `+data.data.ProformaInvoiceDocument+`</a>`;
                                var labels = ` <label for="filename">File Proforma Invoice </label><br>`;
                                var labelparking = "";
                                var action =  `<label for="filename">Upload Dokumen Invoice, Faktur Pajak, Surat Jalan, Tanda Terima Barang</label><br>
                                <input type="file" name="filename[]" data-show-upload="false" data-show-caption="true" multiple capture="camera" required>
                                <div id="dataUploadProformaparking"></div>
                                <small class="text-danger">*Type file PDF</small>`
                            }
                            $("#modalfooterparking").append
                                        (`
                                        <button type="submit" class="btn btn-primary btn-sm pull-left">Save</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal"> Back </button>

                                        `);
                                        $(".datepicker").datepicker({
                                        format: "dd/mm/yyyy",
                                    });
                         }else{
                            if(data.data.ProformaInvoiceDocument == null){
                                var proforma = "";
                                var labels = "";
                                var labelparking = `<label for="filename">File Parking Invoice </label><br>`;
                                var action ="";
                            } else{
                                var labels = ` <label for="filename">File Proforma Invoice </label><br>`;
                                var proforma = `<a class="btn btn-danger btn-sm" href="{{ url('downloadproforma/`+data.document.ProformaInvoiceDocument+`') }}"> <i class="fa fa-download"> </i> `+data.data.ProformaInvoiceDocument+`</a>`;
                                var labelparking = `<label for="filename">File Parking Invoice </label><br>`;
                                var action ="";
                            }
                            for(j=0;j<data.document.length;j++){
                            $("#downloadparking").append
                                (`
                                <a class="btn btn-danger btn-sm" href="{{ url('public/potracking/parking_invoice/`+data.data.Number+`/`+data.document[j].FileName+`') }}"> <i class="fa fa-download"> </i> `+data.document[j].FileName+`</a>
                                `);
                            }
                            if(data.data.Status == "Approve Parking"){
                                var color = "success";
                                var fungsi = "button";
                                var remark = "#";
                                var ico = "fa-check-square";
                                var textnya = "Parking Approve";
                            }else if(data.action == "Update"){
                                var color = "primary";
                                var fungsi = "submit";
                                var remark = "Update";
                                var ico = "";
                                var textnya = "Approve Parking?";
                            }else{
                                var color = "secondary";
                                var fungsi = "button";
                                var ico = "fa-pause";
                                var remark = "#";
                                var textnya = "Wait Approve Parking";
                            }
                            $("#modalfooterparking").append
                                        (`
                                        <input type="hidden" name="`+remark+`" class="btn btn-`+color+` btn-sm pull-left" value="`+textnya+`"></input>
                                        <button type="`+fungsi+`" class="btn btn-`+color+` btn-sm pull-left">`+textnya+` <i class="fa `+ico+`"></i></button>
                                        <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal"> Back </button>

                                        `);
                                        $(".datepicker").datepicker({
                                        format: "dd/mm/yyyy",
                                    });
                            }

                        $("#proformauploudparking").append
                            (`
                            `+labels+`
                            `+proforma+` <br>
                            `+labelparking+`
                            `+action+`
                            `);
                        $('#ParkingInvoice').modal('show');

                    }
             });

        });

});

</script>
