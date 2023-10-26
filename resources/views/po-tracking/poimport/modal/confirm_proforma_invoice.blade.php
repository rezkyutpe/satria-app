<div class="modal fade" id="verifyProforma" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h6>Confirm Payment Date</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('verify_proforma') }}" method="POST">
                    @csrf
                    <div id="dataConfirmedDate_Number"></div>
                    <div class="m-2">
                        <div class="table-responsive">
                            <table class="table nowrap table-bordered text-center table-md" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Item No.</th>
                                        <th>Material</th>
                                        <th>Total Qty</th>
                                        <th>Confirmed Qty</th>
                                        <th>Price&nbsp;&frasl;<sub>pcs</sub>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody id="TablePO"></tbody>
                            </table>
                        </div>
                    </div>
                    <div id="formConfirmedDate"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        $('#datatable-visibility tbody').on('click', '.verifyProforma', function ()
        {
            var id = $(this).attr('data-number');
            $.ajax({
                url : "{{url('viewcaridataimport')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#TablePO').empty();
                    $('#dataConfirmedDate_Number').empty();
                    $('#formConfirmedDate').empty();
                    $(".datepicker").datepicker();
                     for(i=0;i<data.dataall.length;i++)
                    {
                        var tgl             = new Date(data.dataall[i].ConfirmedDate);
                        var tanggal         = String(tgl.getDate()).padStart(2, '0');
                        var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun           = tgl.getFullYear();
                        var ConfirmedDate   = tanggal + '/' + bulan + '/' + tahun;
                        var price           = data.dataall[i].NetPrice;
                        const real_price          = price.split('.');
                        $("#TablePO").append
                        (`
                            <tr>
                                <td>`+data.dataall[i].ItemNumber+`</td>
                                <td data-toogle='tooltip' title='`+data.dataall[i].Description+`'>`+data.dataall[i].Material+`</td>
                                <td>`+data.dataall[i].Quantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</td>
                                <td>`+data.dataall[i].ActualQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</td>
                                <td>
                                    <div style="float:left;">`+data.dataall[i].Currency+`</div>
                                    <div style="float:right;">`+real_price[0].toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</div>
                                </td>
                            </tr>
                        `);
                    }
                    $("#dataConfirmedDate_Number").append
                    (`
                        <div class='table-responsive'>
                            <table class='table table-borderless text-center'>
                                <thead>
                                    <tr>
                                        <th class="text-left">Confirm Payment Date</th>
                                        <th class="text-left">Purchase Order Number</th>
                                    </tr>    
                                </thead>    
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="date" name="confirmed_date" class="form-control">    
                                        </td>
                                        <td>
                                            <input type="text" name="Number" class="form-control" value="`+data.dataid.Number+`" disabled>
                                            <input type="hidden" name="Number" class="form-control" value="`+data.dataid.Number+`">
                                            <input type="hidden" name="Vendor" class="form-control" value="`+data.dataid.VendorCode+`">    
                                        </td> 
                                    </tr>
                                </tbody>
                            </table>    
                        </div>
                    `);
                    $("#formConfirmedDate").append
                    (`
                        <div class="modal-footer">
                            <input type="submit" name="action" value="Save" class="btn btn-primary btn-sm pull-left">
                            <input type="submit" name="action"  value="Revision" class="btn btn-warning btn-sm pull-left" onclick="return confirm('Are you sure you will revise this proforma invoice?');">
                        </div>
                    `);

                    $('#verifyProforma').modal('show');
                }
            });
        });
    });
</script>