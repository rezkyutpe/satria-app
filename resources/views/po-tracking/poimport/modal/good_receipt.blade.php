<div class="modal fade" id="goodReceipt" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 text-center>Good Receipt</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#">
                @csrf
                    <div class="m-2">
                        <div class="table-responsive">
                            <table class="table nowrap table-striped text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Item No.</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Confirmed Qty</th>
                                    </tr>
                                </thead>
                                <tbody id="dataItem">
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table nowrap table-striped text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>GR Date</th>
                                        <th>GR Quantity</th>
                                        <th>Document Material</th>
                                        <th>Movement Type</th>
                                        <th>GR Completed</th>
                                    </tr>
                                </thead>
                                <tbody id="dataGR">
                                </tbody>
                            </table>
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
        $('#datatable-visibility tbody').on('click', '.goodReceipt', function ()
        {
            var id = $(this).attr('data-id');
            console.log(id);
            // var id = $(this).attr('data-number');
            $.ajax({
                url : "{{route('caridata.id')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#dataItem').empty();
                    $('#dataGR').empty();
                    
                    for(i=0;i<data.dataall.length;i++)
                    {
                        var tgl             = new Date(data.dataall[i].ConfirmedDate);
                        var tanggal         = String(tgl.getDate()).padStart(2, '0');
                        var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun           = tgl.getFullYear();
                        var ConfirmedDate   = tanggal + '/' + bulan + '/' + tahun;
                        var price           = data.dataall[i].NetPrice;
                        const real_price    = price.split('.');

                        $("#dataItem").append
                        (`
                            <tr>
                                <td>`+data.dataid.Number+`</td>
                                <td>`+data.dataall[i].ItemNumber+`</td>
                                <td class="text-left">`+data.dataall[i].Material+`</td>
                                <td>`+data.dataall[i].Description+`</td>
                                <td>`+data.dataall[i].ActualQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</td>
                            </tr>
                        `);
                    }
                    if (data.dataGR == "") {
                        $("#dataGR").append
                        (`
                            <tr>
                                <td colspan="5">Data Not Found</td>
                            </tr>
                        `);
                    }else{
                        for(i=0;i<data.dataGR.length;i++)
                        {
                            var tgl             = new Date(data.dataGR[i].GoodsReceiptDate);
                            var tanggal         = String(tgl.getDate()).padStart(2, '0');
                            var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var tahun           = tgl.getFullYear();
                            var GRDate          = tanggal + '/' + bulan + '/' + tahun;
                            var completed       = '-';
                            if (data.dataGR[i].GoodsReceiptQuantity == data.dataall[i].ActualQuantity) {
                                completed = 'Completed';
                            }else{
                                completed = 'Not Completed'
                            }
                            $("#dataGR").append
                            (`
                                <tr>
                                    <td>`+GRDate+`</td>
                                    <td>`+data.dataGR[i].GoodsReceiptQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") +' / '+ data.dataall[i].ActualQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")+`</td>
                                    <td>`+data.dataGR[i].DocumentNumber+`</td>
                                    <td>`+data.dataGR[i].MovementType+`</td>
                                    <td>`+completed+`</td>
                                </tr>
                            `);
                        }
                    }
                    $('#goodReceipt').modal('show');
                }
            });
        });

    });
</script>