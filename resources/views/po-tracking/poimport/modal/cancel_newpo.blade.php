<div class="modal fade" id="poCancel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h6 class="modal-title" id="exampleModalLabel">Canceled PO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                  </button>
            </div>
            <form method="post" action="#" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-body text-center" id="cancel"></div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#datatable-visibility tbody').on('click', '.cancelPO', function ()
        {
            var id = $(this).attr('data-parent');
            $.ajax({
                url : "{{url('viewcancelpoimport')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    
                    //Tambahan Detail
                    var poNumber        = data.subcont.Number;
                    var ItemNumber      = data.subcont.ItemNumber;
                    var Material        = data.subcont.Material;
                    var MatDesc         = data.subcont.Description;
                    var qty             = data.subcont.Quantity;
                    var id              = data.subcont.ID;
                    var date            = new Date(data.subcont.DeliveryDate);
                    var dd              = String(date.getDate()).padStart(2, '0');
                    var mm              = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy            = date.getFullYear();
                    var DeliveryDate    = dd + '/' + mm + '/' + yyyy;
                    $('#cancel').empty();
                    
                    var html = 
                    `   
                        <div class="row m-1">
                            <div class='table-responsive'>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                        <th >PO Number</th>
                                        <th >Item Number</th>
                                        <th >Material</th>
                                        <th >Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>`+poNumber+`</td>
                                            <td>`+ItemNumber+`</td>
                                            <td data-toggle='tooltip' title='`+MatDesc+`'>`+Material+`</td>
                                            <td>`+qty+`</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row m-1">
                            <div class='table-responsive'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th>Delivery Date</th>
                                            <th>Quantity</th>
                                            <th>Canceled Quantity</th>
                                            <th>Canceled Arrival Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;
                    
                    for(i=0;i<data.viewtable.length;i++)
                    {
                        var tgl            = new Date(data.viewtable[i].ConfirmedDate);
                        var tanggal              = String(tgl.getDate()).padStart(2, '0');
                        var bulan              = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun            = tgl.getFullYear();
                        var ConfirmedDate    = tanggal + '/' + bulan + '/' + tahun;
                        var checkDate = ConfirmedDate == '01/01/1970' ? "-" : ConfirmedDate;
                        var confimedQty     = data.viewtable[i].ConfirmedQuantity == null ? qty:data.viewtable[i].ConfirmedQuantity;
                        html += 
                        `   
                            <tr>
                                <td>`+DeliveryDate+`</td>
                                <td>`+qty+`</td>
                                <td>`+confimedQty+`</td>
                                <td>`+checkDate+`</td>
                            </tr>
                        `;
                    }
                    html+=`</tbody></table></div></div>`;
                    $('#cancel').append(html);
                    $('#poCancel').modal('show') ;
                }
            });
        });
    });
</script>