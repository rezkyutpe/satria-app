<div class="modal fade" id="proformaInvoice" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-info" id="Judul">
            </div>
            <div class="modal-body">
                <form method="POST" action="{{url('proformasubcontractor')}}" enctype="multipart/form-data">
                @csrf
                    <span id="dataFormProforma"></span>
                    <div class="table-responsive">
                        <table class="table nowrap table-striped text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Item No.</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Confirmed Qty</th>
                                    <th>Price (IDR)</th>
                                    <th>Total (IDR)</th>
                                </tr>
                            </thead>
                            <tbody id="dataPO">
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group" id="proformauploud">
                    </div>
                    <div class="modal-footer" id="modalfooter">
                         </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#datatable-responsive tbody').on('click', '.proformaInvoice', function ()
        {
                var id = $(this).attr('data-id');
                // var id = $(this).attr('data-number');
                $.ajax({
                    url : "{{url('view_proformasubcontractor')}}?id="+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('#dataPO').empty();
                        $('#dataUploadProforma').empty();
                        $('#dataFormProforma').empty();
                        $('.ConfirmedDate').val(data.dataid.ConfirmedDate);
                        $('.Qty').val(data.dataid.ConfirmedQuantity);
                        $('.ID').val(data.dataid.ID);
                        $('.po_no').val(data.dataid.Number);
                        $(".datepicker").datepicker();
                        $('#modalfooter').empty();
                        $('#Judul').empty();
                        $('#proformauploud').empty();
                        var totalbayar = 0;
                        for(i=0;i<data.data.length;i++)
                        {
                            var tgl             = new Date(data.data[i].ConfirmedDate);
                            var tanggal         = String(tgl.getDate()).padStart(2, '0');
                            var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var tahun           = tgl.getFullYear();
                            var ConfirmedDate   = tanggal + '/' + bulan + '/' + tahun;
                            var price           = data.data[i].NetPrice;
                            var float          = Number.parseFloat(price,'');
                            var total = data.data[i].NetPrice * data.data[i].ConfirmedQuantity ;
                            totalbayar += parseInt(total);
                           var totalbayars =  totalbayar.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            if (data.dataid.DeliveryDate < '2021-04-01') {
                                var ppn = (10/100) ;
                            } else {
                                var ppn = (11/100) ;
                            }
                                if(data.actionmenu.c==1) {
                                    var Need = "hidden";
                                    var Needs = "Submit";
                                }else{
                                    var Need = "Submit"
                                    var Needs = "hidden";
                                }

                            var ppn = totalbayar * ppn ;
                            var ppns =  ppn.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            var amounttotal = totalbayar + ppn ;
                            var amounttotals = amounttotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            var qty          = data.data[i].ConfirmedQuantity ;
                            var totalsub          = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            var subst = float.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            $("#dataPO").append
                            (`
                                <tr>
                                    <td>`+data.data[i].ItemNumber+`</td>
                                    <td class="text-left">`+data.data[i].Material+`</td>
                                    <td class="text-left">`+data.data[i].Description+`</td>
                                    <td>`+data.data[i].ActualQuantity+`</td>
                                    <td>`+subst+`</td>
                                    <td>`+totalsub+`</td>
                                </tr>

                            `);
                        }
                        $("#dataPO").append
                            (`

                                <tr>
                                    <td></td>
                                    <td class="text-left"></td>
                                    <td class="text-left"></td>
                                    <td></td>
                                    <td  class="text-left">Amount</td>
                                    <td>`+totalbayars+`</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-left"></td>
                                    <td class="text-left"></td>
                                    <td></td>
                                    <td  class="text-left">PPN</td>
                                    <td>`+ppns+`</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-left"></td>
                                    <td class="text-left"></td>
                                    <td></td>
                                    <td  class="text-left">Amount Total</td>
                                    <td>`+amounttotals+`</td>
                                </tr>
                            `);
                        $("#dataUploadProforma").append
                        (`
                            <input type="hidden" name="Number" class="form-control" value="`+data.dataid.Number+`">
                            <input type="hidden" name="id" class="form-control" value="`+data.dataid.Number+`">
                            <input type="hidden" name="Item" class="form-control" value="`+data.dataid.ItemNumber+`">
                        `);
                        if(data.dataid.ActiveStage == '2a'){
                            $("#Judul").append
                        (`
                        <h6 text-center>Upload Proforma Invoice</h6>
                        `);
                        $("#dataFormProforma").append
                        (`
                        <div class="row">
                            <div class="col-lg-6">
                            <div class="form-group">
                                <label for="invoice_date">Invoice Date</label>
                                <input type="date" name="invoice_date" required class="form-control">
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <div class="form-group">
                                <label for="po_no">Purchase Order Number</label>
                                <input type="text" name="po_no" class="form-control" value="`+data.dataid.Number+`" disabled>
                            </div>
                        </div>
                            <div class="col-lg-6">
                            <div class="form-group">
                                <label for="invoice_no">Invoice Number</label>
                                <input type="text" name="invoice_no" required class="form-control">
                            </div>
                             </div>
                             <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tax_invoice_no">Tax Invoice Number</label>
                                <input type="text" name="tax_invoice_no" required class="form-control">
                            </div>
                        </div>

                        </div>
                        `);

                        $("#proformauploud").append
                            (`
                            <label for="filename">Upload Proforma Invoice</label><br>
                            <input type="file" name="filename" required>
                            <input type="hidden" name="ActiveStage" class="form-control" value="`+data.dataid.ActiveStage+`" >
                            <input type="hidden" name="Number" class="form-control" value="`+data.dataid.Number+`" >
                            <div id="dataUploadProforma"></div>
                            <small class="text-danger">*Type file PDF</small>
                            `);
                        $("#modalfooter").append
                            (`
                            <button type="`+Need+`" class="btn btn-primary btn-sm pull-left">Upload</button>
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal"> Back </button>

                            `);
                    }else if(data.dataid.ActiveStage == '2b'){
                            $("#Judul").append
                        (`
                        <h6 text-center>Confirmed Proforma Payment</h6>
                        `);
                        $("#dataFormProforma").append
                        (`
                        <div class="row">
                            <div class="col-lg-6">
                            <div class="form-group">
                                <input type="hidden" name="ActiveStage" class="form-control" value="`+data.dataid.ActiveStage+`" >
                                <input type="hidden" name="Number" class="form-control" value="`+data.dataid.Number+`" >
                                <label for="invoice_date">Confirmed Payment Date</label>
                                <input type="date" name="confirm_date"  class="form-control" required>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <div class="form-group">
                                <label for="po_no">Purchase Order Number</label>
                                <input type="text" name="po_no" class="form-control" value="`+data.dataid.Number+`" disabled>
                            </div>
                        </div>
                        </div>
                        `);

                        $("#proformauploud").append
                            (`
                            <label for="filename">Upload Payment</label><br>
                            <input type="file" name="filename" required>
                            <input type="submit" name="action" value="Download Proforma" class="btn btn-danger btn-sm" formtarget="_blank">
                            <input type="`+Need+`" name="action" value="Revisi" class="btn btn-warning btn-sm" onclick="return confirm('Apakah anda Yakin Ingin Revisi File ?');">
                            `);
                        $("#modalfooter").append
                            (`
                            <input type="`+Need+`" name="action" value="Save" class="btn btn-primary btn-sm pull-left">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal"> Back </button>

                            `);

                        }else{
                            $("#Judul").append
                        (`
                        <h6 text-center>Proforma</h6>
                        `);
                            $("#dataFormProforma").append
                            (`
                                <div class="form-group">
                                    <label for="po_no">Purchase Order Number</label>
                                    <input type="hidden" name="ActiveStage" class="form-control" value="`+data.dataid.ActiveStage+`" >
                                    <input type="text" name="Number" class="form-control" value="`+data.dataid.Number+`" readonly>
                                </div>
                            `);
                            $("#modalfooter").append
                            (`
                            <input type="`+Need+`" name="action" value="Need" class="btn btn-primary btn-sm pull-left">
                            <input type="`+Need+`" name="action"  value="Skip" class="btn btn-danger btn-sm pull-left">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal"> Back </button>
                            `);
                            $("#proformauploud").append
                            (`

                            `);
                        }
                        $('#proformaInvoice').modal('show');
                    }
                });
        });

    });
</script>
