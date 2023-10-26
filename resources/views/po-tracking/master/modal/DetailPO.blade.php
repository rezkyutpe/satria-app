<div class="modal fade" id="detailPO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">

        <div class="modal-content">
            <div class="modal-header bg-success" id="Juduls">
            </div>
            <div class="table-responsive">
                <div class="modal-body text-center" id="PO"></div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                    </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

     $('#datatable-responsive tbody').on('click', '.detailspo', function() {
        var id = $(this).attr('data-number');

                // fetch('http://localhost/editreason?id=2')
                $.ajax({
                url : "{{url('caridetailpo')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#Juduls').empty();
                    $('#PO').empty();
                    var html =
                    `
                    <div class="row m-4">
                            <table class="table table-bordered" >
                                <thead >
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Vendor</th>
                                        <th>Vendor Code</th>
                                        <th>PO Creator</th
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>`+data.data.Number+`</td>
                                        <td>`+data.data.Vendor+`</td>
                                        <td>`+data.data.VendorCode+`</td>
                                        <td>`+data.data.PurchaseOrderCreator+`</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>`;

                            html +=
                                `<div class="row m-4">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Item Number</th>
                                                <th>Material</th>
                                                <th>Description</th>
                                                <th>Qty Delivery</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                `;
                                for(i=0;i<data.dataall.length;i++){
                    html+=`
                                            <tr>
                                                <td >`+data.dataall[i].ItemNumber+`</td>
                                                <td >`+data.dataall[i].Material+`</td>
                                                <td >`+data.dataall[i].Description+`</td>
                                                <td >`+data.dataall[i].Quantity+`</td>
                                            </tr>
                                            `;
                                    }
                    html+=`
                                        </tbody>
                            </tbody></table>
                        </div>`;

                    $('#PO').append(html);
                    $("#Juduls").append
                    (`
                    <h6 text-center>Details PO</h6>
                    `);

                    $('#detailPO').modal('show');
                }
            });

        });
    });


</script>
