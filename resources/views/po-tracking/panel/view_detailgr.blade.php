<div class="modal fade" id="view" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 text-center>View Detail GR</h2>
              </div>
            <div class="modal-body text-center">
                <div class="row">
                    <div id="detailPO" class="col-sm-12"></div>
                        <div class="col">
                            <table class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Confirmed Date</th>
                                    <th>Confirmed Qty</th>
                                    <th>No Surat Jalan</th>
                                    <th>GR Date</th>
                                    <th>GR Qty</th>
                                    <th>Document Number</th>
                                    <th>Document Number Item</th>
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
    $(document).ready(function() {

        $('#datatable-responsive tbody').on('click', '.viewgr', function ()
        {
            var number = $(this).attr('data-number');
            var item = $(this).attr('data-item');
            $.ajax({
            url : "{{url('view_detailgr')}}?number="+number+"&item="+item,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#tableview').empty();
                    var date = '';
                    var refdocnumber = '';
                    var datess = '';
                    for(i=0;i<data.data.length;i++){
                        var cnfmdate = new Date(data.data[i].ConfirmedDate);
                        var dlvdate = new Date(data.data[i].DeliveryDateHistory);
                        var grdate = new Date(data.data[i].GoodsReceiptDate);
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
                        if (!data.data[i].GoodsReceiptDate) {
                            datess = '-';
                        }
                        if (!data.data[i].RefDocumentNumber) {
                            refdocnumber = '-';
                        }else{
                            refdocnumber = data.data[i].RefDocumentNumber;
                        }
                        if (!data.data[i].ConfirmedDate) {
                            date = '-';
                        }

                        if(data.data[i].POCategory == null){
                            var category = data.data[i].IsClosed ;
                        }else{
                            var category = data.data[i].POCategory ;
                        }

                        if(data.data[i].MovementType == null){
                            var movementtype = '-' ;
                        }else{
                            var movementtype = data.data[i].MovementType ;
                        }

                        if(data.data[i].ConfirmedQuantity == null || data.data[i].ConfirmedQuantity <= 0){
                            var Confirmedqty = '-' ;
                        }else{
                            var Confirmedqty = data.data[i].ConfirmedQuantity ;
                        }

                        var mt = data.data[i].MovementType;
                        var irdc = data.data[i].IRDebetCredit;
                        var simbol = '';
                        if(category == 'E'){
                            if(mt == '102' || mt == '104' || mt == '106' || mt == '122' || mt == '124'){
                                simbol = '-';
                            }
                        }
                        if(category == 'Q'){
                            if(irdc == 'H'){
                                simbol = '-';
                            }
                        }

                        if(data.data[i].GoodsReceiptQuantity == 0){
                            var grqty = '-' ;
                        }else{
                            var grqty = data.data[i].GoodsReceiptQuantity ;
                        }

                        if(data.data[i].DocumentNumber == null){
                            var docnum = '-' ;
                        }else{
                            var docnum = data.data[i].DocumentNumber ;
                        }

                        if(data.data[i].DocumentNumberItem == null){
                            var docnumitem = '-' ;
                        }else{
                            var docnumitem = data.data[i].DocumentNumberItem;
                        }

                        if(category == null){
                            var categorystatus = '-' ;
                        }else{
                            var categorystatus = category;
                        }

                        $('#tableview').append(`
                            <tr>
                                <td>`+date+`</td>
                                <td>`+Confirmedqty+`</td>
                                <td>`+refdocnumber+`</td>
                                <td>`+datess+`</td>
                                <td>`+simbol+grqty+`</td>
                                <td>`+docnum+`</td>
                                <td>`+docnumitem+`</td>
                                <td>`+movementtype+`</td>
                                <td>`+categorystatus+`</td>
                            </tr>
                        `);
                    }
                    //Tambahan Detail
                    var poNumber        = data.data[0].Number;
                    var ItemNumber      = data.data[0].ItemNumber;
                    var Material        = data.data[0].Material;
                    var MatDesc         = data.data[0].Description;
                    var TQuantity       = data.data[0].Quantity;
                    $('#detailPO').html(`
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Item Number</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>`+poNumber+`</td>
                                    <td>`+ItemNumber+`</td>
                                    <td>`+Material+`</td>
                                    <td>`+MatDesc+`</td>
                                    <td>`+TQuantity+`</td>
                                </tr>
                            </tbody>
                        </table>
                    `);
                    $('#view').modal('show');
                }
            });
        });
    });


</script>
