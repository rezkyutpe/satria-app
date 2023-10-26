<div class="modal fade" id="proformaInvoice" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h6 text-center>Upload Proforma Invoice</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('poimport.uploadproforma') }}" enctype="multipart/form-data">
                @csrf
                    <div class="m-2">
                        <span id="dataFormProforma"></span>
                        <div>Detail PO</div>
                        <div class="table-responsive">
                            <table class="table nowrap table-striped text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Item No.</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Total Qty</th>
                                        <th>Confirmed Qty</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody id="dataPO">
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="filename">Upload Proforma Invoice</label><br>
                            <input type="file" name="filename[]" required>
                            <div id="dataUploadProforma"></div>
                            <small class="text-danger">*Type file PDF, max. size 5MB</small>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm pull-left">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function()
    {
        $('#datatable-visibility tbody').on('click', '.proformaInvoice', function ()
        {
            // var id = $(this).attr('data-id');
            var id = $(this).attr('data-number');
            $.ajax({
                url : "{{url('viewcaridataimport')}}?id="+id,
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

                    for(i=0;i<data.dataall.length;i++)
                    {
                        var tgl             = new Date(data.dataall[i].ConfirmedDate);
                        var tanggal         = String(tgl.getDate()).padStart(2, '0');
                        var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun           = tgl.getFullYear();
                        var ConfirmedDate   = tanggal + '/' + bulan + '/' + tahun;
                        var price           = data.dataall[i].NetPrice;
                        const real_price    = price.split('.');

                        $("#dataPO").append
                        (`
                            <tr>
                                <td>`+data.dataall[i].ItemNumber+`</td>
                                <td class="text-left">`+data.dataall[i].Material+`</td>
                                <td>`+data.dataall[i].Description+`</td>
                                <td>`+data.dataall[i].Quantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</td>
                                <td>`+data.dataall[i].ActualQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</td>
                                <td>
                                    <div style="float:left;">`+data.dataall[i].Currency+`</div>
                                    <div style="float:right;">`+real_price[0].toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</div>
                                </td>
                            </tr>
                        `);
                    }

                    $("#dataUploadProforma").append
                    (`
                        <input type="hidden" name="Number" class="form-control" value="`+data.dataid.Number+`">
                        <input type="hidden" name="Vendor" class="form-control" value="`+data.dataid.VendorCode+`">
                        <input type="hidden" name="ActiveStage" class="form-control" value="`+data.dataid.ActiveStage+`">
                    `);
                    $("#dataFormProforma").append
                    (`
                        <div class="form-group">
                            <label for="po_no">Purchase Order Number</label>
                            <input type="text" name="po_no" class="form-control" value="`+data.dataid.Number+`" disabled>
                        </div>
                    `);
                    $('#proformaInvoice').modal('show');
                }
            });
        });

    });
</script>
