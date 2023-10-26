
<div class="modal fade" id="view" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 text-center>View Po Local</h2>
              </div>
            <div class="modal-body text-center">
                <div class="row">
                        <div class="col-12">
                            <table id="datatable-fixed-header" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Confirmed Date</th>
                                    <th>Confirmed Qty</th>
                                    <th>Incoming Date</th>
                                    <th>GR Date</th>
                                    <th>GR Qty</th>
                                    <th>Document Number</th>
                                    <th>Movement Type</th>
                                    <th>Status</th>
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
$(document).ready(function()
    {
        $('#datatable-visibility tbody').on('click', '.view_po', function ()
        {

        var number = $(this).attr('data-number');
        var item = $(this).attr('data-item');
        $.ajax({
        url : "{{url('viewcaridataimportbyid')}}?number="+number+"&item="+item,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

        $('#tableview').empty();
                var date = '';
                var dates = '';
                var datess = '';
            for(i=0;i<data.dataall.length;i++){

                var cnfmdate = new Date(data.dataall[i].ConfirmedDate);
                var dlvdate = new Date(data.dataall[i].DeliveryDateHistory);
                var grdate = new Date(data.dataall[i].GoodsReceiptDate);
                var dd = String(cnfmdate.getDate()).padStart(2, '0');
                var mm = String(cnfmdate.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = cnfmdate.getFullYear();
                var ddd = String(dlvdate.getDate()).padStart(2, '0');
                var mmm = String(dlvdate.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyyy = dlvdate.getFullYear();
                var dddd = String(grdate.getDate()).padStart(2, '0');
                var mmmm = String(grdate.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyyyy = grdate.getFullYear();
                var date = dd + '/' + mm + '/' + yyyy;
                var dates = ddd + '/' + mmm + '/' + yyyyy;
                var datess = dddd + '/' + mmmm + '/' + yyyyyy;
                if (!data.dataall[i].GoodsReceiptDate) {
                    datess = null;
                }
                if (!data.dataall[i].DeliveryDateHistory) {
                    dates = null;
                }
                if (!data.dataall[i].ConfirmedDate) {
                    date = null;
                }

        $('#tableview').append(`
            <tr>
                <td>`+date+`</td>
                <td>`+data.dataall[i].ConfirmedQuantity+`</td>
                <td>`+dates+`</td>
                <td>`+datess+`</td>
                <td>`+data.dataall[i].GoodsReceiptQuantity+`</td>
                <td>`+data.dataall[i].DocumentNumber+`</td>
                <td>`+data.dataall[i].MovementType+`</td>
                <td>`+data.dataall[i].POCategory+`</td>
            </tr>
        `);
            }
        $('#view').modal('show');
        }
        });
    });
});

</script>
