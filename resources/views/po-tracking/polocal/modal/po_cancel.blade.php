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
                var id = $(this).attr('data-id');
                $.ajax({
                    url : "{{url('viewpolocal')}}?id="+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        var qty             = data.subcont.Quantity ;
                        var id              = data.subcont.ID;
                        var date            = new Date(data.subcont.DeliveryDate);
                        var dd              = String(date.getDate()).padStart(2, '0');
                        var mm              = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy            = date.getFullYear();
                        var DeliveryDate    = dd + '/' + mm + '/' + yyyy;
                        $('#cancel').empty();
                        for(i=0;i<data.viewtable.length;i++){
                        var tgl            = new Date(data.viewtable[i].ConfirmedDate);
                        var tanggal              = String(tgl.getDate()).padStart(2, '0');
                        var bulan              = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun            = tgl.getFullYear();
                        var ConfirmedDate    = tanggal + '/' + bulan + '/' + tahun;
                        $("#cancel").append(`
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group text-left">
                                        <label class="form-control-label">Delivery Date:</label>
                                        <input type="text-center" name="DeliveryDate" class="form-control DeliveryDates" autocomplete="off"readonly value="`+DeliveryDate+`">
                                        <input type="hidden" name="ID" class="form-control ID" autocomplete="off" value="`+id+`" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group text-left">
                                        <label class="form-control-label">Quantity:</label>
                                        <input type="text-center" name="Quantity" class="form-control" autocomplete="off"readonly value="`+qty+`">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group text-left">
                                        <label class="form-control-label">Confirm  Quantity:</label>
                                        <input type="number" name="ConfirmedQuantity"  class="form-control qty" autocomplete="off" value="`+data.viewtable[i].ConfirmedQuantity+`" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group text-left">
                                        <label class="form-control-label">Confirm Arrival Date:</label>
                                        <input type="text" name="ConfirmedDate"  class="form-control DeliveryDates" autocomplete="off" value=" `+ConfirmedDate+`" readonly>
                                    </div>
                                </div>
                            </div>`);
                        }
                        $('#poCancel').modal('show') ;
                    }
                    });

            });

    });
</script>
