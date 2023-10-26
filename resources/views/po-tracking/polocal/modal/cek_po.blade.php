<div class="modal fade" id="cekpo" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info" id="Judulss"></div>
            <div class="table-responsive">
                <div class="modal-body text-center" id="ticketss"></div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#datatable-responsive tbody').on('click', '.cekpo', function ()
        {
                var id = $(this).attr('data-id');
                $.ajax({
                    url : "{{url('view_cekpo')}}?id="+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('#Judulss').empty();
                        $('#ticketss').empty();
                        var html =
                        `
                        <div class="row m-4">
                                <table class="table  table-md ">
                                    <thead class="thead-light">
                                        <tr>
                                        <th class="col-2">PO Number :</th>
                                        <th class="col-2">`+data.datapo.Number+`</th>
                                        <th class="col-2">Vendor :</th>
                                        <th class="col-2">`+data.datapo.Vendor+`</th>
                                        <th class="col-2">PO Creator :</th>
                                        <th class="col-2">`+data.datapo.PurchaseOrderCreator+`</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>`;
                        html +=
                            `<div class="row m-4">
                                <table class="table table-md">
                                    <thead class="thead-light">
                                        <tr>
                                        <th>Item Number</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Qty Delivery</th>

                                        </tr>
                                    </thead>
                                    <tbody>`;
                       for(i=0;i<data.data.length;i++){

                            html +=
                            `
                                <tr>
                                    <td >`+data.data[i].ItemNumber+`</td>
                                    <td >`+data.data[i].Material+`</td>
                                    <td >`+data.data[i].Description+`</td>
                                    <td>`+data.data[i].Quantity+`</td>

                                </tr>
                            `
                        }

                        html+=`</tbody></table>
                            </div>`;
                        $('#ticketss').append(html);
                        $("#Judulss").append
                        (`
                        <h6 text-center>CEK PO</h6>
                        `);

                        $('#cekpo').modal('show');
                    }
                });



        });
    });


</script>
