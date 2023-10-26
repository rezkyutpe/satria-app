<div class="modal fade" id="view" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 text-center>View Detail</h2>
              </div>
            <div class="modal-body text-center">
                <div class="row">
                        <div class="col-12">
                            <table id="datatable-fixed-header" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Delivery Date</th>
                                    <th>Quantity</th>
                                    <th>GR Date</th>
                                    <th>GR Qty</th>
                                    <th>Movement Type</th>
                                    <th>Document Number</th>
                                    <th>Status Movement</th>
                                    <th>Status Delivery</th>
                                    <th>Status Receive  </th>
                                </tr>
                                </thead>
                                <tbody id="tableview">
                                </tbody>
                            </table>
                      </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Back </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#datatable-responsive tbody').on('click', '.details', function ()
        {

                var number = $(this).attr('data-number');
                var item = $(this).attr('data-item');

                $.ajax({
                url : "{{url('view_detaildsgr')}}?number="+number+"&item="+item,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {

                    $('#tableview').empty();
                            var date = '';
                            var datess = '';
                        for(i=0;i<data.data.length;i++){
                            if (!data.data[i].ConfirmedDate) {
                                date = data.data[i].DeliveryDate;
                            }else{
                                date = data.data[i].ConfirmedDate ;
                            }
                            var cnfmdate = new Date(date);
                            var grdate = new Date(data.data[i].GoodsReceiptDate);
                            var dd = String(cnfmdate.getDate()).padStart(2, '0');
                            var mm = String(cnfmdate.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyy = cnfmdate.getFullYear();
                            var dddd = String(grdate.getDate()).padStart(2, '0');
                            var mmmm = String(grdate.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyyyy = grdate.getFullYear();
                            var date = dd + '/' + mm + '/' + yyyy;
                            var datess = dddd + '/' + mmmm + '/' + yyyyyy;
                            if(!data.data[i].ConfirmedQuantity){
                                var qty = data.data[i].Quantity ;
                            }else{
                                var qty = data.data[i].ConfirmedQuantity ;
                            }

                    $('#tableview').append(`
                        <tr>
                            <td>`+date+`</td>
                            <td>`+qty+`</td>
                            <td>`+datess+`</td>
                            <td>`+data.data[i].GoodsReceiptQuantity+`</td>
                            <td>`+data.data[i].MovementType+`</td>
                            <td>`+data.data[i].DocumentNumber+`</td>
                            <td>`+data.data[i].StatusMovement+`</td>
                            <td>`+data.data[i].StatusDelivery+`</td>
                            <td>`+data.data[i].StatusReceive+`</td>

                        </tr>
                    `);
                        }
                    $('#view').modal('show');
                    }
                });
        });
    });


</script>
